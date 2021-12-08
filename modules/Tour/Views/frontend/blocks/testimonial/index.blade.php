@if($list_item)
<div class="bravo-testimonial bg-gray testimonial-block testimonial-v1 border-bottom border-color-8">
    <div class="container space-1">
        <div class="">
            <h1 class="text-black mb-0">
                {{$title}}
            </h1>
            <p>What our guests are saying about us</p>
        </div>
        <!-- Slick Carousel -->
        <div class="travel-slick-carousel u-slick u-slick--equal-height u-slick-bordered-primary u-slick--gutters-3 mb-4 pb-1" data-slides-show="3" data-center-mode="true" data-autoplay="true" data-speed="3000" data-pagi-classes="text-center u-slick__pagination mb-0 mt-n6" data-responsive='[ { "breakpoint": 992, "settings": { "slidesToShow": 2 } }, { "breakpoint": 768, "settings": { "slidesToShow": 1 } } ]'>
            @foreach($list_item as $item)
            <?php $avatar_url = get_file_url($item['avatar'], 'full') ?>
            <div class="item">
                <!-- Testimonials -->
                <div class="card rounded-xs border-0 w-100 bg-transparent">
                    <div class="card-body p-5 border-color-7 border  bg-white">
                        <div class="mb-5 text-center">
                                <h5>Nice place</h5>
                            <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary">
                                <div class="green-lighter ml-1 letter-spacing-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                            </label>
                        </div>
                        <p class="mb-0 text-gray-1 text-lh-inherit">
                            {{$item['desc']}}
                        </p>
                    </div>
                    <div class="card-body position-relative">
                        <div class="d-flex">
                            <div class="ml-3 arrowdown">
                                <img class="img-fluid rounded-circle" src="{{$avatar_url}}" alt="{{$item['name']}}">
                            </div>
                            <div class="py-5 ml-2">
                                <h6 class="font-size-17 font-weight-bold text-gray-6 mb-0">{{$item['name']}}</h6>
                                <span class="text-muted font-size-normal">{{$item['position']}}</span>
                            </div>
                            <figure class="d-none" id="quote1">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56px" height="48px" class="injected-svg js-svg-injector" data-parent="#quote1">
                                    <text kerning="auto" font-family="Lato" fill="rgb(235, 240, 247)" font-weight="bold" font-size="150px" x="0px" y="109.401px">“</text>
                                </svg>
                            </figure>
                        </div>

                    </div>
                </div>

                <!-- End Testimonials -->
            </div>
            @endforeach
        </div>
        <!-- End Slick Carousel -->
    </div>
</div>
@endif
