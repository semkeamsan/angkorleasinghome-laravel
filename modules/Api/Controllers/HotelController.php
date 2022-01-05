<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Modules\Hotel\Models\Hotel;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationCategory;
use Modules\Core\Models\Attributes;

class HotelController extends Controller
{
    protected $hotelClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->hotelClass = Hotel::class;
        $this->locationClass = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
    }

    public function index(Request $request)
    {
        $list = call_user_func([$this->hotelClass, 'search'], $request);

        $limit_location = 15;
        if (empty(setting_item("hotel_location_search_style")) or setting_item("hotel_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'data' => $list->map(function ($row) {
                return $row->dataForApi();
            }),
            'list_location' => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'hotel_min_max_price' => $this->hotelClass::getMinMaxPrice(),
            "blank" => 1,
            "seo_meta" => $this->hotelClass::getSeoMetaForPageList()
        ];
        $data['attributes'] = Attributes::where('service', 'hotel')->orderBy("position", "desc")->with(['terms' => function ($query) {
            $query->withCount('hotel');
        }, 'translations'])->get();
        return $this->sendSuccess($data);
    }

    public function detail($slug)
    {
        $row = $this->hotelClass::where('slug', $slug)->orWhere('id', $slug)->with(['location', 'translations', 'hasWishList'])->first();;
        if (empty($row) or !$row->hasPermissionDetailView()) {
            return $this->sendError(__("Hotel not found"));
        }
        $hotel_related = [];
        $location_id = $row->location_id;
        if (!empty($location_id)) {
            $hotel_related = $this->hotelClass::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$row->id])->with(['location', 'translations', 'hasWishList'])->get();
        }
        $review_list = $row->getReviewList();
        $this->setActiveMenu($row);
        return $this->sendSuccess([
            'data' => $row->dataForApi(true),
            'hotel_related' => $hotel_related,
            'location_category' => $this->locationCategoryClass::where("status", "publish")->with('location_category_translations')->get(),
            'booking_data' => $row->getBookingData(),
            'review_list' => $review_list,
            'body_class' => 'is_single',
        ]);
    }

    public function checkAvailability()
    {
        $hotel_id = \request('hotel_id');
        if (\request()->input('firstLoad') == "false") {
            $rules = [
                'hotel_id' => 'required',
                'start_date' => 'required:date_format:Y-m-d',
                'end_date' => 'required:date_format:Y-m-d',
                'adults' => 'required',
            ];
            $validator = \Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->all());
            }

            if (strtotime(\request('end_date')) - strtotime(\request('start_date')) < DAY_IN_SECONDS) {
                return $this->sendError(__("Dates are not valid"));
            }
            if (strtotime(\request('end_date')) - strtotime(\request('start_date')) > 30 * DAY_IN_SECONDS) {
                return $this->sendError(__("Maximum day for booking is 30"));
            }
        }

        $hotel = $this->hotelClass::find($hotel_id);
        if (empty($hotel_id) or empty($hotel)) {
            return $this->sendError(__("Hotel not found"));
        }

        if (\request()->input('firstLoad') == "false") {
            $numberDays = abs(strtotime(\request('end_date')) - strtotime(\request('start_date'))) / 86400;
            if (!empty($hotel->min_day_stays) and $numberDays < $hotel->min_day_stays) {
                return $this->sendError(__("You must to book a minimum of :number days", ['number' => $hotel->min_day_stays]));
            }

            if (!empty($hotel->min_day_before_booking)) {
                $minday_before = strtotime("today +" . $hotel->min_day_before_booking . " days");
                if (strtotime(\request('start_date')) < $minday_before) {
                    return $this->sendError(__("You must book the service for :number days in advance", ["number" => $hotel->min_day_before_booking]));
                }
            }
        }

        $rooms = $hotel->getRoomsAvailability(request()->input());

        return $this->sendSuccess([
            'rooms' => $rooms
        ]);
    }
}
