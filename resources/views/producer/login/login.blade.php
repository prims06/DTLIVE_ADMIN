@extends('producer.layout.page-app')
@section('tab_title', __('label.login'))

@section('content')
    <div class="h-100 d-flex login-bg">
        <div class="app-logo text-center">
            <h1 class="primary-color">{{ App_Name() }}</h1>
        </div>
        <div class="app-login-box">
            <h5 class="mb-0 font-weight-bold mt-4">
                <span class="d-block mb-2">{{__('label.welcome_back_producer')}}</span>
                <span>{{__('label.please_sign_in_to_your_account')}}</span>
            </h5>

            @php
                $emailValue = env('DEMO_MODE') == 'ON' ? 'producer@producer.com' : '';
                $passwordValue = env('DEMO_MODE') == 'ON' ? 'producer' : '';
            @endphp
            <form id="login_form">
                <div class="form-row mt-4">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label>{{__('label.email')}}</label>
                            <input name="email" type="email" value="{{ $emailValue }}" class="form-control" placeholder="{{__('label.email_here')}}" autofocus>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label>{{__('label.password')}}</label>
                            <input name="password" type="password" value="{{ $passwordValue }}" placeholder="{{__('label.password_here')}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-row mt-4">
                    <div class="col-sm-12 text-center text-sm-left">
                        <button class="btn btn-default my-2 btn-block" onclick="save_login()" type="button">{{__('label.login')}}</button>
                    </div>
                </div>
            </form>
            @if( env('DEMO_MODE') == 'ON') 
                <hr>
                <h6>
                    {{__('label.if_you_cannot_login_then')}}<a href="{{ env('APP_URL'). '/public/producer/login' }}" target="_blank" class="btn-link">{{__('label.click_here')}}</a>
                </h6>
            @endif
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
                url: '{{ route("producer.save.login") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'login_form', '{{ route("producer.dashboard") }}');
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