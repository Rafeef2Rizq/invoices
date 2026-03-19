<x-guest-layout>
    <h5 class="fw-bold mb-4">Sign In</h5>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    Forgot password?
                </a>
            @endif
            <button type="submit" class="btn btn-primary px-4">Sign In</button>
        </div>

        <hr>
        <div class="text-center small">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-decoration-none">Register</a>
        </div>
    </form>
</x-guest-layout>