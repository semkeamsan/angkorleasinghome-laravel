@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp

<div class="item mb-4">
    <div class="bg-danger border h-20 width-60 px-3 position-absolute right--2 text-white z-index-1 overflow-hidden " style="right: 15px">
        {{ $row->created_at->format('d') }}
        <span class="arrow">
            <span class="line line-sm bg-white w-100 m-0"></span>
        </span>
        {{ $row->created_at->format('M') }}
    </div>
    <a class="d-block rounded-xs overflow-hidden mb-3" href="{{$row->getDetailUrl()}}">
        @if($row->image_id)
            @if(!empty($disable_lazyload))
                <img src="{{get_file_url($row->image_id,'medium')}}" class="img-fluid w-100" alt="{{$translation->name ?? ''}}">
            @else
                {!! get_image_tag($row->image_id,'medium',['class'=>'img-fluid w-100','alt'=>$row->title]) !!}
            @endif
        @endif
    </a>
    <h6 class="font-size-17 pt-xl-1 font-weight-bold font-weight-bold mb-1 text-gray-6">
        <a href="{{$row->getDetailUrl()}}">
            {!! clean($translation->title) !!}
        </a>
    </h6>
    <a class="text-gray-1" href="{{$row->getDetailUrl()}}">
        <span> {!! get_exceprt($translation->content,70,"...") !!}</span>
    </a>
    <p>
        <a href="{{$row->getDetailUrl()}}" class="pl-0 font-weight-lighter btn arrow-link-sm arrow-link arrow-link-danger"> Read more</a>
    </p>
</div>

