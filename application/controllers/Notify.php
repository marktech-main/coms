<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);
class Notify extends CI_Controller {

	function __construct(){
		parent::__construct();
		# $this->load->model('get_data');
		$this->load->model('transactions_model');
    $this->load->model('notify_model');
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

	public function index(){
		$notify_message_list = $this->notify_model->get_latest_notify_message($this->user_id); // get notification messsage list
		$data['notify_message_list'] = $notify_message_list;
		// $data['can_create_request'] = can_create_request($this->user_role);
		$data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
		$this->load->view('notifications',$data); // load page notifications with data
	}

  public function get_total_unread_notify_message(){
    $result = $this->notify_model->get_total_unread_notify_message($this->user_id);
    echo json_encode(['total_record' => $result]); // state, message, status,
  }

  public function get_latest_notify_message(){
		$limit = (isset($_POST['limit']) ? $_POST['limit'] : 5);
		$page = isset($_POST['page']) ? $_POST['page'] * $limit : 0;
		$data = [
			$this->user_id,
			$page,
			$limit
		];
		$result = $this->notify_model->get_latest_notify_message($data);
		$state = FALSE;
		$html = '';
		if(!empty($result)){
			$state = TRUE;
			foreach ($result as $notify) {
				$notify_id = $notify['id'];
				$transaction_id = $notify['transaction_id'];
				$content = $notify['content'];
				$content_status = $notify['content_status'];
				$notify_state = $notify['state'];
				$timestamp = $notify['timestamp'];
				$html .= '<li '.(!$notify_state ? "class='notify-msg unread-notify'" : "class='notify-msg'").' data-transaction-id="'.encrypt($transaction_id).'" data-notify-id="'.$notify_id.'" action="notify">';
				$html .= '<a style="display: flex;">';
				$html .= '<div>';
				# SUCCESS - fa fa-check-square-o
				# ERROR - fa fa-times-circle
				# WARN - fa fa-exclamation-triangle
				# INFO - fa fa-info-circle
				switch ($content_status) {
					case 'SUCCESS':
						$html .= '<i class="fa fa-check-square-o"></i>';
						break;
					case 'ERROR':
						$html .= '<i class="fa fa-times-circle"></i>';
						break;
					case 'WARN':
						$html .= '<i class="fa fa-exclamation-triangle"></i>';
						break;
					case 'INFO':
						$html .= '<i class="fa fa-info-circle"></i>';
						break;
					default:
						$html .= '<i class="fa fa-info-circle"></i>';
						break;
				}
				$html .= ' '. $content .' ';
				$html .= '<span class="pull-right text-muted small">';
				$html .= '<time class="timeago" datetime="'.$timestamp.'">'.$timestamp.'</time>';
				$html .= '</span>';
				$html .= '</div>';
				$html .= '</a>';
				$html .= '</li>';
				$html .= '<li class="divider"></li>';
			}

			// See All Alerts
			// $html .= '<li>';
			// $html .= '<a href="notifications" class="text-center">';
			// $html .= '<strong>See All Alerts</strong>';
			// $html .= '</a>';
			if(sizeof($result) > 4){
				$html .= '<div class="loading-info"><img src="'.base_url().'images/ajax-loader.gif" /></div>';
			}
			// $html .= '</li>';
		}else{
			// $html .= '<li>';
			// $html .= '<a class="text-center nodata">';
			// $html .= '<strong>NO DATA</strong>';
			// $html .= '</a>';
			// $html .= '</li>';
			$html .= '<div style="text-align:center;"><strong>NO DATA</strong></div>';
		}
		echo $html;
		// echo json_encode(['state' => $state, 'message' => $html]); // state, message, status,
  }

	public function get_notify_message(){
		// print_r($_POST);
		$limit = (isset($_POST['limit']) ? $_POST['limit'] : 5);
		$page = isset($_POST['page']) ? $_POST['page'] * $limit : 0;
		$data = [
			$this->user_id,
			$page,
			$limit
		];
		$result = $this->notify_model->get_latest_notify_message($data);
		$state = FALSE;
		$html = '';
		if(!empty($result)){
			$state = TRUE;
			foreach ($result as $notify) {
				$notify_id = $notify['id'];
				$transaction_id = $notify['transaction_id'];
				$content = $notify['content'];
				$content_status = $notify['content_status'];
				$notify_state = $notify['state'];
				$timestamp = $notify['timestamp'];
				$html .= '<li '.(!$notify_state ? "class='notify-msg unread-notify'" : "class='notify-msg'").' data-transaction-id="'.encrypt($transaction_id).'" data-notify-id="'.$notify_id.'" action="notify">';
				$html .= '<a style="display: flex;">';
				$html .= '<div>';
				# SUCCESS - fa fa-check-square-o
				# ERROR - fa fa-times-circle
				# WARN - fa fa-exclamation-triangle
				# INFO - fa fa-info-circle
				switch ($content_status) {
					case 'SUCCESS':
						$html .= '<i class="fa fa-check-square-o"></i>';
						break;
					case 'ERROR':
						$html .= '<i class="fa fa-times-circle"></i>';
						break;
					case 'WARN':
						$html .= '<i class="fa fa-exclamation-triangle"></i>';
						break;
					case 'INFO':
						$html .= '<i class="fa fa-info-circle"></i>';
						break;
					default:
						$html .= '<i class="fa fa-info-circle"></i>';
						break;
				}
				$html .= ' '. $content .' ';
				$html .= '<span class="pull-right text-muted small">';
				$html .= '<time class="timeago" datetime="'.$timestamp.'">'.$timestamp.'</time>';
				$html .= '</span>';
				$html .= '</div>';
				$html .= '</a>';
				$html .= '</li>';
				$html .= '<li class="divider"></li>';
			}
		}
		echo $html;
		// echo json_encode(['state' => $state, 'message' => $html]); // state, message, status,
	}

	public function mark_all_notify_as_read(){
		$state = $this->notify_model->mark_all_notify_as_read($this->user_id);
		echo json_encode(['state' => $state, 'message' => 'mark all notify as read']); // state, message, status,
	}

}
