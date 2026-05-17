@extends('admin.layout.page-app')
@section('tab_title', __('label.login'))

@section('content')
    <div class="h-100">
        <div class="h-100 no-gutters row">
            <div class=" h-100 col-12">
                <div class="left-caption">
                    <img src="{{ Login_Image() }}" class="bg-img" oncontextmenu="return false;"/>
                    <div class="caption">
                        <div class="login-card">
                            <div class="pb-5">
                                <div class="app-logo mb-4">
                                    <img src="{{ Tab_Icon() }}" class="mx-auto d-block" oncontextmenu="return false;"/>
                                </div>

                                <h3 class="primary-color font-weight-bold mb-2">{{__('label.login')}}</h3>
                                <h4 class="mb-0 font-weight-bold">
                                    <span class="d-block mb-2">{{__('label.welcome_back_admin')}}</span>
                                    <span>{{__('label.please_sign_in_to_your_account')}}</span>
                                </h4>

                                @php
                                    $emailValue = env('DEMO_MODE') == 'ON' ? 'admin@admin.com' : '';
                                    $passwordValue = env('DEMO_MODE') == 'ON' ? 'admin' : '';
                                @endphp

                                <form id="login_form" autocomplete="off">
                                    <div class="form-row mt-4">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label>{{__('label.email')}}</label>
                                                <input name="email" type="email" value="{{ $emailValue }}" placeholder="{{__('label.email_here')}}" class="form-control" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label>{{__('label.password')}}</label>
                                                <input name="password" type="password" value="{{ $passwordValue }}" placeholder="{{__('label.password_here')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col-sm-12 text-center text-sm-left w-100">
                                            <button class="btn btn-default mw-120 w-100" onclick="save_login()" type="button">{{__('label.login')}}</button>
                                        </div>
                                    </div>
                                </form>

                                @if( env('DEMO_MODE') == 'ON') 
                                    <hr>
                                    <h6>
                                        {{__('label.if_you_cannot_login_then')}}<a href="{{ env('APP_URL'). '/public/admin/login' }}" target="_blank" class="btn-link">{{__('label.click_here')}}</a>
                                    </h6>
                                @endif
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_login() {
            $("#dvloader").show();
            var formData = new FormData($("#login_form")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.save.login") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'login_form', '{{ route("admin.dashboard") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Press Enter Key & Save Form
        $('#login_form').keypress((e) => { 
            // Enter key corresponds to number 13 
            if (e.which === 13) { 
                save_login();
            } 
        })
    </script>
@endsection