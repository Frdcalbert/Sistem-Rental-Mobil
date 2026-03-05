<?php
// Memicu Laravel untuk menjalankan migrasi lewat kode
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

// Ini untuk memproses request agar facade Laravel aktif
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

try {
    echo "Sedang memproses migrasi...<br>";
    // Menjalankan php artisan migrate --force
    Artisan::call('migrate', ['--force' => true]);
    echo "Selesai! Output:<br><pre>" . Artisan::output() . "</pre>";
    echo "<br><a href='/'>Klik di sini untuk kembali ke Beranda</a>";
} catch (\Exception $e) {
    echo "Gagal: " . $e->getMessage();
}
