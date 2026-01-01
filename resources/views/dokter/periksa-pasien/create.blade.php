<x-layouts.app title="Periksa Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Periksa Pasien</h1>

                {{-- ERROR MESSAGES --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-select">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" 
                                            data-nama="{{ $obat->nama_obat }}"
                                            data-harga="{{ $obat->harga }}"
                                            data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }} 
                                            (Stok: {{ $obat->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Harga</label>
                                <p id="total-harga" class="fw-bold">Rp 0</p>
                            </div>

                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('periksa-pasien.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const selectObat = document.getElementById('select-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalHargaEl = document.getElementById('total-harga');

    let daftarObat = [];

    selectObat.addEventListener('change', () => {
        const selectedOption = selectObat.options[selectObat.selectedIndex];
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga || 0);
        const stok = parseInt(selectedOption.dataset.stok || 0);

        if (!id) return;

        // Cek apakah obat sudah ada di daftar
        if (daftarObat.some(o => o.id == id)) {
            alert('Obat ini sudah ditambahkan!');
            selectObat.selectedIndex = 0;
            return;
        }

        // Cek stok
        if (stok <= 0) {
            alert(`Stok obat ${nama} habis!`);
            selectObat.selectedIndex = 0;
            return;
        }

        daftarObat.push({
            id,
            nama,
            harga,
            stok,
            jumlah: 1
        });
        renderObat();
        selectObat.selectedIndex = 0;
    });

    function renderObat() {
        listObat.innerHTML = '';
        let total = 0;

        daftarObat.forEach((obat, index) => {
            total += obat.harga * obat.jumlah;

            // Tentukan badge stok
            let badgeStok = '';
            let stokSisa = obat.stok;
            if (stokSisa <= 0) {
                badgeStok = '<span class="badge bg-danger ms-2">HABIS</span>';
            } else if (stokSisa <= 10) {
                badgeStok = '<span class="badge bg-warning text-dark ms-2">MENIPIS</span>';
            } else {
                badgeStok = '<span class="badge bg-success ms-2">TERSEDIA</span>';
            }

            const item = document.createElement('li');
            item.className = 'list-group-item';
            item.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${obat.nama}</strong> ${badgeStok}
                        <br>
                        <small class="text-muted">Harga: Rp ${obat.harga.toLocaleString()} | Stok tersedia: ${obat.stok}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusObat(${index})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0" style="min-width: 60px;">Jumlah:</label>
                    <input type="number" 
                        class="form-control form-control-sm" 
                        style="max-width: 100px;"
                        value="${obat.jumlah}" 
                        min="1" 
                        max="${obat.stok}"
                        onchange="ubahJumlah(${index}, this.value)">
                    <span class="text-muted">Subtotal: Rp ${(obat.harga * obat.jumlah).toLocaleString()}</span>
                </div>
            `;
            listObat.appendChild(item);
        });

        inputBiaya.value = total;
        totalHargaEl.textContent = `Rp ${total.toLocaleString()}`;
        inputObatJson.value = JSON.stringify(daftarObat.map(o => ({
            id_obat: parseInt(o.id),
            jumlah: parseInt(o.jumlah)
        })));
    }

    function ubahJumlah(index, jumlahBaru) {
        jumlahBaru = parseInt(jumlahBaru);
        const obat = daftarObat[index];

        if (jumlahBaru < 1) {
            alert('Jumlah minimal adalah 1');
            renderObat();
            return;
        }

        if (jumlahBaru > obat.stok) {
            alert(`Stok ${obat.nama} tidak mencukupi! Stok tersedia: ${obat.stok}`);
            renderObat();
            return;
        }

        daftarObat[index].jumlah = jumlahBaru;
        renderObat();
    }

    function hapusObat(index) {
        if (confirm('Yakin ingin menghapus obat ini?')) {
            daftarObat.splice(index, 1);
            renderObat();
        }
    }
</script>

<style>
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>