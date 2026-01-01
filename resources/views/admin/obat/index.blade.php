<x-layouts.app title="Data Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- ALERT FLASH MESSAGE --}}
                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h1 class="mb-4">Data Obat</h1>

                <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Obat
                </a>

                <!-- ✅ RINGKASAN STOK -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-exclamation-triangle"></i> Stok Habis
                                </h5>
                                <h2>{{ $obats->where('stok', '<=', 0)->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-exclamation-circle"></i> Stok Menipis
                                </h5>
                                <h2>{{ $obats->where('stok', '>', 0)->where('stok', '<=', 10)->count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-check-circle"></i> Stok Tersedia
                                </h5>
                                <h2>{{ $obats->where('stok', '>', 10)->count() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">Id</th>
                                <th>Nama Obat</th>
                                <th>Kemasan</th>
                                <th style="width: 120px;">Harga</th>
                                <th style="width: 100px;">Stok</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                           {{-- START: PERULANGAN DATA OBAT --}}
                            @foreach ($obats as $obat) 
                                <tr>
                                    <td>{{ $obat->id }}</td>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->kemasan }}</td>
                                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                    
                                    {{-- ✅ KOLOM STOK DENGAN WARNA --}}
                                    <td class="text-center">
                                        <strong 
                                            @if($obat->stok <= 0) 
                                                class="text-danger"
                                            @elseif($obat->stok <= 10)
                                                class="text-warning"
                                            @else
                                                class="text-success"
                                            @endif
                                        >
                                            {{ $obat->stok }}
                                        </strong>
                                    </td>
                                    
                                    {{-- ✅ BADGE STATUS STOK --}}
                                    <td class="text-center">
                                        @if($obat->stok <= 0)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> HABIS
                                            </span>
                                        @elseif($obat->stok <= 10)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle"></i> MENIPIS
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> TERSEDIA
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <a href="{{ route('obat.edit', $obat->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Edit Obat">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('obat.destroy', $obat->id) }}" 
                                              method="POST" 
                                              style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Hapus Obat"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus obat {{ $obat->nama_obat }}?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            
                            {{-- Jika data kosong, tampilkan pesan --}}
                            @if ($obats->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada Data Obat yang tersedia.</td>
                                </tr>
                            @endif
                            {{-- END: PERULANGAN DATA OBAT --}}     
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Auto-hide alert after 3 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
</x-layouts.app>