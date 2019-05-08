<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handles API login tasks
     *
     * @param  Request $request
     * @return bool
     */
    public function login(LoginRequest $request) {
        $login = $request->validated();

        $client = new Client();
        
        try {
            // Send verification request to Passport login endpoint
            $response = $client->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $login->username,
                    'password' => $login->password
                ]
            ]);
        } catch (BadResponseException $e) {
            switch($e->getCode()) {
                case 400:
                    abort(400, 'Invalid Request: please enter a username and password');
                case 401:
                    abort(401, 'Incorrect username/password');
                default:
                    abort($e->getCode(), 'Oops, something went wrong. Please try again later.');
            }
        }

        return $response->getBody();
    }

    /**
     * Handles logout via API
     *
     * @return Response
     */
    public function logout() {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->revoke();
        });

        return response()->json(true, 200);
    }
}
