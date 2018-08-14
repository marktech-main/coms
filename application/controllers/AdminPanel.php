<?php
defined('BASEPATH') OR exit('No direct script admincess allowed');
header('Access-Control-Allow-Origin: *', false);
class AdminPanel extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
    $this->load->model('users_model');
		if(!is_logged_in()){ // authentication verify by session login
      redirect('login', 'refresh'); // if not verify then redirect to login page
    }else{
      $this->user = decrypt($this->session->userdata('user_data')); // to set global user (object)
      $this->user_role = $this->user['user_role']; // to set global user role
      $this->division_id  = $this->user['division']; // to set global division id
      $this->user_id = $this->user['user_id']; // to set global user id
			$this->privilege = verify_all_privilege($this->user);
      if(!can_access_admin_panel($this->user_role)){ // to verify if this session can't access admin panel
        redirect('main', 'refresh'); // redirect not authorization to dashboard
      }
    }
	}

	public function user(){
		$data['division_list'] = $this->admin_model->get_division_list(); // get division list
		$data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
		// $data['can_create_request'] = can_create_request($this->user_role); // to check is this user can create the transadmintion request
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		$this->load->view('admin-panel/user-list',$data); // load profile.php
	}

	public function get_json_user_list($value='')
	{
		$user_list = self::request_user_list();
		echo $user_list;
	}

	public function request_user_list(){
			// reAuth::user_auth([1,2,3,4,5,6]);
			// sessionHelper::update_last_activity();

			$user = decrypt($this->session->userdata('user_data'));
			$division_id = $user['division'];
			$user_role = $user['user_role'];
			$columns = array(
					// datatable column index  => database column name
					0 => 'division_name',
					1 => 'user_role_name',
					2 => 'complete_name',
					3 => 'username',
					4 => 'email',
			);

			$key_word = (isset($_POST['search']['value']) ? $_POST['search']['value'] : '');
			$page = (isset($_POST['start']) ? $_POST['start'] : 0);
			$limit = (isset($_POST['length']) ? $_POST['length'] : 10);
			$order_column = (isset($_POST['order'][0]['column']) ? $columns[$_POST['order'][0]['column']] : '');
			$order_dir = (isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc');
			$data['draw'] = (isset($_POST['draw']) ? $_POST['draw'] : 1);

			$data['list'] = self::get_user_list([$key_word, $order_column, $order_dir, $page, $limit, 'list', '0']);
			$response['data'] = $data['list'];
			$response['draw'] = $data['draw'];
			$response['recordsTotal'] = self::count_user_list();
			$response['recordsFiltered'] = (!empty($_POST['search']['value']) ? count($data['list']) : $response['recordsTotal']);
			return json_encode($response);  // send data as json format
	}

	public function get_user_list( $param ){
		$result = $this->admin_model->get_user_list($param); // get user list
		$user_list = array();
		if (!empty($result)) {
				$data['recordsFiltered'] = count($result);
				foreach ($result as $k => $v) {
					if(!is_administrator($v['user_role_id']) && $v['user_id'] != $this->user_id){
						$tmp_data['user_id'] = encrypt($v['user_id']);
						$tmp_data['division_id'] = encrypt($v['division_id']);
						$tmp_data['user_role_id'] = encrypt($v['user_role_id']);
						$tmp_data['division_name'] = $v['division_name'];
						$tmp_data['user_role_name'] = $v['user_role_name'];
						$tmp_data['complete_name'] = $v['complete_name'];
						$tmp_data['email'] = $v['email'];
						$tmp_data['username'] = $v['username'];
						$tmp_data['password'] = $v['password'];
						$tmp_data['is_active'] = $v['is_active'];
						$tmp_data['is_ppr_payment'] = $v['is_ppr_payment'];
						$tmp_data['created_by_id'] = $v['created_by_id'];
						$tmp_data['created_by_name'] = $v['created_by_name'];
						$tmp_data['updated_by_id'] = $v['updated_by_id'];
						$tmp_data['updated_by_name'] = $v['updated_by_name'];
						array_push($user_list, $tmp_data);
					}
				}
		}
		return $user_list;
	}

	public function count_user_list(){
		$total_result = $this->admin_model->get_user_list(['', '', '', '0', '0', 'count_all', '0']); // get user list
		$total_record = 0;
		foreach ($total_result AS $key => $val) {
				$total_record = $val['total_record'];
		}
		return $total_record;
	}

	// to verify current user session is payment team or not.
	public function change_user_active(){
		$user_id = !empty($this->input->post('user_id')) ? decrypt($this->input->post('user_id')) : '0';
		$user_active = $this->input->post('user_active');
		$result = $this->admin_model->change_user_active([$user_id, $user_active]);
		$return_status = '';
		$return_message = '';
		if($result){
			$return_status = 'success';
			$return_message = 'Your request was successful';
		}else{
			$return_status = 'error';
			$return_message = 'Your request failed';
		}
		echo json_encode(['state' => $result, 'message' => $return_message, 'status' => $return_status]); // state, message, status,
	}

  public function addUser(){

		if(!is_administrator($this->user_role)){ // to verify if this session can't access admin panel
			redirect('main', 'refresh'); // redirect not authorization to dashboard
		}

    // $data['admintive_menu'] = 'dashboard'; // to set admintive navigator menu
    $data['division_list'] = $this->admin_model->get_division_list(); // get division list
    $data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
    // $data['can_create_request'] = can_create_request($this->user_role); // to check is this user can create the transadmintion request
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		if(!$this->input->post()){
      $this->load->view('admin-panel/user-form',$data); // load index.php with dataset
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
        $this->load->view('admin-panel/user-form',$data); // load profile.php
      }else{
        // continue to add new user
        $division_id = $this->input->post('division_id');
        $user_role_id = $this->input->post('user_role_id');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $complete_name = $this->input->post('complete_name');
        $email = $this->input->post('email');
				$is_ppr_payment =  !empty($this->input->post('is_ppr_payment')) ? '1' : '0';

        $query_data = [
          decrypt($division_id),
          decrypt($user_role_id),
          $username,
          encrypt($password),
          $complete_name,
          $email,
					$is_ppr_payment,
          $this->user_id
        ];
        $state = $this->users_model->user_do_save($query_data);
        redirect('main', 'refresh'); // if verify then redirect to main page
      }

    }
  }

	public function updateUser(){
		$data = array();
		#$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		if(!$this->input->post() || !empty($_POST['state'])){
			$user_id = !empty($this->input->post('user_id')) ? decrypt($this->input->post('user_id')) : '' ;
			$data = [
				'',
				'',
				'',
				'0',
				'0',
				'search_by_id',
				$user_id
			];
			$user_account = $this->admin_model->get_user($data);
			$data['division_list'] = $this->admin_model->get_division_list(); // get division list
			$data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
			$data['user_account'] = $user_account;
			$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
			$this->load->view('admin-panel/user-form',$data); // load user-form.php with dataset
		}else{
			// do code
      $this->load->helper(array('form', 'url')); // load library form , url
      $this->load->library('form_validation'); // load form validation library
			$this->form_validation->set_rules('division_id', 'Division', 'required');
      $this->form_validation->set_rules('user_role_id', 'User Role', 'required'); // to filters form input username by trim and do xss cleaning
      $this->form_validation->set_rules('complete_name','Complete Name', 'required|trim|min_length[4]|max_length[50]|callback_verify_complete_name');
      $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|min_length[4]|max_length[50]');
      $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[50]|callback_verify_username|callback_is_username_exist_except_id');
      $this->form_validation->set_rules('password', 'Password', 'required|trim|alpha_numeric|min_length[8]|max_length[16]');

      if ($this->form_validation->run() == FALSE)
      {
				$user_id = !empty($this->input->post('user_id')) ? decrypt($this->input->post('user_id')) : '' ;
				$data = [
					'',
					'',
					'',
					'0',
					'0',
					'search_by_id',
					$user_id
				];
				$user_account = $this->admin_model->get_user($data);
				$data['division_list'] = $this->admin_model->get_division_list(); // get division list
				$data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
				$data['user_account'] = $user_account;
				$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
        $this->load->view('admin-panel/user-form',$data); // load profile.php
      }else{
        // continue to add new user
        $division_id = $this->input->post('division_id');
        $user_role_id = $this->input->post('user_role_id');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $complete_name = $this->input->post('complete_name');
        $email = $this->input->post('email');
				$user_id = !empty($this->input->post('user_id')) ? decrypt($this->input->post('user_id')) : '' ;
				$is_ppr_payment =  !empty($this->input->post('is_ppr_payment')) ? '1' : '0';
        $query_data = [
          decrypt($division_id),
          decrypt($user_role_id),
          $username,
          encrypt($password),
          $complete_name,
          $email,
					$is_ppr_payment,
          $this->user_id,
					$user_id
        ];
        $state = $this->users_model->user_do_update($query_data);
        redirect('AdminPanel/user', 'refresh'); // if verify then redirect to main page
      }
		}
	}

	public function bankAccount(){
		$data['division_list'] = $this->admin_model->get_division_list(); // get division list
		$data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
		// $data['can_create_request'] = can_create_request($this->user_role); // to check is this user can create the transadmintion request
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		$this->load->view('admin-panel/bank-account-list',$data); // load profile.php
	}

	public function get_json_customer_bank_account_list(){
		$customer_bank_account_list = self::customer_bank_account_list();
		echo $customer_bank_account_list;
	}

	public function customer_bank_account_list(){
		$division_id = $this->division_id;
		$columns = array(
				// datatable column index  => database column name
				0 => 'customer_bank_account_name',
				1 => 'customer_bank_account_number',
				2 => 'created_by_name',
				3 => 'created_date',
				4 => 'updated_by_name',
				5 => 'updated_date'
		);

		$key_word = (isset($_POST['search']['value']) ? $_POST['search']['value'] : '');
		$page = (isset($_POST['start']) ? $_POST['start'] : 0);
		$limit = (isset($_POST['length']) ? $_POST['length'] : 10);
		$order_column = (isset($_POST['order'][0]['column']) ? $columns[$_POST['order'][0]['column']] : '');
		$order_dir = (isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc');
		$data['draw'] = (isset($_POST['draw']) ? $_POST['draw'] : 1);
		$data['list'] = self::get_customer_bank_account_list([$key_word, $order_column, $order_dir, $page, $limit, 'list', '0']);
		$response['data'] = $data['list'];
		$response['draw'] = $data['draw'];
		$response['recordsTotal'] = self::count_customer_bank_account_list();
		$response['recordsFiltered'] = (!empty($_POST['search']['value']) ? count($data['list']) : $response['recordsTotal']);

		// print_r(json_encode($response));
		return json_encode($response);  // send data as json format
	}

	public function get_customer_bank_account_list( $param ){
		$result = $this->admin_model->get_customer_bank_account_list($param); // get customer bank account list
		$customer_bank_account_list = array();
		if (!empty($result)) {
				$data['recordsFiltered'] = count($result);
				foreach ($result as $k => $v) {
						$tmp_data['customer_bank_account_id'] = encrypt($v['customer_bank_account_id']);
						$tmp_data['customer_bank_account_name'] = $v['customer_bank_account_name'];
						$tmp_data['customer_bank_account_number'] = $v['customer_bank_account_number'];
						$tmp_data['created_by_name'] = $v['created_by_name'];
						$tmp_data['created_date'] = $v['created_date'];
						$tmp_data['updated_by_name'] = $v['updated_by_name'];
						$tmp_data['updated_date'] = $v['updated_date'];
						array_push($customer_bank_account_list, $tmp_data);
				}
		}
		return $customer_bank_account_list;

	}

	public function count_customer_bank_account_list(){
		$total_result = $this->admin_model->get_customer_bank_account_list(['', '', '', '0', '0', 'count_all', '0']); // get request transaction list
		$total_record = 0;
		foreach ($total_result AS $key => $val) {
				$total_record = $val['total_record'];
		}
		return $total_record;
	}

	public function addBankAccount(){
		$data = array();
		if(!$this->input->post()){
			// load form view
			$data = $this->privilege;
			$this->load->view('admin-panel/bank-account-form',$data);
		}else{
			// post data to model
			// step 1 : form validation included check duplicate account_name
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('account_name', 'Account Name', 'trim|required|min_length[4]|max_length[50]|callback_is_customer_bank_account_exist');
			$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required|min_length[4]|max_length[25]|numeric');
			if($this->form_validation->run() == FALSE){
				$data = $this->privilege;
				$this->load->view('admin-panel/bank-account-form',$data);
			}else{
				// step 2 : insert data to DB
				$customer_bank_account_name = $this->input->post('account_name');
				$customer_bank_account_number = $this->input->post('account_number');
				$customer_bank_account_data = [
					'0',
					$customer_bank_account_name,
					$customer_bank_account_number,
					$this->user_id
				];
				$insert_state = $this->admin_model->customer_bank_account_do_save($customer_bank_account_data);
				// step 3 : notification and redirect to list page
				if($insert_state){
					// when success
					redirect('AdminPanel/bankAccount', 'refresh');
				}
			}
		}

	}

	public function updateBankAccount(){
		$data = array();
		#$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		if(!$this->input->post() || !empty($_POST['state'])){
			$customer_bank_account_id = !empty($this->input->post('customer_bank_account_id')) ? decrypt($this->input->post('customer_bank_account_id')) : '' ;
			$data = [
				'',
				'',
				'',
				'',
				'',
				'search_by_id',
				$customer_bank_account_id
			];
			$customer_bank_account = $this->admin_model->get_customer_bank_account($data);
			$data['customer_bank_account'] = $customer_bank_account;
			$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
			$this->load->view('admin-panel/bank-account-form',$data); // load bank-account-form.php with dataset
		}else{
			// step 1 : form validation included check duplicate account_name
			$this->load->helper(array('form', 'url')); // load library form , url
			$this->load->library('form_validation'); // load form validation library
			$this->form_validation->set_rules('account_name', 'Account Name', 'trim|required|min_length[4]|max_length[50]|callback_is_customer_bank_account_exist');
			$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required|min_length[4]|max_length[25]|numeric');
			if ($this->form_validation->run() == FALSE)
			{
				$customer_bank_account_id = !empty($this->input->post('customer_bank_account_id')) ? decrypt($this->input->post('customer_bank_account_id')) : '' ;
				$data = [
					'',
					'',
					'',
					'',
					'',
					'search_by_id',
					$customer_bank_account_id
				];
				$customer_bank_account = $this->admin_model->get_customer_bank_account($data);
				$data['customer_bank_account'] = $customer_bank_account;
				$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
				$this->load->view('admin-panel/bank-account-form',$data); // load bank-account-form.php with dataset
			}else{
				// step 2 : insert data to DB
				$customer_bank_account_id = !empty($this->input->post('customer_bank_account_id')) ? decrypt($this->input->post('customer_bank_account_id')) : '' ;
				$customer_bank_account_name = $this->input->post('account_name');
				$customer_bank_account_number = $this->input->post('account_number');
				$customer_bank_account_data = [
					$customer_bank_account_id,
					$customer_bank_account_name,
					$customer_bank_account_number,
					$this->user_id
				];
				$update_state = $this->admin_model->customer_bank_account_do_save($customer_bank_account_data);
				// step 3 : notification and redirect to list page
				if($update_state){
					// when success
					redirect('AdminPanel/bankAccount', 'refresh');
				}
			}


		}

	}

	public function is_customer_bank_account_exist(){
		$return_val = FALSE;
			$state = $this->admin_model->is_customer_bank_account_exist($this->input->post("account_name"));
			if(!$state){
				$return_val = TRUE;
			}else{
				$this->form_validation->set_message('is_customer_bank_account_exist','Account name already exist');
			}
		return $return_val;
	}

  public function website(){
    $data['division_list'] = $this->admin_model->get_division_list(); // get division list
    $data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
    $data['website_list'] = $this->admin_model->get_website_list('0'); // get website list
    // $data['can_create_request'] = can_create_request($this->user_role); // to check is this user can create the transadmintion request
    $data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
    $this->load->view('admin-panel/website-list',$data); // load profile.php
  }

  public function get_website_list(){
    $division_id = $this->division_id;
    $user_role = $user['user_role'];
    $columns = array(
        // datatable column index  => database column name
        0 => 'transaction_id',
        1 => 'website_name',
        2 => 'transaction_type_name',
    );

    $key_word = (isset($_POST['search']['value']) ? $_POST['search']['value'] : '');
    $page = (isset($_POST['start']) ? $_POST['start'] : 0);
    $limit = (isset($_POST['length']) ? $_POST['length'] : 10);
    $order_column = (isset($_POST['order'][0]['column']) ? $columns[$_POST['order'][0]['column']] : '');
    $order_dir = (isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc');
    $data['draw'] = (isset($_POST['draw']) ? $_POST['draw'] : 1);

    // $division_id = (is_tech_team($division_id) ? '0' : $division_id); // division Super-Tech can see all division records
    $division_id = (is_tech_team($division_id) || is_payment_team($user_role) ? '0' : $division_id); // division Super-Tech and Payment Team can see all division records

    $data['list'] = self::get_request_transaction_list([$key_word, $order_column, $order_dir, $page, $limit, 'list', $division_id, $filter_transaction]);
    $response['data'] = $data['list'];
    $response['draw'] = $data['draw'];
    $response['recordsTotal'] = self::count_request_transaction_list($filter_transaction);
    $response['recordsFiltered'] = (!empty($_POST['search']['value']) ? count($data['list']) : $response['recordsTotal']);

    // print_r(json_encode($response));
    return json_encode($response);  // send data as json format
  }

  public function count_website_list(){

  }

  public function game(){
    $data['division_list'] = $this->admin_model->get_division_list(); // get division list
    $data['user_role_list'] = $this->admin_model->get_user_role_list(); // get user role list
    // $data['can_create_request'] = can_create_request($this->user_role); // to check is this user can create the transadmintion request
    $data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
    $this->load->view('admin-panel/website-list',$data); // load profile.php
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

	public function is_username_exist_except_id(){
		$return_val = FALSE;
			$state = $this->users_model->is_username_exist_except_id([$this->input->post("username"), decrypt($this->input->post("user_id"))]);
			if(!$state){
				$return_val = TRUE;
			}else{
				$this->form_validation->set_message('is_username_exist_except_id','Username already exist');
			}
		return $return_val;
	} // callback_is_username_exist_except_id

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

	function updateAnnouncement(){
		$content = is_array($this->input->post("value")) ? implode(",", $this->input->post("value")) : '';
		$data = [
			$content,
			$this->user_id
		];
		$result = $this->admin_model->update_announcement($data); // do update
		echo json_encode(['state' => $result, 'data' => $content]); // state, message, status,
	}

}
