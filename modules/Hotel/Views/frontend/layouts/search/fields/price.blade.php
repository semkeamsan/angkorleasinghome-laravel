<div class="item bg-gray d-none">
    <span class="d-none text-gray-1 text-left font-weight-normal">
        {{ $field['title'] ?? '' }}
    </span>
    {{-- <div class="border-bottom bg-gray px-2 border-width-2 border-color-1 mb-4 form-content">
        <div class="input-group py-2 flex-nowrap form-price-search">
            <div class="input-group-prepend">
                <span class="d-flex align-items-center mr-2 font-size-21">
                    {{ $currency['symbol'] ?? '' }}
                </span>
            </div>
            @php
                $hotel_page_search_price = setting_item('hotel_page_search_price');
                if (!empty($hotel_page_search_price)) {
                    $hotel_page_search_price = json_decode($hotel_page_search_price, true);
                }
                if (empty($hotel_page_search_price) or !is_array($hotel_page_search_price)) {
                    $hotel_page_search_price = [];
                }
            @endphp
            <select name="price_range" class="w-100 p-2 border">
                @foreach ($hotel_page_search_price as $price)
                    @for ($i = $price['from']; $i < $price['to']; $i += $price['increment'])
                        <option value="{{ $i }};{{ $i + $price['increment'] }}">{{ $i }} -
                            {{ $i + $price['increment'] }}</option>
                    @endfor
                @endforeach
            </select>
        </div>
    </div> --}}
    {{-- @php
        $hotel_min_max_price = Modules\Hotel\Models\Hotel::getMinMaxPrice();
        $price_min = $pri_from = floor(App\Currency::convertPrice($hotel_min_max_price[0]));
        $price_max = $pri_to = ceil(App\Currency::convertPrice($hotel_min_max_price[1]));
        $currency = App\Currency::getCurrency(App\Currency::getCurrent());
    @endphp

    <div class="p-2 border-width-2 border-color-1 mb-4 form-content">
        <div class="pb-1 mb-1 d-flex text-lh-1">
            <span>{{ $currency['symbol'] ?? '' }}</span>
            <span id="rangeSliderMinResult">{{ $price_min }}</span>
            <span class="mx-0dot5"> — </span>
            <span>{{ $currency['symbol'] ?? '' }}</span>
            <span id="rangeSliderMaxResult">{{ $price_max }}</span>
        </div>
       <input class="front-price d-none" type="text" name="price_range"
               data-extra-classes="u-range-slider"
               data-type="double"
               data-grid="false"
               data-step="50"
               data-hide-from-to="true"
               data-min="{{$price_min}}"
               data-max="{{$price_max}}"
               data-from="50"
               data-to="{{ $price_max }}"
               data-prefix="{{$currency['symbol'] ?? ''}}">


    </div> --}}
</div>
<div class="item">
    <span class="d-none text-gray-1  font-weight-normal mb-0 text-left">
        {{ $field['title'] ?? '' }}
    </span>
    <div class="mb-4">
        <div class="input-group bg-gray border-bottom border-width-2 border-color-1 px-2 py-2">
            <i class="icofont-dollar d-flex align-items-center mr-2 text-primary font-weight-semi-bold font-size-22">
                {{ $currency['symbol'] ?? '' }}
            </i>

            <?php
            $list_json = [];
            $hotel_page_search_price = setting_item('hotel_page_search_price');
            if (!empty($hotel_page_search_price)) {
                $hotel_page_search_price = json_decode($hotel_page_search_price, true);
            }
            if (empty($hotel_page_search_price) or !is_array($hotel_page_search_price)) {
                $hotel_page_search_price = [];
            }

            ?>
            @foreach ($hotel_page_search_price as $price)
                @for ($i = $price['from']; $i < $price['to']; $i += $price['increment'])
                    @php
                        $list_json[] = [
                            'id' => $i . ';' . ($i + $price['increment']),
                            'title' => $i . ' → ' . ($i + $price['increment']),
                        ];
                    @endphp

                @endfor
            @endforeach
            @php
                 $list_json[] = [
                        'id' => collect($hotel_page_search_price)->last()['to'] . ';1000000',
                        'title' => collect($hotel_page_search_price)->last()['to'] . ' → '. __('Up'),
                    ];
                $name =  str_replace(';',' → ', request('price_range', $list_json[0]['title']));
                $name =  str_replace('1000000',__('Up'), $name);
                $list_json[] = [
                        'id' => 'all',
                        'title' => __('All'),
                ];
            @endphp
            <div class="smart-search  bg-gray border-0 p-0 form-control  height-40">
                <input type="text"
                    class="smart-select parent_text font-weight-bold font-size-16 shadow-none hero-form font-weight-bold border-0 p-0"
                    readonly placeholder="{{ __('Price') }}"  data-onLoad="{{ __('Loading...') }}"
                    data-default="{{ json_encode($list_json) }}" data-icon="icofont-dollar"
                    value="{{ $name }}">
                <input type="hidden" class="child_id" name="price_range" value="{{ request('price_range',$list_json[0]['id']) }}">
            </div>
        </div>
    </div>
</div>
