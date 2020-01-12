<?php

namespace App\SupportClasses;

use Illuminate\Support\Facades\DB;

class HelperFunctions
{
    static function date_time($time, $type = 'current', $date_only = true, $datetime = null)
    {
        if (is_null($datetime)) :
            $datetime = \DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
            $datetime->setTimezone(new \DateTimeZone(\Config::get('customconstants.options.time_zone')));
        endif;

        $datetime->modify($time);

        if ($type === 'start') :
            $string     = explode(' ', $datetime->format(\Config::get('customconstants.options.time_format')));
            $string[1]  = '00:00:00.000';
            $string     = implode(' ', $string);
        elseif ($type === 'end') :
            $string     = explode(' ', $datetime->format(\Config::get('customconstants.options.time_format')));
            $string[1]  = '23:59:59.999';
            $string     = implode(' ', $string);
        elseif ($type === 'current') :
            $string = $datetime->format(\Config::get('customconstants.options.time_format'));
        endif;

        if ($date_only): return $string;
        else:            return [$datetime, $string];
        endif;
    }

    static function get_status($value, $type = 'numeric')
    {
        $to_string  = ['Offline', 'Active', 'Away'];
        $to_numeric = ['Offline' => 0, 'Active' => 1, 'Away' => 2];

        if ($type == 'numeric'):
            if (isset($to_numeric[$value])): return $to_numeric[$value];
            else:                            return '';
            endif;
        else:
            if (isset($to_string[$value])): return $to_string[$value];
            else:                           return '';
            endif;
        endif;
    }

    static function add_friend($user_id, $new_friend_id, $friend_on)
    {
        DB::table('friends')->insert([
            'user_id'   => $user_id,
            'friend_id' => $new_friend_id,
            'friend_on' => $friend_on
        ]);
    }

    static function add_friend_to_all($new_friend_id, $friend_on)
    {
        $offset   = 0;
        $limit    = 25;
        $user_ids = DB::table('users')->select('id')->where('id', '!=', $new_friend_id)->orderBy('id', 'asc')->offset($offset)->limit($limit)->get();

        while ($user_ids->isNotEmpty()):
            foreach ($user_ids as $user_id):
                self::add_friend($user_id->id, $new_friend_id, $friend_on);
                self::add_friend($new_friend_id, $user_id->id, $friend_on);
            endforeach;

            $offset  += $limit;
            $user_ids = DB::table('users')->select('id')->orderBy('id', 'asc')->offset($offset)->limit($limit)->get();
        endwhile;
    }

    static function page_controller($page_class)
    {
        $result                  = [];
        $result['current_page']  = $page_class->currentPage();
        $result['last_page']     = $page_class->lastPage();
        $result['previous_page'] = $result['current_page'] - 1;
        $result['next_page']     = $result['current_page'] + 1;
        $result['previous_page'] = $result['previous_page'] == 0 ? $result['last_page'] : $result['previous_page'];
        $result['next_page']     = $result['next_page'] > $result['last_page'] ? 1 : $result['next_page'];

        return $result;
    }

    static function get_month_name($date, $with_day = false, $with_year = false, $full_name = false)
    {
        $date = explode(' ', $date);
        $date = explode('-', $date[0]);
        $result = '';

        if ($full_name): $result = date('F', mktime(0, 0, 0, (int) $date[1], 10));
        else: $result = date('M', mktime(0, 0, 0, (int) $date[1], 10));
        endif;

        if ($with_day): $result .= " {$date[2]},";
        endif;

        if ($with_year): $result .= " {$date[0]}";
        endif;

        return $result;
    }
}