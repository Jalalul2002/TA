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
            'username' => 'admin1',
            'name' => 'Master Admin',
            'email' => 'admin@uinsgd.ac.id',
            'usertype' => 'admin',
            'password' => Hash::make('master12345')
        ]);

        User::create([
            'name' => 'Teknik Informatika',
            'email' => 'laboran-if@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Teknik Informatika',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Teknik Elektro',
            'email' => 'laboran-te@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Teknik Elektro',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Kimia',
            'email' => 'laboran-kim@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Kimia',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Biologi',
            'email' => 'laboran-bio@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Biologi',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Fisika',
            'email' => 'laboran-fis@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Fisika',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Agroteknologi',
            'email' => 'laboran-agro@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Agroteknologi',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Matematika',
            'email' => 'laboran-mat@uinsgd.ac.id',
            'usertype' => 'staff',
            'prodi' => 'Matematika',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'name' => 'Saintek Admin',
            'email' => 'saintek@uinsgd.ac.id',
            'usertype' => 'user',
            'password' => Hash::make('Ju@r4')
        ]);

        User::create([
            'username' => '1207050055',
            'name' => 'Jalalul Mu`ti',
            'email' => 'jalalul2000@gmail.com',
            'usertype' => 'mahasiswa',
            'password' => Hash::make('user12345')
        ]);

        User::create([
            'username' => '111222333',
            'name' => 'Edward Elrich',
            'usertype' => 'dosen',
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
