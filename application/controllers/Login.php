<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		# $this->load->model('get_data');
    $this->load->model('users_model');
	}

	public function index()
	{
    if(is_logged_in()){
        // $this->load->view('welcome_message'); // redirect to dashboard
        redirect(base_url(), 'refresh'); // redirect to dashboard
    }else{
      $this->load->view('login'); // display login page with errors notification
    }
	}

	public function do_login(){
    $this->load->helper(array('form', 'url')); // load library form , url
    $this->load->library('form_validation'); // load form validation library
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|xss_clean'); // to filters form input username by trim and do xss cleaning
		$this->form_validation->set_rules('password', 'Password', 'required');

      # valid user input
      $username = $this->input->post("username");
      $password = $this->input->post("password");
      $remember = $this->input->post("remember");

      $auth	=	 $this->users_model->do_login( $username, $password, $remember );
      if(is_logged_in()){
          // $this->load->view('welcome_message'); // redirect to dashboard
          // $this->form_validation->set_message('errors_notification', $auth);

					// connect to socket serverSide
					self::connect_socket();
		  /*updated PPR login status*/
		on_login_with_username();
		on_update_loggedin_user_status();

      }else{
        $this->load->view('login'); // display login page with errors notification
      }


	}

  public function do_logout()
  {
		$user = decrypt($this->session->userdata('user_data'));
		on_logout_with_username($user['username']);
		$this->users_model->do_logout( $user['username'] );
    $this->session->unset_userdata('user_data');
    $this->session->sess_destroy();
	/*updated PPR login status*/
    on_update_loggedin_user_status();
    // redirect('login', 'refresh');
	echo '<script>console.log("disconnected from socket server"); document.location.href="'.base_url().'login";</script>';
  }

	public function connect_socket(){
		echo '<script>console.log("connected to socket server"); document.location.href="'.base_url().'";</script>';
	}

}
