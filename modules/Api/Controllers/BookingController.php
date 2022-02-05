<?php

namespace Modules\Api\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Models\Terms;
use Modules\Booking\Models\Booking;
use Modules\Core\Models\Attributes;
use Illuminate\Support\Facades\Auth;
use Modules\Location\Models\Location;
use Modules\Template\Models\Template;
use Illuminate\Support\Facades\Validator;
use Modules\Hotel\Models\Hotel;

class BookingController extends \Modules\Booking\Controllers\BookingController
{
    protected $booking;


    public function __construct()
    {
        $this->booking = Booking::class;
        parent::__construct();
        $this->middleware('auth:api')->except([
            'detail', 'getConfigs', 'getHomeLayout', 'getTypes', 'checkout', 'doCheckout', 'checkStatusCheckout', 'confirmPayment', 'getGatewaysForApi',
            'thankyou',
        ]);
    }

    function generate_menu($location = 'primary')
    {
        $options['walker'] = $options['walker'] ?? '\\Modules\\Core\\Walkers\\MenuWalker';

        $setting = json_decode(setting_item('menu_locations'), true);

        if (!empty($setting)) {
            foreach ($setting as $l => $menuId) {
                if ($l == $location and $menuId) {
                    $menu = (new \Modules\Core\Models\Menu())->findById($menuId);
                    return $menu->translateOrOrigin(app()->getLocale());
                }
            }
        }
    }

    public function getTypes()
    {
        $types = get_bookable_services();

        $res = [];
        foreach ($types as $type => $class) {
            $obj = new $class();
            $res[$type] = [
                'icon' => call_user_func([$obj, 'getServiceIconFeatured']),
                'name' => call_user_func([$obj, 'getModelName']),
                'search_fields' => [],
            ];
        }
        return $res;
    }

    public function getConfigs()
    {
        $languages = \Modules\Language\Models\Language::getActive();
        $template = Template::find(setting_item('api_app_layout'));

        // currency
        $currency = \App\Currency::getActiveCurrency();
        $current_currency = \App\Currency::getCurrent('currency_main');
        $menu = $this->generate_menu();
        $hotel_search_fields = [];
        foreach (json_decode(setting_item('hotel_search_fields', '[]')) as $search) {
            if (@$search->attr) {
                $attr = \Modules\Core\Models\Attributes::find($search->attr);
                $translate =  $attr->translateOrOrigin(app()->getLocale());
                $hotel_search_fields[$search->position] = [
                    'id' => $attr->id,
                    'name' => $translate->name,
                    'children' => $attr->terms->map(function ($row) {
                        $translate = $row->translateOrOrigin(app()->getLocale());
                        return [
                            'id' => $row->id,
                            'name' => $translate->name,
                        ];
                    }),
                ];
            } elseif ($search->field == 'location') {
                $hotel_search_fields[$search->position] = [
                    'id' =>null,
                    'name' => $search->title,
                    'children' => Location::where('status', 'publish')->get()->toTree()->map(function($row){
                        $translate = $row->translateOrOrigin(app()->getLocale());
                        return [
                            'id' => $row->id,
                            'name' => $translate->name,
                            'children' => $row->children->map(function($row){
                                $translate = $row->translateOrOrigin(app()->getLocale());
                                return [
                                    'id' => $row->id,
                                    'name' => $translate->name,
                                    'children' => [],
                                ];
                            }),
                        ];
                    }),
                ];
            } elseif ($search->field == 'price') {
                $hotel_page_search_price = json_decode(setting_item('hotel_page_search_price', '[]'));
                $hotel_price_range =  [
                   [
                    'id' => 'All',
                    'name' => __('All'),
                   ]
                ];
                foreach ($hotel_page_search_price as $price) {
                    for ($i = $price->from; $i < $price->to; $i += $price->increment) {
                        $hotel_price_range[] = [
                            'id' => $i . ';' . ($i + $price->increment),
                            'name' => $i . ' → ' . ($i + $price->increment),
                        ];
                    }
                }
                $hotel_price_range[] = [
                    'id' => collect($hotel_page_search_price)->last()->to . ';1000000',
                    'name' => collect($hotel_page_search_price)->last()->to . ' → ' . __('Up'),
                ];

                $hotel_search_fields[$search->position] = [
                    'id' =>null,
                    'name' => $search->title,
                    'children' => $hotel_price_range,
                ];
            }
        }
        ksort($hotel_search_fields);


        $res = [
            'site_info' => [
                'logo' => get_file_url(setting_item("logo_id_2"), 'full'),
                'logo_white' => get_file_url(setting_item("logo_id"), 'full'),
                'site_title' => setting_item("site_title"),
                'phone' => setting_item("phone_contact"),
                'email' => setting_item("admin_email"),
                'main_menu' => json_decode($menu->items),
                'enquiry_for_telegram' => setting_item("enquiry_for_telegram"),
            ],
            'languages' => $languages->map(function ($lang) {
                return $lang->only(['locale', 'name']);
            }),
            'hotel' => [
                'search_fields' => array_values($hotel_search_fields),
                'filters' => [
                    'price_range' =>$hotel_price_range,
                    'attributes' => Attributes::whereIn('service', ['hotel','hotel_room'])->get()->map(function($row){
                        $translate =  $row->translateOrOrigin(app()->getLocale());
                        return [
                            'id' => $row->id,
                            'name' => $translate->name,
                            'children' => Terms::where("attr_id", $row->id)->get()->map(function($row){
                                $translate =  $row->translateOrOrigin(app()->getLocale());
                                return [
                                    'id' => $row->id,
                                    'name' => $translate->name,
                                    'count_hotel' => $row->hotel->count()
                                ];
                            })
                        ];
                    }),
                ],
                'order_by' => [
                    [
                        'id' => '',
                        'name' => 'Recommended',
                    ],
                    [
                        'id' => 'price_low_high',
                        'name' => __('Price (Low to high)'),
                    ],
                    [
                        'id' => 'price_high_low',
                        'name' => __('Price (High to low)'),
                    ],
                    [
                        'id' => 'rate_high_low',
                        'name' => __('Rating (High to low)'),
                    ],
                ]
            ],
            //'booking_types' => $this->getTypes(),
            'app_layout' => $template ? json_decode($template->content, true) : [],
            'is_enable_guest_checkout' => (int)is_enable_guest_checkout(),
            'currency_main' => $current_currency,
            'currency' => $currency,
            'country' => get_country_lists(),

        ];
        return $this->sendSuccess($res);
    }

    public function getHomeLayout()
    {
        $res = [];
        $template = Template::find(setting_item('api_app_layout'));
        if (!empty($template)) {
            $res = $template->getProcessedContentAPI();
        }
        return $this->sendSuccess(
            [
                "data" => $res
            ]
        );
    }


    protected function validateCheckout($code)
    {

        $booking = $this->booking::where('code', $code)->first();

        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }

        return true;
    }

    public function detail(Request $request, $code)
    {

        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            return $this->sendError(__("Booking not found!"))->setStatusCode(404);
        }

        if ($booking->status == 'draft') {
            return $this->sendError(__("You do not have permission to access"))->setStatusCode(404);
        }
        $data = [
            'booking' => $booking,
            'service' => $booking->service,
        ];
        if ($booking->gateway) {
            $data['gateway'] = get_payment_gateway_obj($booking->gateway);
        }
        return $this->sendSuccess(
            $data
        );
    }

    protected function validateDoCheckout()
    {

        $request = \request();
        /**
         * @param Booking $booking
         */
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $code = $request->input('code');
        $booking = $this->booking::where('code', $code)->first();
        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }

        return true;
    }

    public function checkStatusCheckout($code)
    {
        $booking = $this->booking::where('code', $code)->first();
        $data = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];
        if (empty($booking)) {
            $data = [
                'error' => true,
                'redirect' => url('/')
            ];
        }

        if ($booking->status != 'draft') {
            $data = [
                'error' => true,
                'redirect' => url('/')
            ];
        }
        return response()->json($data, 200);
    }

    public function getGatewaysForApi()
    {
        $res = [];
        $gateways = $this->getGateways();
        foreach ($gateways as $gateway => $obj) {
            $res[$gateway] = [
                'logo' => $obj->getDisplayLogo(),
                'name' => $obj->getDisplayName(),
                'desc' => $obj->getApiDisplayHtml(),
            ];
            if ($option = $obj->getForm()) {
                $res[$gateway]['form'] = $option;
            }
            if ($options = $obj->getApiOptions()) {
                $res[$gateway]['options'] = $options;
            }
        }
        return $this->sendSuccess($res);
    }

    public function thankyou(Request $request, $code)
    {

        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            abort(404);
        }

        if ($booking->status == 'draft') {
            return redirect($booking->getCheckoutUrl());
        }

        $data = [
            'page_title' => __('Booking Details'),
            'booking' => $booking,
            'service' => $booking->service,
        ];
        if ($booking->gateway) {
            $data['gateway'] = get_payment_gateway_obj($booking->gateway);
        }
        return view('Booking::frontend/detail', $data);
    }

       /**
     * @todo Handle Add To Cart Validate
     *
     * @param Request $request
     * @return string json
     */
    public function addToCart(Request $request)
    {

        if(!is_enable_guest_checkout() and !Auth::check()){
            return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        }
        if(request()->user() && !request()->user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user')==1){
            return $this->sendError(__("You have to verify email first"), ['url' => url('api/auth/email/resend/verify')]);
        }

        $validator = Validator::make($request->all(), [
            'service_id'   => 'required|integer',
            'service_type' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $service_type = $request->input('service_type');
        $service_id = $request->input('service_id');
        $allServices = get_bookable_services();
        if (empty($allServices[$service_type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$service_type];
        $service = $module::find($service_id);
        if (empty($service) or !is_subclass_of($service, '\\Modules\\Booking\\Models\\Bookable')) {
            return $this->sendError(__('Service not found'));
        }
        if (!$service->isBookable()) {
            return $this->sendError(__('Service is not bookable'));
        }

        if(Auth::id() == $service->create_user){
            return $this->sendError(__('You cannot book your own service'));
        }

        return $service->addToCart($request);
    }

    public function create(Request $request, $object_model)
    {
        if ($object_model != 'hotel' && $object_model != 'tour') {
            return $this->sendError(__('Service not found'));
        }
        $user = $request->user();
        $rules = [
            'hotel_id' => 'required',
            'object_id' => 'required',
            'gateway' => 'required',
            'object_id' => 'required',
            'start_date' => 'required:date_format:Y-m-d',
            'end_date' => 'required:date_format:Y-m-d',
            'commission' => 'required',
            'total' => 'required',
            'total_guests' => 'required',
            'email' => 'required|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required',
            'credit' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->all());
        }
        if (strtotime(\request('end_date')) - strtotime(\request('start_date')) < DAY_IN_SECONDS) {
            return $this->sendError(__("Dates are not valid"));
        }
        if (strtotime(\request('end_date')) - strtotime(\request('start_date')) > 30 * DAY_IN_SECONDS) {
            return $this->sendError(__("Maximum day for booking is 30"));
        }
        $hotel = Hotel::find($request->post('hotel_id'));
        if (is_null($hotel)) {
            return $this->sendError(__("This hotel are not existing"));
        }

        $credit = $request->post('credit', 0);
        $wallet_total_used = credit_to_money($credit);
        if($wallet_total_used > 0){
            $credit = money_to_credit(0, true);
            $wallet_total_used = 0;
        }

        $data_booking = [
            'vendor_id' => $request->post('hotel_id'),
            'customer_id' => $user->id,
            'gateway' => $request->post('gateway'),
            'object_id' => $request->post('object_id'),
            'object_model' => $object_model,
            'start_date' => $request->post('start_date'),
            'end_date' => $request->post('end_date'),
            'status' => 'draft',
            'total' => $request->post('total'),
            'total_guests' => $request->post('total_guests'),
            'commission' => $request->post('commission'),
            'email' => $request->post('email'),
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'phone' => $request->post('phone'),
            'country' => $request->post('country')
        ];

        $created_booking = Booking::create($data_booking);
        $created_booking->pay_now = floatval($created_booking->deposit == null ? $created_booking->total : $created_booking->deposit);
        $created_booking->wallet_credit_used = floatval($credit);
        $created_booking->wallet_total_used = floatval($wallet_total_used);
        $created_booking->total_before_fees = floatval($wallet_total_used);
        $created_booking->vendor_service_fee_amount = 0;
        $created_booking->vendor_service_fee = "";

        $created_booking->save();
        return $this->sendSuccess(['booking' => $created_booking], __("You booking " . $object_model . " has been processed successfully"));
    }
}
