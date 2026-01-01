<x-layouts.app title="Edit Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">

                <h1 class="mb-4">Edit Obat</h1>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('obat.update', $obat->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_obat" class="form-label">Nama Obat
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama_obat" id="nama_obat"
                                            class="form-control @error('nama_obat') is-invalid @enderror"
                                            value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                                        @error('nama_obat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kemasan" class="form-label">Kemasan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="kemasan" id="kemasan"
                                            class="form-control @error('kemasan') is-invalid @enderror"
                                            value="{{ old('kemasan', $obat->kemasan) }}"
                                            placeholder="Contoh: Strip, Botol, Tube"
                                            required>
                                        @error('kemasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="harga" class="form-label">Harga
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" id="harga"
                                        class="form-control @error('harga') is-invalid @enderror"
                                        value="{{ old('harga', $obat->harga) }}" required min="0" step="1">
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ✅ MANAJEMEN STOK DENGAN MODE -->
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Manajemen Stok</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Mode Update Stok</label>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="stok_mode" id="mode_set" value="set" checked>
                                                <label class="btn btn-outline-primary" for="mode_set">
                                                    <i class="fas fa-edit"></i> Set Stok
                                                </label>

                                                <input type="radio" class="btn-check" name="stok_mode" id="mode_add" value="add">
                                                <label class="btn btn-outline-success" for="mode_add">
                                                    <i class="fas fa-plus"></i> Tambah Stok
                                                </label>

                                                <input type="radio" class="btn-check" name="stok_mode" id="mode_reduce" value="reduce">
                                                <label class="btn btn-outline-warning" for="mode_reduce">
                                                    <i class="fas fa-minus"></i> Kurangi Stok
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="stok_value" class="form-label">Jumlah
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="stok_value" id="stok_value"
                                                class="form-control @error('stok_value') is-invalid @enderror"
                                                value="{{ old('stok_value', 0) }}" required min="0" step="1">
                                            @error('stok_value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="alert alert-info mb-0">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Stok Saat Ini:</strong><br>
                                                <h3 class="mb-0">{{ $obat->stok }} unit</h3>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Perubahan:</strong><br>
                                                <h3 class="mb-0" id="stok_change">-</h3>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Stok Baru:</strong><br>
                                                <h3 class="mb-0" id="stok_preview">{{ $obat->stok }} unit</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ✅ INFO STOK SAAT INI -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Stok Saat Ini:</strong> 
                                {{ $obat->stok }} unit
                                
                                @if($obat->stok <= 0)
                                    <span class="badge bg-danger ms-2">HABIS</span>
                                @elseif($obat->stok <= 10)
                                    <span class="badge bg-warning text-dark ms-2">MENIPIS</span>
                                @else
                                    <span class="badge bg-success ms-2">TERSEDIA</span>
                                @endif
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('obat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>