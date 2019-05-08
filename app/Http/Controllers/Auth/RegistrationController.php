<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    /**
     * Handles registration via API
     *
     * @param  App\Http\Requests\RegistrationRequest $request
     * @return bool
     */
    public function __invoke(RegistrationRequest $request) {
        $registration = $request->validated();

        $user = DB::transaction(function () use ($registration) {
            return User::create([
                'name' => $registration['name'],
                'surname' => $registration['surname'],
                'email' => $registration['email'],
                'password' => Hash::make($registration['password']),
            ]);
        });

        event(new Registered($user));

        return true;
    }
}
