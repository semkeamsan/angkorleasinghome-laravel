@php
$hotel_page_search_price = setting_item('hotel_page_search_price');
if (!empty($hotel_page_search_price)) {
   $hotel_page_search_price = json_decode($hotel_page_search_price, true);
}
if (empty($hotel_page_search_price) or !is_array($hotel_page_search_price)) {
   $hotel_page_search_price = [];
}
$hotel_page_search_price = collect($hotel_page_search_price);
$price_min = $pri_from = floor(App\Currency::convertPrice($hotel_page_search_price->first()['from']));
$price_max = $pri_to = ceil(App\Currency::convertPrice($hotel_page_search_price->last()['to']));
if (!empty($price_range = Request::query('price_range'))) {
$pri_from = explode(";", $price_range)[0];
$pri_to = explode(";", $price_range)[1];
}
$currency = App\Currency::getCurrency(App\Currency::getCurrent());
@endphp
<div class="item filter-item dropdown filter-simple" id="price-range-dropdown">
    <span class="d-none text-gray-1 text-left font-weight-normal">
        {{ $field['title'] ?? '' }}
    </span>
    <div class="mb-4" data-toggle="dropdown">
        <div class="input-group bg-gray border-bottom border-width-2 border-color-1 py-2 px-2">
            <span class="align-items-center d-flex  font-size-22 font-weight-semi-bold mr-2 text-primary">{{$currency['symbol'] ?? ''}}</span>
            <div class="smart-search border-0 p-0 form-control bg-gray height-40" style="padding-top: 2px !important;">
             
               <span id="show-price-range">
                    <input type="text" class=" parent_text  font-weight-bold font-size-16 shadow-none hero-form font-weight-bold border-0 p-0" readonly="" placeholder=" {{ $pri_from }} → {{ $pri_to }}" >
               </span>
            </div>
        </div>
    </div>
    <div class="filter-dropdown dropdown-menu dropdown-menu-left">
        <div class="bravo-filter-price">    
            
            <span class="d-block font-size-lg-15 font-size-17 font-weight-bold text-dark mb-2">{{ __("Price Range") }} ({{$currency['symbol'] ?? ''}})</span>
            <div class="pb-3 mb-1 text-lh-1 form-row">
                <div class="col">
                    <div class="mb-2">{{ __('Min') }}</div>
                    <div class="input-group input-group-sm">
                        
                        <input type="number" id="rangeSliderMinResult" class="form-control" min="{{$price_min}}">
                            <div class="input-group-append">
                            <span class="input-group-text">{{$currency['symbol'] ?? ''}}</span>
                          </div>
                        
                       </div>
                </div>
            
               <div class="col">
                <div class="float-right mb-2">{{ __('Max') }}</div>
                <div class="input-group input-group-sm">
               
                    <input type="number" id="rangeSliderMaxResult" class="form-control" min="{{$price_min}}"  max="{{$price_max}}"/>
                        <div class="input-group-append">
                        <span class="input-group-text">{{$currency['symbol'] ?? ''}}</span>
                      </div>
                    
                   </div>
               </div>
               
               
             
            </div>
            <input class="filter-price" type="text" name="price_range"
                   data-extra-classes="u-range-slider height-35"
                   data-type="double"
                   data-grid="false"
                   data-hide-from-to="true"
                    data-step="{{$hotel_page_search_price->first()['increment']}}"
                   data-min="{{$price_min}}"
                   data-max="{{$price_max}}"
                  
                   data-from="{{$pri_from}}"
                   data-to="{{$pri_to}}"
                   data-prefix="{{$currency['symbol'] ?? ''}}"
                   data-result-min="#rangeSliderMinResult"
                   data-result-max="#rangeSliderMaxResult">
            <div class="text-right">
                <a href="#" onclick="return false;" class="btn btn-primary btn-sm btn-apply-advances">{{__("APPLY")}}</a>
            </div>
        </div>
    </div>
</div>