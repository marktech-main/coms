<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('transactions_model');
		$this->load->model('websites_model');
		$this->load->model('games_model');
		$this->load->model('notify_model');
		if(!is_logged_in()){ // authentication verify by session login
      redirect('login', 'refresh'); // if not verify then redirect to login page
		}else{
      $this->user = decrypt($this->session->userdata('user_data'));
      $this->user_role = $this->user['user_role'];
      $this->division_id  = $this->user['division'];
      $this->user_id = $this->user['user_id'];
			$this->username = $this->user['username'];
			$this->privilege = verify_all_privilege($this->user);
    }
	}

	public function create()
	{
		if(!$this->input->post()){
			$user = decrypt($this->session->userdata('user_data'));
			$division_id 	= (is_tech_team($user['division']) ? '0' : $this->division_id); // division Super-Tech can see all division records
			$data['transaction_types_list'] = $this->transactions_model->get_transaction_types_list(); // select transaction types list
			$data['websites_list'] = $this->websites_model->get_websites_list([$division_id]); // select websites list
			$data['custom_request_form'] = $this->division_id == 2 ? TRUE : FALSE;
			$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team

			// [FOR TEST] connect to socket server to update data table
			// on_create_request_transaction();

			if($data['can_create_request']){
				$this->load->view('order-form', $data); // load order-form.php
			}else{
				redirect('main', 'refresh'); // if not verify then redirect to login page
			}
		}else{
			//
			// if ($this->form_validation->run() == FALSE) {
			//     echo validation_errors();
			// }

			$user = decrypt($this->session->userdata('user_data'));
	    $division_id  = $user['division'];
	    $division_id 	= (is_tech_team($user['division']) ? '0' : $division_id); // division Super-Tech can see all division records
	    $user_id = $user['user_id'];

			/*
			* === STEP 1 ===
			* purpose : to verify customer_id that should already exist on COMS DB then populate customer bank number and customer bank name
			* remark : skip this step continue later on phase 2
			*/

			/*
			* === STEP 2 ===
			* purpose : verify form data with form validation
			*/
	    $transaction_type_name = $this->input->post("transaction_type_name");
	    $this->load->helper(array('form', 'url')); // load library form , url
	    $this->load->library('form_validation'); // load form validation library
	    $this->form_validation->set_rules('transaction_type_id', 'Request type', 'required');
	    if($transaction_type_name == "NEW-REGISTER"){
				$this->form_validation->set_rules('customer_id', 'Customer Name', 'trim|required|min_length[4]'); // to filters form input username by trim and do xss cleaning
			}else{
				$this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|required|min_length[4]'); // to filters form input username by trim and do xss cleaning
			}
			if($transaction_type_name != "RESET-PASSWORD"){
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|greater_than[10]');
			}
	    $this->form_validation->set_rules('website_id', 'Website', 'required');
	    $this->form_validation->set_rules('from_game_id', 'From Game', 'callback_check_from_game_id');
	    if($transaction_type_name == "TRANSFER"){
	      $this->form_validation->set_rules('to_game_id', 'To Game', 'callback_check_to_game_id');
	    }else{
				if($transaction_type_name != "RESET-PASSWORD"){
					$this->form_validation->set_rules('game_amount', 'Amount', 'trim|callback_numeric_array|callback_euqal_number');
		      $this->form_validation->set_rules('bank_account_number', 'Bank account number', 'trim|required|min_length[5]');
		      $this->form_validation->set_rules('bank_account_name', 'Bank account name', 'trim|required|min_length[5]');
				}
	    }
	    $this->form_validation->set_rules('remark', 'Remark', 'trim');

	    if ($this->form_validation->run() == FALSE)
	    {
				// $data['can_create_request'] = can_create_request($this->user_role);
				// $data = array();
				// $data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
	      // $data['transaction_types_list'] = $this->transactions_model->get_transaction_types_list(); // select transaction types list
	      // $data['websites_list'] = $this->websites_model->get_websites_list([$division_id]); // select websites list
				// $data['custom_request_form'] = $this->division_id == 2 ? TRUE : FALSE;
				// $this->load->view('order-form',$data); // load order-form.php
				// echo validation_errors();
				echo json_encode([
					'state' => FALSE,
					'message' => validation_errors()
					]);
	    }else{
	      // continue to save transaction
	      $transaction_type_id = decrypt($this->input->post("transaction_type_id"));
				$website_id = decrypt($this->input->post("website_id"));
	      $customer_id = $this->input->post('customer_id');
	      $amount = $this->input->post('amount');
				$priority =  !empty($this->input->post('priority')) ? '1' : '0';
				$fee = !empty($this->input->post('fee')) ? $this->input->post('fee') : '';
				$transaction_time = !empty($this->input->post('transaction_time')) ? $this->input->post('transaction_time') : '0000-00-00 00:00:00' ;

				$transaction_data['from_website_game_id'] = decrypt($this->input->post('from_game_id'));

	      // $from_website_game_id = decrypt($this->input->post('from_game_id'));
	      if($transaction_type_name == "TRANSFER"){
	        $transaction_data['to_website_game_id'] = decrypt($this->input->post('to_game_id'));
	        $bank_account_number = '';
	        $bank_account_name = '';
	      }else{
	        $to_website_game_id = '';
	        $bank_account_number = $this->input->post('bank_account_number');
	        $bank_account_name = $this->input->post('bank_account_name');
					$transaction_data['amount'] = $this->input->post('game_amount');
	      }
	      $remark = $this->input->post('remark');

				/*
				* === STEP 3 ===
				* prepare data for proceed
				*/

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
					$priority
	      ];

				/*
				* === STEP 4 ===
				* proceed add transaction to DB
				*/
	      $result = $this->transactions_model->transaction_do_save($data); // do insert to DB

				/*
				* === STEP 5 ===
				* push notification to another users once successful
				*/
				if($result){ // if insert to DB successful then proceed with notification step
					on_create_request_transaction();
					// redirect('main', 'refresh'); // if verify then redirect to login page

					echo json_encode([
						'state' => TRUE,
						'message' => 'your request transaction was successful',
						'redirect_url' => 'http://' . $_SERVER['SERVER_NAME'] . '/main'
						]);
				}

	    }
		}

	}

	public function update(){
		$user = decrypt($this->session->userdata('user_data'));
		$division_id  = $user['division'];
		$user_role = $user['user_role'];
		$user_id = $user['user_id'];
		// $data['can_update_request'] = can_update_request($user_role);
		// $data['can_create_request'] = can_create_request($user_role);
		$data = array();
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		if(!$this->input->post() || !empty($_POST['state'])){
			// $division_id = (is_tech_team($division_id) ? '0' : $division_id); // division Super-Tech can see all division records
			$division_id = (is_administrator($this->user_role) || is_tech_team($division_id) || is_payment_team($user_role) ? '0' : $division_id); // division Super-Tech and Payment Team can see all division records

			$transaction_id  = ((isset($_POST['transaction_id']) || $_POST['transaction_id'] != '0' ) ? decrypt($_POST['transaction_id']) : '');
			// if numeric and not empty
			if(!empty($transaction_id) && is_numeric($transaction_id)){
				/*
				* === STEP 1 ===
				* purpose : if PT then update process time if update by null
				* remark : if no then skip this step
				*/
				$user_role = $user['user_role'];
				if(can_update_request($user_role)){ // to check user privilege to verify transactions
					$data = [
						$transaction_id,
						'START-PROCESSING',
						$user_id,
						'',
						''
					];

					$update_state = $this->transactions_model->transaction_do_update_status($data); // update status to PROCESSING if current status is QUEUE
					/*
					* === STEP 2 ===
					* purpose : if updated then send notification to CS that they request on processing
					* remark : if no then skip this step
					*/
					if($update_state){

						// to change notification message
						// Your request for user xxxx DEPOSIT 5000 successful
						// You update transaction xxxx status to successful
						$notify_request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $division_id]); // get individual request transaction data
						$notify_customer_id = $notify_request_transaction->customer_id; // customer_id
						$notify_request_type = $notify_request_transaction->transaction_type_name; // transaction_type_name
						$notify_amount = $notify_request_transaction->amount != '0' ? $notify_request_transaction->amount : ''; // amount

						// insert notify message to DB before send socket\
						// ==== FOR OWNER ====
						// prepare data for notify messages
						$requester_user = $this->transactions_model->get_requester_id($transaction_id);// get requester user_id
						// $notify_data = [
						// 	$requester_user->user_id, // user_id
						// 	$transaction_id, // transaction_id
						// 	'Your transaction '.$transaction_id.' is processing.', // content
						// 	'info' // status
						// ];
						// new
						$notify_data = [
							$requester_user->user_id, // user_id
							$transaction_id, // transaction_id
							'Your transaction request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' is processing.', // content
							'info' // status
						];

						// do insert notify message to DB then return boolean
						$notify_state = $this->notify_model->notify_message_do_save($notify_data); // insert notify data to DB

						// ==== FOR OPERATOR ====
						// $owner_notify_data = [
						// 	$user_id, // user_id
						// 	$transaction_id, // transaction_id
						// 	'Your updated transaction '.$transaction_id.' to processing.', // content
						// 	'info' // status
						// ];
						// new
						$owner_notify_data = [
							$user_id, // user_id
							$transaction_id, // transaction_id
							'Your updated transaction request for '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' to processing.', // content
							'info' // status
						];
						// do insert notify message to DB then return boolean
						$owner_notify_state = $this->notify_model->notify_message_do_save($owner_notify_data); // insert notify data to DB

						on_update_request_transaction(encrypt($transaction_id), 'PROCESSING', ''); // send socket message to socket server
					}

				}

				/*
				* === STEP 3 ===
				* purpose : prepare trasaction data
				*/
				$request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $division_id]); // get individual request transaction data
				$game_list = $this->games_model->get_games_list_by_division([$division_id]); // get game list by division
				$website_games_id_list = json_decode($request_transaction->transaction_data)->from_website_game_id; // game id list
				$transction_data_list = array();
				if($request_transaction->transaction_type_name == 'TRANSFER'){
					$to_website_games_id_list = json_decode($request_transaction->transaction_data)->to_website_game_id; // game id list
					$transaction_data['from_website_game_name'] = array_hashmap($game_list, 'website_game_id', array_values((array)$website_games_id_list)[0])['game_name'];
					$transaction_data['to_website_game_name'] = array_hashmap($game_list, 'website_game_id', array_values((array)$to_website_games_id_list)[0])['game_name'];
					$transction_data_list[] = $transaction_data;
				}else{
					$game_amount_list = (array)json_decode($request_transaction->transaction_data)->amount; // amount per game id list
					foreach ($website_games_id_list as $key => $value) {
						$website_game_name = array_hashmap($game_list, 'website_game_id', $value)['game_name'];
						$transaction_data['website_game_name'] = $website_game_name;
						$transaction_data['game_amount'] = array_values($game_amount_list)[$key];
						$transction_data_list[] = $transaction_data;
					}
				}

				// get update by

				$data['transaction_code'] = encrypt($request_transaction->transaction_id);
				$data['request_transaction'] = $request_transaction; // request transaction data in object format
				$data['transaction_data'] = $transction_data_list; // transaction data in array format

				// are you operator who incharge this transacition
				$data['are_you_operator'] = ($request_transaction->updated_by_id == $this->user_id) ? TRUE : FALSE;

				// added 2017-01-19
				// prepare data
				$data['am_i_requester'] = ($request_transaction->created_by_id == $this->user_id) ? TRUE : FALSE;
				// added 2017-01-19
				$data['chat_history'] = $this->transactions_model->get_chat_history($transaction_id);
				$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
				// $data['can_update_request'] = can_update_request($user_role);
				// $data['can_create_request'] = can_create_request($user_role);
				// echo '<pre>';
				// print_r($data);
				// echo '</pre>';
				// die();

				if($data['are_you_operator'] || $data['am_i_requester']){
					$data['sender_id'] = $this->user_id;
					$data['receiver_id'] = $data['am_i_requester'] ? $request_transaction->updated_by_id : $request_transaction->created_by_id;
				}else{
					$data['sender_id'] = $this->user_id;
					$data['receiver_id'] = $this->user_id;
				}
				$this->load->view('verify-form', $data); // load verify-form.php
			}
		}else{
			$user = decrypt($this->session->userdata('user_data'));
			$division_id  = $user['division'];
			// $division_id 	= (is_tech_team($user['division']) ? '0' : $division_id); // division Super-Tech can see all division records
			$division_id = (is_tech_team($this->division_id) || is_payment_team($this->user_role) ? '0' : $this->division_id); // division Super-Tech and Payment Team can see all division records

			$user_id = $user['user_id'];
			$this->form_validation->set_rules('transaction_id', 'Transaction ID', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$transaction_id = decrypt($this->input->post("transaction_id"));
			// pending required reason for pending
			if($this->input->post("status") == 'PENDING'){
				$this->form_validation->set_rules('reason', 'Reason', 'required');
			}

	    if ($this->form_validation->run() == FALSE)
	    {
				if(!empty($transaction_id) && is_numeric($transaction_id)){
					// prepare trasaction data
					$request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $division_id]); // get individual request transaction data
					$game_list = $this->games_model->get_games_list_by_division([$division_id]); // get game list by division
					$website_games_id_list = json_decode($request_transaction->transaction_data)->from_website_game_id; // game id list
					$transction_data_list = array();
					if($request_transaction->transaction_type_name == 'TRANSFER'){
						$to_website_games_id_list = json_decode($request_transaction->transaction_data)->to_website_game_id; // game id list
						$transaction_data['from_website_game_name'] = array_hashmap($game_list, 'website_game_id', array_values((array)$website_games_id_list)[0])['game_name'];
						$transaction_data['to_website_game_name'] = array_hashmap($game_list, 'website_game_id', array_values((array)$to_website_games_id_list)[0])['game_name'];
						$transction_data_list[] = $transaction_data;
					}else{
						$game_amount_list = (array)json_decode($request_transaction->transaction_data)->amount; // amount per game id list
						foreach ($website_games_id_list as $key => $value) {
							$website_game_name = array_hashmap($game_list, 'website_game_id', $value)['game_name'];
							$transaction_data['website_game_name'] = $website_game_name;
							$transaction_data['game_amount'] = array_values($game_amount_list)[$key];
							$transction_data_list[] = $transaction_data;
						}
					}

					$data['transaction_code'] = encrypt($request_transaction->transaction_id);
					$data['request_transaction'] = $request_transaction; // request transaction data in object format
					$data['transaction_data'] = $transction_data_list; // transaction data in array format
					$data['are_you_operator'] = ($request_transaction->updated_by_id == $this->user_id) ? TRUE : FALSE;

					// added 2017-01-19
					// prepare data
					$data['chat_history'] = $this->transactions_model->get_chat_history($transaction_id); // added to fixed bugs
					$data['am_i_requester'] = ($request_transaction->created_by_id == $this->user_id) ? TRUE : FALSE;

					if($data['are_you_operator'] || $data['am_i_requester']){
						$data['sender_id'] = $this->user_id;
						$data['receiver_id'] = $data['am_i_requester'] ? $request_transaction->updated_by_id : $request_transaction->created_by_id;
					}else{
						$data['sender_id'] = $this->user_id;
						$data['receiver_id'] = $this->user_id;
					}
	      	$this->load->view('verify-form',$data); // load order-form.php
				}else{
					// display error message
					// $data['can_update_request'] = can_update_request($this->user_role);
					// $data['can_create_request'] = can_create_request($this->user_role);
					$data = array();
					// are you operator who incharge this transacition
					$data['are_you_operator'] = ($request_transaction->updated_by_id == $this->user_id) ? TRUE : FALSE;
					// added 2017-01-19
					// prepare data
					$data['am_i_requester'] = ($request_transaction->created_by_id == $this->user_id) ? TRUE : FALSE;
					$data['chat_history'] = $this->transactions_model->get_chat_history($transaction_id);
					$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
					if($data['are_you_operator'] || $data['am_i_requester']){
						$data['sender_id'] = $this->user_id;
						$data['receiver_id'] = $data['am_i_requester'] ? $request_transaction->updated_by_id : $request_transaction->created_by_id;
					}else{
						$data['sender_id'] = $this->user_id;
						$data['receiver_id'] = $this->user_id;
					}
	      	$this->load->view('verify-form',$data); // load order-form.php
				}

	    }else{

				$transaction_id = decrypt($this->input->post("transaction_id"));
				$status = $this->input->post("status");
				$remark = $this->input->post('remark');
				$reason = $this->input->post('reason');
				// {"user_id":"11", "content":"waiting for ID", "timestamp":"2017-01-19"},
				// $remark = '{"user_id":"'.$this->user_id.'", "username":"'.$this->username.'", "content":"'.htmlspecialchars($this->input->post('remark')).'", "timestamp":"'.date("Y-m-d H:i:s").'"}';

				$data = [
	        $transaction_id,
					$status,
	        $user_id,
					$remark,
					$reason
	      ];
				/*
				* === STEP 4 ===
				* proceed add transaction to DB
				*/

				$result = $this->transactions_model->transaction_do_update_status($data); // do update DB

				/*
				* === STEP 5 ===
				* push notification to another users once successful
				*/
				if($result){ // if insert to DB successful then proceed with notification step
					// insert notify message to DB before send socket
					$notify_status = '';
					// prepare data for notify messages
					switch ($status) { // for notify js status
						case 'SUCCESSFUL':
							$notify_status = 'success';
							break;
						case 'PENDING':
							$notify_status = 'warn';
							break;
						case 'CANCELLED':
							$notify_status = 'error';
							break;
						default:
							$notify_status = 'info';
							break;
					}
					// insert notify message to DB before send socket
					// ==== FOR OWNER ====
					// prepare data for notify messages
					$division_id = (is_tech_team($this->division_id) || is_payment_team($this->user_role) ? '0' : $this->division_id); // division Super-Tech and Payment Team can see all division records
					$notify_request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $division_id]); // get individual request transaction data
					$notify_customer_id = $notify_request_transaction->customer_id; // customer_id
					$notify_request_type = $notify_request_transaction->transaction_type_name; // transaction_type_name
					$notify_amount = $notify_request_transaction->amount != '0' ? $notify_request_transaction->amount : ''; // amount
					$requester_user = $this->transactions_model->get_requester_id($transaction_id);// get requester user_id
					// $notify_data = [
					// 	$requester_user->user_id, // user_id
					// 	$transaction_id, // transaction_id
					// 	'Your transaction '.$transaction_id.' is '.strtolower($status).'.', // content
					// 	$notify_status // status
					// ];
					// new
					$notify_data = [
						$requester_user->user_id, // user_id
						$transaction_id, // transaction_id
						'Your transaction request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' is '.strtolower($status).'.', // content
						$notify_status // status
					];

					// do insert notify message to DB then return boolean
					$notify_state = $this->notify_model->notify_message_do_save($notify_data); // insert notify data to DB

					// ==== FOR OPERATOR ====
					// $owner_notify_data = [
					// 	$user_id, // user_id
					// 	$transaction_id, // transaction_id
					// 	'Your updated transaction '.$transaction_id.' to '.strtolower($status).'.', // content
					// 	$notify_status // status
					// ];
					// new
					$owner_notify_data = [
						$user_id, // user_id
						$transaction_id, // transaction_id
						'You updated transaction request for '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' to '.strtolower($status).'.', // content
						$notify_status // status
					];

					// do insert notify message to DB then return boolean
					$owner_notify_state = $this->notify_model->notify_message_do_save($owner_notify_data); // insert notify data to DB


					on_update_request_transaction(encrypt($transaction_id), $status, $reason);
					// remark, should separate cancelled, successful, and other case
					if(strtolower($status) == 'successful'){
						on_update_transaction_to_successful($transaction_id);
					}


					redirect('main', 'refresh'); // if not verify then redirect to login page
				}else{
					// can be component function
					$request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $division_id]); // get individual request transaction data
					$game_list = $this->games_model->get_games_list_by_division([$division_id]); // get game list by division
					$website_games_id_list = json_decode($request_transaction->transaction_data)->from_website_game_id; // game id list
					$transction_data_list = array();
					if($request_transaction->transaction_type_name == 'TRANSFER'){
						$to_website_games_id_list = json_decode($request_transaction->transaction_data)->to_website_game_id; // game id list
						$transaction_data['from_website_game_name'] = array_hashmap($game_list, 'website_game_id', array_values((array)$website_games_id_list)[0])['game_name'];
						$transaction_data['to_website_game_name'] = array_hashmap($game_list, 'website_game_id', array_values((array)$to_website_games_id_list)[0])['game_name'];
						$transction_data_list[] = $transaction_data;
					}else{
						$game_amount_list = (array)json_decode($request_transaction->transaction_data)->amount; // amount per game id list
						foreach ($website_games_id_list as $key => $value) {
							$website_game_name = array_hashmap($game_list, 'website_game_id', $value)['game_name'];
							$transaction_data['website_game_name'] = $website_game_name;
							$transaction_data['game_amount'] = array_values($game_amount_list)[$key];
							$transction_data_list[] = $transaction_data;
						}
					}
					// $data['can_update_request'] = can_update_request($this->user_role);
					// $data['can_create_request'] = can_create_request($this->user_role);
					$data = array();
					// are you operator who incharge this transacition
					$data['are_you_operator'] = ($request_transaction->updated_by_id == $this->user_id) ? TRUE : FALSE;
					$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
					$data['transaction_code'] = encrypt($request_transaction->transaction_id);
					$data['request_transaction'] = $request_transaction; // request transaction data in object format
					$data['transaction_data'] = $transction_data_list; // transaction data in array format
					$data['chat_history'] = $this->transactions_model->get_chat_history($transaction_id);
					// test
					on_update_request_transaction(encrypt($transaction_id), 'PROCESSING', '');
	      	$this->load->view('verify-form',$data); // load order-form.php

					// ./can be component function
				}
			}
		}
	}

	// added for update request transaction detail on verify page.
	public function get_request_transaction_update(){
		$transaction_id = is_numeric($this->input->post("transaction_id")) ? $this->input->post("transaction_id") : decrypt($this->input->post("transaction_id"));
		$user = decrypt($this->session->userdata('user_data'));
		$division_id  = $user['division'];
		// $division_id 	= (is_tech_team($user['division']) ? '0' : $division_id); // division Super-Tech can see all division records
		$division_id = (is_tech_team($this->division_id) || is_payment_team($this->user_role) ? '0' : $this->division_id); // division Super-Tech and Payment Team can see all division records
		$request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $division_id]); // get individual request transaction data
		echo json_encode($request_transaction);
	}

  public function get_game_list()
  {
    $website_id  = ((isset($_POST['website_id']) || $_POST['website_id'] != '0' ) ? decrypt($_POST['website_id']) : '0');
    $result   = $this->games_model->get_games_list([$website_id]); // get request transaction list
    $html 	  = '';
    $html 	 .= '<option value="" selected hidden disabled>Choose Game</option>';
    if (!empty($result)) {
        $data['recordsFiltered'] = count($result);
        foreach ($result as $k => $v) {
            $website_game_id = encrypt($v['website_game_id']);
            #$game_id = encrypt($v['game_id']);
            $game_name = $v['game_name'];
            $html .= '<option value="'. $website_game_id .'">'. $game_name .'</option>';
        }
    }
    echo $html;
  }

	// verify-form
	public function euqal_number(){
		$game_amount = $this->input->post("game_amount");
		$totally_amount	=	$this->input->post("amount");
		$summary_game_amount = 0;
		$return_val = FALSE;
		foreach ($game_amount as $amount) {
			$summary_game_amount += $amount;
		}

		if($totally_amount == $summary_game_amount){
			$return_val = TRUE;
		}else{
			$this->form_validation->set_message('euqal_number', 'Amount miss match');
		}
		return $return_val;
	}
	// verify-form
	public function numeric_array(){
			$game_amount = $this->input->post("game_amount");
			$return_val = FALSE;

			if(array_map("is_numeric", $game_amount)){
				$return_val = TRUE;
			}else{
				$this->form_validation->set_message('numeric_array', 'Amount miss match');
			}
			return $return_val;

	}
	// verify-form
	public function check_from_game_id(){
		$from_game_id = $this->input->post("from_game_id");
		$game_amount = $this->input->post("game_amount");
		$return_val = FALSE;
		if(!empty($from_game_id) && sizeof($from_game_id) == sizeof($game_amount)){ // check array and not empty
			$return_val = TRUE;
		}else{
			$this->form_validation->set_message('check_from_game_id', 'Game data incorrect');
		}
		return $return_val;
	}
	// verify-form
	public function check_to_game_id(){
		$to_game_id = $this->input->post("to_game_id");
		$return_val = FALSE;
		if(!empty($to_game_id)){ // check array and not empty
			$return_val = TRUE;
		}else{
			$this->form_validation->set_message('check_to_game_id', 'Game data incorrect');
		}
		return $return_val;
	}

	// this function include insert Notification to DB
	public function am_i_requester(){
		$user = decrypt($this->session->userdata('user_data'));
		$user_id = $user['user_id'];
		$user_role = $user['user_role'];
		$division_id  = $user['division'];
		$transaction_id = decrypt($_POST['transaction_id']);
		$status = $_POST['status'];
		$reason = $_POST['reason']; // added for pending status
		// prepare data
		$data = [
			$transaction_id,
			$user_id
		];

		$result = $this->transactions_model->am_i_requester($data); // do update DB
		$return_status = '';
		$return_message = '';

		$notify_request_transaction = $this->transactions_model->get_request_transaction([$transaction_id, $this->division_id]); // get individual request transaction data
		$notify_customer_id = $notify_request_transaction->customer_id; // customer_id
		$notify_request_type = $notify_request_transaction->transaction_type_name; // transaction_type_name
		$notify_amount = $notify_request_transaction->amount != '0' ? $notify_request_transaction->amount : ''; // amount


		if($result){ // if requester
			// do query to collect transaction data
			# code ..

			// switch case for status [ success, info , warn , error ]
			switch (strtoupper($status)) {
				case 'SUCCESSFUL':
					$return_status = 'SUCCESS';
					// $return_message = 'Your request for user '.$transaction_id.' successful';
					$return_message = 'Your request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' successful.'; // content
					break;
				case 'PROCESSING':
					$return_status = 'INFO';
					$return_message = 'Your request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' on processing';
					break;
				case 'PENDING':
					$return_status = 'WARN';
					$return_message = 'Your request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' on pending';
					break;
				case 'CANCELLED':
					$return_status = 'ERROR';
					$return_message = 'Your request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' cancelled';
					break;
				default:
					$return_status = 'INFO';
					$return_message = 'Your request for user '.$notify_customer_id.' '.$notify_request_type.' '.$notify_amount.' on processing';
					break;
			}

			// do insert notify message to DB link to transaction requester
			# code ..
		}
		echo json_encode(['state' => $result, 'message' => $return_message, 'transaction_status' => strtoupper($status), 'message_status' => $return_status, 'reason' => $reason]); // state, message, status, type
	}

	// to verify current user session is payment team or not.
	public function am_i_payment_team(){
		$user = decrypt($this->session->userdata('user_data'));
		$user_id = $user['user_id'];
		$user_role = $user['user_role'];
		$division_id  = $user['division'];
		$result = is_payment_team($user_role);
		$return_status = '';
		$return_message = '';
		if($result){
			$return_status = 'info';
			$return_message = 'You have new request';
		}
		echo json_encode(['state' => $result, 'message' => $return_message, 'message_status' => $return_status]); // state, message, status,
	}

	public function get_request_transaction_statistic()
	{
		$user 				= decrypt($this->session->userdata('user_data'));
		$division_id  = $user['division'];
		$user_role 		= $user['user_role'];

		// $division_id = (is_tech_team($division_id) ? '0' : $division_id); // division Super-Tech can see all division records
		$division_id = (is_tech_team($division_id) || is_payment_team($user_role) ? '0' : $division_id); // division Super-Tech and Payment Team can see all division records
		if(is_cs_team($user_role)){
			$statistic_result = $this->transactions_model->get_cs_request_transaction_statistic($division_id); // get request transaction statistic
		}else{
			$statistic_result = $this->transactions_model->get_request_transaction_statistic($division_id); // get request transaction statistic
		}
		echo json_encode( (array)$statistic_result ); // total_request, total_deposit, total_withdrawal, total_transfer, total_new_register, total_cancelled
	}

	public function get_customer_bank_account(){
		$keywords = empty($this->input->get("keywords")) ? '0' : $this->input->get("keywords");
		$customer_bank_account_list = $this->transactions_model->get_customer_bank_account([$keywords]);
		$customer_bank_list = array();
		foreach ($customer_bank_account_list as $key => $value) {
			$customer_bank['value'] = $value['account_name'];
			$customer_bank['data'] = $value['account_number'];
			$customer_bank_list[] = $customer_bank;
		}
		echo json_encode( ['suggestions' => $customer_bank_list] );
	}

	public function update_pending_transaction(){
		$transaction_id = decrypt($_POST['transaction_id']);
		$status = $_POST['status'];
		$data = [
			$transaction_id,
			$this->user_id,
			$status
		];
		$result = $this->transactions_model->update_pending_transaction($data);
		$return_status = '';
		$return_message = '';
		if($result){
			$return_status = 'info';
			$return_message = 'CS accept your request for pending transaction';
			$status_message = ($status == "ACCEPT") ? 'accepted': 'declined';
			// insert notify message to DB before send socket\
			// ==== FOR OPERATOR ====
			// prepare data for notify messages
			$operator_user = $this->transactions_model->get_operator_id($transaction_id);// get operator user_id
			$notify_data = [
				$operator_user->user_id, // user_id
				$transaction_id, // transaction_id
				'Your request for pending transaction was '.$status_message.' by CS.', // content
				'info' // status
			];

			# you accept to pending transaction

			// do insert notify message to DB then return boolean
			$notify_state = $this->notify_model->notify_message_do_save($notify_data); // insert notify data to DB

			// ==== FOR OWNER ====
			$owner_notify_data = [
				$this->user_id, // user_id
				$transaction_id, // transaction_id
				'You '.$status_message.' request to pending transaction.', // content
				'info' // status
			];
			// do insert notify message to DB then return boolean
			$owner_notify_state = $this->notify_model->notify_message_do_save($owner_notify_data); // insert notify data to DB

			// send socket to node server for notify PT that CS accept/decline request for pending transaction
			on_update_pending_transaction($transaction_id, $status); // send socket message to socket server
		}
		echo json_encode(['state' => $result, 'message' => $return_message, 'message_status' => 'info']); // state, message, status,
	}
}
