<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);
class AC extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('ac_model');
    $this->load->model('users_model');
		if(!is_logged_in()){ // authentication verify by session login
      redirect('login', 'refresh'); // if not verify then redirect to login page
    }else{
      $this->user = decrypt($this->session->userdata('user_data')); // to set global user (object)
      $this->user_role = $this->user['user_role']; // to set global user role
      $this->division_id  = $this->user['division']; // to set global division id
      $this->user_id = $this->user['user_id']; // to set global user id
			$this->privilege = verify_all_privilege($this->user);
      if(!is_administrator($this->user_role)){ // to verify if this session is not Administrator
        redirect('main', 'refresh'); // redirect not authorization to dashboard
      }
    }
	}

	public function index()
	{
    // $data['active_menu'] = 'dashboard'; // to set active navigator menu
    $data['division_list'] = $this->ac_model->get_division_list(); // get division list
    $data['user_role_list'] = $this->ac_model->get_user_role_list(); // get user role list
    // $data['can_create_request'] = can_create_request($this->user_role); // to check is this user can create the transaction request
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		if(!$this->input->post()){
      $this->load->view('add-user-form',$data); // load index.php with dataset
    }else{
      // do code
      $this->load->helper(array('form', 'url')); // load library form , url
      $this->load->library('form_validation'); // load form validation library
      $this->form_validation->set_rules('division_id', 'Division', 'required');
      $this->form_validation->set_rules('user_role_id', 'User Role', 'required'); // to filters form input username by trim and do xss cleaning
      $this->form_validation->set_rules('complete_name','Complete Name', 'required|trim|min_length[4]|max_length[50]|callback_verify_complete_name');
      $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|min_length[4]|max_length[50]');
      $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[50]|callback_verify_username|callback_is_username_exist');
      $this->form_validation->set_rules('password', 'Password', 'required|trim|alpha_numeric|min_length[8]|max_length[16]');
      $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|alpha_numeric|min_length[8]|max_length[16]|callback_check_password');

      if ($this->form_validation->run() == FALSE)
      {
        $this->load->view('add-user-form',$data); // load profile.php
      }else{
        // continue to add new user
        $division_id = $this->input->post('division_id');
        $user_role_id = $this->input->post('user_role_id');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $complete_name = $this->input->post('complete_name');
        $email = $this->input->post('email');

        $query_data = [
          decrypt($division_id),
          decrypt($user_role_id),
          $username,
          encrypt($password),
          $complete_name,
          $email,
          $this->user_id
        ];
        $state = $this->users_model->user_do_save($query_data);
        redirect('main', 'refresh'); // if verify then redirect to main page
      }

    }
	}

  public function verify_complete_name(){
    $return_val = FALSE;

    if(preg_match('/^[a-zA-Z0-9 _\.\-]+$/', $this->input->post("complete_name"))){
      $return_val = TRUE;
    }else{
      $this->form_validation->set_message('verify_complete_name', 'Complete name allow spaces, alphabet, numeric, underscore(_), period(.) and dash(-)');
    }
    return $return_val;
  }

  public function verify_username(){
    $return_val = FALSE;
    if(preg_match('/^[a-zA-Z0-9_\.\-]+$/', $this->input->post("username"))){
      $return_val = TRUE;
    }else{
      $this->form_validation->set_message('verify_username', 'Username allow alphabet, numeric, underscore(_), period(.) and dash(-)');
    }
    return $return_val;
  }

  public function is_username_exist(){
    $return_val = FALSE;
      $state = $this->users_model->is_username_exist($this->input->post("username"));
      if(!$state){
        $return_val = TRUE;
      }else{
        $this->form_validation->set_message('is_username_exist','Username already exist');
      }
    return $return_val;
  } // callback_is_username_exist

  function check_password(){
    $return_val = FALSE;
    if ($this->input->post("password") == $this->input->post("confirm_password")) {
      $return_val = TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_password', 'Password miss match');
    }
    return $return_val;
  }// callback_check_password
}
