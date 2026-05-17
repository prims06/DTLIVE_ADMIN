@extends('admin.layout.page-app')
@section('page_title', __('label.add_transaction'))
@section('tab_title', __('label.add_transaction'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_transaction')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.transaction.index') }}">{{__('label.transactions')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_transaction')}}</li>
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
                            <a href="{{route('admin.transaction.create')}}" class="btn btn-cancel mw-120">{{__('label.clear')}}</a>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (isset($user->id)) { ?>
                <div class="card custom-border-card mt-3">
                    <form enctype="multipart/form-data" id="add_transaction">
                        @csrf
                        <input name="user_id" type="hidden" class="form-control" readonly id="user_id" value="{{$user->id}}">
                        <div class="form-row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.name')}}</label>
                                    <input name="full_name" type="text" class="form-control" readonly value="{{$user->full_name}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.mobile_number')}}</label>
                                    <input name="mobile_number" type="text" class="form-control" readonly value="{{$user->mobile_number}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>{{__('label.email')}}</label>
                                    <input name="email" type="text" class="form-control" readonly value="{{$user->email}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{__('label.package')}}</label>
                                    <select name="package_id" class="form-control">
                                        <option value="">{{__('label.select_package')}}</option>
                                        @foreach($package as $row)
                                        <option value="{{$row->id}}">{{$row->name}} &nbsp; - &nbsp;{{$row->price}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
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
    <script>

        // Sidebar Scroll Down
        sidebar_down($(document).height());

        function add_transaction() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#add_transaction")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.transaction.store") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'add_transaction', '{{ route("admin.transaction.index") }}');
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
                url: '{{ route("admin.searchUser")}}',
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