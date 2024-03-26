@extends('layouts.common.header')

@section('content')
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <span class="d-none d-lg-block">Forgot Password</span>
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <p class="text-center small">Enter your email to reset password</p>
                  </div>

                  @if (session('success'))
                      <div id="flash-message" class="alert alert-success">
                          {{ session('success') }}
                      </div>
                  @endif

                  <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" id="email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    
                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Send Password Reset Link</button>
                        </div>

                        <div class="col-12">
                          <button type="button" class="btn btn-secondary w-100" onclick="window.location.href = '{{ route('login') }}'">Cancel</button>
                      </div>

                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
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


