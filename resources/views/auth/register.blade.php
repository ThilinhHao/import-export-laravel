@extends('layouts.master')

@section('title', 'Register')

@section('content')

@php
    use App\Constants\AppConstants;
@endphp

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="">
                            <span class="d-none d-lg-block">NiceAdmin</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                <p class="text-center small">Enter your personal details to create account</p>
                            </div>

                            @if (session('success'))
                                <div id="flash-message" class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form class="row g-3" action="{{ route('store') }}" method="POST" id="registrationForm">
                                @csrf
                                <div class="col-12">
                                    <label for="yourName" class="form-label">User Name</label>
                                    <input type="text" name="name" class="form-control" id="yourName" value="{{ old('name') }}">

                                    @error('name')
                                        <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="yourEmail" class="form-label">Your Email</label>
                                    <input type="email" name="email" class="form-control" id="yourEmail" value="{{ old('email') }}">

                                    @error('email')
                                        <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="yourPassword">

                                    @error('password')
                                        <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="yourPassword" class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" id="confirmPassword">

                                    @error('confirm_password')
                                        <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="userRole" class="form-label">Permission</label>
                                    <select name="role" class="form-select" id="userRole">
                                        <option value="">Select permission</option>
                                        @foreach (AppConstants::ROLES as $role)
                                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                                {{ ucwords($role) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('role')
                                        <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" name="terms" type="checkbox" id="acceptTerms" {{ old('terms') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                                    </div>

                                    @error('terms')
                                        <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
                                </div>
                                <div class="col-12">
                                    <p class="small mb-0">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="credits">
                        <!-- All the links in the footer should remain intact. -->
                        <!-- You can delete the links only if you purchased the pro version. -->
                        <!-- Licensing information: https://bootstrapmade.com/license/ -->
                        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        // remove flash message
        const flashMessage = document.querySelector("#flash-message");
        if (flashMessage !== null) {
            setTimeout(() => {
                flashMessage.remove();
            }, 5000);
        }
    </script>
@endpush
