<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require './application/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Register extends Rest_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('transactions_model');
	}

	public function index_get() {
		$data = array(
				'status' => 'error',
				'description' => 'request not available'
			);

		$this->response($data);
	}

	public function create_post() {	

		$transaction_type_id = 4; //register
		$transaction_type_name = 'NEW-REGISTER';
		$user_id = 175; // Affiliate
		$fee = 0;
		$transaction_time = '0000-00-00 00:00:00';	
		$come_from = 'Affiliate';

		$website_id = $this->input->post('website_id');
		$customer_id = $this->input->post('customer_id');
		$amount = $this->input->post('amount');
		$bank_account_number = $this->input->post('bank_account_number');
		$bank_account_name = $this->input->post('bank_account_name');
		$remark = $this->input->post('remark');
		$priority = 1;	

		$transaction_data['from_website_game_id'] = $this->input->post('from_game_id');
		$transaction_data['amount'] = $this->input->post('game_amount');
		$transaction_data_json = json_encode($transaction_data);

		$data = [
	        $transaction_type_id,
			$website_id,
			$customer_id,
			$transaction_data_json,
	        $amount,
	        $bank_account_number,
	        $bank_account_name,
	        $remark,
	        $user_id,
	        $transaction_type_name,
					$fee,
					$transaction_time,
					$priority,
					$come_from
	      ];

	      // echo '<pre>';
	      // print_r($data);
	      // exit();

		$result = $this->transactions_model->transaction_do_save($data);

		if($result){
			$uuid = uniqid();
			on_create_request_transaction($uuid);
		}
		$this->response($result);
	}
}
