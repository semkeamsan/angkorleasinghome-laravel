
{{-- Room Lease Expert --}}
@isset($terms)
<div id="front-terms" class="d-none">
    <div class="container space-bottom-1">
        <div class="row mb-3 pt-md-3 mt-1 pb-md-3 mb-md-2 align-items-end">
            <div class="col-xl-4 offset-xl-4 font-weight-bold font-size-xs-20 font-size-30 mb-0 text-lh-sm">
                @if (setting_item('term_subtitle'))
                    <small class="font-size-xs-16 font-size-14 mb-0 text-lh-sm d-block mt-1" id="subtitle">
                        <span class="arrow"><span class="line"></span></span>
                        {{ setting_item_with_lang('term_subtitle',request()->query('lang')) }}
                    </small>
                @endif

                <h1 id="title">{{ setting_item_with_lang('term_title',request()->query('lang')) }}</h1>
            </div>
        </div>
        <div class="row mb-1">

            @foreach ($terms as $item)

            <div class=" {{ $loop->last ? 'col-md-6 col-xl-8 col-sm-4' : 'col-md-6 col-xl-4 col-sm-4' }}  mb-xl-3 mb-md-4">
                <a href="/hotel/?terms[]={{ $item->id }}" class="text-white font-weight-bold font-size-21 mb-3 text-lh-1 d-block">
                    <div class="min-height-350 bg-img-hero rounded-border border-0 position-relative" style="background-image: url({{ $item->image() }});">
                        <div class="bottom-0 position-absolute text-center py-5 w-100">
                            <div>
                                <h1 class="m-0 text-white"> {{ $item->name }}</h1>
                                <p class="text-white"><small>{{ $item->hotel_count }} Properties</small></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            @endforeach
            @if (setting_item('term_enable_travel'))
                <div class="{{ $terms->count() ?'col-xl-4' : 'col-xl-12' }} mb-xl-3 mb-md-4">
                    <a href="/page/home-tour" class="text-white font-weight-bold font-size-21 mb-3 text-lh-1 d-block">
                        <div class="min-height-350 bg-img-hero rounded-border border-0 position-relative" style="background-image: url(/uploads/0000/1/2021/12/06/ta-prohm-temple-angkor-travel-guide-winetraveler.jpg);">
                            <div class="bottom-0 position-absolute text-center py-5 w-100" style="background: #0a0a0a6b;">
                                <div>
                                    <h1 class="m-0"> {{__('Travel')}}</h1>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endisset

{{-- Vision --}}

@if (setting_item('vision_title'))
{{-- <style>
    #our-vision:before {
        opacity: 1;
        background-image: url({{ $vision->image }});
}
</style> --}}
<div id="front-vision" class="d-none">
    <div class="container">
        <div class="position-relative text-center z-index-1 text-center">
            <h1 class="text-black mb-0">
                {{ setting_item('vision_title') }}
            </h1>
        </div>
        <div class="row p-5 text-center">
            <div class="col-xl-5 offset-xl-4">
                <?php
                $vision_details = setting_item_with_lang('vision_details',request()->query('lang'));
                if(!empty($vision_details)) $vision_details = json_decode($vision_details,true);
                if(empty($vision_details) or !is_array($vision_details))
                $vision_details = [];
                ?>
                @foreach ($vision_details as $key => $item)

                <div class="input-group mb-4">
                    <div class="boxshape {{  @$item['style'] }}">
                        <h2 class="text">{{ $key + 1 }}</h2>
                    </div>
                    <div class="form-control px-xl-10 py-4 text-center" style="border-color:#777232;min-height: 92px;height:100%; border-radius: 10px;border-color:#777232;">
                        <p class="font-size-18 m-0">{!! str_replace('\n', '<br>' ,@$item['val']) !!}</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
        <div class="row p-5">
            <div class="col">
                <div>
                    <h1>{{ __('Our Values') }}</h1>
                </div>
                <?php
                    $vision_values = setting_item_with_lang('vision_values',request()->query('lang'));
                    if(!empty($vision_values)) $vision_values = json_decode($vision_values,true);
                    if(empty($vision_values) or !is_array($vision_values))
                    $vision_values = [];
                ?>
                @if ( count($vision_values))
                <div class="container px-2">
                    <div class="row">
                        @foreach ($vision_values as $key => $item)
                        <div class="col-xl-6 py-3">
                            <div class="ml-7">
                                <span class="position-absolute font-size-18 left-0 border rounded-circle r-circle r-circle-{{ @$item['style'] }}">{{ $key+1 }}</span>
                                <span class="font-size-18">{{ @$item['val'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
        <div class="row p-5">
            <div class="col-xl-12">
                <div class="d-flex">
                    <h1>{{setting_item_with_lang('benefit_sub_title',request()->query('lang')) }}</h1>
                    <span class="ml-3 my-auto font-size-18">
                        {{setting_item_with_lang('benefit_sub_subtitle',request()->query('lang')) }}
                    </span>
                </div>
            </div>
            <?php
                $vision_actions = setting_item_with_lang('vision_actions',request()->query('lang'));
                if(!empty($vision_actions)) $vision_actions = json_decode($vision_actions,true);
                if(empty($vision_actions) or !is_array($vision_actions))
                $vision_actions = [];
            ?>
            @if (count($vision_actions))
            <div class="col-xl-12 text-center">
                <ul class="list-inline mb-0">

                    @foreach ($vision_actions as $key => $item)

                    <li class="list-inline-item m-3">
                        <a class="btn btn-icon btn-social text-white" href="{{ @$item['link'] }}" style="background:var(--color-golden) !important;height:3rem!important;width:3rem!important;height:3rem!important;width:3rem!important">
                            <i class="{{ @$item['icon'] }} btn-icon__inner"></i>
                        </a>

                        <p>{{ @$item['text'] }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

        </div>
    </div>
</div>
@endif

{{-- Benefit --}}
@if (setting_item('benefit_title'))
<div id="front-benefit" class="d-none">
    <div class="row">
        <div class="col-xl-6">
            @if (setting_item('benefit_subtitle') )
            <small class="font-size-xs-16 font-size-14 mb-0 text-lh-sm d-block mt-1">
                <span class="arrow"><span class="line"></span></span>
                {{ setting_item('benefit_subtitle') }}
            </small>
            @endif
            <h1 class="mb-5">{{ setting_item('benefit_title') }}</h1>
            <p class="mb-6 font-size-18">{{ setting_item('benefit_detail') }}</p>
            @if (setting_item('benefit_link'))
            <a class="btn btn-danger arrow-link" href="{{ setting_item('benefit_link') }}">{{setting_item('benefit_link_title') }}</a>
            @endif

        </div>
        <?php
            $benefit_images = setting_item_with_lang('benefit_images',request()->query('lang'));
            if(!empty($benefit_images)) $benefit_images = json_decode($benefit_images,true);
            if(empty($benefit_images) or !is_array($benefit_images))
            $benefit_images = [];
        ?>
        <div class="col-xl-4 pl-xl-10 text-center py-3">
            @if (setting_item('benefit_images'))
            <div class="owl-carousel h-100">
                @foreach ($benefit_images as $item)
                <div class="item bg-dark p-2">
                    <img class="w-100 h-100" src="{{ \Modules\Media\Helpers\FileHelper::url(@$item['image'])  }}">
                    <div class="caption caption position-absolute text-white">
                        <p class="font-size-20 mb-0 text-white">{{ @$item['title'] }}</p>
                        <small>{{ @$item['subtitle'] }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
@endif

{{-- Mission --}}
<div id="front-mission" class="d-none">
    <div class="row">
        <div class="col-xl-12 p-0">
            <div class="card bg-transparent border-0">
                <div class="card-body row">
                    @if (setting_item('mission_image'))
                        <div class="col-sm-12 col-xl-6 mb-3"><img class="w-100" src="{{ \Modules\Media\Helpers\FileHelper::url(setting_item('mission_image'))  }}" alt="" /></div>
                    @endif

                    <div class="col-xl-5 ml-xl-5 my-auto">
                        @if (setting_item('mission_subtitle'))
                        <small class="font-size-xs-16 font-size-14 mb-0 text-lh-sm d-block mt-1">
                            <span class="arrow"> <span class="line"></span> </span>
                             {{ setting_item('mission_subtitle') }}
                            </small>
                        @endif
                        <h1 class="mb-5"> {{ setting_item('mission_title') }}</h1>
                        <p class="mb-5 font-size-20">{{ setting_item('mission_detail') }}</p>
                        <a class="btn btn-danger  arrow-link" href="{{ setting_item('mission_link') }}">{{ setting_item('mission_link_title') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Map --}}
<div id="front-map" class="d-none">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2">
                <div class="row bg-gray">
                    <div class="col-xl-8">
                        <h1 class="p-2">{{ __('Location is always an asset') }}</h1>
                        {!! setting_item("page_contact_iframe_google_map") !!}

                    </div>
                    <div class="col-sm-12 col-xl-4 py-3">
                        <div class="py-xl-15 float-xl-right text-center">
                            <p class="mb-8">
                                <a id="see-on-map" class="btn btn-danger mb-3 arrow-link" target="_blank" href="{{  setting_item("page_contact_link_google_map") }}">{{ __('SEE ON MAP') }}</a>
                            </p>
                            <p class="border-bottom font-size-18" style="width: fit-content;">{{ setting_item("location_contact") }}</p>
                            <p class="border-bottom font-size-18" style="width: fit-content;"> {{ setting_item("phone_contact") }}</p>
                            <p class="font-size-18" style="width: fit-content;">
                                <a href="mailto:{{ setting_item("email_from_address") }}">
                                    {{ setting_item("email_from_address") }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!is_api())


<div class="bravo_footer mt-4 border-top">
    <div class="main-footer">
        <div class="container">
            <div class="row justify-content-xl-between mb-3">
                @if(!empty($info_contact = clean(setting_item_with_lang('footer_info_text'))))
                <div class="col-12 col-lg-4 col-xl-3dot1 mb-6 mb-md-10 mb-xl-0">
                    {!! clean($info_contact) !!}
                </div>
                @endif
                @if($list_widget_footers = setting_item_with_lang("list_widget_footer"))
                <?php $list_widget_footers = json_decode($list_widget_footers);?>
                @foreach($list_widget_footers as $key=>$item)
                <div class="col-12 col-md-6 col-lg-{{$item->size ?? '3'}} col-xl-1dot8 mb-6 mb-md-10 mb-xl-0">
                    <div class="nav-footer">
                        <h4 class="h6 font-weight-bold mb-2 mb-xl-4">{{$item->title}}</h4>
                        {!! clean($item->content) !!}
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-xl-6 offset-xl-3">
                    <div class="mb-4 mb-xl-2">
                        <h4 class="h6 font-weight-bold mb-2 text-center mb-xl-3">{{ __('Sign up for our mailing list to get latest updates and offers.') }}</h4>
                        <p class="m-0 text-gray-1 d-none">{{ __('Sign up for our mailing list to get latest updates and offers.') }}</p>
                    </div>
                    <form action="{{route('newsletter.subscribe')}}" class="subcribe-form bravo-subscribe-form bravo-form">
                        @csrf
                        <div class="bg-white border input-group rounded-pill">
                            <input type="text" name="email" class="form-control rounded-left-pill border-right-0 height-54 font-size-14 border-0  email-input" placeholder="{{__('Enter Your Email Address')}}">
                            <div class="input-group-append">
                                <button type="submit" class="btn-submit btn btn-danger rounded-pill height-54 min-width-112 font-size-14 ml-1">{{__('Subscribe')}}
                                    <i class="fa fa-spinner fa-pulse fa-fw"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-mess"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="border-top border-bottom border-color-8 space-1 d-none">
        <div class="container">
            <div class="sub-footer d-flex align-items-center justify-content-between">
                <a class="d-inline-flex align-items-center" href="{{ url('/') }}" aria-label="AngkorLeasing">
                    {!! get_image_tag(setting_item_with_lang('logo_id_2')) !!}
                    <span class="brand brand-dark">{{ setting_item_with_lang('logo_text') }}</span>
                </a>
                <div class="footer-select bravo_topbar d-flex align-items-center">
                    <div class="mr-3">
                        @include('Language::frontend.switcher')
                    </div>
                    @include('Core::frontend.currency-switcher')
                </div>
            </div>
        </div>
    </div>
    <div class="copy-right">
        <div class="container context">
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! setting_item_with_lang("footer_text_left") ?? '' !!}
                    <div class="f-visa d-none">
                        {!! setting_item_with_lang("footer_text_right") ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
<a class="travel-go-to u-go-to-modern bg-transparent border" href="#" data-position='{"bottom": 15, "right": 15 }' data-type="fixed" data-offset-top="400" data-compensation="#header" data-show-effect="slideInUp" data-hide-effect="slideOutDown">
    <span class="flaticon-arrow u-go-to-modern__inner"></span>
</a>
@include('Layout::parts.login-register-modal')
@include('Layout::parts.chat')
@if(Auth::id())
@include('Media::browser')
@endif
<link rel="stylesheet" href="{{asset('libs/flags/css/flag-icon.min.css')}}">

{!! \App\Helpers\Assets::css(true) !!}

{{--Lazy Load--}}
<script src="{{asset('libs/lazy-load/intersection-observer.js')}}"></script>
<script async src="{{asset('libs/lazy-load/lazyload.min.js')}}"></script>
<script>
    // Set the options to make LazyLoad self-initialize
    window.lazyLoadOptions = {
        elements_selector: ".lazy",
        // ... more custom settings?
    };

    // Listen to the initialization event and get the instance of LazyLoad
    window.addEventListener('LazyLoad::Initialized', function(event) {
        window.lazyLoadInstance = event.detail.instance;
    }, false);

</script>

<script src="{{ asset('libs/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('libs/jquery-migrate/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('libs/header.js') }}"></script>
<script type="text/javascript" src="{{ asset("libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
<link rel="stylesheet" href="{{  asset("libs/ion_rangeslider/css/ion.rangeSlider.css") }}">
<script>
    $(document).on('ready', function() {
        $.MyTravelHeader.init($('#header'));
        $(`#our-mission`).html($(`#front-mission`).html());
        $(`#our-vision`).html($(`#front-vision`).html());
        $(`#our-vision`).parents('.container').wrap(`<div class="bg-gray">`);
        $(`#room-lease-expert`).html($(`#front-terms`).html());
        if ($(`#front-benefit`).length) {
            $(`#benefits`).css({
                background: 'url(/images/Group.png) no-repeat top'
            , }).addClass('pt-xl-7 mt-2').html($(`#front-benefit`).html());
            $(`#benefits`).find('.owl-carousel').owlCarousel({
                items: 1
                , loop: true
                , margin: 30
                , nav: false
                , dots: true
            , });
        } else {
            $(`#benefits`).remove();
        }

        $(".front-price").each(function() {

            $(this).ionRangeSlider({
                hide_min_max: true
                , hide_from_to: true
                , type: $(this).data('min') ? $(this).data('min') : "double"
                , min: $(this).data('min') ? $(this).data('min') : 0
                , max: $(this).data('max') ? $(this).data('max') : 0
                , from: $(this).data('from') ? $(this).data('from') : 0
                , to: $(this).data('to') ? $(this).data('to') : 0
                , step: $(this).data('step') ? $(this).data('step') : 50
                , prefix: "$"
                , onChange: (data)=>{
                    $(`#rangeSliderMinResult`).text(data.from);
                    $(`#rangeSliderMaxResult`).text(data.to);
                },
                onFinish : (data)=>{
                    if(data.to > 500){
                        $(this).data("ionRangeSlider").update({
                            step: 500,
                        });
                    }else{
                        $(this).data("ionRangeSlider").update({
                            step: 50,
                        });
                    }
                }
            });

        });
    $(`#map`).html($(`#front-map`).html());

    });

</script>
<script src="{{ asset('libs/lodash.min.js') }}"></script>
<script src="{{ asset('libs/vue/vue'.(!env('APP_DEBUG') ? '.min':'').'.js') }}"></script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/bootbox/bootbox.min.js') }}"></script>

<script src="{{ asset('libs/fancybox/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('libs/slick/slick.js') }}"></script>


@if(Auth::id())
<script src="{{ asset('module/media/js/browser.js?_ver='.config('app.version')) }}"></script>
@endif
<script src="{{ asset('libs/carousel-2/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset("libs/daterange/moment.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("libs/daterange/daterangepicker.min.js") }}"></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/functions.js?_ver='.config('app.version')) }}"></script>
<script src="{{asset('libs/custombox/custombox.min.js')}}"></script>
<script src="{{asset('libs/custombox/custombox.legacy.min.js')}}"></script>
<script src="{{ asset('libs/custombox/window.modal.js') }}"></script>

@if(
setting_item('tour_location_search_style')=='autocompletePlace' || setting_item('hotel_location_search_style')=='autocompletePlace' || setting_item('car_location_search_style')=='autocompletePlace' || setting_item('space_location_search_style')=='autocompletePlace' || setting_item('hotel_location_search_style')=='autocompletePlace' || setting_item('event_location_search_style')=='autocompletePlace'
)
{!! App\Helpers\MapEngine::scripts() !!}
@endif

<script src="{{ asset('libs/pusher.min.js') }}"></script>
<script src="{{ asset('js/home.js?_ver='.config('app.version')) }}"></script>

@if(!empty($is_user_page))
<script src="{{ asset('module/user/js/user.js?_ver='.config('app.version')) }}"></script>
@endif
@if(setting_item('cookie_agreement_enable')==1 and request()->cookie('booking_cookie_agreement_enable') !=1 and !is_api() and !isset($_COOKIE['booking_cookie_agreement_enable']))
<div class="booking_cookie_agreement p-3 fixed-bottom">
    <div class="container d-flex ">
        <div class="content-cookie">{!! setting_item_with_lang('cookie_agreement_content') !!}</div>
        <button class="btn save-cookie">{!! setting_item_with_lang('cookie_agreement_button_text') !!}</button>
    </div>
</div>
<script>
    var save_cookie_url = "{{route('core.cookie.check')}}";

</script>
<script src="{{ asset('js/cookie.js?_ver='.config('app.version')) }}"></script>
@endif

{!! \App\Helpers\Assets::js(true) !!}

@yield('footer')

@php \App\Helpers\ReCaptchaEngine::scripts() @endphp
