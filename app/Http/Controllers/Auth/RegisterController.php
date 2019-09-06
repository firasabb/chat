<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Storage;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'username' => ['required', 'string', 'max:20', 'unique:users']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $avatar_url = Storage::url('no-avatar.png');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'avatar_url' => $avatar_url,
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole('user');
        return $user;
    }

    public function checkusername(Request $request){
        

        $validator = Validator::make($request->all(), [
            'username' => 'string'
        ]);

        if($validator->fails()){
            $response = array(
                'status' => 'error',
                'error' => 'Please provide a correct username',
            );
            return response()->json($response);
        }

        $username = $request->username;

        if($username){

            $user = User::where('username', $username)->first();

            if($user){
                $response = array(
                    'status' => 'error',
                    'error' => 'Please provide another username',
                );
                return response()->json($response);
            } else {
                $response = array(
                    'status' => 'success'
                );
                return response()->json($response);
            }
        }
    }
}
