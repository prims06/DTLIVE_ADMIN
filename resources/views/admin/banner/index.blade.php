@extends('admin.layout.page-app')
@section('page_title', __('label.banner'))
@section('tab_title', __('label.banner'))

@section('content')
    @include('admin.layout.sidebar')

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.banner')}}</h1>

            <div class="row">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.banner')}}</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default-white" style="border-radius: 10px;">
                        <i class="fa-solid fa-arrow-up-wide-short fa-1x"></i>
                    </button>
                </div>
            </div>

            @if(isset($type) && $type != null && count($type) > 0)
                <ul class="tabs nav nav-pills custom-tabs inline-tabs mt-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="app-tab" onclick="Selected_Type('{{$type[0]['id']}}', '{{$type[0]['type']}}', 1)" data-is_home_screen="1" href="#app" role="tab" data-toggle="tab" aria-controls="app" aria-selected="true">{{__('label.home')}}</a>
                    </li>
                    @for ($i = 0; $i < count($type); $i++) 
                        <li class="nav-item">
                            <a class="nav-link" id="{{$type[$i]['name']}}-tab" onclick="Selected_Type('{{$type[$i]['id']}}', '{{$type[$i]['type']}}', 2)" data-is_home_screen="2" data-id="{{$type[$i]['id']}}" data-type="{{$type[$i]['type']}}" data-toggle="tab" href="#{{$type[$i]['name']}}" role="tab" aria-controls="{{$type[$i]['name']}}" aria-selected="true">{{ $type[$i]['name']}}</a>
                        </li>
                    @endfor
                </ul>
            @endif

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.add_banner')}}</h5>
                        @if(isset($type) && $type != null && count($type) > 0)
                        <div class="card-body">
                            <form id="save_banner" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-row radio-row">
                                    <div class="d-flex justify-content-start">
                                        @for ($i = 0; $i < count($type); $i++)
                                            <div class="custom-control custom-radio mr-3">
                                                <input type="radio" name="type_id" class="custom-control-input" id="Video_Selecte{{$i}}" onclick="Selected_Type('{{ $type[$i]['id'] }}', '{{ $type[$i]['type'] }}', 2)" data-id="{{ $type[$i]['id'] }}" data-type="{{ $type[$i]['type'] }}" data-name="{{ $type[$i]['name'] }}" value="{{ $type[$i]['id'] }}" {{ $i == 0 ? 'checked' : ''}}>
                                                <label class="custom-control-label font-weight-bold h6" for="Video_Selecte{{$i}}">{{ $type[$i]['name'] }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="form-row mt-4">
                                    <div class="col-md-2 mr-3 subvideo_type">
                                        <div class="form-group">
                                            <label>{{__('label.sub_video_type')}}</label>
                                            <select class="form-control" name="subvideo_type" id="subvideo_type">
                                                <option value="" selected disabled>{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.video')}}</option>
                                                <option value="2">{{__('label.show')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 option_class_video">
                                        <div class="form-group">
                                            <label>{{__('label.video')}}</label>
                                            <select class="form-control" name="video_id" id="video_id" style="width:100%!important;">
                                                <option selected disabled>{{__('label.select_video')}}</option>
                                                @foreach ($video as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="after-add-more"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">{{__('label.banner_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="contentListId">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form id="save_banner_sortable" enctype="multipart/form-data">
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_banner_sortable()">{{__('label.save')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- select 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <script>
        $("#video_id").select2();

        var type = $('input[name=type_id]:checked').data('type');
        if(type == 1 || type == 2 || type == 8){
            $(".subvideo_type").hide();
        }
        function Selected_Type(type_id, type, is_home_page){

            $("#video_id").empty();
            $('#video_id').append(`<option selected disabled>{{__('label.select_video')}}</option>`);

            $(".subvideo_type").hide();
            if(type == 5 || type == 6 || type == 7){
                $(".subvideo_type").show();
            }
            if(is_home_page == 1) {
                $("#Video_Selecte0").prop('checked', true);
            }

            if (type == 1 || type == 2 || type == 8) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("admin.banner.data") }}',
                    data: {
                        type_id:type_id, 
                        type:type
                    },
                    success: function(resp) {
                        for (var i = 0; i < resp.result.length; i++) {                            
                            $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else if (type == 5 || type == 6 || type == 7) {

                var subvideo_type = $('#subvideo_type').find(":selected").val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("admin.banner.data") }}',
                    data: {
                        type_id:type_id,
                        type:type,
                        subvideo_type:subvideo_type
                    },
                    success: function(resp) {
                        for (var i = 0; i < resp.result.length; i++) {
                            $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown, textStatus);
                    }
                });
            }
        };

        // Sub Video Type
        $('#subvideo_type').on('change', function () {

            $("#video_id").empty();
            $('#video_id').append(`<option selected disabled>{{__('label.select_video')}}</option>`);

            var Tab = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var subvideo_type = $(this).children("option:selected").val();

            if (Is_home_screen == 1) {
                var type_id = $('input[name=type_id]:checked').data('id');
                var type = $('input[name=type_id]:checked').data('type');
            } else if (Is_home_screen == 2) {
                var type_id = Tab.data("id");
                var type = Tab.data("type");
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.banner.data") }}',
                data: {
                    type_id:type_id,
                    type:type,
                    subvideo_type:subvideo_type,
                },
                success: function(resp) {
                    for (var i = 0; i < resp.result.length; i++) {
                        $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown, textStatus);
                }
            });
        });

        // Save
        $('#video_id').on('change', function () {
			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                var Tab = $("ul.tabs li a.active");
                var Is_home_screen = Tab.data("is_home_screen");
                var Video_Id = $(this).children("option:selected").val();
                var subvideo_type = $('#subvideo_type').find(":selected").val();

                if(Is_home_screen == 1){

                    var Type_Id = $('input[name=type_id]:checked').val();
                    var Video_Type = $('input[name=type_id]:checked').data('type');
                } else {

                    var Type_Id = Tab.data("id");
                    var Video_Type = Tab.data("type");
                }  

                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("admin.banner.store") }}',
                    data: {
                        is_home_screen:Is_home_screen,
                        type_id:Type_Id,
                        video_type: Video_Type,
                        video_id:Video_Id,
                        subvideo_type:subvideo_type,
                    },
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_banner', '{{ route("admin.banner.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });

            } else {
                showError();
            }
        });

        // Banner List
        var sort_order_array = [];
        var Tab = $("ul.tabs li a.active");
        var Is_home_screen = Tab.data("is_home_screen");
        if(Is_home_screen == 1){

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: {is_home_screen:Is_home_screen},
                url: '{{ route("admin.banner.list") }}',
                success: function(resp) {

                    sort_order_array = [];
                    if(resp.result.length > 0){
                        sort_order_array = resp.result;

                        var label_type = '{{__("label.type")}}';
                        var label_video = '{{__("label.video")}}';

                        var data ='<div class="form-group row mb-0 pb-0">' +
                                    '<div class="col-md-2">' +
                                        '<label>'+label_type+'</label>' +
                                    '</div>' +
                                    '<div class="col-md-4">' +
                                        '<label>'+label_video+'</label>' +
                                    '</div>' +
                                '</div>';
                        $('.after-add-more').append(data);
                    }

                    for (var i = 0; i < resp.result.length; i++) {
                        var data ='<div class="form-group row">' +
                                    '<div class="col-md-2">' +
                                        '<input type="text" class="form-control" name="type" value="'+ resp.result[i].type.name +'" readonly/>' +
                                        '<input type="hidden" class="form-control" name="video_type" value=""/>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                        '<input type="text" class="form-control" name="video" value="'+ resp.result[i].video.name +'" id="video" readonly/>' +
                                    '</div>' +
                                    '<div class="col-md-1">' +
                                        '<a onclick="DeleteBanner('+ resp.result[i].id +')" class="btn delete-btn py-2" id="remove"><i class="fa-solid fa-trash-can fa-xl"></i></a>' +                                   
                                    '</div>' +
                                '</div>';
                        $('.after-add-more').append(data);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        $('.nav-item a').on('click', function() {

            var label_type = '{{__("label.type")}}';
            var label_video = '{{__("label.video")}}';

            var Is_home_screen = $(this).data("is_home_screen");
            $(".after-add-more .row").remove();
            if(Is_home_screen == 2){

                $('.radio-row').hide();
                var type_id = $(this).data("id");

                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: {type_id:type_id, is_home_screen:Is_home_screen},
                    url: '{{ route("admin.banner.list") }}',
                    success: function(resp) {
                        $("#dvloader").hide();

                        sort_order_array = [];
                        if(resp.result.length > 0){
                            sort_order_array = resp.result;

                            var data ='<div class="form-group row mb-0 pb-0">' +
                                        '<div class="col-md-2">' +
                                            '<label>'+label_type+'</label>' +
                                        '</div>' +
                                        '<div class="col-md-4">' +
                                            '<label>'+label_video+'</label>' +
                                        '</div>' +
                                    '</div>';
                            $('.after-add-more').append(data);
                        }

                        for (var i = 0; i < resp.result.length; i++) {
                            var data ='<div class="form-group row">' +
                                        '<div class="col-md-2">' +
                                            '<input type="text" class="form-control" name="type" value="'+ resp.result[i].type.name +'" readonly/>' +
                                            '<input type="hidden" class="form-control" name="video_type" value=""/>' +
                                        '</div>' +
                                        '<div class="col-md-6">' +
                                            '<input type="text" class="form-control" name="video" value="'+ resp.result[i].video['name'] +'" id="video" readonly/>' +
                                        '</div>' +
                                        '<div class="col-md-1">' +
                                            '<a onclick="DeleteBanner('+ resp.result[i].id +')" class="btn delete-btn py-2" id="remove"><i class="fa-solid fa-trash-can fa-xl"></i></a>' +                                   
                                        '</div>' +
                                    '</div>';
                            $('.after-add-more').append(data);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                $('.radio-row').show();
                
                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: {is_home_screen:Is_home_screen},
                    url: '{{ route("admin.banner.list") }}',
                    success: function(resp) {
                        $("#dvloader").hide();

                        sort_order_array = [];
                        if(resp.result.length > 0){
                            sort_order_array = resp.result;

                            var data ='<div class="form-group row mb-0 pb-0">' +
                                        '<div class="col-md-2">' +
                                            '<label>'+label_type+'</label>' +
                                        '</div>' +
                                        '<div class="col-md-4">' +
                                            '<label>'+label_video+'</label>' +
                                        '</div>' +
                                    '</div>';
                            $('.after-add-more').append(data);
                        }

                        for (var i = 0; i < resp.result.length; i++) {
                            var data ='<div class="form-group row">' +
                                        '<div class="col-md-2">' +
                                            '<input type="text" class="form-control" name="type" value="'+ resp.result[i].type.name +'" readonly/>' +
                                            '<input type="hidden" class="form-control" name="video_type" value=""/>' +
                                        '</div>' +
                                        '<div class="col-md-6">' +
                                            '<input type="text" class="form-control" name="video" value="'+ resp.result[i].video.name +'" id="video" readonly/>' +
                                        '</div>' +
                                        '<div class="col-md-1">' +
                                            '<a onclick="DeleteBanner('+ resp.result[i].id +')" class="btn delete-btn py-2" id="remove"><i class="fa-solid fa-trash-can fa-xl"></i></a>' +                                   
                                        '</div>' +
                                    '</div>';
                            $('.after-add-more').append(data);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            };
        });

        // Delete Banner
        function DeleteBanner(id) {
			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                var url = "{{route('admin.banner.destroy', '')}}"+"/"+id;

                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    url:url,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_banner', '{{ route("admin.banner.index") }}');
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

        // Sortable type
        $("#contentListId").sortable({
            update: function(event, ui) {
                getIdsOfContent();
            }
        });
        function getIdsOfContent() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id")
                    .replace("imageNo", ""));
            });
            $('#outputvalues').val(values);
        }
        function sortableBTN() {
            var listContainer = $('#contentListId');
            listContainer.html('');

            var htmlContent = ''; 
            for (var i = 0; i < sort_order_array.length; i++) { 
                htmlContent += '<div id="'+ sort_order_array[i].id +'" class="listitemClass mb-3 sortablebox">' +
                                '<p class="m-2 py-1">'+ ((sort_order_array[i].video && sort_order_array[i].video.name) ? sort_order_array[i].video.name : '') +'</p>' +
                            '</div>';
            }
            listContainer.append(htmlContent);
        }
        function save_banner_sortable() {
			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#save_banner_sortable")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.banner.sortable.save") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_banner_sortable', '{{ route("admin.banner.index") }}');
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