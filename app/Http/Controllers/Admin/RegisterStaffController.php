<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisterStaffController extends Controller
{
    public function create()
    {
        return view('admin.add-staff');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // Mengubah semua input menjadi huruf kecil
            $input = $request->all();

            // Mengubah semua input menjadi huruf kecil kecuali 'name'
            array_walk_recursive($input, function (&$value, $key) {
                if ($key == 'usertype') {
                    $value = strtolower($value);
                }
            });

            // Mengubah 'name' menjadi format capitalize
            if (isset($input['name'])) {
                $input['name'] = ucwords(strtolower($input['name']));
            }

            $request->merge($input);

            // Validasi Input
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'usertype' => ['required', 'string', 'max:6'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Membuat User
            $user = User::create([
                'name' => $request->name,
                'usertype' => $request->usertype,
                'prodi' => $request->prodi,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            return redirect()->route('admin.staff')->with('success', 'Data staff berhasil ditambah.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-staff', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);

            $input = $request->all();

            // Mengubah semua input menjadi huruf kecil kecuali 'name'
            array_walk_recursive($input, function (&$value, $key) {
                if ($key == 'usertype') {
                    $value = strtolower($value);
                }
            });

            // Mengubah 'name' menjadi format capitalize
            if (isset($input['name'])) {
                $input['name'] = ucwords(strtolower($input['name']));
            }

            $request->merge($input);

            // Validasi Input
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $id],
                'usertype' => ['required', 'string', 'max:6'],
                'prodi' => ['nullable', 'string', 'max:255'],
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            ]);

            // Update data staff
            $user->update([
                'name' => $request->name,
                'usertype' => $request->usertype,
                'prodi' => $request->prodi,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            return redirect()->route('admin.staff')->with('success', 'Data staff berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }
}
