<div class="bravo-list-news">
    <div class="deals-carousel-block deals-carousel-v1 border-color-8">
        <div class="container space-1">
            <div class="d-flex justify-content-between mb-3 pt-md-3 mt-1 pb-md-3 mb-md-2 align-items-end">
                <div class="font-weight-bold font-size-xs-20 font-size-30 mb-0 text-lh-sm">
                    <small class="font-size-xs-16 font-size-14 mb-0 text-lh-sm d-block mt-1">
                        <span class="arrow"><span class="line"></span></span>
                        {{ __('Our Blog') }}
                    </small>
                    <h2 class="text-left mt-3">{{$title}}</h2>
                </div>

            </div>

            <div class="travel-slick-carousel u-slick u-slick--gutters-3" data-slides-show="3" data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-classic--v2 u-slick__arrow-centered--y rounded-circle" data-arrow-left-classes="flaticon-back u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-xl-n8" data-arrow-right-classes="flaticon-next u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-xl-n8" data-pagi-classes="d-lg-none text-center u-slick__pagination mt-4" data-responsive='[ { "breakpoint": 1025, "settings": { "slidesToShow": 3 } }, { "breakpoint": 992, "settings": { "slidesToShow": 2 } }, { "breakpoint": 768, "settings": { "slidesToShow": 1 } }, { "breakpoint": 554, "settings": { "slidesToShow": 1 } } ]'>
                @foreach($rows as $row)
                    <div class="col-lg-4 col-md-6">
                        @include('News::frontend.blocks.list-news.loop')
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
