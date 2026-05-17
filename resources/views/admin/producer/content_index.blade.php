@extends('admin.layout.page-app')
@section('page_title', __('label.producer_content'))
@section('tab_title', __('label.producer_content'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.producer_content')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.producer.index') }}">{{__('label.producer')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.producer_content')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.producer.index') }}" class="btn btn-default-white mw-120" style="margin-top:-14px">{{__('label.producer_list')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <!-- Search -->
                <form action="{{ route('admin.producer.content', ['producer_id' => $producer_id, 'content_type' => $content_type])}}" method="GET">
                    <div class="page-search">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl"></i></span>
                            </div>
                            <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                        <div class="mr-3 ml-4">
                            <button class="btn btn-default-white" type="submit">{{__('label.search')}}</button>
                        </div>
                    </div>
                    <div class="page-search mb-2">
                        <label class="mt-2 assest-color" style="min-width: 65px; font-weight: 600;">{{__('label.sort_by')}}</label>
                        @if($content_type == 1 || $content_type == 2)
                        <div class="sorting mr-3 w-25">
                            <select class="form-control" name="input_rent">
                                <option value="0" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 0 ? 'selected' : ''}} @endif>{{__('label.all_video')}}</option>
                                <option value="1" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 1 ? 'selected' : ''}} @endif>{{__('label.rent_video')}}</option>
                            </select>
                        </div>
                        @endif
                        @if($content_type == 1)
                        <div class="sorting mr-3 w-25">
                                <select class="form-control" name="input_premimum">
                                    <option value="all">{{__('label.all_video')}}</option>
                                    <option value="0" @if(isset($_GET['input_premimum'])){{ $_GET['input_premimum'] == 0 ? 'selected' : ''}} @endif>{{__('label.non_premium')}}</option>
                                    <option value="1" @if(isset($_GET['input_premimum'])){{ $_GET['input_premimum'] == 1 ? 'selected' : ''}} @endif>{{__('label.premium')}}</option>
                                </select>
                            </div>
                        @endif
                        <div class="sorting w-25">
                            <select class="form-control" name="input_status">
                                <option value="all">{{__('label.all_status')}}</option>
                                <option value="0" @if(isset($_GET['input_status'])){{ $_GET['input_status'] == 0 ? 'selected' : ''}} @endif>{{__('label.hide')}}</option>
                                <option value="1" @if(isset($_GET['input_status'])){{ $_GET['input_status'] == 1 ? 'selected' : ''}} @endif>{{__('label.show')}}</option>
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Card -->
                <div class="row">
                    @foreach ($result as $key => $value)
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                        <div class="card video-card">
                            <div class="position-relative">
                                @if($value->is_premium == 1)
                                    <div class="ribbon ribbon-top-left"><span>{{__('label.premium')}}</span></div>
                                @endif
                                <img class="card-img-top" src="{{ $value->thumbnail }}">
                            </div>
                            <div class="card-body assest-color">
                                <h6><Strong>{{__('label.type')}} : {{ $value['type']['name'] ?? ""}}</Strong></h6>
                                <h5 class="card-title">{{ $value->name }}</h5>
                                <hr class="mb-2" style="border: 1px dashed;">
                                <div class="row">
                                    <div class="col-6">
                                        <span class="d-flex text-align-center">
                                            <i class="fa-regular fa-eye fa-xl mr-2" style="margin-top:12px"></i>
                                            <h5 class="counting" data-count="{{ No_Format($value->total_view ?? 0) }}">{{ No_Format($value->total_view) }}</h5>
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex justify-content-end">
                                            @if($value->status == 1)
                                            <button class="btn btn-sm show-btn" id="{{$value->id}}" onclick="change_status('{{$value->id}}', '{{$value->status}}')">{{__('label.show')}}</button>
                                            @elseif($value->status == 0)
                                            <button class="btn btn-sm hide-btn" id="{{$value->id}}" onclick="change_status('{{$value->id}}', '{{$value->status}}')">{{__('label.hide')}}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>Showing {{ $result->firstItem() }} to {{ $result->lastItem() }} of total {{$result->total()}} entries</div>
                    <div>{{ $result->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function change_status(id, status) {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "{{route('admin.producer.content_status')}}",
                    data: {
                        id: id,
                        content_type : "{{ $content_type }}",
                        producer_id : "{{ $producer_id }}"
                    },
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