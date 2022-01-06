<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Modules\Event\Models\Event;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationCategory;
use Modules\Core\Models\Attributes;

class EventController extends Controller
{
    protected $eventClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->eventClass = Event::class;
        $this->locationClass = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Event::isEnable()) {
            return $this->sendError(__("Event not found"));
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $list = call_user_func([$this->eventClass, 'search'], $request);
        $limit_location = 15;
        if (empty(setting_item("event_location_search_style")) or setting_item("event_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'data' => $list->map(function ($row) {
                return $row->dataForApi();
            }),
            'list_location' => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'event_min_max_price' => $this->eventClass::getMinMaxPrice(),
            "blank" => 1,
            "seo_meta" => $this->eventClass::getSeoMetaForPageList()
        ];
        $data['attributes'] = Attributes::where('service', 'event')->orderBy("position", "desc")->with(['terms' => function ($query) {
            $query->withCount('event');
        }, 'translations'])->get();

        return $this->sendSuccess($data);
    }

    public function detail($slug)
    {
        $row = $this->eventClass::where('slug', $slug)->orWhere('id', $slug)->with(['location', 'translations', 'hasWishList'])->first();;
        if (empty($row) or !$row->hasPermissionDetailView()) {
            return $this->sendError(__("Event not found"));
        }
        $translation = $row->translateOrOrigin(app()->getLocale());
        $event_related = [];
        $location_id = $row->location_id;
        if (!empty($location_id)) {
            $event_related = $this->eventClass::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$row->id])->with(['location', 'translations', 'hasWishList'])->get();
        }
        $review_list = $row->getReviewList();
        $data = [
            'data' => $row->dataForApi(true),
            'translation' => $translation,
            'event_related' => $event_related,
            'location_category' => $this->locationCategoryClass::where("status", "publish")->with('location_category_translations')->get(),
            'booking_data' => $row->getBookingData(),
            'review_list' => $review_list,
            'body_class' => 'is_single',
        ];
        return $this->sendSuccess($data);
    }
}
