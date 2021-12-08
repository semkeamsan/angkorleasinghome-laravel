<div class="item bg-gray" style="height: 56px;">
    <span class="d-none text-gray-1 text-left font-weight-normal">
        {{ $field['title'] ?? "" }}
    </span>
    <div class="p-2 border-width-2 border-color-1 mb-4 form-content">
        <div class="pb-1 mb-1 d-flex text-lh-1">
            <span>$</span>
            <span id="rangeSliderMinResult">100</span>
            <span class="mx-0dot5"> — </span>
            <span>$</span>
            <span id="rangeSliderMaxResult">800</span>
        </div>
        <input class="front-price d-none" type="text" name="price_range"
               data-extra-classes="u-range-slider"
               data-type="double"
               data-grid="false"
               data-hide-from-to="true"
               data-min="300"
               data-max="900"
               data-from="300"
               data-to="800"
               data-prefix="{{$currency['symbol'] ?? ''}}">
    </div>
</div>
