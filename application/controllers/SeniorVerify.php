<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);
class SeniorVerify extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('seniorverify_model');
	}
	
	public function start_senior_validation()
	{	
		$post_pword = $this->input->post('pword');
		$trans_id = $this->input->post('trans_id');
		$sv_option = $this->input->post('senior_validation_option');


		$inp = file_get_contents('json/spword.json');
		$data = json_decode($inp);
		$pword_validation = 'INVALID';
		$user = '';

		foreach ($data as $row) {
			$json_pword = $row->pword;
			if($json_pword == $post_pword) {
				$pword_validation = 'VALID';
				$user = $row->user;
			}
		}

		if($pword_validation == 'VALID') {
			switch ($sv_option) {
				case 'verify':
					$verify_log = array(
						'senior' 		=> $user,
						'verify_start' 	=> date("Y-m-d H:i:s"),
						'verify_end'	=> ''
					);
					$senior_log = array(
						'senior' 	=> $user,
						'status' 	=> 'VERIFYING',
						'stamptime'	=> date("Y-m-d H:i:s")
					);
					$verify_json_log = json_encode($verify_log);
					$senior_json_log = json_encode($senior_log);
					$response = $this->seniorverify_model->start_verify($trans_id, $verify_json_log, $senior_json_log);
					break;
				case 'fix':
					$fix_log = array(
						'senior' 		=> $user,
						'fix_start' 	=> date("Y-m-d H:i:s"),
						'fix_end'	=> ''
					);
					$senior_log = array(
						'senior' 	=> $user,
						'status' 	=> 'FIXING',
						'stamptime'	=> date("Y-m-d H:i:s")
					);
					$fix_json_log = json_encode($fix_log);
					$senior_json_log = json_encode($senior_log);
					$response = $this->seniorverify_model->start_fix($trans_id, $fix_json_log, $senior_json_log);
					break;
			}	

			$result = $senior_log; //verified
		} else {
			$result = array(
				'status'	=>	$pword_validation // unverified
			);
		}

		echo json_encode($result);
	}

	public function end_senior_validation()
	{	
		$trans_id = $this->input->post('trans_id');
		$senior = $this->input->post('senior');
		$sv_option = $this->input->post('senior_validation_option');

		switch ($sv_option) {
			case 'verify':
				$select_verify = $this->seniorverify_model->select_verify($trans_id);
				$verifying = json_decode($select_verify[0]->verify);
				$verifying_last = end($verifying);
				$verify_last_timestamp = $verifying_last->verify_start;
				$verify_last_reset = array_pop($verifying);
				$new_verify_array = array(
					'senior' 		=> $senior,
					'verify_start' 	=> $verify_last_timestamp,
					'verify_end'	=> date("Y-m-d H:i:s")
				);
				array_push($verifying, $new_verify_array);
				$verify_json_log = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($verifying), ENT_NOQUOTES));
				$senior_log = array(
					'senior' 	=> $senior,
					'status' 	=> 'VERIFIED',
					'stamptime'	=> date("Y-m-d H:i:s")
				);
				$senior_json_log = json_encode($senior_log);
				$result = $this->seniorverify_model->end_verify($trans_id, $verify_json_log, $senior_json_log);
				break;
			case 'fix':
				$select_fix = $this->seniorverify_model->select_fix($trans_id);
				$fixing = json_decode($select_fix[0]->fix);
				$fixing_last = end($fixing);
				$fix_last_timestamp = $fixing_last->fix_start;
				$fix_last_reset = array_pop($fixing);

				$new_fix_array = array(
					'senior' 		=> $senior,
					'fix_start' 	=> $fix_last_timestamp,
					'fix_end'	=> date("Y-m-d H:i:s")
				);
				array_push($fixing, $new_fix_array);
				$fix_json_log = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($fixing), ENT_NOQUOTES));
				$senior_log = array(
					'senior' 	=> $senior,
					'status' 	=> 'FIXED',
					'stamptime'	=> date("Y-m-d H:i:s")
				);
				$senior_json_log = json_encode($senior_log);
				$result = $this->seniorverify_model->end_fix($trans_id, $fix_json_log, $senior_json_log);
				break;
		}


		echo $senior_json_log;
	}

}