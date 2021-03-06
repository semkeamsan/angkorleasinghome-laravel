<?php

namespace Modules\Api\Controllers;


use App\Http\Controllers\Controller;
use Modules\Location\Models\LocationCategory;
use Modules\Space\Models\Space;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;

class SpaceController extends Controller
{
    protected $spaceClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->spaceClass = Space::class;
        $this->locationClass = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Space::isEnable()) {
            return $this->sendError(__("Space not found"));
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {

        $list = call_user_func([$this->spaceClass, 'search'], $request);
        $limit_location = 15;
        if (empty(setting_item("space_location_search_style")) or setting_item("space_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'data' => $list->map(function ($row) {
                return $row->dataForApi();
            }),
            'total' => $list->total(),
            'currentPage' => $list->currentPage(),
            'lastPage' => $list->lastPage(),
            'perPage' => $list->perPage(),
            'lastPage' => $list->lastPage(),
            'list_location' => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'space_min_max_price' => $this->spaceClass::getMinMaxPrice(),
        ];

        $data['attributes'] = Attributes::where('service', 'space')->orderBy("position", "desc")->with(['terms' => function ($query) {
            $query->withCount('space');
        }, 'translations'])->get();

        return $this->sendSuccess($data);
    }

    public function detail($slug)
    {
        $row = $this->spaceClass::where('slug', $slug)->orWhere('id', $slug)->with(['location', 'translations', 'hasWishList'])->first();
        if (empty($row) or !$row->hasPermissionDetailView()) {
            return $this->sendError('no found');

        }
        $translation = $row->translateOrOrigin(app()->getLocale());
        $space_related = [];
        $location_id = $row->location_id;
        if (!empty($location_id)) {
            $space_related = $this->spaceClass::where('location_id', $location_id)->where("status", "publish")->take(8)->whereNotIn('id', [$row->id])->with(['location', 'translations', 'hasWishList'])->get();
        }
        $review_list = $row->getReviewList();
        $data = [
            'data' => $row->dataForApi(true),
            'translation' => $translation,
            'space_related' => $space_related,
            'location_category' => $this->locationCategoryClass::where("status", "publish")->with('location_category_translations')->get(),
            'booking_data' => $row->getBookingData(),
            'review_list' => $review_list,
        ];
        return $this->sendSuccess($data);
    }
}
