<div class="widget-shadow">
    <div class="login-body">
        <form action="{{ $action ?? route('login') }}" method="post">
            @csrf
            <input type="email" class="user" name="email" placeholder="Enter Your Email" required="">
            <input type="password" name="password" class="lock" placeholder="Password" required="">
            <div class="forgot-grid">
                <label class="checkbox"><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}><i></i>Remember me</label>
                <div class="forgot">
                    <a href="{{ route('password.request') }}">forgot password?</a>
                </div>
                <div class="clearfix"> </div>
            </div>
            <input type="submit" name="Sign In" value="Sign In">
            <div class="registration">
                Don't have an account ?
                <a class="text-white bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" href="{{ route('register') }}">
                    Create an account
                </a>
            </div>
        </form>
    </div>
</div>
