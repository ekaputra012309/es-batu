<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title . config('app.name') }}</title>
    <link rel="shortcut icon" href={{ asset('backend/img/logo.ico') }} type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.min.css?v=3.2.0') }}">

    <style>
        #auth {
            height: 100vh;
            overflow-x: hidden;
        }

        #auth #auth-right {
            height: 100%;
            background: url("https://cdn.britannica.com/84/203584-050-57D326E5/speed-internet-technology-background.jpg"),
                linear-gradient(90deg, #2d499d, #3f5491);
            background-size: cover;
            background-position: center;
        }

        #auth #auth-left {
            padding: 5rem;
        }

        .btn-primary {
            background-color: #3498db;
            color: #fff;
            font-weight: bolder;
            border: #3498db;
        }

        @media screen and (max-width: 1399.9px) {
            #auth #auth-left {
                padding: 3rem;
            }
        }

        @media screen and (max-width: 767px) {
            #auth #auth-left {
                padding: 5rem;
            }
        }

        @media screen and (max-width: 576px) {
            #auth #auth-left {
                padding: 5rem 3rem;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-4 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <img src="{{ asset($companyProfile->image) }}" height="100" alt="Company Logo">
                        <!-- <img src="{{asset('backend/img/logo.png')}}" alt="Logo" style="height: 100px"> -->
                        <!-- <h2>DNA KONSULTANT</h2> -->
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="show-password">
                                    <label for="show-password">
                                        Show Password
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-8 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/adminlte.min.js?v=3.2.0') }}"></script>
    <script>
        $(document).ready(function() {
            $('#show-password').on('change', function() {
                var passwordField = $('#password');
                var passwordFieldType = passwordField.attr('type');
                if ($(this).is(':checked')) {
                    passwordField.attr('type', 'text');
                } else {
                    passwordField.attr('type', 'password');
                }
            });
        });
    </script>
</body>

</html>