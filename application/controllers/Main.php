<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);
class Main extends CI_Controller {

	function __construct(){
		parent::__construct();
		# $this->load->model('get_data');
		$this->load->model('transactions_model');
		$this->load->model('websites_model');
		$this->load->model('games_model');
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
			# debug code
			// echo '<pre>';
			// print_r($this->session->userdata);
			// echo '</pre>';

			# old query
			// $user = decrypt($this->session->userdata('user_data'));
			// if(is_tech_team($user['division'])){ // if user belong with tech team
			// 	$transaction_list = $this->transactions_model->get_request_transaction_list($user); // get request from all division
			// }else{
			// 	$transaction_list = $this->transactions_model->get_request_transaction_list_by_division($user['division']); // get request from specific division
			// }

			# new queryget_request_transaction_statistic
			// $transaction_list = self::request_transaction_list();
			$request_transaction_statistic = self::get_request_transaction_statistic();
			$data['active_menu'] = 'dashboard';
			$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
			// $data['can_create_request'] = can_create_request($this->user_role);
			// $data['can_view_report'] = can_view_report($this->user_role);
			$data['request_transaction_statistic'] = $request_transaction_statistic;
			$data['transaction_type'] = (object)[
				'deposit' => encrypt(1),
				'withdraw' => encrypt(2),
				'transfer' => encrypt(3),
				'new_register' => encrypt(4)
			];
			$user = decrypt($this->session->userdata('user_data'));
			$username = $user["username"];
			$user_role = $user['user_role'];
			echo "<script language='javascript' type='text/javascript'>";
			echo "localStorage.setItem('username', '".$username."');";
			echo "localStorage.setItem('role', '".$user_role."')";
			echo "</script>";
			$this->load->view('index', $data); // load index.php with dataset
	}

	// create common function for verify all privilege by session (object)

	public function get_json_transaction_list($value='')
	{
		// CANCELLED and not empty
		$filter_transaction = '';
		switch ($_POST['filter_transaction']) {
			case '': // to select all
				$filter_transaction = '';
				break;
			case 'QUEUE':
				$filter_transaction = 'QUEUE';
				break;
			case 'PROCESSING':
				$filter_transaction = 'PROCESSING';
				break;
			case 'PENDING':
				$filter_transaction = 'PENDING';
				break;
			case 'CANCELLED':
				$filter_transaction = 'CANCELLED';
				break;
			case 'SUCCESSFUL':
				$filter_transaction = 'SUCCESSFUL';
				break;
			default:
				$filter_transaction = decrypt($_POST['filter_transaction']);
				break;
		}

		$transaction_list = self::request_transaction_list($filter_transaction);
		
		echo $transaction_list;
	}

	public function request_transaction_list($filter_transaction = ''){
			// reAuth::user_auth([1,2,3,4,5,6]);
			// sessionHelper::update_last_activity();

			$user = decrypt($this->session->userdata('user_data'));
			$division_id = $user['division'];
			$user_role = $user['user_role'];
			$columns = array(
					// datatable column index  => database column name
					0 => 'transaction_id',
					1 => 'website_id',
					2 => 'transaction_type_id',
					3 => 'customer_id',
					4 => 'amount',
					5 => 'status',
					6 => 'created_by',
					7 => 'request_time',
					8 => 'updated_by',
					9 => 'process_time',
					10 => 'complete_time',
					11 => 'come_from'
			);

			$key_word = (isset($_POST['search']['value']) ? $_POST['search']['value'] : '');
			$page = (isset($_POST['start']) ? $_POST['start'] : 0);
			$limit = (isset($_POST['length']) ? $_POST['length'] : 10);
			$order_column = (isset($_POST['order'][0]['column']) ? $columns[$_POST['order'][0]['column']] : '');
			$order_dir = (isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc');
			$data['draw'] = (isset($_POST['draw']) ? $_POST['draw'] : 1);
			$division_filter = ( ( isset($_POST['filter_division']) && !empty($_POST['filter_division']) ) ? decrypt($_POST['filter_division']) : '0' );
			// $division_id = (is_tech_team($division_id) ? '0' : $division_id); // division Super-Tech can see all division records
			$division_id = (is_tech_team($division_id) || is_payment_team($user_role) ? '0' : $division_id); // division Super-Tech and Payment Team can see all division records

			if(!is_cs_team($user_role)){
				$division_id = $division_filter;
			}

			$filter_uuid = ( (isset($_POST['filter_uuid']) && !empty($_POST['filter_uuid']) ) ? $_POST['filter_uuid'] : '' );

			$data['list'] = self::get_request_transaction_list(['key_word' => $key_word, 'order_column' => $order_column, 'order_dir' => $order_dir, 'page' => $page, 'limit' => $limit, 'fnc_type' => 'list', 'filter_division' => $division_id, 'filter_transaction' => $filter_transaction, 'filter_uuid' => $filter_uuid]);
			$response['data'] = $data['list'];
			$response['draw'] = $data['draw'];
			$response['recordsTotal'] = self::count_request_transaction_list($filter_transaction);
			$response['recordsFiltered'] = (!empty($_POST['search']['value']) ? count($data['list']) : $response['recordsTotal']);

			// print_r(json_encode($response));
			return json_encode($response);  // send data as json format
	}

	public function get_request_transaction_list( $param ){
			// $result = requestTransaction_model::request_transaction_list($param);
			$result = $this->transactions_model->get_request_transaction_list($param); // get request transaction list
			$request_transaction_list = array();

            $user = decrypt($this->session->userdata('user_data'));
            $division_id = $user['division'];
            $user_role = $user['user_role'];

			if (!empty($result)) {
					$data['recordsFiltered'] = count($result);
					foreach ($result as $k => $v) {
							$tmp_data['transaction_id'] = encrypt($v['transaction_id']);
							$tmp_data['transaction_code'] = $v['transaction_id'];
							#$tmp_data['transaction_type_name'] = $v['transaction_type_name'];
							$tmp_data['transaction_type_name'] = get_transaction_type_name_by_id($v['transaction_type_id']);
							#$tmp_data['website_name'] = $v['website_name'];
                            $tmp_data['website_name'] = get_website_name_by_id($v['website_id']);
							$tmp_data['customer_id'] = $v['customer_id'];
							// $tmp_data['customer_username'] = $v['customer_username'];
							$tmp_data['priority'] = $v['priority'];
							$tmp_data['amount'] = $v['amount'];
							$tmp_data['status'] = $v['status'];
							$tmp_data['created_by'] = $v['created_by'];
							$tmp_data['created_by_name'] = $v['created_by_name'];
							$tmp_data['request_time'] = $v['request_time'];
							$tmp_data['updated_by'] = $v['updated_by'];
							$tmp_data['updated_by_name'] = $v['updated_by_name'];
							$tmp_data['process_time'] = $v['process_time'];
							$tmp_data['complete_time'] = $v['complete_time'];
							$tmp_data['come_from'] = $v['come_from'];
							$tmp_data['DT_RowClass'] = get_division_name_by_website_id($v['website_id']).' '.$v['status'].' '.$v['website_id'];

                            // exception supervisor and tech
							if(is_supervisor($user_role) == TRUE || is_administrator($user_role) == TRUE || is_tech_team($user_role) == TRUE || is_payment_team($user_role) == TRUE) {
                                array_push($request_transaction_list, $tmp_data);
                            }else {
                                if (get_division_name_by_id($division_id)  == get_division_name_by_website_id($v['website_id']) ){
                                    array_push($request_transaction_list, $tmp_data);
                                }
                            }


					}
			}
			return $request_transaction_list;
	}

	public function count_request_transaction_list($filter_transaction = ''){
			$user 				= decrypt($this->session->userdata('user_data'));
			$division_id  = $user['division'];
			$user_role = $user['user_role'];

			$division_filter = ( ( isset($_POST['filter_division']) && !empty($_POST['filter_division']) ) ? decrypt($_POST['filter_division']) : '0' );
			// $division_id = (is_tech_team($division_id) ? '0' : $division_id); // division Super-Tech can see all division records
			$division_id = (is_tech_team($division_id) || is_payment_team($user_role) ? '0' : $division_id); // division Super-Tech and Payment Team can see all division records

			if(!is_cs_team($user_role)){
				$division_id = $division_filter;
			}

			$filter_uuid = ( (isset($_POST['filter_uuid']) && !empty($_POST['filter_uuid']) ) ? $_POST['filter_uuid'] : '' );
			$total_result = $this->transactions_model->get_request_transaction_list(['key_word' => '', 'order_column' => '', 'order_dir' => '', 'page' => '0', 'limit' => '0', 'fnc_type' => 'count_all', 'filter_division' => $division_id, 'filter_transaction' => $filter_transaction, 'filter_uuid' => $filter_uuid]); // get request transaction list
			$total_record = 0;
			foreach ($total_result AS $key => $val) {
					$total_record = $val['total_record'];
			}
			return $total_record;
	}

	public function get_request_transaction_statistic()
	{
		$user 				= decrypt($this->session->userdata('user_data'));
		$division_id  = $user['division'];
		$user_role 		= $user['user_role'];
		$filter_uuid = ( (isset($_POST['uuid']) && !empty($_POST['uuid']) ) ? $_POST['uuid'] : '' );
		// $division_id = (is_tech_team($division_id) ? '0' : $division_id); // division Super-Tech can see all division records
		$division_id = (is_tech_team($division_id) || is_payment_team($user_role) ? '0' : $division_id); // division Super-Tech and Payment Team can see all division records
		if(is_cs_team($user_role)){
			$statistic_result = $this->transactions_model->get_cs_request_transaction_statistic(['filter_division' => $division_id,'filter_uuid' => $filter_uuid]); // get request transaction statistic
		}else{
			$statistic_result = $this->transactions_model->get_request_transaction_statistic(['filter_division' => $division_id,'filter_uuid' => $filter_uuid]); // get request transaction statistic
		}
		return $statistic_result;
	}

	public function get_division_filter(){
		$division_list = $this->transactions_model->get_division_list();
		$html = '';
		foreach ($division_list as $k => $v) {
			if($v['division_name'] != 'Super-Tech' ){
				$html .= '<option value="'.encrypt($v['division_id']).'">'.$v['division_name'].'</option>';
			}
		}
		echo $html;
	}

}
