<?php
namespace App\Http\Controllers\Auth;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        $config = [];
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
        }

        return view('auth.login', compact('config'));
    }

    protected function validateLogin(Request $request)
    {
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
            if($config['recaptcha'] == 1) {
                $this->validate($request, [
                    $this->username() => 'required',
                    'password' => 'required',
                    'g-recaptcha-response' => 'required|recaptcha',
                ]);
            }
        } else {
            $this->validate($request, [
                $this->username() => 'required', 'password' => 'required',
            ]);
        }

    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $repassword = 0;
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
            if($config['repassword'] != 0) {
                $repassword = $config['repassword'];
            }
        }

        if(Session::has('repassword')) {
            $data = Session::get('repassword');
            $email = $request->input('email');
            if($email == $data['email']){
                $data['times'] += 1;
                Session::put('repassword', $data);
                if ($data['times'] >= $repassword) {
                    $email_db = \App\User::where('email', $email)->get();
                    if(count($email_db) > 0) {
                        $email_db->forefill(['status' => false])->save();
                    }
                    Session::forget('repassword');

                    return redirect()->back()
                        ->withInput($request->only($this->username(), 'remember'))
                        ->withErrors([
                            $this->username() => Lang::get('auth.blocked'),
                        ]);
                }
            }
        } else {
            Session::put('repassword', array(
                'email' => $request->input('email'),
                'times' => '1',
            ));
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->status != 1 ) {

            // Log the user out.
            $this->logout($request);

            // Return them to the log in form.
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    // This is where we are providing the error message.
                    $this->username() => Lang::get('auth.blocked'),
                ]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/login');
    }
}