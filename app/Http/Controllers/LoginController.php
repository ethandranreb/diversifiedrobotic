<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class LoginController extends Controller
{
    function login_view()
    {
        return view('login/base');
    }

    function login_request(Request $request)
    {
        $this->validate($request, ['email' => 'required', 'passWord' => 'required']);

        $result = ['status' => 'error', 'values' => []];

        if (Auth::guard('dashboardguard')->attempt(['email' => $request->email, 'password' => $request->passWord])):
            DB::table('users')->where('email', '=', $request->email)->limit(1)->update(['status' => 1]);
            $result['status'] = 'success';
            // $admin_info = DB::table('admins_meta')->select('admin_id', 'meta')->where('username', '=', $request->userName)->first();

            // if (! empty($admin_info)):
            //     $meta = $admin_info->meta;

            //     HelperFunctions::remove_admin_temp_files($admin_info->admin_id);

            //     if (empty($meta)):
            //         $meta = EncryptDecrypt::encrypt($request->passWord);

            //         DB::table('admins_meta')->where('username', '=', $request->userName)->update(['meta' => $meta]);
            //     endif;

            //     redirect()->intended('admin-dashboard');
            // else:
            //     $result['values'][] = 'login-input-1';
            //     $result['values'][] = 'The username and/or password is incorrect';
            //     $result['values'][] = 'login-input-2';
            //     $result['values'][] = 'The username and/or password is incorrect';

            //     echo json_encode($result);
            // endif;
        else:
            $result['values'][] = 'email';
            $result['values'][] = 'The username and/or password is incorrect';
            $result['values'][] = 'password';
            $result['values'][] = 'The username and/or password is incorrect';

            header('Content-Type: application/json;charset=utf-8');

            echo json_encode($result);
        endif;
    }
}
