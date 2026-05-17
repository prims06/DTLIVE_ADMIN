@extends('admin.layout.page-app')
@section('page_title', __('label.withdrawal_request'))
@section('tab_title', __('label.withdrawal_request'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('label.withdrawal_request')}} </h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.withdrawal_request')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card mt-2">
                <!-- Search -->
                <div class="page-search mb-3">
                    <div class="sorting mr-4 w-25">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" name="producer_id" id="input_producer">
                            <option value="all">{{__('label.all_producer')}}</option>
                            @foreach ($producer as $key => $value)
                                <option value="{{$value->id}}">
                                    {{ $value->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sorting w-25">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" id="input_status">
                            <option value="all">{{__('label.all_status')}}</option>
                            <option value="0">{{__('label.pending')}}</option>
                            <option value="1">{{__('label.completed')}}</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.producer')}}</th>
                                <th>{{__('label.email')}}</th>
                                <th>{{__('label.mobile_number')}}</th>
                                <th>{{__('label.price')}}</th>
                                <th>{{__('label.date')}}</th>
                                <th>{{__('label.action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
                    url: "{{ route('admin.withdrawal.index') }}",
                    data: function(d) {
                        d.input_status = $('#input_status').val();
                        d.input_producer = $('#input_producer').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'producer',
                        name: 'producer',
                        render: function(data) {
                            return data ? data.full_name : "-";
                        }
                    },
                    {
                        data: 'producer',
                        name: 'producer',
                        render: function(data) {
                            return data ? data.email : "-";
                        }
                    },
                    {
                        data: 'producer',
                        name: 'producer',
                        render: function(data) {
                            return data ? data.mobile_number : "-";
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
                        data: 'date',
                        name: 'date'
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                ],
            });
                    
            $('#input_status, #input_producer').change(function() {
                table.draw();
            });
        });
        
        function change_status(id, status) {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if (Check_Admin == 1) {

                $("#dvloader").show();
                var url = "{{route('admin.withdrawal.show', '')}}" + "/" + id;
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
                                $('#' + id).text('{{__("label.completed")}}').removeClass('hide-btn').addClass('show-btn');
                            } else {
                                $('#' + id).text('{{__("label.pending")}}').removeClass('show-btn').addClass('hide-btn');
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