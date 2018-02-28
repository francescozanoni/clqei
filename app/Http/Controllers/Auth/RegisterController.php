<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\UserService;
use App\User;
use Auth;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * @var UserService
     */
    protected $userService;

    /**
     * Create a new controller instance.
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('not_student');

        $this->userService = $userService;
    }

    /**
     * Handle a registration request for the application.
     *
     * This method overrides the default from trait
     * Illuminate\Foundation\Auth\RegistersUsers
     * in order to restrict auto-login only after student users registration.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($user->role === User::ROLE_STUDENT) {
            $this->guard()->login($user);
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if (Auth::guest()) {
            $rules = $this->userService->getGuestValidationRules();
        } else {
            if (Auth::user()->can('createAdministrator', User::class)) {
                $rules = $this->userService->getAdministratorValidationRules();
            } else {
                $rules = $this->userService->getViewerValidationRules();
            }
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
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
            if ($user->role === User::ROLE_STUDENT) {
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

    // @todo add password change
}
