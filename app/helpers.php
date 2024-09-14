<?php

use App\Models\Land;
use App\Models\Member;

function active_class($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_active_route($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

if (!function_exists('get_land_name')) {
    function get_land_name($land_id, $column)
    {
        $land_columns = Land::where('id', $land_id)->first();
        return ($land_columns->$column ?? '');
    }
}

if (!function_exists('get_member_name')) {
    function get_member_name($member_id, $column)
    {
        $member_columns = Member::where('id', $member_id)->first();
        return ($member_columns->$column ?? '');
    }
}
if (!function_exists('get_member_phone')) {
    function get_member_phone($member_id, $column)
    {
        $member_columns = Member::where('id', $member_id)->first();
        return ($member_columns->$column ?? '');
    }
}
