<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Modules\Flight\Models\SeatType;
use Modules\Flight\Models\Flight;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use DB;

class FlightController extends Controller
{
    protected $flightClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->flightClass = Flight::class;
        $this->locationClass = Location::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Flight::isEnable()) {
            return $this->sendError(__("Flight not found"));
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $list = call_user_func([$this->flightClass, 'search'], $request);
        $limit_location = 15;
        if (empty(setting_item("flight_location_search_style")) or setting_item("flight_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'data' => $list->map(function ($row) {
                return $row->dataForApi();
            }),
            'list_location' => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'seatType' => SeatType::get(),
            'flight_min_max_price' => $this->flightClass::getMinMaxPrice(),
            "blank" => 1,
        ];
        $data['attributes'] = Attributes::where('service', 'flight')->orderBy("position", "desc")->with(['terms' => function ($query) {
            $query->withCount('flight');
        }, 'translations'])->get();

        return $this->sendSuccess($data);
    }

    public function detail($id)
    {
        $row = $this->flightClass::with(['flightSeat.seatType', 'airportFrom', 'airportTo', 'airline', 'bookingPassengers'])->find($id);
        if (empty($row)) {
            return $this->sendError('no found');
        } else {
            if (!empty($row->airline)) {
                $row->airline->append(['image_url']);
            }
            $bookingPassengers = $row->bookingPassengers->countBy('seat_type')->toArray();
            if (!empty($row->flightSeat)) {
                foreach ($row->flightSeat as &$value) {
                    if (!empty($bookingPassengers[$value->seat_type])) {
                        $value->max_passengers = $value->max_passengers - $bookingPassengers[$value->seat_type];
                        if ($value->max_passengers < 0) {
                            $value->max_passengers = 0;
                        }
                    }
                    $value->price_html = format_money($value->price);
                    $value->number = 0;
                }
            }
            $row->departure_time_html = $row->departure_time->format('H:i');
            $row->departure_date_html = $row->departure_time->format('D, d M y');
            $row->arrival_time_html = $row->arrival_time->format('H:i');
            $row->arrival_date_html = $row->arrival_time->format('D, d M y');

            return $this->sendSuccess(['data' => $row->dataForApi(true)],);
        }
    }
}
