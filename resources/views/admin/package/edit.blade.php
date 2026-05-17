@extends('admin.layout.page-app')
@section('page_title', __('label.edit_package'))
@section('tab_title', __('label.edit_package'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.edit_package')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.package.index') }}">{{__('label.package')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.edit_package')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.package.index') }}" class="btn btn-default-white mw-120" style="margin-top:-14px">{{__('label.package_list')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <form enctype="multipart/form-data" id="package_update">
                    <input type="hidden" value="{{$data->id}}" name="id">
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.package_type')}}<span class="text-danger">*</span></label>
                                <select class="form-control" id="package_type" name="package_type">
                                    <option value="1" {{ $data->package_type == 1 ? 'selected' : '' }}>{{__('label.paid')}}</option>
                                    <option value="2" {{ $data->package_type == 2 ? 'selected' : '' }}>{{__('label.free')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                <input name="name" type="text" value="{{$data->name}}" class="form-control" placeholder="{{__('label.name_here')}}" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3 price_group">
                            <div class="form-group">
                                <label>{{__('label.price')}}<span class="text-danger">*</span></label>
                                <input name="price" type="number" value="{{$data->price}}" class="form-control" placeholder="{{__('label.price_here')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.no_of_device_sync')}}<span class="text-danger">*</span></label>
                                <input name="no_of_device_sync" type="number" value="{{$data->no_of_device_sync}}"  min="1" class="form-control" placeholder="{{__('label.no_of_device_sync_here')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.package_time')}}<span class="text-danger">*</span></label>
                                <select class="form-control" id="validity_type" name="type">
                                    <option value="">{{__('label.select_type')}}</option>
                                    <option value="Day" {{$data->type == 'Day' ? 'selected' : ''}}>{{__('label.day')}}</option>
                                    <option value="Week" {{$data->type == 'Week' ? 'selected' : ''}}>{{__('label.week')}}</option>
                                    <option value="Month" {{$data->type == 'Month' ? 'selected' : ''}}>{{__('label.month')}}</option>
                                    <option value="Year" {{$data->type == 'Year' ? 'selected' : ''}}>{{__('label.year')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3 package_time">
                            <div class="form-group">
                                <select class="form-control mt-2" name="time">
                                    <option value="">{{__('label.select_number')}}</option>
                                    @for($i=1; $i<=12; $i++)
                                        <option value="{{$i}}" {{$data->time == $i ? 'selected' : ''}}>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.watch_on_tv')}}<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="watch_on_laptop_tv" id="watch_on_laptop_tv_yes" class="custom-control-input" value="1" {{$data->watch_on_laptop_tv == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="watch_on_laptop_tv_yes">{{__('label.yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="watch_on_laptop_tv" id="watch_on_laptop_tv_no" class="custom-control-input" value="0" {{$data->watch_on_laptop_tv == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="watch_on_laptop_tv_no">{{__('label.no')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.ads_free_content')}}<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="ads_free_content" id="ads_free_content_yes" class="custom-control-input" value="1" {{$data->ads_free_content == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="ads_free_content_yes">{{__('label.yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="ads_free_content" id="ads_free_content_no" class="custom-control-input" value="0" {{$data->ads_free_content == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="ads_free_content_no">{{__('label.no')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 mt-3">
                            <div class="form-group">
                                <label>{{__('label.android_product_package')}}</label>
                                <input name="android_product_package" value="{{$data->android_product_package}}" type="text" class="form-control" placeholder="{{__('label.android_package_here')}}">
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="form-group">
                                <label>{{__('label.ios_product_package')}}</label>
                                <input name="ios_product_package" value="{{$data->ios_product_package}}" type="text" class="form-control" placeholder="{{__('label.ios_package_here')}}">
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <div class="form-group">
                                <label>{{__('label.web_product_package')}}</label>
                                <input name="web_product_package" value="{{$data->web_product_package}}" type="text" class="form-control" placeholder="{{__('label.web_package_here')}}">
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="package_update()">{{__('label.save')}}</button>
                        <a href="{{route('admin.package.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_method" value="PATCH">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>

        // Sidebar Scroll Down
        sidebar_down($(document).height());

        function package_update() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#package_update")[0]);
                $("#dvloader").show();

                $.ajax({
                    type: 'POST',
                    url: '{{route("admin.package.update", [$data->id])}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'package_update', '{{ route("admin.package.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        }

        $(document).ready(function() {

            var package_type = "<?php echo $data->package_type; ?>";
            if (package_type == 1) {
                $('.price_group').show();
            } else {
                $('.price_group').hide();
            }
            $('#package_type').on('change', function() {
                let type = $(this).val();
                if (type == 1) {
                    $('.price_group').show();
                } else {
                    $('.price_group').hide();
                }
            });

            var validity_type = "<?php echo $data->type; ?>";
            if (validity_type == "Day") {
                for (let i = 8; i <= 31; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else if (validity_type == "Week") {
                for (let i = 5; i <= 31; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else if (validity_type == "Month") {
                for (let i = 13; i <= 12; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else if (validity_type == "Year") {
                for (let i = 2; i <= 12; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else {
                $('.package_time').hide();
            }
        });

        $('#validity_type').on('click', function() {
            $('.package_time').show();
            var type = $("#validity_type").val()

            for (let i = 1; i <= 12; i++) {
                $(".package_time option[value=" + i + "]").show();
                $(".package_time option[value=" + i + "]").attr("selected", false);
            }

            if (type == "Day") {
                for (let i = 8; i <= 31; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else if (type == "Week") {
                for (let i = 5; i <= 31; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else if (type == "Month") {
                for (let i = 13; i <= 12; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else if (type == "Year") {
                for (let i = 2; i <= 12; i++) {
                    $(".package_time option[value=" + i + "]").hide();
                }
            } else {
                $('.package_time').hide();
            }
        })
    </script>
@endsection