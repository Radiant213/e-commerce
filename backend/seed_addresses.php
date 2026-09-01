<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$budi = User::where('email', 'budi@example.com')->first();
if ($budi) {
    $budi->addresses()->delete();
    $budi->addresses()->create([
        'label' => 'Rumah Utama',
        'recipient_name' => 'Budi Santoso',
        'phone' => '082198765432',
        'address_line' => 'Jl. Melati Blok C2 No. 12, Kel. Dago, Kec. Coblong',
        'city' => 'Kota Bandung',
        'postal_code' => '40135',
        'is_primary' => true,
        'notes' => 'Pagar hitam, samping pos satpam',
    ]);
    $budi->addresses()->create([
        'label' => 'Kantor (Radiant Tech)',
        'recipient_name' => 'Budi Santoso (Dept. Engineering)',
        'phone' => '082198765432',
        'address_line' => 'Menara Sudirman Lantai 18, Jl. Jend. Sudirman Kav. 60',
        'city' => 'Jakarta Selatan',
        'postal_code' => '12190',
        'is_primary' => false,
        'notes' => 'Titipkan di lobby resepsionis lantai dasar',
    ]);
    echo "Seeded addresses for Budi successfully.\n";
}
