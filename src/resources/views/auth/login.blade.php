@extends('layouts.auth')

@section('main')
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Admin</b>LTE</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form method="POST" action="#" id="form-login">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email"/>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <span class="invalid-feedback" role="alert">
                        <strong id="email_err"></strong>
                    </span>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <span class="invalid-feedback" role="alert">
                        <strong id="password_err"></strong>
                    </span>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
            <div class="social-auth-links text-center mt-2 mb-3">
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div>

            <p class="mb-1">
                <a href="/auth/forgot">
                    I forgot my password
                </a>
            </p>
            <p class="mb-0">
                <a href="/auth/register" class="text-center">
                    Register a new membership
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('page-js')
<script src="{{ asset('/assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/sweetalert/sweetalert2@10.js') }}"></script>
<script>
    $(document).ready(function() {
        let token = localStorage.getItem('access_token')
        if (token) {
            location.href = '/reminder'
        }

        function clearForm() {
            $('#email').val('')
            $('#password').val('')
        }

        function clearError() {
            $('#email').removeClass('is-invalid')
            $('#password').removeClass('is-invalid')

            $('#email_err').empty()
            $('#password_err').empty()
        }

        $('#form-login').on('submit', function(e){
            e.preventDefault()

            let email = $('#email').val()
            let password = $('#password').val()
            
            axios.post(`/api/session`, {email, password})
                .then(res => {
                    if (res.data.ok === true) {
                        clearForm()
                        clearError()

                        localStorage.setItem('access_token', res.data.data.access_token)
                        localStorage.setItem('refresh_token', res.data.data.refresh_token)

                        Swal.fire({
                            icon: 'success',
                            title: "Success",
                            text: 'Success Login'
                        })

                        setTimeout(() => location.href = '/reminder', 1000)
                    }
                })
                .catch(err => {
                    if (err.response.data.ok === false) {
                        Swal.fire({
                            icon: 'warning',
                            title: "Failed",
                            text: err.response.data.msg,
                        })

                        clearForm()
                        clearError()
                    }

                    if (err.response.data.errors) {
                        clearError()

                        if (err.response.data.errors.email) {
                            $('#email').addClass('is-invalid')
                            $('#email_err').text(err.response.data.errors.email[0])
                        }
                        if (err.response.data.errors.password) {
                            $('#password').addClass('is-invalid')
                            $('#password_err').text(err.response.data.errors.password[0])
                        }
                    }
                })
        })
    })
</script>
@endpush