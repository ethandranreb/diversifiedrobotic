<?php

namespace App\Http\Controllers;

use App\SupportClasses\EncryptDecrypt;
use App\SupportClasses\HelperFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    function registration_view()
    {
        return view('registration/base');
    }

    function registration_verify(Request $request)
    {
        $first_name  = ucwords(strtolower($request->firstName));
        $middle_name = ucwords(strtolower($request->middleName));
        $last_name   = ucwords(strtolower($request->lastName));
        $result      = ['status' => 'success', 'values' => []];

        // CHECK IF NAME EXISTS
        if (DB::table('users')->where([['first_name', '=', $first_name], ['middle_name', '=', $middle_name], ['last_name', '=', $last_name]])->count('id')):
            $result['values'][] = 'first-name';
            $result['values'][] = 'The name is already registered';
            $result['values'][] = 'middle-name';
            $result['values'][] = 'The name is already registered';
            $result['values'][] = 'last-name';
            $result['values'][] = 'The name is already registered';
            $result['status']   = 'error';
        endif;

        // CHECK IF EMAIL EXISTS
        if (DB::table('users')->where('email', '=', $request->email)->count('id')):
            $result['values'][] = 'email';
            $result['values'][] = 'The email address is already registered';
            $result['status']   = 'error';
        endif;

        if ($result['status'] == 'success'):
            $new_member_index = \Config::get('customconstants.options.new_member_index');

            $request->session()->forget($new_member_index);
            $request->session()->put($new_member_index, []);
            $request->session()->forget("{$new_member_index}.first_name.0", $first_name);
            $request->session()->forget("{$new_member_index}.middle_name.0", $middle_name);
            $request->session()->forget("{$new_member_index}.last_name.0", $last_name);
            $request->session()->forget("{$new_member_index}.email.0");
            $request->session()->forget("{$new_member_index}.password.0");
            $request->session()->push("{$new_member_index}.first_name", $first_name);
            $request->session()->push("{$new_member_index}.middle_name", $middle_name);
            $request->session()->push("{$new_member_index}.last_name", $last_name);
            $request->session()->push("{$new_member_index}.email", $request->email);
            $request->session()->push("{$new_member_index}.password", $request->passWord);

            \Session::save();

            $result['values']['first_name']  = $first_name;
            $result['values']['middle_name'] = $middle_name;
            $result['values']['last_name']   = $last_name;
            $result['values']['email']       = $request->email;
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function registration_proceed(Request $request)
    {
        $new_member_index = \Config::get('customconstants.options.new_member_index');
        $first_name       = $request->session()->get("{$new_member_index}.first_name.0");
        $middle_name      = $request->session()->get("{$new_member_index}.middle_name.0");
        $last_name        = $request->session()->get("{$new_member_index}.last_name.0");
        $email            = $request->session()->get("{$new_member_index}.email.0");
        $password         = $request->session()->get("{$new_member_index}.password.0");
        $result           = ['status' => 'error'];

        // CHECK IF NAME EXISTS
        if (DB::table('users')->where([['first_name', '=', $first_name], ['middle_name', '=', $middle_name], ['last_name', '=', $last_name]])->count('id')):
            $result['values'][] = 'first-name';
            $result['values'][] = 'The name is already registered';
            $result['values'][] = 'middle-name';
            $result['values'][] = 'The name is already registered';
            $result['values'][] = 'last-name';
            $result['values'][] = 'The name is already registered';
        else: $result['status'] = 'success';
        endif;

        // CHECK IF EMAIL EXISTS
        if (DB::table('users')->where('email', '=', $email)->count('id')):
            $result['values'][] = 'email';
            $result['values'][] = 'The email address is already registered';
            $result['status']   = 'error';
        // else:
        //     if ($result['status'] != 'error'): $result['status'] = 'success';
        //     endif;
        endif;

        if ($result['status'] == 'success'):
            $current_datetime = HelperFunctions::date_time('NOW');

            $id = DB::table('users')->insertGetId([
                'first_name'    => $first_name,
                'middle_name'   => $middle_name,
                'last_name'     => $last_name,
                'email'         => $email,
                'password'      => Hash::make($password),
                'password_meta' => EncryptDecrypt::encrypt($password),
                'registered_on' => $current_datetime
            ]);

            HelperFunctions::add_friend_to_all($id, $current_datetime);
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }
}
