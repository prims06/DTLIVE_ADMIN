@extends('admin.layout.page-app')
@section('page_title', __('label.notification_setting'))
@section('tab_title', __('label.notification_setting'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.notification_setting')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.notification.index') }}">{{__('label.notification')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.notification_setting')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{__('label.notification_setting')}}</h5>
                <div class="card-body">
                    <form id="setting" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.onesignal_app_id')}}<span class="text-danger">*</span></label>
                                    <input name="onesignal_app_id" type="text" class="form-control" value="{{ $result['onesignal_app_id'] }}" placeholder="{{__('label.onesignal_app_id_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.onesignal_reset_key')}}<span class="text-danger">*</span></label>
                                    <input name="onesignal_rest_key" type="text" class="form-control" value="{{ $result['onesignal_rest_key'] }}" placeholder="{{__('label.onesignal_reset_key_here')}}">
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="notification_setting()">{{__('label.save')}}</button>
                            <a href="{{route('admin.notification.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function notification_setting() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if (Check_Admin == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#setting")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.notification.settingsave") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'setting', '{{ route("admin.notification.index") }}');
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