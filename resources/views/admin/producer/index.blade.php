@extends('admin.layout.page-app')
@section('page_title', __('label.producer'))
@section('tab_title', __('label.producer'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.producer')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.producer')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.producer.create') }}" class="btn btn-default-white mw-120" style="margin-top: -14px;">{{__('label.add_producer')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <!-- Search -->
                <form action="{{ route('admin.producer.index')}}" method="GET">
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
                </form>

                <div class="row mt-2">
                    @foreach ($data as $key => $value)
                        <div class="col-12 col-xl-4">
                            <div class="card landscape-card">
                                <div class="media">
                                    <img src="{{ $value['image'] }}" class="wallet-image">
                                    <div class="card-body px-2 py-0">
                                        <h6 class="landscape-card-title">{{$value['user_name'] ?? ''}}</h6>
                                        <p class="landscape-card-name mb-0">{{$value['full_name'] ?? ''}}</p>
                                        <div class="landscape-card-border"></div>
                                        <h6 class="">{{$value['email'] ?? ''}}</h6>
                                        <div class="landscape-card-border"></div>
                                        <h6 class="">{{$value['mobile_number'] ?? ''}}</h6>
                                        <div class="landscape-card-border"></div>
                                    </div>
                                </div>
                                <ul class="list-inline overlap-control mb-0 mt-2" aria-labelledby="dropdownMenuLink">
                                    <li class="list-inline-item">
                                        <a class="btn edit-delete-btn" href="{{route('admin.producer.content', ['producer_id' => $value->id, 'content_type' => 1])}}">
                                            {{__('label.movies')}}: {{ $value->movies_count }}
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a class="btn edit-delete-btn" href="{{route('admin.producer.content', ['producer_id' => $value->id, 'content_type' => 2])}}">
                                            {{__('label.tvshows')}}: {{ $value->tvshow_count }}
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a class="btn edit-delete-btn" href="{{route('admin.producer.content', ['producer_id' => $value->id, 'content_type' => 3])}}">
                                            {{__('label.shorts')}}: {{ $value->shorts_count }}
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a class="btn edit-delete-btn" href="{{route('admin.producer.edit', [$value->id])}}">
                                            <i class="fa-solid fa-pen-to-square fa-xl" class="dot-icon"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a class="btn edit-delete-btn" href="{{route('admin.producer.show', [$value->id])}}" onclick="return confirm('{{ __('label.delete_producer') }}')">
                                            <i class="fa-solid fa-trash-can fa-xl" class="dot-icon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
                <div class="pb-5"> {{ $data->links() }} </div>
            </div>
        </div>
    </div>
@endsection