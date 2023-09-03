<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');//->except('logout');
    }


    public function perform()
    {
//        dd(request());
        $validator = \Validator::make(request()->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:2'],
        ]);

//        dd($validator);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 401);
        }

        if (!Auth::attempt(request()->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Email or Password don\'t match our record'
            ], 401);
        }

        $user = User::where('email', request()->email)->first();
//        dd($user);
        return response()->json([
            'message' => 'You are logged in to ' . $user->email,
            'token' => $user->createToken('Login Token')->plainTextToken,
            'user' => new UserResource($user)
        ]);


    }
}
