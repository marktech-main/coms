<?php
defined('BASEPATH') OR exit('No direct script admincess allowed');
header('Access-Control-Allow-Origin: *', false);
class Monitor extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
    $this->load->model('users_model');
    $this->load->model('monitor_model');
		if(!is_logged_in()){ // authentication verify by session login
      redirect('login', 'refresh'); // if not verify then redirect to login page
    }else{
      $this->user = decrypt($this->session->userdata('user_data')); // to set global user (object)
      $this->user_role = $this->user['user_role']; // to set global user role
      $this->division_id  = $this->user['division']; // to set global division id
      $this->user_id = $this->user['user_id']; // to set global user id
			$this->privilege = verify_all_privilege($this->user);
      if(!can_monitor_user($this->user_role)){ // to verify if this session can't access admin panel
        redirect('main', 'refresh'); // redirect not authorization to dashboard
      }
    }
	}

  public function index()
  {
    $data['active_menu'] = 'dashboard';
    $data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
    $filter_uuid = ( (isset($_POST['uuid']) && !empty($_POST['uuid']) ) ? $_POST['uuid'] : '' );
    $data['monitoring_data'] = $this->force_update_monitoring_user();
    $this->load->view('monitor', $data);
  }

  public function force_update_monitoring_user(){
    $filter_uuid = ( (isset($_POST['uuid']) && !empty($_POST['uuid']) ) ? $_POST['uuid'] : '' );
    $result = $this->monitor_model->get_monitoring_data( ['filter_uuid' => $filter_uuid] );
    $response_html = '';
    foreach ($result as $k => $v) {
      $tr_class = $v['services'] == 1 ? 'PROCESSING' : 'IDLE' ;
      $response_html .= '<tr class="'.$tr_class.'"><td>'.$v['username'].'</td><td>'.$tr_class.'</td></tr>';
    }
    return $response_html;
  }

}
