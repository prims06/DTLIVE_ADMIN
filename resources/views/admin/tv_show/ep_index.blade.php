@extends('admin.layout.page-app')
@section('page_title', __('label.episodes'))
@section('tab_title', __('label.episodes'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.episodes')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tvshow.index', ['type_id' => $type['id']]) }}">{{$type['name']}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.episodes')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.tvshow.episode.add', ['id' => $tvshow_id, 'type_id' => $type['id']]) }}" class="btn btn-default-white mw-150" style="margin-top: -14px;">{{__('label.add_new_episode')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <!-- Search -->
                <form action="{{ route('admin.tvshow.episode.index', ['id' => $tvshow_id, 'type_id' => $type['id']]) }}" method="GET">
                    <input type="hidden" name="show_id" id="show_id" value="{{$tvshow_id}}">
                    <div class="page-search mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                                </span>
                            </div>
                            <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                        <div class="sorting mr-3" style="width: 450px;">
                            <label>{{__('label.sort_by')}}</label>
                            <select class="form-control" name="input_season">
                                <option value="0" selected>{{__('label.all_season')}}</option>
                                @for ($i = 0; $i < count($season); $i++) 
                                <option value="{{ $season[$i]['id'] }}" @if(isset($_GET['input_season'])){{ $_GET['input_season'] == $season[$i]['id'] ? 'selected' : ''}} @endif>
                                    {{ $season[$i]['name'] }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="mr-3 ml-2">
                            <button class="btn btn-default-white" type="submit">{{__('label.search')}}</button>
                        </div>
                        <div class="mr-3 ml-3" title="{{__('label.sortable')}}">
                            <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default-white" style="border-radius: 10px;">
                                <i class="fa-solid fa-arrow-up-wide-short fa-xl"></i>
                            </button>
                        </div>
                    </div>
                </form>

            <div class="row">
                @foreach ($data as $key => $value)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <div class="card video-card">
                        <div class="position-relative">

                                @if($value->is_premium == 1)
                                    <div class="ribbon ribbon-top-left"><span>{{__('label.premium')}}</span></div>
                                @endif

                                <img class="card-img-top" src="{{$value->thumbnail}}" alt="">
                                @if($value->video_upload_type == "server_video")
                                <button class="btn play-btn-top video" data-toggle="modal" data-target="#videoModal" data-video="{{$value->video_320}}" data-image="{{$value->thumbnail}}">
                                    <i class="fa-regular fa-circle-play text-white fa-4x mr-2 mt-2"></i>
                                </button>
                                @endif

                                <ul class="list-inline overlap-control" aria-labelledby="dropdownMenuLink">
                                    <li class="list-inline-item">
                                        <a class="edit-delete-btn" href="{{ route('admin.tvshow.episode.edit', ['id' => $value->id, 'type_id' => $type['id']])}}">
                                            <i class="fa-solid fa-pen-to-square fa-xl" class="dot-icon"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a class="edit-delete-btn" href="{{ route('admin.tvshow.episode.delete', ['tvshow_id' => $value->show_id, 'id' => $value->id, 'type_id' => $type['id']])}}" onclick="return confirm('{{__('label.delete_episode')}}')">
                                            <i class="fa-solid fa-trash-can fa-xl" class="dot-icon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-4">{{$value->name}}</h5>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <span class="d-flex align-items-center assest-color">
                                            <i class="fa-regular fa-eye fa-lg mr-2"></i>
                                            <h5 class="counting mb-0" data-count="{{ No_Format($value->total_view ?? 0) }}">{{ No_Format($value->total_view )}}</h5>
                                        </span>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <strong class="assest-color mr-2">@if($value->season){{$value->season->name}}@endif</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center">
                    <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
                    <div class="pb-5"> {{ $data->links() }} </div>
                </div>
            </div>

            <!-- Video Modal -->
            <div class="modal fade" id="videoModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0 bg-transparent">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <video controls width="800" height="500" preload='none' poster="" id="theVideo" controlsList="nodownload noplaybackrate" disablepictureinpicture>
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sortable Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="exampleModalLabel">{{__('label.episode_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="imageListId">
                                @foreach ($sortorder_data as $key => $value)
                                <div id="{{$value->id}}" class="listitemClass mb-3 sortablebox">
                                    <p class="m-2 py-1">{{$value->name}}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form enctype="multipart/form-data" id="save_episode_sortable">
                                @csrf
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_episode_sortable()">{{__('label.save')}}</button>
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
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <script>
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

        $("#imageListId").sortable({
            update: function(event, ui) {
                getIdsOfImages();
            }
        });
        function getIdsOfImages() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id")
                    .replace("imageNo", ""));
            });
            $('#outputvalues').val(values);
        }

        function save_episode_sortable() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){
                $("#dvloader").show();
                var formData = new FormData($("#save_episode_sortable")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.tvshow.episode.sortable") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_episode_sortable', '{{ route("admin.tvshow.episode.index",["id" => $tvshow_id, "type_id" => $type["id"]]) }}');
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