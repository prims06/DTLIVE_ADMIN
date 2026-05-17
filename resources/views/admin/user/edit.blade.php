@extends('admin.layout.page-app')
@section('page_title', __('label.edit_user'))
@section('tab_title', __('label.edit_user'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.edit_user')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{__('label.users')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.edit_user')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-default-white mw-120" style="margin-top:-14px">{{__('label.user_list')}}</a>
                </div>
            </div>

			<div class="card custom-border-card">
                <form id="update_user" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $data['id'] }}">
                    <input type="hidden" name="old_storage_type" value="{{ $data['storage_type'] }}">
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" value="{{ $data['full_name'] }}" class="form-control" placeholder="{{__('label.full_name_here')}}" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="mobile_number" value="{{ $data['mobile_number'] }}" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.email')}}<span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ $data['email'] }}" class="form-control" placeholder="{{__('label.email_here')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ml-5">
                                <label>{{__('label.image')}}</label>
                                <div class="avatar-upload my-2 image-upload-wrapper">
                                    <input type='file' name="image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                    <label class="avatar-preview">
                                        <img src="{{ $data['image'] }}" class="imagePreview" />
                                    </label>
                                </div>
                                <input type="hidden" name="old_image" value="{{ $data['image'] }}">                                       
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="update_user()">{{__('label.update')}}</button>
                        <a href="{{route('admin.user.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_method" value="PATCH">
                    </div>
                </form>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function update_user(){
			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#update_user")[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{route("admin.user.update", [$data->id])}}',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'update_user', '{{ route("admin.user.index") }}');
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