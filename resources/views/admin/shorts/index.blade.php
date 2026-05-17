@extends('admin.layout.page-app')
@section('page_title', $type['name'])
@section('tab_title', $type['name'])

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{$type['name']}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$type['name']}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.shorts.add', ['type_id' => $type['id']]) }}" class="btn btn-default-white mw-150" style="margin-top: -14px;">{{__('label.add_content')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <!-- Search -->
                <form action="{{ route('admin.shorts.index', ['type_id' => $type['id']])}}" method="GET">
                    <div class="page-search">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                                </span>
                            </div>
                            <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                        
                        <div class="mr-3 ml-4">
                            <button class="btn btn-default-white" type="submit">{{__('label.search')}}</button>
                        </div>
                    </div>
                    <div class="page-search mb-2">
                        <div class="sorting mr-3 w-50">
                            <label>{{__('label.sort_by')}}</label>
                            <select class="form-control" name="input_producer" id="input_producer">
                                <option value="0" selected>{{__('label.all_producer')}}</option>
                                @for ($i = 0; $i < count($producer); $i++) 
                                <option value="{{ $producer[$i]['id'] }}" @if(isset($_GET['input_producer'])){{ $_GET['input_producer'] == $producer[$i]['id'] ? 'selected' : ''}} @endif>
                                    {{ $producer[$i]['user_name'] }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="sorting w-25">
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
                                    <img class="card-img-top" src="{{ $value->thumbnail }}">
                                    @if($value->trailer_type == "server_video")
                                        <button class="btn play-btn-top video" data-toggle="modal" data-target="#videoModal" data-video="{{ $value->trailer_url }}" data-image="{{ $value->thumbnail }}">
                                            <i class="fa-regular fa-circle-play text-white fa-4x mr-2 mt-2"></i>
                                        </button>
                                    @endif

                                    <ul class="list-inline overlap-control" aria-labelledby="dropdownMenuLink">
                                        <li class="list-inline-item">
                                            <a class="edit-delete-btn" href="{{ route('admin.shorts.edit', ['shorts_id' => $value->id, 'type_id' => $type['id']]) }}">
                                                <i class="fa-solid fa-pen-to-square fa-xl" class="dot-icon"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a class="edit-delete-btn" href="{{route('admin.shorts.show', ['shorts_id' => $value->id, 'type_id' => $type['id']]) }}" onclick="return confirm('{{__('label.delete_shorts')}}')">
                                                <i class="fa-solid fa-trash-can fa-xl" class="dot-icon"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-4" title="{{ $value->name }}">{{ $value->name }}</h5>
                                    <div class="row">
                                        <div class="col-4">
                                            <span class=" mt-1 d-flex align-items-center assest-color">
                                                <i class="fa-regular fa-eye fa-lg mr-2"></i>
                                                <h5 class="counting mb-0" data-count="{{ No_Format($value->total_view ?? 0) }}">{{ No_Format($value->total_view )}}</h5>
                                            </span>
                                        </div>
                                        <div class="col-8 d-flex justify-content-between"> 
                                            @if($value->status == 1)
                                                <button class="btn btn-sm show-btn" id="{{$value->id}}" onclick="change_status('{{$value->id}}', '{{$value->status}}')">{{__('label.show')}}</button>
                                            @elseif($value->status == 0)
                                                <button class="btn btn-sm hide-btn" id="{{$value->id}}" onclick="change_status('{{$value->id}}', '{{$value->status}}')">{{__('label.hide')}}</button>
                                            @endif
                                            <a href="{{ route('admin.shorts.episode.index', ['id' => $value->id, 'type_id' => $value->type_id]) }}" class="btn btn-sm info-btn">{{__('label.episode_list')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center">
                    <div> Showing {{ $result->firstItem() }} to {{ $result->lastItem() }} of total {{$result->total()}} entries </div>
                    <div class="pb-5"> {{ $result->links() }} </div>
                </div>
            </div>

            <!-- Video Model -->
            <div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0 bg-transparent">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <video controls width="800" height="500" preload="none" poster="" id="theVideo" controlsList="nodownload noplaybackrate" disablepictureinpicture>
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).on('click', '.video', function () {
            const theModal = $(this).data("target");
            const videoSRC = $(this).data("video");            
            const videoPoster = $(this).data("image");

            // Set source and poster
            $(theModal + ' source').attr('src', videoSRC);
            $(theModal + ' video').attr('poster', videoPoster);

            const video = $(theModal + ' video')[0];
            video.load();

            $("#videoModal .close").click(function() {
                video.pause()
                video.currentTime = 0;
                $(theModal + ' source').attr('src', '');
            });
            $('#videoModal').on('contextmenu', function (e) {
                e.preventDefault();
            });
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
                    url: "{{route('admin.shorts.status')}}",
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
    </script>
@endsection