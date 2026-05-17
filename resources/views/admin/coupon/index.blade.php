@extends('admin.layout.page-app')
@section('page_title', __('label.coupon'))
@section('tab_title', __('label.coupon'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.coupon')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.coupon')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Add Coupon -->
            <div class="card custom-border-card">
                <h5 class="card-header">{{__('label.add_coupon')}}</h5>
                <div class="card-body">
                    <form id="coupon" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                    <input name="name" type="text" class="form-control" placeholder="{{__('label.name_here')}}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.start_date')}}<span class="text-danger">*</span></label>
                                    <input name="start_date" type="date" class="form-control" min="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.end_date')}}<span class="text-danger">*</span></label>
                                    <input name="end_date" type="date" class="form-control" min="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.amount_type')}}</label>
                                    <select class="form-control" name="amount_type" id="amount_type">
                                        <option value="1">{{__('label.price')}}</option>
                                        <option value="2">{{__('label.percentage')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="label">{{__('label.price')}} / {{__('label.percentage')}}<span class="text-danger">*</span></label>
                                    <input name="price" type="number" class="form-control" placeholder="{{__('label.please_enter_price_percentage')}}" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.is_use')}}</label>
                                    <select class="form-control" name="is_use">
                                        <option value="0">{{__('label.multiple_use')}}</option>
                                        <option value="1">{{__('label.single_use')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.is_use_limit')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_use_limit" id="is_use_limit_no" class="custom-control-input" value="0" checked>
                                            <label class="custom-control-label" for="is_use_limit_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_use_limit" id="is_use_limit_yes" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="is_use_limit_yes">{{__('label.yes')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 use_limit">
                                <div class="form-group">
                                    <label>{{__('label.use_limit')}}<span class="text-danger">*</span></label>
                                    <input name="use_limit" type="number" min="0" class="form-control" placeholder="{{__('label.use_limit_here')}}">
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_coupon()">{{__('label.save')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="{{__('label.search')}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>  
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.unique_id')}}</th>
                                <th>{{__('label.name')}}</th>
                                <th>{{__('label.start_date')}}</th>
                                <th>{{__('label.end_date')}}</th>
                                <th>{{__('label.amount_type')}}</th>
                                <th>{{__('label.price')}}</th>
                                <th>{{__('label.is_use')}}</th>
                                <th>{{__('label.is_use_limit')}}</th>
                                <th>{{__('label.use_limit')}}</th>                                
                                <th>{{__('label.status')}}</th>
                                <th>{{__('label.action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Model -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_coupon')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_coupon" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="edit_name" class="form-control" placeholder="{{__('label.name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.start_date')}}<span class="text-danger">*</span></label>
                                            <input name="start_date" type="date" id="edit_start_date"  class="form-control" min="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.end_date')}}<span class="text-danger">*</span></label>
                                            <input name="end_date" type="date" id="edit_end_date"  class="form-control" min="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.amount_type')}}</label>
                                            <select class="form-control" name="amount_type" id="edit_amount_type">
                                                <option value="1">{{__('label.price')}}</option>
                                                <option value="2">{{__('label.percentage')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label">{{__('label.price')}} / {{__('label.percentage')}}<span class="text-danger">*</span></label>
                                            <input name="price" type="number" id="edit_price" class="form-control" placeholder="{{__('label.please_enter_price_percentage')}}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.is_use')}}</label>
                                            <select class="form-control" name="is_use" id="edit_is_use">
                                                <option value="0">{{__('label.multiple_use')}}</option>
                                                <option value="1">{{__('label.single_use')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.is_use_limit')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="edit_is_use_limit" id="edit_is_use_limit_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_is_use_limit_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="edit_is_use_limit" id="edit_is_use_limit_yes" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="edit_is_use_limit_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_use_limit">
                                        <div class="form-group">
                                            <label>{{__('label.use_limit')}}<span class="text-danger">*</span></label>
                                            <input name="use_limit" type="number" min="0" id="edit_use_limit" class="form-control" placeholder="{{__('label.use_limit_here')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_coupon()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-chevron-left'></i>",
                        next: "<i class='fa-solid fa-chevron-right'></i>"
                    }
                },
                ajax:
                    {
                    url: "{{ route('admin.coupon.index') }}",
                    data: function(d){
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'unique_id',
                        name: 'unique_id',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'amount_type',
                        name: 'amount_type',
                        render: function(data, type, full, meta) {
                            if (data == 1) {
                                return "{{__('label.price')}}";
                            } else if (data == 2) {
                                return "{{__('label.percentage')}}";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'is_use',
                        name: 'is_use',
                        render: function(data, type, full, meta) {
                            if (data == 0) {
                                return "{{__('label.multiple_use')}}";
                            } else if (data == 1) {
                                return "{{__('label.single_use')}}";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'is_use_limit',
                        name: 'is_use_limit',
                        render: function(data, type, full, meta) {
                            if (data == 0) {
                                return "{{__('label.no')}}";
                            } else if (data == 1) {
                                return "{{__('label.yes')}}";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'use_limit',
                        name: 'use_limit',
                        render: function(data, type, full, meta) {
                            return data ?? 0
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('#input_search').keyup(function() {
                table.draw();
            });

            $(".use_limit").hide();
            $('input[type=radio][name=is_use_limit]').change(function() {
                if (this.value == 1) {
                    $(".use_limit").show();
                } else if (this.value == 0) {
                    $(".use_limit").hide();
                }
            });
        });

        function save_coupon(){
            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#coupon")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("admin.coupon.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'coupon', '{{ route("admin.coupon.index") }}');
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

        $(document).on("click", ".edit_coupon", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var start_date = $(this).data('start_date');
            var end_date = $(this).data('end_date');
            var amount_type = $(this).data('amount_type');
            var price = $(this).data('price');
            var is_use = $(this).data('is_use');
            var is_use_limit = $(this).data('is_use_limit');
            var use_limit = $(this).data('use_limit');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #edit_start_date").val(start_date);
            $(".modal-body #edit_end_date").val(end_date);
            $(".modal-body #edit_amount_type").val(amount_type).attr("selected","selected");
            $(".modal-body #edit_price").val(price);
            $(".modal-body #edit_is_use").val(is_use).attr("selected","selected");
            $(".modal-body #edit_use_limit").val(use_limit);
            if(is_use_limit == 1){
                $("#edit_is_use_limit_yes").prop('checked', true);
                $("#edit_is_use_limit_no").prop('checked', false);
            } else {
                $("#edit_is_use_limit_yes").prop('checked', false);
                $("#edit_is_use_limit_no").prop('checked', true);
            }


            if(is_use_limit == 1) {
                $(".edit_use_limit").show();
            } else if (is_use_limit == 0) {
                $(".edit_use_limit").hide();
            }
            $('input[type=radio][name=edit_is_use_limit]').change(function() {
                if (this.value == 1) {
                    $(".edit_use_limit").show();
                } else if (this.value == 0) {
                    $(".edit_use_limit").hide();
                }
            });
        });
        

        function update_coupon() {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#update_coupon")[0]);
                var Edit_Id = $("#edit_id").val();

                var url = '{{ route("admin.coupon.update", ":id") }}';
                    url = url.replace(':id', Edit_Id);

                $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();

                        if(resp.status == 200){
                            $('#EditModel').modal('toggle');
                        }
                        get_responce_message(resp, 'update_coupon', '{{ route("admin.coupon.index") }}');
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

        function change_status(id) {

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var url = "{{route('admin.coupon.show', '')}}" + "/" + id;
                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: id,
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