<?php

namespace App\Http\Controllers\Frontend;

use App\Account;
use DB;
use Exception;
use Illuminate\Http\Request;

class AuthController
{


    public $auth_server;
    public $auth_client_id;
    public $auth_redirect_uri;
    public $redirectPath = 'home';


    /**
     * FrontendAuthController constructor.
     */
    public function __construct()
    {
        $this->auth_server = env('AUTH_SERVER');
        $this->auth_client_id = env('AUTH_CLIENT_ID');
        $this->auth_redirect_uri = url('callback');
    }

    protected function auth($request)
    {
        $access_token = $request->input('access_token');

        if (!$access_token) {
            return  [
                'errors' => 'Invalid access token'
            ];
        }

        $get_user_info_url = $this->auth_server .  '/oauth/user/info/get' . '?access_token=' . $access_token;

        try {
            $page = file_get_contents($get_user_info_url);
            $content = json_decode($page);

            if (isset($content->error)) {
                return  [
                    'errors' =>  $content->error
                ];
            } else {
                return [
                    'access_token' => $access_token,
                    'uid' => isset($content->uid) ? $content->uid : -1,
                    'nickname' => isset($content->nickname) ? $content->nickname : '',
                    'open_id' => isset($content->open_id) ? $content->open_id : -1,
                    'username' => isset($content->username) ? $content->username : '',
                    'platform' => isset($content->platform) ? $content->platform : '-1'
                ];
            }
        } catch (Exception $e) {
            return  [
                'errors' => $e->getMessage()
            ];
        }
    }

    /**
     * Return exist user by uid or create new users
     * @param $gUser
     * @return mixed
     */
    protected function findOrCreateAccount($gUser)
    {
        $account = null;

        $checkExisted = Account::where('uid', $gUser['uid'])->get();

        if ($checkExisted->count() > 0) {
            $account = $checkExisted->first();
        } else {
            $username = (isset($gUser['nickname']) && $gUser['nickname']) ? $gUser['nickname'] : $gUser['username'];

            DB::beginTransaction();

            try {

                $account = Account::create([
                    'username' => $username,
                    'email' => isset($gUser['email']) ? $gUser['email'] : '',
                    'uid' => $gUser['uid']
                ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();

            }
        }
        return $account;
    }


    /**
     * Handle authenticate by login user base on uid.
     * @param $authenticate
     * @return \Illuminate\Http\RedirectResponse|string
     */
    protected function garenaHandle($authenticate)
    {
        $account = $this->findOrCreateAccount($authenticate);
        if ($account) {
            auth('frontend')->login($account, true);
            session()->put('access_token', $authenticate['access_token']);
            return redirect()->intended($this->redirectPath);
        } else {
            return 'Có lỗi xảy ra trong quá trình đăng nhập xin thử lại sau!';
        }
    }


    /**
     * Redirect to Garena Authenticate Server
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToAuthServer()
    {
        return redirect()->away($this->auth_server . '/oauth/login' . '?response_type=token&client_id=' . $this->auth_client_id . '&redirect_uri=' .urlencode($this->auth_redirect_uri) . '&all_platforms=1');
    }

    public function logout()
    {
        auth('frontend')->logout();
        //$auth_logout_url = env('AUTH_SERVER') . '/oauth/logout' . '?access_token=' . session()->get('access_token'). '&redirect_uri=' . urlencode( url('/'));
		/* use for app 200016 */
        $auth_logout_url = env('AUTH_SERVER') . '/oauth/logout' . '?access_token=' . session()->get('access_token'). '&format=redirect&redirect_uri='.url('/callback?logout=1');
        session()->forget('access_token');

        return redirect()->away($auth_logout_url);
//        return view('frontend.logout', compact('auth_logout_url'));
    }


    /**
     * Handle response from Garena Auth server.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|mixed|string
     */
    public function callback(Request $request)
    {
        if ($request->input('logout') == 1) {
            return redirect('/');
        }

        $authenticate  = $this->auth($request);

        if (isset($authenticate['errors'])) {
            return $authenticate['errors'];
        } else {
            return $this->garenaHandle($authenticate);
        }
    }
}
