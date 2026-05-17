@extends('producer.layout.page-app')
@section('page_title', __('label.profile'))
@section('tab_title', __('label.profile'))

@section('content')
	@include('producer.layout.sidebar')

	<div class="right-content">
		@include('producer.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.profile')}}</h1>

			<div class="row mb-2">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{__('label.profile')}}</li>
					</ol>
				</div>
			</div>

            <div class="card custom-border-card">
                <form id="profile" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <input type="hidden" name="old_storage_type" value="{{$data->storage_type}}">
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.user_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="user_name" value="{{$data->user_name}}" class="form-control" placeholder="{{__('label.user_name_here')}}" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" value="{{$data->full_name}}" class="form-control" placeholder="{{__('label.full_name_here')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.email')}}<span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{$data->email}}" class="form-control" placeholder="{{__('label.email_here')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="mobile_number" value="{{$data->mobile_number}}" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group ml-5">
                                <label>{{__('label.image')}}</label>
                                <div class="avatar-upload my-2 image-upload-wrapper">
                                    <input type='file' name="image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden/>
                                    <label class="avatar-preview">
                                        <img src="{{ $data->image }}" class="imagePreview" />
                                    </label>
                                    <input type="hidden" name="old_image" value="{{$data->image}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top text-right pt-3">
                        <button type="button" class="btn btn-default mw-120" onclick="update_profile()">{{__('label.update')}}</button>
                        <input type="hidden" name="_method" value="PATCH">
                    </div>
                </form>
            </div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function update_profile(){
            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#profile")[0]);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{route("producer.profile.update", [$data->id])}}',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'profile', '{{ route("producer.profile.index") }}');
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