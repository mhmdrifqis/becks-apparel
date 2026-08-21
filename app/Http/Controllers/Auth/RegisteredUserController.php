<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use App\Helpers\PhoneHelper;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+?62|0)8[1-9][0-9]{7,11}$/', 'unique:'.User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Nomor WhatsApp/Telepon harus nomor Indonesia yang valid (contoh: 081234567890).',
            'phone.unique' => 'Nomor WhatsApp/Telepon sudah terdaftar.',
        ]);

        $phone = PhoneHelper::normalize($request->phone);
        $email = $request->filled('email') ? $request->email : $phone . '@becksapparel.com';

        $user = User::create([
            'name' => $request->name,
            'phone' => $phone,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        // Otomatis kasih Role Pelanggan
        $user->assignRole('Pelanggan');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
