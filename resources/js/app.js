import './bootstrap';
import * as bootstrap from 'bootstrap';

// Import SEMUA CSS di sini (Vite akan mengurus bundling)
import 'bootstrap/dist/css/bootstrap.min.css';
import 'admin-lte/dist/css/adminlte.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../css/app.css'; // Tetap impor file CSS yang di atas

// Import JS
import 'admin-lte/dist/js/adminlte.min.js';

// Pastikan inisialisasi AdminLTE berjalan setelah DOM siap
// Anda mungkin perlu jQuery jika template AdminLTE Anda memerlukannya
document.addEventListener('DOMContentLoaded', () => {
    // Tambahkan kode inisialisasi AdminLTE jika ada
    // Contoh:
    // if (typeof jQuery !== 'undefined') {
    //     $('[data-widget="treeview"]').Treeview('init');
    // }
});