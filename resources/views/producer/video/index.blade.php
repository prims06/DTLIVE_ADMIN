@extends('producer.layout.page-app')
@section('page_title', $type['name'])
@section('tab_title', $type['name'])

@section('content')
    @include('producer.layout.sidebar')

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{$type['name']}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$type['name']}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('producer.video.add', ['type_id' => $type['id']]) }}" class="btn btn-default-white mw-120" style="margin-top: -14px;">{{__('label.add_content')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <!-- Search -->
                <form action="{{ route('producer.video.index', ['type_id' => $type['id']])}}" method="GET">
                    <div class="page-search">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                                </span>
                            </div>
                            <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                        <div>
                            <button class="btn btn-default-white mw-120" type="submit">{{__('label.search')}}</button>
                        </div>
                    </div>
                    <div class="page-search mb-3">
                        <div class="sorting mr-3" style="width: 45%;">
                            <label>{{__('label.sort_by')}}</label>
                            <select class="form-control" name="input_rent">
                                <option value="0" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 0 ? 'selected' : ''}} @endif>{{__('label.all_video')}}</option>
                                <option value="1" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 1 ? 'selected' : ''}} @endif>{{__('label.rent_video')}}</option>
                            </select>
                        </div>
                        <div class="sorting mr-3" style="width: 45%;">
                            <select class="form-control" name="input_premimum">
                                <option value="all">{{__('label.all_video')}}</option>
                                <option value="0" @if(isset($_GET['input_premimum'])){{ $_GET['input_premimum'] == 0 ? 'selected' : ''}} @endif>{{__('label.non_premium')}}</option>
                                <option value="1" @if(isset($_GET['input_premimum'])){{ $_GET['input_premimum'] == 1 ? 'selected' : ''}} @endif>{{__('label.premium')}}</option>
                            </select>
                        </div>
                        <div class="sorting" style="width: 45%;">
                            <select class="form-control" name="input_status">
                                <option value="all">{{__('label.all_status')}}</option>
                                <option value="0" @if(isset($_GET['input_status'])){{ $_GET['input_status'] == 0 ? 'selected' : ''}} @endif>{{__('label.hide')}}</option>
                                <option value="1" @if(isset($_GET['input_status'])){{ $_GET['input_status'] == 1 ? 'selected' : ''}} @endif>{{__('label.show')}}</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="row">
                    @foreach ($result as $key => $value)
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                            <div class="card video-card">
                                <div class="position-relative">
        
                                    @if($value->is_premium == 1)
                                        <div class="ribbon ribbon-top-left"><span>{{__('label.premium')}}</span></div>
                                    @endif

                                    <img class="card-img-top" src="{{ $value->thumbnail }}">
                                    @if($value->video_upload_type == "server_video")
                                        <button class="btn play-btn-top video" data-toggle="modal" data-target="#videoModal" data-video="{{ $value->video_320 }}" data-image="{{ $value->thumbnail }}">
                                            <i class="fa-regular fa-circle-play text-white fa-4x mr-2 mt-2"></i>
                                        </button>
                                    @endif
        
                                    <ul class="list-inline overlap-control" aria-labelledby="dropdownMenuLink">
                                        <li class="list-inline-item">
                                            <a class="edit-delete-btn" href="{{ route('producer.video.edit', ['video_id' => $value->id, 'type_id' => $type['id']]) }}">
                                                <i class="fa-solid fa-pen-to-square fa-xl"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a class="edit-delete-btn" href="{{route('producer.video.show', ['video_id' => $value->id, 'type_id' => $type['id']]) }}" onclick="return confirm('{{__('label.delete_content')}}')">
                                                <i class="fa-solid fa-trash-can fa-xl"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-4" title="{{ ($value->name) }}">{{ $value->name }}</h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="d-flex align-items-center assest-color">
                                                <i class="fa-regular fa-eye fa-lg mr-2"></i>
                                                <h5 class="counting mb-0" data-count="{{ No_Format($value->total_view ?? 0) }}">{{ No_Format($value->total_view )}}</h5>
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-end">
                                                @if($value->status == 1)
                                                    <button class="btn btn-sm show-btn" id="{{ $value->id }}" onclick="change_status('{{$value->id}}', '{{$value->status}}')">{{__('label.show')}}</button>
                                                @elseif($value->status == 0)
                                                    <button class="btn btn-sm hide-btn" id="{{ $value->id }}" onclick="change_status('{{$value->id}}', '{{$value->status}}')">{{__('label.hide')}}</button>
                                                @endif
                                                @if($type['type'] == 5)
                                                    <button class="btn btn-sm info-btn releases_modal ml-3" data-toggle="modal" data-target="#ReleasesModal" data-id="{{ $value->id }}">{{__('label.releases')}}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Model -->
            <div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0 bg-transparent">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <video controls width="800" height="500" preload='none' poster="" id="theVideo" controlsList="nodownload noplaybackrate" disablepictureinpicture>
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Releases Modal -->
            <div class="modal fade" id="ReleasesModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.releases_video')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="release_video" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="type_id" id="type_id">
                                                <option value="">{{__('label.select_type')}}</option>
                                                @foreach ($releases_type as $key => $value)
                                                <option value="{{ $value->id }}" data-type="{{ $value->type }}">
                                                    {{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 channel_list">
                                        <div class="form-group">
                                            <label>{{__('label.channel')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="channel_id" id="channel_id" style="width: 100% !important;">
                                            <option value="">{{__('label.select_channel')}}</option>
                                                @foreach ($channel_list as $key => $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="release_video()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div> Showing {{ $result->firstItem() }} to {{ $result->lastItem() }} of total {{$result->total()}} entries </div>
                <div class="pb-5"> {{ $result->links() }} </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $('#channel_id').select2({ dropdownParent: $('#ReleasesModal') });
        
        $(function() {
            $(".video").click(function() {
                var theModal = $(this).data("target"),
                    videoSRC = $(this).attr("data-video"),
                    videoPoster = $(this).attr("data-image"),
                    videoSRCauto = videoSRC + "";

                $(theModal + ' source').attr('src', videoSRCauto);
                $(theModal + ' video').attr('poster', videoPoster);
                $(theModal + ' video').load();
                $(theModal + ' button.close').click(function() {
                    $(theModal + ' source').attr('src', videoSRC);
                });
            });
        });
        $("#videoModal .close").click(function() {
            theVideo.pause()
        });

        function change_status(id, status) {
    
            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "{{route('producer.video.status')}}",
                    data: {id: id},
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {

                            if (resp.status_code == 1) {
                                $('#' + id).text('{{__("label.show")}}').removeClass('hide-btn').addClass('show-btn');
                            } else {
                                $('#' + id).text('{{__("label.hide")}}').removeClass('show-btn').addClass('hide-btn');
                            }
                            toastr.success(resp.success);
                        } else {
                            toastr.error(resp.errors);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        };

        // ===== Releases Video =====
        $(".releases_modal").click(function() {
            var video_id = $(this).attr("data-id");
            $("#release_video #edit_id").val(video_id);
        });

        $(".channel_list").hide();
        $('#type_id').on('change', function () {

            var type_type = $(this).find('option:selected').data("type");
            $(".channel_list").hide();
            if(type_type == 6){
                $(".channel_list").show();
            }
        });

        function release_video() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#release_video")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("producer.video.releases") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();

                        if(resp.status == 200){
                            $('#EditModel').modal('toggle');
                        }
                        get_responce_message(resp, 'release_video', '{{ route("producer.video.index", ["type_id" => $type["id"]]) }}');
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