<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Auth;
use App;
use DB;
use App\Models\Student;
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
        $this->middleware('not_student');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required',
        ];
        
        // Guest users can only register student users.
        // Authenticated users can only register viewer users.
        if (Auth::guest()) {
            $rules['role'] .= '|in:student';
            $rules['identification_number'] = 'required|regex:/^\\d{8}$/|unique:students';
            $rules['gender'] = 'required|in:male,female';
            $countryCodes = App::make('App\Services\CountryService')->getCountryCodes();
            $rules['nationality'] = 'required|in:' . implode(',', $countryCodes);
        } else {
            $rules['role'] .= '|in:viewer';
        }
        
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
    
        $user = null;
        
        DB::transaction(function () use (&$user, $data) {
        
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
        ]);
        
        // If a student user is created, the related student model is created.
        // @todo add student data validation.
        // @todo move this logic to an event.
        if ($user->role === 'student') {
            $student = new Student;
            $student->identification_number = $data['identification_number'];
            $student->gender = $data['gender'];
            $student->nationality = $data['nationality'];
            $student->user()->associate($user);
            $student->save();
        }
        
        });
        
        return $user;
    }
    
    /**
     * Handle a registration request for the application.
     *
     * This method overrides the default from trait
     * Illuminate\Foundation\Auth\RegistersUsers
     * in order to restrict auto-login only after student users registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($user->role === 'student') {
            $this->guard()->login($user);
        }

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
