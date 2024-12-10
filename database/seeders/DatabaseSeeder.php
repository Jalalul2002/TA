<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Master Admin',
            'email' => 'master@admin.id',
            'usertype' => 'admin',
            'password' => Hash::make('master12345')
        ]);

        User::create([
            'name' => 'Teknik Informatika',
            'email' => 'if@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Teknik Informatika',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Teknik Elektro',
            'email' => 'te@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Teknik Elektro',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Kimia',
            'email' => 'kim@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Kimia',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Biologi',
            'email' => 'bio@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Biologi',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Fisika',
            'email' => 'fis@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Fisika',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Agroteknologi',
            'email' => 'agro@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Agroteknologi',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Matematika',
            'email' => 'mat@staff.id',
            'usertype' => 'staff',
            'prodi' => 'Matematika',
            'password' => Hash::make('user12345')
        ]);

        // Product::factory(20)->create();

        $filePath = database_path('seeders/Dataset.csv');

        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ';');

            while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
                // Gabungkan header dan data baris
                $rowData = array_combine($header, $row);
                // $rowData = array_map(function($value) {
                //     return $value === '' ? null : $value;
                // }, $rowData);
                FacadesDB::table('assetlabs')->insert($rowData);
            }
            fclose($handle);
        }
    }
}
