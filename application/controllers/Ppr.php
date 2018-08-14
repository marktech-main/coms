<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);

class Ppr extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('ppr_model');
	}

	public function index() {
		$template = 'ppr/index';
		$data['filter_data'] = '';	

		$this->load->view($template,$data);

	}

	public function notallowed() {
		$template = 'ppr/notallowed';
		$data['data'] = '';	

		$this->load->view($template,$data);
	}

	public function getAll() {
		$date = 'today';
		$today_list = $this->ppr_model->get_today($date);
	
		if(count($today_list) == 0) {
			$result = '';
		} else {
			$speed = array();
			foreach ($today_list as $item) {
				$speed[] = ($item->time_completed - $item->deduct + $item->add_action);
				$trans[] = $item->total_trans;
			}
			$total_speed = array_sum($speed);
			$total_trans = array_sum($trans);

			$data = array();
			foreach ($today_list as $item) {			
				$list_speed = $this->speed_by_id($item->team_id, $date);
				$sum_speed = array_sum($list_speed);
				$ave_speed = $sum_speed/$item->total_trans;
				$score = $this->score($ave_speed/60, $total_speed/60, $item->total_trans, $total_trans);

				$data[(string)$score][] = array(
					'team_id'	 => $item->team_id,
					'name'		 => $item->name,
					'min_speed'  => gmdate('H:i:s',min($list_speed)),
					'ave_speed'  => gmdate('H:i:s',$ave_speed),
					'max_speed'  => gmdate('H:i:s',max($list_speed)),
					'speed_min'  => round($ave_speed/60,2),
					'total_trans'=> $item->total_trans,
					'score' 	 => $score
				);
				$score_all[] = $score;
			}
			$score_sum = array_sum($score_all);
			krsort($data);
			$limit = 1;
			foreach ($data as $row) {
				foreach ($row as $key => $item) {
					$score_perc = $item['score']/$score_sum*100;
					$item['total_score'] = round($score_perc, 2);
					if ($limit <= 17) {
						$result[] = (object)$item;
						$limit++;
					}else{
						break;
					}
				}
			}
		}
		echo json_encode($result);
	}

	public function top_1stweek() {
		$date = 'week';
		$topweek_list = $this->ppr_model->get_today($date);
		
		$speed = array();
		foreach ($topweek_list as $item) {
			$speed[] = ($item->time_completed - $item->deduct + $item->add_action);
			$trans[] = $item->total_trans;
		}
		$total_speed = array_sum($speed);
		$total_trans = array_sum($trans);

		$data = array();

		foreach ($topweek_list as $item) {			
			$list_speed = $this->speed_by_id($item->team_id, $date);
			$sum_speed = array_sum($list_speed);
			$ave_speed = $sum_speed/$item->total_trans;
			$score = $this->score($ave_speed/60, $total_speed/60, $item->total_trans, $total_trans);

			$data[(string)$score][] = array(
				'team_id'	 => $item->team_id,
				'name'		 => $item->name,
				'total_trans'=> $item->total_trans,
				'score' 	 => $score
			);	
			$score_all[] = $score;
		}
		$score_sum = array_sum($score_all);
		krsort($data);
		$limit = 1;
		foreach ($data as $row) {
			foreach ($row as $key => $item) {
				$score_perc = $item['score']/$score_sum*100;
				$item['total_score'] = round($score_perc, 2);
				if ($limit <= 3) {
					$result[] = (object)$item;
					$limit++;
				}else{
					break;
				}
			}	
		}
		echo json_encode($result);
	}

	public function poor_1stweek() {
		$date = 'week';
		$topweek_list = $this->ppr_model->get_today($date);
		

		$speed = array();
		foreach ($topweek_list as $item) {
			$speed[] = ($item->time_completed - $item->deduct + $item->add_action);
			$trans[] = $item->total_trans;
		}
		$total_speed = array_sum($speed);
		$total_trans = array_sum($trans);

		$data = array();

		foreach ($topweek_list as $item) {			
			$list_speed = $this->speed_by_id($item->team_id, $date);
			$sum_speed = array_sum($list_speed);
			$ave_speed = $sum_speed/$item->total_trans;
			$score = $this->score($ave_speed/60, $total_speed/60, $item->total_trans, $total_trans);

			$data[(string)$score][] = array(
				'team_id'	 => $item->team_id,
				'name'		 => $item->name,
				'total_trans'=> $item->total_trans,
				'score' 	 => $score
			);	
			$score_all[] = $score;
		}
		$score_sum = array_sum($score_all);
		ksort($data);
		$limit = 1;
		foreach ($data as $row) {
			foreach ($row as $key => $item) {
				$score_perc = $item['score']/$score_sum*100;
				$item['total_score'] = round($score_perc, 2);
				if ($limit <= 3) {
					$result[] = (object)$item;
					$limit++;
				}else{
					break;
				}
			}
		}
		echo json_encode($result);
	}

	public function top_lastmonth() {
		$date = 'month';
		$topweek_list = $this->ppr_model->get_today($date);
		// echo "<pre>";
		// print_r($topweek_list);
		// echo "</pre>";
		// exit();

		$speed = array();
		foreach ($topweek_list as $item) {
			$speed[] = ($item->time_completed - $item->deduct + $item->add_action);
			$trans[] = $item->total_trans;
		}
		$total_speed = array_sum($speed);
		$total_trans = array_sum($trans);

		$data = array();

		foreach ($topweek_list as $item) {			
			$list_speed = $this->speed_by_id($item->team_id, $date);
			$sum_speed = array_sum($list_speed);
			$ave_speed = $sum_speed/$item->total_trans;
			$score = $this->score($ave_speed/60, $total_speed/60, $item->total_trans, $total_trans);

			$data[(string)$score][] = array(
				'team_id'	 => $item->team_id,
				'name'		 => $item->name,
				'total_trans'=> $item->total_trans,
				'score' 	 => $score
			);	
			$score_all[] = $score;
		}
		$score_sum = array_sum($score_all);
		krsort($data);
		$limit = 1;
		foreach ($data as $row) {
			foreach ($row as $key => $item) {
				$score_perc = $item['score']/$score_sum*100;
				$item['total_score'] = round($score_perc, 2);
				if ($limit <= 4) {
					$result[] = (object)$item;
					$limit++;
				}else{
					break;
				}
			}
		}
		echo json_encode($result);
	}

	public function poor_lastmonth() {
		$date = 'month';
		$topweek_list = $this->ppr_model->get_today($date);
		

		$speed = array();
		foreach ($topweek_list as $item) {
			$speed[] = ($item->time_completed - $item->deduct + $item->add_action);
			$trans[] = $item->total_trans;
		}
		$total_speed = array_sum($speed);
		$total_trans = array_sum($trans);

		$data = array();

		foreach ($topweek_list as $item) {			
			$list_speed = $this->speed_by_id($item->team_id, $date);
			$sum_speed = array_sum($list_speed);
			$ave_speed = $sum_speed/$item->total_trans;
			$score = $this->score($ave_speed/60, $total_speed/60, $item->total_trans, $total_trans);

			$data[(string)$score][] = array(
				'team_id'	 => $item->team_id,
				'name'		 => $item->name,
				'total_trans'=> $item->total_trans,
				'score' 	 => $score
			);	
			$score_all[] = $score;
		}
		$score_sum = array_sum($score_all);
		ksort($data);
		$limit = 1;
		foreach ($data as $row) {
			foreach ($row as $key => $item) {
				$score_perc = $item['score']/$score_sum*100;
				$item['total_score'] = round($score_perc, 2);
				if ($limit <= 4) {
					$result[] = (object)$item;
					$limit++;
				}else{
					break;
				}
			}
		}
		echo json_encode($result);
	}


	public function speed_by_id($id, $date) {
		$list_speed = array();
		$speed_avg_tran = $this->ppr_model->get_speed_id($id, $date);
		foreach ($speed_avg_tran as $stem) {
			$list_speed[] = $stem->speed;
		}
		return $list_speed;
	}

	public function score($speed_avg, $total_allspeed, $total_trans, $total_alltrans) {
		$score = 0;
		$trans = ($total_trans/$total_alltrans);
		switch ($speed_avg) {
			case ($speed_avg <= 1):
				$score = (5*$total_trans)/$total_allspeed+$trans/2;
				break;
			case ($speed_avg > 1 && $speed_avg <= 2):
				$score = (4*$total_trans)/$total_allspeed+$trans/2;
				break;
			case ($speed_avg > 2 && $speed_avg <= 3):
				$score = (3*$total_trans)/$total_allspeed+$trans/2;
				break;
			case ($speed_avg > 3 && $speed_avg <= 4):
				$score = (2*$total_trans)/$total_allspeed+$trans/2;
				break;
			case ($speed_avg > 4 && $speed_avg <= 5):
				$score = (1*$total_trans)/$total_allspeed+$trans/2;
				break;
			case ($speed_avg > 5 && $speed_avg <= 10):
				$score = (0.75*$total_trans)/$total_allspeed+$trans/2;
				break;
			case ($speed_avg > 10):
				$score = (0.25*$total_trans)/$total_allspeed+$trans/2;
				break;
		}
		return $score;
	}


	public function pre_mon_comparison(){
		$last1month = $this->ppr_model->last1month();
		$last2month = $this->ppr_model->last2month();

		$tot_min1 = $last1month[0]->tot_min - $last1month[0]->deduct + $last1month[0]->add;
		$ave_speed1 = $tot_min1 / $last1month[0]->tot_trans;

		$data1 = array(
			'tot_min' => round($tot_min1/60, 0), 
			'ave_speed' => round($ave_speed1/60, 1),
			'tot_trans' => $last1month[0]->tot_trans
		);

		$tot_min2 = $last2month[0]->tot_min - $last2month[0]->deduct + $last2month[0]->add;
		$ave_speed2 = $tot_min2 / $last2month[0]->tot_trans;

		$data2 = array(
			'tot_min' => round($tot_min2/60, 0), 
			'ave_speed' => round($ave_speed2/60, 1),
			'tot_trans' => $last2month[0]->tot_trans
		);
		
		$alldata = array(
			'last1month' => $data1,
			'last2month' => $data2
		);
		echo json_encode($alldata);
	}

	public function curr_mon_comparison(){
		$currmonth = $this->ppr_model->currmonth();
		$tot_min = ($currmonth[0]->tot_min - $currmonth[0]->deduct) + $currmonth[0]->add;
		$ave_speed = $tot_min / $currmonth[0]->tot_trans;

		if(count($currmonth) == 0) {
			$data = array(
				'tot_min' => 0, 
				'ave_speed' => 0,
				'tot_trans' => 0
			);
		} else {
			$data = array(
				'tot_min' => round($tot_min/60, 0), 
				'ave_speed' => round($ave_speed/60, 1),
				'tot_trans' => $currmonth[0]->tot_trans
			);	
		}
		echo json_encode((object)$data);
	}

	public function get_online_users(){
		$online_users = $this->ppr_model->onine_users();
		echo json_encode($online_users);
	}
}
