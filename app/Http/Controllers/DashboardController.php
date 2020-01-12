<?php

namespace App\Http\Controllers;

use App\SupportClasses\EncryptDecrypt;
use App\SupportClasses\HelperFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Hash;

/**
 * This is the main class for the member once logged in
 * All backend process are here once a member has created an account and logged in
 */
class DashboardController extends Controller
{
    private $max_items_per_page = 25;

    function __construct()
    {
        $this->middleware('auth:dashboardguard');
    }

    function newsfeeds_view()
    {
        return view('dashboard.newsfeed');
    }

    function newsfeeds_get(Request $request)
    {
        $user_id     = Auth::user()->id;
        $friend_ids  = DB::table('friends')->select('friend_id')->where('user_id', '=', $user_id)->get();
        $friend_list = [$user_id];

        if ($friend_ids->isNotEmpty()):
            foreach ($friend_ids as $friend_id):
                $friend_list[] = $friend_id->friend_id;
            endforeach;
        endif;

        $list    = DB::table('posts_meta')->join('posts', 'posts.id', '=', 'posts_meta.post_id')->join('users', 'users.id', '=', 'posts_meta.created_by_id')->select('posts.id', 'posts.post_title', 'posts.post_body', 'posts_meta.created_by_id', 'posts_meta.created_on', 'posts_meta.display_for_id', 'posts_meta.shared_on', 'users.first_name', 'users.last_name', 'users.status')->whereIn('posts_meta.display_for_id', $friend_list)->whereNull('posts_meta.shared_on')->orderBy('posts_meta.created_on', 'desc')->paginate($this->max_items_per_page, ['*'], 'page', (int) $request->page);
        $result  = ['postList' => [], 'page' => []];

        if ($list->isNotEmpty()):
            foreach ($list as $item):
                $created_on          = explode(' ', $item->created_on);
                $has_like            = DB::table('likes')->where([['post_id', '=', $item->id], ['like_by_id', '=', $user_id]])->count('id');
                $has_comment         = DB::table('comments')->where('post_id', '=', $item->id)->count('id');
                $status              = strtolower(HelperFunctions::get_status($item->status, 'string'));
                $temp1               = new \stdClass;
                $temp1->postDate     = HelperFunctions::get_month_name($created_on[0], true, true, true);
                $temp1->postId       = $item->id;
                $temp1->postStatus   = $status;
                $temp1->postBy       = "{$item->first_name} {$item->last_name}";
                $temp1->postTitle    = $item->post_title;
                $temp1->postBody     = $item->post_body;
                $temp1->postEdit     = ($item->created_by_id == $user_id) ? 1 : 0;
                $temp1->postComments = [];
                $temp1->postLikes    = DB::table('likes')->where('post_id', '=', $item->id)->count('id');
                $temp1->postCanLike  = $has_like ? 0 : 1;
                $temp1->postCanComment = ($has_comment >= 2) ? 0 : 1;

                $comments = DB::table('comments')->join('users', 'users.id', '=', 'comments.comment_by_id')->where('comments.post_id', '=', $item->id)->orderBy('comments.comment_on', 'desc')->get();

                if ($comments->isNotEmpty()):
                    foreach ($comments as $comment):
                        $comment_on             = explode(' ', $comment->comment_on);
                        $temp2                  = new \stdClass;
                        $temp2->postCommentText = $comment->comment;
                        $temp2->postCommentDate = HelperFunctions::get_month_name($comment_on[0], true, true, true);
                        $temp2->postCommentBy   = "{$comment->first_name} {$comment->last_name}";
                        $temp1->postComments[]  = $temp2;
                    endforeach;
                endif;

                $result['postList'][] = $temp1;
            endforeach;
        endif;

        $result['page'] = HelperFunctions::page_controller($list);

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function newsfeeds_get_my(Request $request)
    {
        $user_id = Auth::user()->id;
        $list    = DB::table('posts_meta')->join('posts', 'posts.id', '=', 'posts_meta.post_id')->join('users', 'users.id', '=', 'posts_meta.created_by_id')->select('posts.id', 'posts.post_title', 'posts.post_body', 'posts_meta.created_by_id', 'posts_meta.created_on', 'posts_meta.display_for_id', 'posts_meta.shared_on', 'users.first_name', 'users.last_name')->where('posts_meta.display_for_id', '=', $user_id)->orderBy('posts_meta.created_on', 'desc')->paginate($this->max_items_per_page, ['*'], 'page', (int) $request->page);
        $result  = ['postList' => [], 'page' => []];

        if ($list->isNotEmpty()):
            foreach ($list as $item):
                $created_on          = explode(' ', $item->created_on);
                $temp1               = new \stdClass;
                $temp1->postDate     = HelperFunctions::get_month_name($created_on[0], true, true, true);
                $temp1->postId       = $item->id;
                $temp1->postBy       = "{$item->first_name} {$item->last_name}";
                $temp1->postTitle    = $item->post_title;
                $temp1->postBody     = $item->post_body;
                $temp1->postLikes    = DB::table('likes')->where('post_id', '=', $item->id)->count('id');
                $temp1->postComments = [];
                $comments = DB::table('comments')->join('users', 'users.id', '=', 'comments.comment_by_id')->where('comments.post_id', '=', $item->id)->orderBy('comments.comment_on', 'desc')->get();

                if ($comments->isNotEmpty()):
                    foreach ($comments as $comment):
                        $comment_on             = explode(' ', $comment->comment_on);
                        $temp2                  = new \stdClass;
                        $temp2->postCommentDate = HelperFunctions::get_month_name($comment_on[0], true, true, true);
                        $temp2->postCommentBy   = "{$comment->first_name} {$comment->last_name}";
                        $temp1->postComments[]  = $temp2;
                    endforeach;
                endif;

                $result['postList'][] = $temp1;
            endforeach;
        endif;

        $result['page'] = HelperFunctions::page_controller($list);

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function post_new_save(Request $request)
    {
        $current_datetime = HelperFunctions::date_time('NOW');
        $user_id          = Auth::user()->id;
        $post_id          = DB::table('posts')->insertGetId([
            'post_title' => $request->postTitle,
            'post_body'  => $request->postBody
        ]);

        DB::table('posts_meta')->insert([
            'post_id'        => $post_id,
            'created_by_id'  => $user_id,
            'created_on'     => $current_datetime,
            'display_for_id' => $user_id
        ]);

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode(['status' => 'success']);
    }

    function post_share(Request $request)
    {
        $result = ['status' => 'error'];

        if (isset($request->id)):
            $id        = (int) $request->id;
            $user_id   = Auth::user()->id;
            $post_info = DB::table('posts_meta')->where([['post_id', '=', $id], ['created_by_id', '=', $user_id], ['display_for_id', '=', $user_id]])->first();

            if (! empty($post_info)):
                $friend_ids = DB::table('friends')->select('friend_id')->where('user_id', '=', $user_id)->get();

                if ($friend_ids->isNotEmpty()):
                    $current_datetime = HelperFunctions::date_time('NOW');

                    foreach ($friend_ids as $friend_id):
                        if (DB::table('posts_meta')->where([['post_id', '=', $id], ['created_by_id', '=', $post_info->created_by_id], ['created_on', '=', $post_info->created_on], ['display_for_id', '=', $friend_id->friend_id]])->count('id')):
                        else:
                            DB::table('posts_meta')->insert([
                                'post_id'        => $id,
                                'created_by_id'  => $post_info->created_by_id,
                                'created_on'     => $post_info->created_on,
                                'display_for_id' => $friend_id->friend_id,
                                'shared_on'      => $current_datetime
                            ]);
                        endif;
                    endforeach;

                    $result['status'] = 'success';
                endif;
            endif;
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function post_like(Request $request)
    {
        $result = ['status' => 'error', 'likes' => 0];

        if (isset($request->id)):
            $post_id = (int) $request->id;

            DB::table('likes')->insert([
                'post_id'    => $post_id,
                'like_by_id' => Auth::user()->id,
                'like_on'    => HelperFunctions::date_time('NOW')
            ]);

            $result['status'] = 'success';
            $result['likes']  = DB::table('likes')->where('post_id', '=', $post_id)->count('id');
            $result['postId'] = $post_id;
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function post_edit(Request $request)
    {
        $result = ['status' => 'error', 'values' => []];

        if (isset($request->id) && ! empty($request->id)):
            $post_id   = (int) $request->id;
            $post_info = DB::table('posts')->where('id', '=', $post_id)->first();

            if (! empty($post_info)):
                $temp1               = new \stdClass;
                $temp1->postTitle    = $post_info->post_title;
                $temp1->postBody     = $post_info->post_body;
                $result['status']    = 'success';
                $result['values']    = $temp1;
            endif;
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function post_save_comment(Request $request)
    {
        $result = ['status' => 'error'];

        if (isset($request->id) && ! empty($request->id)):
            $post_id = (int) $request->id;

            if (DB::table('posts')->where('id', '=', $post_id)->count('id')):
                DB::table('comments')->insert([
                    'post_id'       => $post_id,
                    'comment_by_id' => Auth::user()->id,
                    'comment_on'    => HelperFunctions::date_time('NOW'),
                    'comment'       => $request->commentBody
                ]);

                $result['status'] = 'success';
            endif;
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);

    }

    function profile_edit_view()
    {
        return view('dashboard.profile-edit');
    }

    function profile_status_view()
    {
        return view('dashboard.profile-status');
    }

    function profile_status_update(Request $request)
    {
        $result     = ['status' => 'error', 'values' => []];
        $new_status = (int) $request->status;

        if ($new_status != Auth::user()->status && in_array($new_status, [0, 1, 2])):
            DB::table('users')->where('id', '=', Auth::user()->id)->update(['status' => $new_status]);

            $result['status'] = 'success';
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function profile_save(Request $request)
    {
        $first_name  = ucwords(strtolower($request->firstName));
        $middle_name = ucwords(strtolower($request->middleName));
        $last_name   = ucwords(strtolower($request->lastName));
        $result      = ['status' => 'no-change', 'values' => []];
        $id          = Auth::user()->id;

        // CHECK IF NAME EXISTS
        if (DB::table('users')->where([['first_name', '=', $first_name], ['middle_name', '=', $middle_name], ['last_name', '=', $last_name], ['id', '!=', $id]])->count('id')):
            $result['values'][] = 'first-name';
            $result['values'][] = 'The name is already registered';
            $result['values'][] = 'middle-name';
            $result['values'][] = 'The name is already registered';
            $result['values'][] = 'last-name';
            $result['values'][] = 'The name is already registered';
            $result['status']   = 'error';
        endif;

        // CHECK IF EMAIL EXISTS
        if (DB::table('users')->where([['email', '=', $request->email], ['id', '!=', $id]])->count('id')):
            $result['values'][] = 'email';
            $result['values'][] = 'The email address is already registered';
            $result['status']   = 'error';
        endif;

        if ($result['status'] != 'error'):
            if (! empty($request->passWord)):
                DB::table('users')->where('id', '=', $id)->update([
                    'password'      => Hash::make($request->passWord),
                    'password_meta' => EncryptDecrypt::encrypt($request->passWord)
                ]);

                $result['status'] = 'success';
            endif;

            if (($first_name != Auth::user()->first_name) || ($middle_name != Auth::user()->middle_name) || ($last_name != Auth::user()->last_name) || ($request->email != Auth::user()->email)):
                DB::table('users')->where('id', '=', $id)->update([
                    'first_name'  => $first_name,
                    'middle_name' => $middle_name,
                    'last_name'   => $last_name,
                    'email'       => $request->email
                ]);

                $result['status'] = 'success';
            endif;
        endif;

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function profile_my_newsfeeds()
    {
        return view('dashboard.profile-my-newsfeed');
    }

    function user_info(Request $request)
    {
        $result                             = ['status' => 'error', 'values' => []];
        $result['values']['first_name']     = Auth::user()->first_name;
        $result['values']['middle_name']    = Auth::user()->middle_name;
        $result['values']['last_name']      = Auth::user()->last_name;
        $result['values']['email']          = Auth::user()->email;
        $result['values']['status']         = Auth::user()->status;
        $result['values']['status_verbose'] = HelperFunctions::get_status(Auth::user()->status, 'string');
        $result['status']                   = 'success';

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode($result);
    }

    function logout_request(Request $request)
    {
        DB::table('users')->where('id', '=', Auth::user()->id)->update(['status' => 0]);

        Auth::guard('dashboardguard')->logout();

        $request->session()->flush();

        header('Content-Type: application/json;charset=utf-8');

        echo json_encode(['status' => 'success']);
    }
}
