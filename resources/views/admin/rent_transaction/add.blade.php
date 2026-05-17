@extends('admin.layout.page-app')
@section('page_title', __('label.add_rent_transaction'))
@section('tab_title', __('label.add_rent_transaction'))

@section('content')
    @include('admin.layout.sidebar')

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    
    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_rent_transaction')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rent-transaction.index') }}">{{__('label.rent_transaction')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_rent_transaction')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <form enctype="multipart/form-data" id="search_user">
                    @csrf
                    <div class="form-row">
                        <div class="col-8">
                            <div class="form-group">
                                <input name="name" type="text" class="form-control" id="name" placeholder="{{__('label.search_user_name_or_mobile')}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-default mw-120 mr-3" onclick="search_user()">{{__('label.search')}}</button>
                            <a href="{{route('admin.rent-transaction.create')}}" class="btn btn-cancel mw-120">{{__('label.clear')}}</a>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (isset($user->id)) { ?>
                <div class="card custom-border-card mt-3">
                    <form enctype="multipart/form-data" id="add_transaction">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.name')}}</label>
                                    <input name="user_id" type="hidden" class="form-control" readonly id="user_id" value="{{$user->id}}">
                                    <input name="full_name" type="text" class="form-control" readonly value="@if($user->full_name){{$user->full_name}}@else - @endif">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.mobile_number')}}</label>
                                    <input name="mobile_number" type="text" class="form-control" readonly value="@if($user->mobile_number){{$user->mobile_number}}@else - @endif">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.email')}}</label>
                                    <input name="email" type="text" class="form-control" readonly value="@if($user->email){{$user->email}}@else - @endif">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.rent_videos')}}</label>
                                    <select name="rent_video_id" class="form-control" id="rent_video_id">
                                        <option value="">{{__('label.select_video')}}</option>
                                        @foreach($rent_video as $row)
                                            <option value="{{$row->id}}">{{$row->name}}&nbsp;&nbsp; - &nbsp;&nbsp;{{$row->rent_price_list->price ?? 0}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.rent_tvshows')}}</label>
                                    <select name="rent_show_id" class="form-control" id="rent_show_id">
                                        <option value="">{{__('label.select_show')}}</option>
                                        @foreach($rent_tv_show as $row)
                                            <option value="{{$row->id}}">{{$row->name}}&nbsp;&nbsp; - &nbsp;&nbsp;{{$row->rent_price_list->price ?? 0}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-gray">{{__('label.transaction_note')}}</label>
                            </div>
                        </div>
                        <div class="col-1 mt-2">
                            <button type="button" class="btn btn-default mw-120" onclick="add_transaction()">{{__('label.save')}}</button>
                        </div>
                    </form>
                </div>
            <?php } else { ?>
                <div class="card custom-border-card mt-3">
                    <div class="col-12">
                        <h3>{{__('label.user_list')}}</h3>

                        <div id="user_list"></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $("#rent_video_id").select2();
        $("#rent_show_id").select2();

        function add_transaction() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#add_transaction")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.rent-transaction.store") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'add_transaction', '{{ route("admin.rent-transaction.index") }}');
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
        function search_user() {

            var formData = new FormData($("#search_user")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.rentSearchUser") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $('#user_list').html(resp.result);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection