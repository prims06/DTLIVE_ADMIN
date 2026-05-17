@extends('admin.layout.page-app')
@section('page_title', __('label.panel_settings'))
@section('tab_title', __('label.panel_settings'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">

            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.panel_settings')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.panel_settings')}}</li>
                    </ol>
                </div>
            </div>

            <form id="pannel_setting" enctype="multipart/form-data">
                <input type="hidden" name="old_panel_login_page_img" value="{{ $result['panel_login_page_img'] }}">
                <input type="hidden" name="old_panel_profile_no_img" value="{{ $result['panel_profile_no_img'] }}">
                <input type="hidden" name="old_panel_normal_no_img" value="{{ $result['panel_normal_no_img'] }}">
                <input type="hidden" name="old_panel_portrait_no_img" value="{{ $result['panel_portrait_no_img'] }}">
                <input type="hidden" name="old_panel_landscape_no_img" value="{{ $result['panel_landscape_no_img'] }}">
                <input type="hidden" name="old_powered_by_image" value="{{ $result['powered_by_image'] }}">
                <div class="row">
                    <div class="col-3">
                        <div class="card custom-border-card">
                            <h5 class="card-header">{{__('label.login_page_image')}}</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="avatar-upload image-upload-wrapper">
                                                <input type='file' name="panel_login_page_img" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{ $result['panel_login_page_img'] }}" class="imagePreview" />
                                                </label>
                                            </div>
                                            <label class="mt-3 text-gray">{{__('label.size_2640_3960_pixels')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card custom-border-card">
                            <h5 class="card-header">{{__('label.powered_by_image')}}</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="avatar-upload image-upload-wrapper">
                                                <input type='file' name="powered_by_image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{ $result['powered_by_image'] }}" class="imagePreview" />
                                                </label>
                                            </div>
                                            <label class="mt-3 text-gray">{{__('label.brand_image')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card custom-border-card">
                            <h5 class="card-header">{{__('label.by_default_image')}}</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.profile_image')}}<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="avatar-upload image-upload-wrapper">
                                                    <input type='file' name="panel_profile_no_img" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                                    <label class="avatar-preview">
                                                        <img src="{{$result['panel_profile_no_img']}}" class="imagePreview" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.normal_image')}}<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="avatar-upload image-upload-wrapper">
                                                    <input type='file' name="panel_normal_no_img" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                                    <label class="avatar-preview">
                                                        <img src="{{$result['panel_normal_no_img']}}" class="imagePreview" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.portrait_image')}}<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <div class="avatar-upload image-upload-wrapper">
                                                    <input type='file' name="panel_portrait_no_img" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                                    <label class="avatar-preview">
                                                        <img src="{{$result['panel_portrait_no_img']}}" class="imagePreview" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.landscape_image')}}<span class="text-danger">*</span></label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="panel_landscape_no_img" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                                <label class="avatar-preview landscape-preview">
                                                    <img src="{{ $result['panel_landscape_no_img'] }}" class="imagePreview" id="imagePreviewLandscape"/>
                                                    <input type="hidden" class="form-control" id="landscape_tmdb" name="landscape_tmdb">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-default-white mw-120" onclick="save_panel_setting()">{{__('label.save')}}</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        // Sidebar Scroll Down
        sidebar_down($(document).height());

        function save_panel_setting(){

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#pannel_setting")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("admin.panel.setting.save") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'pannel_setting', '{{ route("admin.panel.setting.index") }}');
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
    </script>
@endsection