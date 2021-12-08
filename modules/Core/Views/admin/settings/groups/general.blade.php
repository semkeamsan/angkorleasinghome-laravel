

<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Site Information")}}</h3>
<p class="form-group-desc">{{__('Information of your website for customer and goole')}}</p>
</div>
<div class="col-sm-8">
    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label class="">{{__("Site title")}}</label>
                <div class="form-controls">
                    <input type="text" class="form-control" name="site_title" value="{{setting_item_with_lang('site_title',request()->query('lang'))}}">
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Site Desc")}}</label>
                <div class="form-controls">
                    <textarea name="site_desc" class="form-control" cols="30" rows="7">{{setting_item_with_lang('site_desc',request()->query('lang'))}}</textarea>
                </div>
            </div>

            @if(is_default_lang())
            <div class="form-group">
                <label class="">{{__("Favicon")}}</label>
                <div class="form-controls form-group-image">
                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('site_favicon',$settings['site_favicon'] ?? "") !!}
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Date format")}}</label>
                <div class="form-controls">
                    <input type="text" class="form-control" name="date_format" value="{{$settings['date_format'] ?? 'm/d/Y' }}">
                </div>
            </div>
            @endif
            @if(is_default_lang())
            <div class="form-group">
                <label>{{__("Timezone")}}</label>
                <div class="form-controls">
                    <select name="site_timezone" class="form-control">
                        <option value="UTC">{{__("-- Default --")}}</option>
                        @if(!empty($timezones = generate_timezone_list()))
                        @foreach($timezones as $item=>$value)
                        <option @if($item==($settings['site_timezone'] ?? '' ) ) selected @endif value="{{$item}}">{{$value}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Change the first day of week for the calendars")}}</label>
                <div class="form-controls">
                    <select name="site_first_day_of_the_weekin_calendar" class="form-control">
                        <option @if("1"==($settings['site_first_day_of_the_weekin_calendar'] ?? '' ) ) selected @endif value="1">{{__("Monday")}}</option>
                        <option @if("0"==($settings['site_first_day_of_the_weekin_calendar'] ?? '' ) ) selected @endif value="0">{{__("Sunday")}}</option>
                    </select>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>

<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Language')}}</h3>
        <p class="form-group-desc">{{__('Change language of your websites')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                @if(is_default_lang())
                <div class="form-group">
                    <label>{{__("Select default language")}}</label>
                    <div class="form-controls">
                        <select name="site_locale" class="form-control">
                            <option value="">{{__("-- Default --")}}</option>
                            @php
                            $langs = \Modules\Language\Models\Language::getActive();
                            @endphp

                            @foreach($langs as $lang)
                            <option @if($lang->locale == ($settings['site_locale'] ?? '') ) selected @endif value="{{$lang->locale}}">{{$lang->name}} - ({{$lang->locale}})</option>
                            @endforeach
                        </select>
                        <p><i><a href="{{url('admin/module/language')}}">{{__("Manage languages here")}}</a></i></p>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Enable Multi Languages")}}</label>
                    <div class="form-controls">
                        <label><input type="checkbox" @if(setting_item('site_enable_multi_lang') ?? ''==1) checked @endif name="site_enable_multi_lang" value="1">{{__('Enable')}}</label>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label>{{__("Enable RTL")}}</label>
                    <div class="form-controls">
                        <label><input type="checkbox" @if(setting_item_with_lang('enable_rtl',request()->query('lang')) ?? '' == 1) checked @endif name="enable_rtl" value="1">{{__('Enable')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(is_default_lang())
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Contact Information')}}</h3>
        <p class="form-group-desc">{{__('How your customer can contact to you')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label>{{__("Admin Email")}}</label>
                    <div class="form-controls">
                        <input type="email" class="form-control" name="admin_email" value="{{$settings['admin_email'] ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Contact Location")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="location_contact" value="{{$settings['location_contact'] ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Email Form Name")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="email_from_name" value="{{$settings['email_from_name'] ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Email Form Address")}}</label>
                    <div class="form-controls">
                        <input type="email" class="form-control" name="email_from_address" value="{{$settings['email_from_address'] ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Phone Contact")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="phone_contact" value="{{$settings['phone_contact'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Homepage')}}</h3>
        <p class="form-group-desc">{{__('Change your homepage content')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label>{{__("Page for Homepage")}}</label>
                    <div class="form-controls">
                        <?php
                            $template = !empty($settings['home_page_id']) ? \Modules\Page\Models\Page::find($settings['home_page_id']) : false;

                            \App\Helpers\AdminForm::select2('home_page_id', [
                                'configs' => [
                                    'ajax' => [
                                        'url'      => url('/admin/module/page/getForSelect2'),
                                        'dataType' => 'json'
                                    ]
                                ]
                            ],
                                !empty($template->id) ? [$template->id, $template->title] : false
                            )
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Header & Footer Settings')}}</h3>
        <p class="form-group-desc">{{__('Change your options')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                @if(is_default_lang())
                <div class="form-group">
                    <label>{{__("Logo White")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('logo_id',$settings['logo_id'] ?? '') !!}
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Logo Color")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('logo_id_2',$settings['logo_id_2'] ?? '') !!}
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label>{{__("Logo Text")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="logo_text" value="{{ setting_item_with_lang('logo_text',request()->query('lang')) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Topbar Left Text")}}</label>
                    <div class="form-controls">
                        <div id="topbar_left_text_editor" class="ace-editor" style="height: 200px" data-theme="textmate" data-mod="html">{{setting_item_with_lang('topbar_left_text',request()->query('lang'))}}</div>
                        <textarea class="d-none" name="topbar_left_text"> {{ setting_item_with_lang('topbar_left_text',request()->query('lang')) }} </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Footer Info Contact")}}</label>
                    <div class="form-controls">
                        <div id="info_text_editor" class="ace-editor" style="height: 400px" data-theme="textmate" data-mod="html">{{setting_item_with_lang('footer_info_text',request()->query('lang'))}}</div>
                        <textarea class="d-none" name="footer_info_text"> {{ setting_item_with_lang('footer_info_text',request()->query('lang')) }} </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Footer List Widget")}}</label>
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="form-group-item">
                                <div class="g-items-header">
                                    <div class="row">
                                        <div class="col-md-3">{{__("Title")}}</div>
                                        <div class="col-md-2">{{__('Size')}}</div>
                                        <div class="col-md-6">{{__('Content')}}</div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                                <div class="g-items">
                                    <?php
                                    $social_share = setting_item_with_lang('list_widget_footer',request()->query('lang'));
                                    if(!empty($social_share)) $social_share = json_decode($social_share,true);
                                    if(empty($social_share) or !is_array($social_share))
                                        $social_share = [];
                                    ?>
                                    @foreach($social_share as $key=>$item)
                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" name="list_widget_footer[{{$key}}][title]" class="form-control" value="{{$item['title']}}">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" name="list_widget_footer[{{$key}}][size]">
                                                    <option @if(!empty($item['size']) && $item['size']=='3' ) selected @endif value="3">1/4</option>
                                                    <option @if(!empty($item['size']) && $item['size']=='4' ) selected @endif value="4">1/3</option>
                                                    <option @if(!empty($item['size']) && $item['size']=='6' ) selected @endif value="6">1/2</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea name="list_widget_footer[{{$key}}][content]" rows="5" class="form-control">{{$item['content']}}</textarea>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                </div>
                                <div class="g-more hide">
                                    <div class="item" data-number="__number__">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" __name__="list_widget_footer[__number__][title]" class="form-control" value="">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" __name__="list_widget_footer[__number__][size]">
                                                    <option value="3">1/4</option>
                                                    <option value="4">1/3</option>
                                                    <option value="6">1/2</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea __name__="list_widget_footer[__number__][content]" class="form-control" rows="5"></textarea>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Footer Text Left")}}</label>
                    <div class="form-controls">
                        <textarea name="footer_text_left" class="d-none has-ckeditor" cols="30" rows="10">{{setting_item_with_lang('footer_text_left',request()->query('lang')) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Footer Text Right")}}</label>
                    <div class="form-controls">
                        <textarea name="footer_text_right" class="d-none has-ckeditor" cols="30" rows="10">{{setting_item_with_lang('footer_text_right',request()->query('lang')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Page contact settings")}}</h3>
        <p class="form-group-desc">{{__('Settings for contact page')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label class="">{{__("Contact title")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="page_contact_title" value="{{setting_item_with_lang('page_contact_title',request()->query('lang'),"We'd love to hear from you")}}">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("List Contact")}}</label>
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="form-group-item">
                                <div class="g-items-header">
                                    <div class="row">
                                        <div class="col-md-4">{{__("Title")}}</div>
                                        <div class="col-md-7">{{__('Info Contact')}}</div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                                <div class="g-items">
                                    <?php
                                    $page_contact_lists = setting_item_with_lang('page_contact_lists',request()->query('lang'));
                                    if(!empty($page_contact_lists)) $page_contact_lists = json_decode($page_contact_lists,true);
                                    if(empty($page_contact_lists) or !is_array($page_contact_lists))
                                        $page_contact_lists = [];
                                    ?>
                                    @foreach($page_contact_lists as $key=>$item)
                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" name="page_contact_lists[{{$key}}][title]" class="form-control" value="{{$item['title']}}">
                                            </div>
                                            <div class="col-md-7">
                                                <label for="">{{ __("Address") }}</label>
                                                <input type="text" name="page_contact_lists[{{$key}}][address]" class="form-control" value="{{$item['address']}}">
                                                <label for="">{{ __("Phone") }}</label>
                                                <input type="text" name="page_contact_lists[{{$key}}][phone]" class="form-control" value="{{$item['phone']}}">
                                                <label for="">{{ __("Email") }}</label>
                                                <input type="text" name="page_contact_lists[{{$key}}][email]" class="form-control" value="{{$item['email']}}">
                                                <label for="">{{ __("Link View on Map") }}</label>
                                                <input type="text" name="page_contact_lists[{{$key}}][link_map]" class="form-control" value="{{$item['link_map']}}">
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                </div>
                                <div class="g-more hide">
                                    <div class="item" data-number="__number__">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">{{ __("Address") }}</label>
                                                <input type="text" __name__="page_contact_lists[__number__][title]" class="form-control" value="">
                                            </div>
                                            <div class="col-md-7">
                                                <label for="">{{ __("Address") }}</label>
                                                <input type="text" __name__="page_contact_lists[__number__][address]" class="form-control" value="">
                                                <label for="">{{ __("Phone") }}</label>
                                                <input type="text" __name__="page_contact_lists[__number__][phone]" class="form-control" value="">
                                                <label for="">{{ __("Email") }}</label>
                                                <input type="text" __name__="page_contact_lists[__number__][email]" class="form-control" value="">
                                                <label for="">{{ __("Link View on Map") }}</label>
                                                <input type="text" __name__="page_contact_lists[__number__][link_map]" class="form-control" value="">
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Iframe google map")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="page_contact_iframe_google_map" value="{{ $settings['page_contact_iframe_google_map'] ?? "" }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Link google map")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="page_contact_link_google_map" value="{{ $settings['page_contact_link_google_map'] ?? "" }}">
                    </div>
                </div>
                @if(is_default_lang())
                <div class="form-group">
                    <label>{{__("Contact Featured Image")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('page_contact_image',setting_item('page_contact_image')) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Benefit settings")}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label class="">{{__("Title")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="benefit_title" value="{{setting_item_with_lang('benefit_title',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("SubTitle")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="benefit_subtitle" value="{{setting_item_with_lang('benefit_subtitle',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Detail")}}</label>
                    <div class="form-controls">
                        <textarea class="form-control" name="benefit_detail">{{setting_item_with_lang('benefit_detail',request()->query('lang'))}} </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Images")}}</label>
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="form-group-item">
                                <div class="g-items-header">
                                    <div class="row">
                                        <div class="col-md-11">{{__("Image")}}</div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                                <div class="g-items">
                                    <?php
                                    $benefit_images = setting_item_with_lang('benefit_images',request()->query('lang'));
                                    if(!empty($benefit_images)) $benefit_images = json_decode($benefit_images,true);
                                    if(empty($benefit_images) or !is_array($benefit_images))
                                        $benefit_images = [];
                                    ?>
                                    @foreach($benefit_images as $key=>$item)

                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <label for="">{{ __("Ttile") }}</label>
                                                <input type="text" name="benefit_images[{{$key}}][title]" class="form-control" value="{{@$item['title']}}">
                                                <label for="">{{ __("Subtitle") }}</label>
                                                <input type="text" name="benefit_images[{{$key}}][subtitle]" class="form-control" value="{{@$item['subtitle']}}">
                                                <label>{{__("Image")}}</label>
                                                <div class="form-controls form-group-image">
                                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('benefit_images['.$key.'][image]',@$item['image']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                </div>
                                <div class="g-more hide">
                                    <div class="item" data-number="__number__">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <label for="">{{ __("Title") }}</label>
                                                <input type="text" __name__="benefit_images[__number__][title]" class="form-control" value="">
                                                <label for="">{{ __("Subtitle") }}</label>
                                                <input type="text" __name__="benefit_images[__number__][subtitle]" class="form-control" value="">
                                                <label for="">{{ __("Image") }}</label>
                                                <div class="form-controls form-group-image">
                                                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('benefit_images[__number__][image]',null) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Link Text")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="benefit_link_title" value="{{ $settings['benefit_link_title'] ?? "" }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Link")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="benefit_link" value="{{ $settings['benefit_link'] ?? "" }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Vision settings")}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label class="">{{__("Title")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="vision_title" value="{{setting_item_with_lang('vision_title',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Sub")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="vision_sub_title" value="{{setting_item_with_lang('vision_sub_title',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("SubTitle")}}</label>
                    <div class="form-controls">
                        <textarea class="form-control" name="vision_sub_subtitle">{{setting_item_with_lang('vision_sub_subtitle',request()->query('lang'))}} </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="form-group-item">
                                <div class="g-items-header">
                                    <div class="row">
                                        <div class="col-md-11">{{__("Details")}}</div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                                <div class="g-items">
                                    <?php
                                    $vision_details = setting_item_with_lang('vision_details',request()->query('lang'));
                                    if(!empty($vision_details)) $vision_details = json_decode($vision_details,true);
                                    if(empty($vision_details) or !is_array($vision_details))
                                        $vision_details = [];
                                    ?>
                                    @foreach($vision_details as $key=>$item)

                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="">{{ __("Text") }}</label>
                                                <input type="text" name="vision_details[{{$key}}][val]" class="form-control" value="{{@$item['val']}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __("Style") }}</label>
                                                <select name="vision_details[{{$key}}][style]" class="form-control">
                                                    <option {{ @$item['style'] != 'left'?: 'selected'  }} value="left">{{ __('Left') }}</option>
                                                    <option {{ @$item['style'] != 'right'?: 'selected'  }} value="right">{{ __('Right') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                </div>
                                <div class="g-more hide">
                                    <div class="item" data-number="__number__">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="">{{ __("Text") }}</label>
                                                <input type="text" __name__="vision_details[__number__][val]" class="form-control" value="">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __("Style") }}</label>
                                                <select __name__="vision_details[__number__][style]" class="form-control">
                                                    <option selected value="left">{{ __('Left') }}</option>
                                                    <option value="right">{{ __('Right') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="form-group-item">
                                <div class="g-items-header">
                                    <div class="row">
                                        <div class="col-md-11">{{__("Values")}}</div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                                <div class="g-items">
                                    <?php
                                    $vision_values = setting_item_with_lang('vision_values',request()->query('lang'));
                                    if(!empty($vision_values)) $vision_values = json_decode($vision_values,true);
                                    if(empty($vision_values) or !is_array($vision_values))
                                        $vision_values = [];
                                    ?>
                                    @foreach($vision_values as $key=>$item)

                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="">{{ __("Text") }}</label>
                                                <input type="text" name="vision_values[{{$key}}][val]" class="form-control" value="{{@$item['val']}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __("Style") }}</label>
                                                <select name="vision_values[{{$key}}][style]" class="form-control">
                                                    <option {{ @$item['style'] != 'left'?: 'selected'  }} value="left">{{ __('Left') }}</option>
                                                    <option {{ @$item['style'] != 'top'?: 'selected'  }} value="top">{{ __('Top') }}</option>
                                                    <option {{ @$item['style'] != 'right'?: 'selected'  }} value="right">{{ __('Right') }}</option>
                                                    <option {{ @$item['style'] != 'bottom'?: 'selected'  }} value="bottom">{{ __('Bottom') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                </div>
                                <div class="g-more hide">
                                    <div class="item" data-number="__number__">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="">{{ __("Text") }}</label>
                                                <input type="text" __name__="vision_values[__number__][val]" class="form-control" value="">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __("Style") }}</label>
                                                <select __name__="vision_values[__number__][style]" class="form-control">
                                                    <option selected value="left">{{ __('Left') }}</option>
                                                    <option value="top">{{ __('Top') }}</option>
                                                    <option value="right">{{ __('Right') }}</option>
                                                    <option value="bottom">{{ __('Bottom') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-controls">
                        <div class="form-group-item">
                            <div class="form-group-item">
                                <div class="g-items-header">
                                    <div class="row">
                                        <div class="col-md-11">{{__("Actions")}}</div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                                <div class="g-items">
                                    <?php
                                    $vision_actions = setting_item_with_lang('vision_actions',request()->query('lang'));
                                    if(!empty($vision_actions)) $vision_actions = json_decode($vision_actions,true);
                                    if(empty($vision_actions) or !is_array($vision_actions))
                                        $vision_actions = [];
                                    ?>
                                    @foreach($vision_actions as $key=>$item)

                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">{{ __("Text") }}</label>
                                                <input type="text" name="vision_actions[{{$key}}][text]" class="form-control" value="{{@$item['text']}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __("Icon") }}</label>
                                                <input type="text" name="vision_actions[{{$key}}][icon]" class="form-control" value="{{@$item['icon']}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">{{ __("Link") }}</label>
                                                <input type="text" name="vision_actions[{{$key}}][link]" class="form-control" value="{{@$item['link']}}">
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                                </div>
                                <div class="g-more hide">
                                    <div class="item" data-number="__number__">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">{{ __("Text") }}</label>
                                                <input type="text" __name__="vision_actions[__number__][text]" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __("Icon") }}</label>
                                                <input type="text" __name__="vision_actions[__number__][icon]" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">{{ __("Link") }}</label>
                                                <input type="text" __name__="vision_actions[__number__][link]" class="form-control">
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Mission settings")}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label class="">{{__("Title")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="mission_title" value="{{setting_item_with_lang('mission_title',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("SubTitle")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="mission_subtitle" value="{{setting_item_with_lang('mission_subtitle',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Detail")}}</label>
                    <div class="form-controls">
                        <textarea class="form-control" name="mission_detail">{{setting_item_with_lang('mission_detail',request()->query('lang'))}} </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Link Text")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="mission_link_title" value="{{ $settings['mission_link_title'] ?? "" }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("Link")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="mission_link" value="{{ $settings['mission_link'] ?? "" }}">
                    </div>
                </div>
                @if(is_default_lang())
                <div class="form-group">
                    <label>{{__("Image")}}</label>
                    <div class="form-controls form-group-image">
                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('mission_image',setting_item('mission_image')) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Term settings")}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label class="">{{__("Title")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="term_title" value="{{setting_item_with_lang('term_title',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="">{{__("SubTitle")}}</label>
                    <div class="form-controls">
                        <input type="text" class="form-control" name="term_subtitle" value="{{setting_item_with_lang('term_subtitle',request()->query('lang'))}}">
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-title"><strong>{{ __('Property Type')}}</strong></div>
                    <div class="panel-body">
                        <div class="terms-scrollable">
                            @foreach($property_types as $term)
                                <label class="term-item">
                                    <input @if(!empty(setting_item('term_properties')) and collect(json_decode(setting_item('term_properties')))->contains($term->id)) checked @endif type="checkbox" name="term_properties[]" value="{{$term->id}}">
                                    <span class="term-name">{{$term->name}}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="term-item">
                        <input {{ setting_item('term_enable_travel') == 0 ?: 'checked' }} type="checkbox" name="term_enable_travel" value="1">
                        <span class="term-name">{{__('Enable Travel')}}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script.body')
<script src="{{asset('libs/ace/src-min-noconflict/ace.js')}}" type="text/javascript" charset="utf-8"></script>
<script>
    (function($) {
        $(`[data-number="__number__"] [name]`).each(function() {
            var name = $(this).attr('name');
            $(this).attr('__name__', name);
            $(this).removeAttr('name');
        });

        $('.ace-editor').each(function() {
            var editor = ace.edit($(this).attr('id'));
            editor.setTheme("ace/theme/" + $(this).data('theme'));
            editor.session.setMode("ace/mode/" + $(this).data('mod'));
            var me = $(this);

            editor.session.on('change', function(delta) {
                // delta.start, delta.end, delta.lines, delta.action
                me.next('textarea').val(editor.getValue());
            });
        });
    })(jQuery)

</script>
@endsection
