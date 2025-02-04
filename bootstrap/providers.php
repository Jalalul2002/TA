<?php

use Maatwebsite\Excel\ExcelServiceProvider;
use Maatwebsite\Excel\Facades\Excel;

return [
    App\Providers\AppServiceProvider::class,
];

return [
    // Provider lainnya
    ExcelServiceProvider::class,
];

// Untuk alias, tambahkan ke file bootstrap/aliases.php (jika ada)
return [
    'Excel' => Excel::class,
];