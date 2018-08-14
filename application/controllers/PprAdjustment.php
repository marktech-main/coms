<?php
defined('BASEPATH') OR exit('No direct script admincess allowed');
header('Access-Control-Allow-Origin: *', false);
class Ppradjustment extends CI_Controller {

	function __construct(){
		parent::__construct();
		# $this->load->model('get_data');
		$this->load->model('ppradj_model');
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
		$get_trans_list = $this->ppradj_model->get_trans_list_today();
		$payment_list = $this->ppradj_model->payment_list();

		$data['today_trans_list'] = $get_trans_list;
		$data['payment_list'] = $payment_list;
		$data['active_menu'] = 'ppradjustment';
		$data = array_merge($data , $this->privilege); 
		$this->load->view('ppr/ppr-adjustment', $data);
	}

	public function time_adjust() {
		$selected = $this->input->post('selected');
		$time = $this->input->post('time');
		$updated_by = $this->input->post('updated_by');
		$action = $this->input->post('action');
		$reason = $this->input->post('reason');
		
		$add_selected = '';
		$list_id = '';
		$count_selected = 0;
		foreach ($selected as $key => $item) {
			$add_selected .= "('".$item['value']."','".$time."','".$updated_by."','".$action."','".$reason."'),"; 
			$list_id .= "".$item['value'].", "; 				
			$count_selected++; 
		}

		$value_selected = rtrim($add_selected, ',');
		$result = $this->ppradj_model->add_selected($value_selected);		
		if($result == 1) {
			$this->sendemail($count_selected, $time, $updated_by, $action, $reason, $list_id);
		}

		echo json_encode($result);	
	}

	public function filtered_list() {
		$payment_name = $this->input->post('payment_name');
		$date = $this->input->post('date');
		$time_from = $this->input->post('time_from');
		$time_to = $this->input->post('time_to');
		$exclude_time = $this->input->post('exclude_time');

		$result = $this->ppradj_model->filter_list($payment_name, $date, $time_from, $time_to, $exclude_time);

		echo json_encode($result);
	}

	public function sendemail($count_selected, $time, $updated_by, $action, $reason, $list_id) {
	    $smtp_user = 'marktech.uni@gmail.com';
	    $smtp_pass = 'asxz4521';
	    $send_from = 'marktech.uni@gmail.com';
	    $send_to = 'SuperI_Supervisor@oleintl.com';//SuperI_Supervisor@oleintl.com,kisito.ong@oleintl.com
	    $send_tocc = 'janine.garcia@oleintl.com,jennielyn.daylo@oleintl.com,lou.dulguime@oleintl.com';
	    $ishtml = true;
	    $subject = 'PPR adjustment notification';
	    $body = '<p>COMS transaction has been adjusted today by <b>'.$updated_by.'</b>.</p>
			<p><b>Details:</b><br>
			Date: '.date("F j, Y, g:i a").'<br>
			Action: '.$action.'<br>
			Time: '.$time.'<br>
			Reason: '.$reason.'<br>
			Number of transaction: '.$count_selected.'<br>
			Transaction ID: '.rtrim($list_id, ', ').'</p>';
	    $altbody = 'altbody';

		$postfields = array(
			'send_from'	=>	$send_from,
			'send_to'	=>	$send_to,
			'send_tocc'	=>	$send_tocc,
			'subject'	=>	$subject,
			'body'		=>	$body
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://servebetter.vip/api/mailer/send');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_exec($ch);
	}

}
