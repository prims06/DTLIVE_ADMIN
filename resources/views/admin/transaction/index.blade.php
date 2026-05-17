@extends('admin.layout.page-app')
@section('page_title', __('label.transactions'))
@section('tab_title', __('label.transactions'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.transactions')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.transactions')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.transaction.create') }}" class="btn btn-default-white mw-120" style="margin-top:-14px">{{__('label.add_transaction')}}</a>
                </div>
            </div>

            <div class="card custom-border-card mt-2">
                <!-- Search -->
                <div class="page-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                            </span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                    <div class="sorting mr-4" style="width: 40%;">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" name="input_package" id="input_package">
                            <option value="0" selected>{{__('label.all_package')}}</option>
                            @for ($i = 0; $i < count($package); $i++) 
                            <option value="{{ $package[$i]['id'] }}" @if(isset($_GET['input_package'])){{ $_GET['input_package'] == $package[$i]['id'] ? 'selected' : ''}} @endif>
                                {{ $package[$i]['name'] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="sorting" style="width: 30%;">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" id="input_type">
                            <option value="all">{{__('label.all')}}</option>
                            <option value="today">{{__('label.today')}}</option>
                            <option value="month">{{__('label.month')}}</option>
                            <option value="year">{{__('label.year')}}</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.coupon_code')}}</th>
                                <th>{{__('label.user')}}</th>
                                <th>{{__('label.email')}}</th>
                                <th>{{__('label.mobile_number')}}</th>
                                <th>{{__('label.package')}}</th>
                                <th>{{__('label.price')}}</th>
                                <th>{{__('label.transaction_id')}}</th>
                                <th>{{__('label.transaction_date')}}</th>
                                <th>{{__('label.expiry_date')}}</th>
                                <th>{{__('label.status')}}</th>
                                <th>{{__('label.transaction_status')}}</th>
                                <th>{{__('label.action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="13" class="text-center"></td>
                            </tr>
                        </tfoot>
                    </table>
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
                ajax: {
                    url: "{{ route('admin.transaction.index') }}",
                    data: function(d) {
                        d.input_type = $('#input_type').val();
                        d.input_package = $('#input_package').val();
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
                        orderable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'user.full_name',
                        name: 'user.full_name',
                        orderable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'user.email',
                        name: 'user.email',
                        orderable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'user.mobile_number',
                        name: 'user.mobile_number',
                        orderable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'package.name',
                        name: 'package.name',
                        render: function(data) {
                            return data ? data : "-";
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
                        data: 'transaction_id',
                        name: 'transaction_id',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'date',
                        name: 'date',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'transaction_status',
                        name: 'transaction_status',
                        render: function(data) {
                            if (data == 1) {
                               return "<h5 class='text-primary'>{{__('label.processing') }}</h5>";
                            } else if (data == 2) {
                                return "<h5 class='text-success'>{{__('label.success') }}</h5>";
                            } else if (data == 3) {
                                return "<h5 class='text-danger'>{{__('label.failed') }}</h5>";
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                    };

                    // computing column Total of the complete result 
                    var Total = api
                        .column(6)
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(1).footer() ).html("{{__('label.total_amount')}} {{Currency_Code() }}"+ " " + Total);
                },
            });

            $('#input_type, #input_package').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });
    </script>
@endsection