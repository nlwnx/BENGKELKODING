<h2>Login</h2>

@if ($erorrs-> any())
    <p style="color: red">{{ $errors->first() }}</p>
@endif

<form method="POST" action="{{ route('login') }}"> 
    @csrf 
    <label>Email</label><br>
    <input type="email" name="email" required><br>

    <label>Password:</label> <br>
    <input type="password" name="password" required><br><br>

    <button type="submit"> Login</button>
</form>

<p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
