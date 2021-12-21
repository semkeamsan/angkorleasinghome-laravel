@if (is_default_lang())
    @php $languages = \Modules\Language\Models\Language::getActive(); @endphp
    <hr>
    <div class="panel">
        <div class="panel-title"><strong>{{ __('Form Search Fields') }}</strong></div>
        <div class="panel-body">
            <div class="form-group">
                <label class="">{{ __('Search Criteria') }}</label>
                <div class="form-controls">
                    <div class="form-group-item">
                        <div class="g-items-header">
                            <div class="row">
                                <div class="col-md-7">{{ __('Search Field') }}</div>
                                <div class="col-md-4">{{ __('Order') }}</div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                        <div class="g-items">
                            @php
                                $hotel_search_fields = setting_item_array('hotel_search_fields');
                                $types = [
                                    'service_name' => __('Service name'),
                                    'location' => __('Location'),
                                    'date' => __('Date'),
                                    'price' => __('Price Range'),
                                    'guests' => __('Guests'),
                                    'attr' => __('Attribute'),
                                ];
                                $attrs = \Modules\Core\Models\Attributes::where('service', 'hotel')->get();
                            @endphp
                            @foreach ($hotel_search_fields as $key => $item)
                                <div class="item" data-number="{{ $key }}">
                                    <div class="row">
                                        <div class="col-md-7">
                                            @if (!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                                @foreach ($languages as $language)
                                                    <?php $key_lang = setting_item('site_locale') != $language->locale ? '_' . $language->locale : ''; ?>
                                                    <div class="g-lang">
                                                        <div class="title-lang">{{ $language->name }}</div>
                                                        <input type="text"
                                                            name="hotel_search_fields[{{ $key }}][title{{ $key_lang }}]"
                                                            value="{{ $item['title' . $key_lang] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                @endforeach
                                            @else
                                                <input type="text"
                                                    name="hotel_search_fields[{{ $key }}][title]"
                                                    value="{{ $item['title'] }}" class="form-control">
                                            @endif
                                            <select name="hotel_search_fields[{{ $key }}][field]"
                                                class="custom-select">
                                                <option value="">{{ __('-- Select field type --') }}</option>
                                                @foreach ($types as $type => $name)
                                                    <option @if ($item['field'] == $type) selected @endif value="{{ $type }}">
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <br>
                                            <select name="hotel_search_fields[{{ $key }}][attr]"
                                                class="mt-2 custom-select">
                                                <option value="">{{ __('-- Select Attribute --') }}</option>
                                                @foreach ($attrs as $attr)
                                                    <option @if (($item['attr'] ?? '') == $attr->id) selected @endif value="{{ $attr->id }}">
                                                        {{ $attr->name }}</option>
                                                @endforeach
                                            </select>
                                            <br>
                                            <select name="hotel_search_fields[{{ $key }}][size]"
                                                class="mt-2 custom-select">
                                                <option @if ($item['size'] == 6) selected @endif value="6">{{ __('Size Column 6') }}
                                                </option>
                                                <option @if ($item['size'] == 4) selected @endif value="4">{{ __('Size Column 4') }}
                                                </option>
                                                <option @if ($item['size'] == 3) selected @endif value="3">{{ __('Size Column 3') }}
                                                </option>
                                                <option @if ($item['size'] == 2) selected @endif value="2">{{ __('Size Column 2') }}
                                                </option>
                                                <option @if ($item['size'] == 1) selected @endif value="1">{{ __('Size Column 1') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number"
                                                name="hotel_search_fields[{{ $key }}][position]" min="0"
                                                value="{{ $item['position'] ?? 0 }}" class="form-control">
                                        </div>
                                        <div class="col-md-1">
                                            <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                    class="fa fa-trash"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-right">
                            <span class="btn btn-info btn-sm btn-add-item"><i
                                    class="icon ion-ios-add-circle-outline"></i> {{ __('Add item') }}</span>
                        </div>
                        <div class="g-more hide">
                            <div class="item" data-number="__number__">
                                <div class="row">
                                    <div class="col-md-7">
                                        <input type="text" __name__="hotel_search_fields[__number__][title]"
                                            class="form-control">
                                        <select __name__="hotel_search_fields[__number__][field]"
                                            class="custom-select">
                                            <option value="">{{ __('-- Select field type --') }}</option>
                                            @foreach ($types as $type => $name)
                                                <option value="{{ $type }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <select __name__="hotel_search_fields[__number__][size]"
                                            class="mt-2 custom-select">
                                            <option value="6">{{ __('Size Column 6') }}</option>
                                            <option value="4">{{ __('Size Column 4') }}</option>
                                            <option value="3">{{ __('Size Column 3') }}</option>
                                            <option value="2">{{ __('Size Column 2') }}</option>
                                            <option value="1">{{ __('Size Column 1') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" __name__="hotel_search_fields[__number__][position]"
                                            min="0" class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                class="fa fa-trash"></i></span>
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
    <div class="panel">
        <div class="panel-title"><strong>{{ __('Form Search Price Range') }}</strong></div>
        <div class="panel-body">
            <div class="form-group">
                <div class="form-controls">
                    <div class="form-group-item">
                        <div class="form-group-item">
                            <div class="g-items-header">
                                <div class="row">
                                    <div class="col-md-11">{{ __('Prices') }}</div>
                                    <div class="col-md-1"></div>
                                </div>
                            </div>
                            <div class="g-items">
                                <?php
                                $hotel_page_search_price = setting_item('hotel_page_search_price');
                                if (!empty($hotel_page_search_price)) {
                                    $hotel_page_search_price = json_decode($hotel_page_search_price, true);
                                }
                                if (empty($hotel_page_search_price) or !is_array($hotel_page_search_price)) {
                                    $hotel_page_search_price = [];
                                }
                                ?>
                                @foreach ($hotel_page_search_price as $key => $item)

                                    <div class="item" data-number="{{ $key }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">{{ __('From') }}</label>
                                                <input type="number" required  min="0"
                                                    name="hotel_page_search_price[{{ $key }}][from]"
                                                    class="form-control"
                                                    value="{{ @$item['from'] }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">{{ __('To') }}</label>
                                                <input type="number" required  min="0"
                                                    name="hotel_page_search_price[{{ $key }}][to]"
                                                    class="form-control"
                                                    value="{{ @$item['to'] }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">{{ __('Increment') }}</label>
                                                <input type="number" required  min="0"
                                                    name="hotel_page_search_price[{{ $key }}][increment]"
                                                    class="form-control"
                                                    value="{{ @$item['increment'] }}">
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                        class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-right">
                                <span class="btn btn-info btn-sm btn-add-item"><i
                                        class="icon ion-ios-add-circle-outline"></i>
                                    {{ __('Add item') }}</span>
                            </div>
                            <div class="g-more hide">
                                <div class="item" data-number="__number__">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">{{ __('From') }}</label>
                                            <input type="number" min="0" value="0"
                                                __name__="hotel_page_search_price[__number__][from]"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">{{ __('To') }}</label>
                                            <input type="number"  min="0" value="0"
                                                __name__="hotel_page_search_price[__number__][to]"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">{{ __('Increment') }}</label>
                                            <input type="number"  min="0" value="0"
                                                __name__="hotel_page_search_price[__number__][increment]"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-1">
                                            <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                    class="fa fa-trash"></i></span>
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

@endif
