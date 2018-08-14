<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

  // $tech_division = [1]; // divisions_id list
  // $create_request = [1,2,3,4,99]; // user_roles_id list
  // $update_request = [1,2,3,5,99]; // user_roles_id list
  // $view_report = [1,2,3,99]; // user_roles_id list

  //
  function verify_all_privilege($user){
    $user = (object) $user;
    $return_val = array();
    $return_val['can_create_request'] = can_create_request($user->user_role);
    $return_val['can_view_report'] = can_view_report($user->user_role);
    $return_val['can_update_request'] = can_update_request($user->user_role);
    $return_val['can_access_admin_panel'] = can_access_admin_panel($user->user_role);
    $return_val['is_cs_team'] = is_cs_team($user->user_role);
    $return_val['is_payment_team'] = is_payment_team($user->user_role);
    $return_val['is_administrator'] = is_administrator($user->user_role);
    $return_val['is_tech_team'] = is_tech_team($user->division);
    $return_val['is_supervisor'] = is_supervisor($user->user_role);
    $return_val['is_senior'] = is_senior($user->user_role);
    $return_val['can_monitor_user'] = can_monitor_user($user->user_role);
    return $return_val;
  }

  function is_tech_team($divisions_id){
    $tech_division = [1]; // divisions_id list
    $return_val = FALSE;
    if(in_array($divisions_id, $tech_division)) {
        $return_val = TRUE;
    }
    return $return_val;
  }

  function is_administrator($user_roles_id){
    $administrator_team = [1]; // user_roles_id list
    $return_val = FALSE;
    if(in_array($user_roles_id, $administrator_team)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function is_payment_team($user_roles_id){
    $payment_team = [2,3,5,7,99]; // user_roles_id list
    $return_val = FALSE;
    if(in_array($user_roles_id, $payment_team)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function is_cs_team($user_roles_id){
    $cs_team = [4,6]; // user_roles_id list
    $return_val = FALSE;
    if(in_array($user_roles_id, $cs_team)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function can_create_request($user_roles_id)
  {
    $create_request = [1,2,3,4,6,99]; // user_roles_id list
    $return_val = FALSE;
    if(in_array($user_roles_id, $create_request)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function can_update_request($user_roles_id)
  {
    $update_request = [1,2,3,5,7,99]; // user_roles_id list
    $return_val = FALSE;
    if(in_array($user_roles_id, $update_request)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function can_view_report($user_roles_id)
  {
    $view_report = [1,2,3,99]; // user_roles_id list
    $return_val = FALSE;
    if(in_array($user_roles_id, $view_report)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function can_access_admin_panel($user_role_id){
    $access_admin_panel = [1,2,3,99]; // user_role_list
    $return_val = FALSE;
    if(in_array($user_role_id, $access_admin_panel)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  function is_supervisor($user_role_id){
    $supervisor = [1,2,3]; // user_role_list
    $return_val = FALSE;
    if(in_array($user_role_id, $supervisor)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  // senior
  function is_senior($user_role_id){
    $senior = [1,2,3,6,7]; // user_role_list
    $return_val = FALSE;
    if(in_array($user_role_id, $senior)){
      $return_val = TRUE;
    }
    return $return_val;
  }

  // for monitor user
  function can_monitor_user($user_role_id){
    $monitor_user = [1,2,3,6,7]; // user_role_id list
    $return_val = FALSE;
    if(in_array($user_role_id, $monitor_user)){
      $return_val = TRUE;
    }
    return $return_val;
  }

?>
