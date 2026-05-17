@extends('admin.layout.page-app')
@section('page_title', __('label.notification_configuration'))
@section('tab_title', __('label.notification_configuration'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">

            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.notification_configuration')}}</h1>

            <div class="row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.notification_configuration')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="col-12 col-sm-8">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            <input type="checkbox" class="custom-control-input" id="notificationToggle" {{ $main_status == 1 ? 'checked' : ''}}>
                            <label class="custom-control-label" for="notificationToggle">{{__('label.do_you_want_to_disable_all_notifications')}}</label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive table" id="dataTable-container">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.type')}}</th>
                                <th>{{__('label.notification')}}</th>
                                <th>{{__('label.mail')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                @if($main_status ==1)
                    <div>
                        <button id="saveButton" class="btn btn-default mw-120" >{{ __('label.save') }}</button>
                    </div>
                @endif
            </div>   
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                lengthMenu: [
                    [15, 100, 500, -1],
                    [15, 100, 500, "All"]
                ],
                ajax: {
                    url: "{{ route('admin.notificationconfiguration.index') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, visible: false},
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'send_notification',
                        render: function(data, type, row) {
                            // Disable checkbox if type is 1, 2, 3, or 4
                            var disabled = (row.type == 'login' || row.type == 'package_expired_notice' || row.type == 'rent_expired_notice') ? 'disabled' : '';
                            return `<input type="checkbox" name="notification[]" class="notification_checkbox custom-checkbox" data-id="${row.id}" ${data == 1 ? 'checked' : ''} ${disabled}>`;
                        },
                        orderable: false
                    },
                    {
                        data: 'send_mail',
                        render: function(data, type, row) {
                            // Disable checkbox if type is 1, 2, 3, or 4
                            var disabled = (row.type == 'login' || row.type == 'package_buy' || row.type == 'package_expired_notice' || row.type == 'rent_buy' || row.type == 'rent_expired_notice') ? 'enabled' : 'disabled';
                            return `<input type="checkbox" name="email[]" class="email_checkbox custom-checkbox" data-id="${row.id}" ${data == 1 ? 'checked' : ''} ${disabled}>`;
                        },
                        orderable: false
                    },
                ],
            });
        });

        var mainstatus = "<?php echo $main_status; ?>";
        if(mainstatus == 1){
            $('#dataTable-container').show();
        }else{
            $('#dataTable-container').hide();
        }

        function SaveNotification(type, status) {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){

                let entries = [];
                $('#datatable tbody tr').each(function () {
                    let entryId = $(this).find('.notification_checkbox').data('id');
                    let notification = $(this).find('.notification_checkbox').is(':checked') ? 1 : 0;
                    let email = $(this).find('.email_checkbox').is(':checked') ? 1 : 0;

                    entries.push({
                        id: entryId,
                        notification: notification,
                        email: email,
                    });
                });

                $("#dvloader").show(); 
                $.ajax({
                    url: '{{ route("admin.notificationconfiguration.store") }}',
                    type: 'POST',
                    data: {
                        entries: entries, 
                        type: type, 
                        status: status,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function (resp) {
                        $("#dvloader").hide(); 
                        get_responce_message(resp, '', '{{ route("admin.notificationconfiguration.index") }}'); 
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide(); 
                        toastr.error(errorThrown, textStatus); 
                    }
                });
            } else {
                showError();
            }
        }

        $('#saveButton').on('click', function () {
            SaveNotification("", 2); // Call the function when Save button is clicked
        });

        // Notification toggle handler
        $('#notificationToggle').change(function () {

            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if(Check_Admin == 1){
                if (this.checked) {
                    // If the checkbox is checked, show the DataTable and enable notifications
                    $('#dataTable-container').show();
                    SaveNotification("all", 1); // Enable all notifications
                } else {
                    // If unchecked, hide the DataTable and disable notifications
                    $('#dataTable-container').hide();
                    SaveNotification("all", 0); // Disable all notifications
                }
            } else{
                showError();
            }
        }); 
    </script>
@endsection