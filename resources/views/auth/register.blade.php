<h2>Register</h2>

@if ($errors->any())
    <ul style="color: red">
        @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf
    <label >Nama Lengkap:</label>
    <input type="text" name="nama" required><br>

    <label >Email:</label>
    <input type="email" name="email" required><br>

    <label >Alamat:</label>
    <input type="text" name="alamat" required><br>

    <label >No HP:</label>
    <input type="text" name="no_hp" required><br>

    <label >No KTP:</label>
    <input type="text" name="no_ktp" required><br>
    
    <label >Password:</label>
    <input type="password" name="password" required><br>

    <label >Konfirmasi Password:</label>
    <input type="password" name="password_confirmation" required><br>

    <button type="submit">Daftar</button>
</form>

<p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>