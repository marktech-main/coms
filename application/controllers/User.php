<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);
class User extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('users_model');
		if(!is_logged_in()){ // authentication verify by session login
      redirect('login', 'refresh'); // if not verify then redirect to login page
    }else{
      $this->user = decrypt($this->session->userdata('user_data'));
      $this->user_role = $this->user['user_role'];
      $this->division_id  = $this->user['division'];
      $this->user_id = $this->user['user_id'];
			$this->privilege = verify_all_privilege($this->user);
    }
	}

	public function index()
	{
    // division_name, user_role_name, username, complete name, email
    $user_profile = $this->users_model->get_user_profile($this->user_id); // get user profile by user id
    // $data['can_create_request'] = can_create_request($this->user_role);
		$data = array();
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
    $data['user_profile'] = $user_profile;
  	if(!$this->input->post() || !empty($_POST['state'])){
      $this->load->view('profile',$data); // load profile.php
    }else{
      $this->form_validation->set_rules('oldpwd', 'Current Password', 'required|trim|alpha_numeric|min_length[8]|max_length[16]|callback_check_old_password');
      $this->form_validation->set_rules('newpwd', 'New Password', 'required|trim|alpha_numeric|min_length[8]|max_length[16]');
      $this->form_validation->set_rules('confirmpwd', 'Confirm Password', 'required|trim|alpha_numeric|min_length[8]|max_length[16]|callback_check_password');
      if ($this->form_validation->run() == FALSE)
      {
        $this->load->view('profile',$data); // load profile.php
      }else{
        // continue to change password
        $old_password = strip_tags($this->input->post("oldpwd"));
        $new_password = strip_tags($this->input->post("newpwd"));
        $query_data = [
          $this->user_id,
          encrypt($old_password),
          encrypt($new_password)
        ];
        $state = $this->users_model->user_change_password($query_data);
        redirect('main', 'refresh'); // if verify then redirect to main page
      }
    }
	}
  function check_old_password( $c_password ){
    $return_val = FALSE;
    $query_data = [
      $this->user_id,
      encrypt($c_password)
    ];
    $state = $this->users_model->check_current_password($query_data);
    if ($state) {
      $return_val = TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_old_password', 'Incorrect current password');
    }
    return $return_val;
  }// callback_check_old_password

  function check_password( $c_password ){
    $return_val = FALSE;
    if ($this->input->post("newpwd") == $c_password) {
      $return_val = TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_password', 'New Password miss match');
    }
    return $return_val;
  }// callback_check_password

}
