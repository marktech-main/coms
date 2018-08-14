<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *', false);

class PprAutoReport extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('pprautoreport_model');
	}

	public function index() {
		$month1 = $this->getmonth1('month1');
		$month2 = $this->getmonth1('month2');
		$month3 = $this->getmonth1('month3');
		$month4 = $this->getmonth1('month4');

		$sum1month = $this->mon_comparison('month1');
		$sum2month = $this->mon_comparison('month2');
		$sum3month = $this->mon_comparison('month3');

		$result = (object)array(
			'm1' => $month1,
			'm2' => $month2,
			'm3' => $month3,
			'm4' => $month4,
			'sum1month' => $sum1month,
			'sum2month' => $sum2month,
			'sum3month' => $sum3month
		);

		$data = array(
			'result' => $result 
		);

		$this->load->view('ppr/autoReport' , $data);
	}

	public function mon_comparison($month){
		$com_data = $this->pprautoreport_model->mon_comparison($month);

		$tot_min = $com_data[0]->tot_min - $com_data[0]->deduct + $com_data[0]->add;
		if($tot_min != 0) {
			$ave_speed = $tot_min / $com_data[0]->tot_trans;	
		} else {
			$ave_speed = 0;
		}		

		$data = array(
			'tot_min' => round($tot_min/60,0), 
			'ave_speed' => round($ave_speed/60, 1),
			'tot_trans' => $com_data[0]->tot_trans
		);

		return (object)$data;
	}

	public function getmonth1($month) {
		$payment_list = $this->pprautoreport_model->get_payments();
		foreach ($payment_list as $item) {
			$monthlist = $this->pprautoreport_model->get_month_data($month, $item->id);
			$plist = (object)array(
				'team_id' => $item->id, 
				'name'    => $item->name,
				'time_completed' => 0,
				'deduct' => 0,
				'add_action' => 0,
				'total_trans' => 0
			);
			$month_list[] = (count($monthlist) == 0) ? $plist : $monthlist[0];
		}

		if(count($month_list) == 0) {
			$result = '';
		} else {
			foreach ($month_list as $item) {
				$speed[] = ($item->time_completed - $item->deduct + $item->add_action);
				$trans[] = $item->total_trans;
			}
			$total_speed = array_sum($speed);
			$total_trans = array_sum($trans);
			
			foreach ($month_list as $item) {			
				$list_speed = $this->speed_by_id($item->team_id, $month);
				$sum_speed = array_sum($list_speed);
				if($item->total_trans == 0) {
					$ave_speed = 0;
					$min_speed = 0;
					$max_speed = 0;
				} else {
					$ave_speed = $sum_speed/$item->total_trans;
					$min_speed = min($list_speed);
					$max_speed = max($list_speed);
				}

				if($item->total_trans != 0) {
					$score = $this->score($ave_speed/60, $total_speed/60, $item->total_trans, $total_trans);
				} else {
					$score = 0;
				}
				
				$data[(string)$score][] = array(
					'team_id'	 => $item->team_id,
					'name'		 => $item->name,
					'min_speed'  => gmdate('H:i:s',($min_speed<0)?0:$min_speed),
					'ave_speed'  => gmdate('H:i:s',$ave_speed),
					'sec_ave_speed'  => $ave_speed,
					'max_speed'  => gmdate('H:i:s',$max_speed),
					'speed_min'  => round($ave_speed/60,2),
					'total_trans'=> $item->total_trans,
					'score' 	 => $score
				);
				$score_all[] = $score;
			}
			$score_sum = array_sum($score_all);
			krsort($data);
			$rank = 1;
			foreach ($data as $row) {
				foreach ($row as $key => $item) {
					if(!empty($score_sum)) {
						$score_perc = $item['score']/$score_sum*100;
					} else {
						$score_perc = 0;
					}					
					$item['total_score'] = round($score_perc, 2);
					$item['rank'] = $rank;
					$result[$item['team_id']] = (object)$item;
					$rank++;

				}
			}
		}
		return $result;
	}

	public function speed_by_id($id, $date) {
		$list_speed = array();
		$speed_avg_tran = $this->pprautoreport_model->get_speed_id($id, $date);
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

	public function get_payment_list($date) {
		$get_total_trans = $this->pprautoreport_model->get_payment_total_trans($date);
		$get_total_speed = $this->pprautoreport_model->get_payment_total_speed($date);

		$total_trans = ($get_total_trans[0]->total_trans == '') ? 0 : $get_total_trans[0]->total_trans;
		$total_speed = ($get_total_speed[0]->total_speed == '') ? 0 : $get_total_speed[0]->total_speed;

		$payment_list = $this->pprautoreport_model->get_payments_sorted($date, $total_trans, $total_speed);
		return $payment_list;
	}

	public function generate_excel() {
		$month = date( 'm-Y', strtotime( 'last month' ) );
		$start_row = 5;
		$daters = date('Y-m');
		$now = new DateTime($daters);
		$date_1 = $now->sub(new DateInterval('P1M'))->format('n');
		$date_2 = $now->sub(new DateInterval('P1M'))->format('n');
		$date_3 = $now->sub(new DateInterval('P1M'))->format('n');

		$daters_f = date('Y-m');
		$now_f = new DateTime($daters_f);
		$fdate_1 = $now_f->sub(new DateInterval('P1M'))->format('F');
		$fdate_2 = $now_f->sub(new DateInterval('P1M'))->format('F');
		$fdate_3 = $now_f->sub(new DateInterval('P1M'))->format('F');

		/** Each monthly data */
		$m1 = $this->getmonth1('month1');
		$m2 = $this->getmonth1('month2');
		$m3 = $this->getmonth1('month3');
		$m4 = $this->getmonth1('month4');

		/** Montly Comparison */
		$sum1month = $this->mon_comparison('month1');
		$sum2month = $this->mon_comparison('month2');
		$sum3month = $this->mon_comparison('month3');

		$this->load->library('excel');

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("PPR")
                                 ->setLastModifiedBy("PPR")
                                 ->setTitle("PPR Monthly Report");

		$objPHPExcel->getActiveSheet()->setTitle('PPR report for '.$fdate_1);

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getDefaultStyle()
                            ->getAlignment()
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

       	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(14);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);

	    $objPHPExcel->setActiveSheetIndex(0)
	    	->mergeCells('C1:F1') // date
	    	->mergeCells('G1:J1') // date
	    	->mergeCells('K1:N1') // date
	    	->mergeCells('C2:F2') // summary
	    	->mergeCells('G2:J2') // summary
	    	->mergeCells('K2:N2') // summary
	    	->mergeCells('C3:F3') // summary
	    	->mergeCells('G3:J3') // summary
	    	->mergeCells('K3:N3'); // summary

		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C1', $fdate_1)
				->setCellValue('G1', $fdate_2)
				->setCellValue('K1', $fdate_3)
				->setCellValue('C2', $sum1month->tot_min.' min /'.$sum1month->tot_trans.' transaction')
				->setCellValue('C3', 'average '.$sum1month->ave_speed.' min / transaction')
				->setCellValue('G2', $sum2month->tot_min.' min /'.$sum2month->tot_trans.' transaction')
				->setCellValue('G3', 'average '.$sum2month->ave_speed.' min / transaction')
				->setCellValue('K2', $sum3month->tot_min.' min /'.$sum3month->tot_trans.' transaction')
				->setCellValue('K3', 'average '.$sum3month->ave_speed.' min / transaction')
				->setCellValue('A4', 'Ranking')
                ->setCellValue('B4', 'Names')
                ->setCellValue('C4', 'Min')
                ->setCellValue('D4', 'Avg')
                ->setCellValue('E4', 'Max')
                ->setCellValue('F4', 'Transaction')
                ->setCellValue('G4', 'Min')
                ->setCellValue('H4', 'Avg')
                ->setCellValue('I4', 'Max')
                ->setCellValue('J4', 'Transaction')
                ->setCellValue('K4', 'Min')
                ->setCellValue('L4', 'Avg')
                ->setCellValue('M4', 'Max')
                ->setCellValue('N4', 'Transaction')
                ->setCellValue('Q1', 'Rank color') //legend
                ->setCellValue('Q2', 'Maintained top') //legend
                ->setCellValue('Q3', 'Maintained mid') //legend
                ->setCellValue('Q4', 'Maintained poor') //legend
                ->setCellValue('S1', 'Speed comparison') //legend
                ->setCellValue('S2', 'Faster') //legend
                ->setCellValue('S3', 'Slower') //legend
                ->setCellValue('S4', 'Same') //legend
                ->setCellValue('T1', 'Ranking Format') //legend
                ->setCellValue('T2', $fdate_1.' | '.$fdate_2.' | '.$fdate_3); //legend

	    foreach($m1 as $item){
        	$rank1 = $item->rank;
        	$rank2 = $m2[$item->team_id]->rank;
        	$rank3 = $m3[$item->team_id]->rank;

    		if($rank1 >= 1 && $rank1 <= 4 && $rank2 >= 1 && $rank2 <= 4 && $rank3 >= 1 && $rank3 <= 4) {
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank1 >= 5 && $rank1 <= 15 && $rank2 >= 5 && $rank2 <= 15 && $rank3 >= 5 && $rank3 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank1 >= 10 && $rank2 >= 10 && $rank3 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank1 == '-' && $rank2 >= 1 && $rank2 <= 4 && $rank3 >= 1 && $rank3 <= 4){
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank2 == '-' && $rank1 >= 1 && $rank1 <= 4 && $rank3 >= 1 && $rank3 <= 4){
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank3 == '-' && $rank1 >= 1 && $rank1 <= 4 && $rank2 >= 1 && $rank2 <= 4){
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank1 == '-' && $rank2 >= 5 && $rank2 <= 15 && $rank3 >= 5 && $rank3 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank2 == '-' && $rank1 >= 5 && $rank1 <= 15 && $rank3 >= 5 && $rank3 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank3 == '-' && $rank1 >= 5 && $rank1 <= 15 && $rank2 >= 5 && $rank2 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank1 == '-' && $rank2 >= 10 && $rank3 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank2 == '-' && $rank1 >= 10 && $rank3 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank3 == '-' && $rank1 >= 10 && $rank2 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank1 == '-' && $rank2 == '-' && $rank3 >= 1 && $rank3 <= 4){
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank2 == '-' && $rank3 == '-' && $rank1 >= 1 && $rank1 <= 4){
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank3 == '-' && $rank1 == '-' && $rank2 >= 1 && $rank2 <= 4){
    			$rank_color = '5cb85c'; // sucess
    		} else if($rank1 == '-' && $rank2 == '-' && $rank3 >= 5 && $rank3 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank2 == '-' && $rank3 == '-' && $rank1 >= 5 && $rank1 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank3 == '-' && $rank1 == '-' && $rank2 >= 5 && $rank2 <= 15){
    			$rank_color = '337ab7'; // mid
    		} else if($rank1 == '-' && $rank2 == '-' && $rank3 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank2 == '-' && $rank3 == '-' && $rank1 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank3 == '-' && $rank1 == '-' && $rank2 >= 10){
    			$rank_color = 'd9534f'; //poor
    		} else if($rank1 == '-' && $rank2 == '-' && $rank3 == '-'){
    			$rank_color = 'd9534f'; //poor
    		} else {
    			$rank_color = 'FFFFFF'; //empty
    		}

    		$ranking = $rank1.' | '.$rank2.' | '.$rank3;

    		/* Month 1 */
    		if($item->score != 0 ) {
		    	if ($item->sec_ave_speed < $m2[$item->team_id]->sec_ave_speed) {
				    $m1color = '5cb85c';
				} else if ($item->sec_ave_speed > $m2[$item->team_id]->sec_ave_speed) {
					$m1color = 'd9534f';
				} else if ($item->sec_ave_speed == $m2[$item->team_id]->sec_ave_speed) {
					$m1color = '337ab7';
				}
    		} else { $m1color = 'FFFFFF'; }

			/* Month 2 */
			if($m2[$item->team_id]->score != 0 ) {
				if ($m2[$item->team_id]->sec_ave_speed < $m3[$item->team_id]->sec_ave_speed) {
					$m2color = '5cb85c';
				} else if ($m2[$item->team_id]->sec_ave_speed > $m3[$item->team_id]->sec_ave_speed) {
					$m2color = 'd9534f';
				} else if ($m2[$item->team_id]->sec_ave_speed == $m3[$item->team_id]->sec_ave_speed) {
					$m2color = '337ab7';
				}
			} else { $m2color = 'FFFFFF'; }
			
			/* Month 3 */
			if($m3[$item->team_id]->score != 0 ) {
				if ($m3[$item->team_id]->sec_ave_speed < $m4[$item->team_id]->sec_ave_speed) {
					$m3color = '5cb85c';
				} else if ($m3[$item->team_id]->sec_ave_speed > $m4[$item->team_id]->sec_ave_speed) {
					$m3color = 'd9534f';
				} else if ($m3[$item->team_id]->sec_ave_speed == $m4[$item->team_id]->sec_ave_speed) {
					$m3color = '337ab7';
				}
			} else { $m3color = 'FFFFFF'; }

			//background color dynamic
			$objPHPExcel->getActiveSheet()
			    ->getStyle('D'.$start_row)
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB($m1color);
			$objPHPExcel->getActiveSheet()
			    ->getStyle('H'.$start_row)
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB($m2color);
			$objPHPExcel->getActiveSheet()
			    ->getStyle('L'.$start_row)
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB($m3color);
			$objPHPExcel->getActiveSheet()
			    ->getStyle('A'.$start_row)
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB($rank_color);
			//background color static
			$objPHPExcel->getActiveSheet()
			    ->getStyle('R2')
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('5cb85c');
			$objPHPExcel->getActiveSheet()
			    ->getStyle('R3')
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('d9534f');
			$objPHPExcel->getActiveSheet()
			    ->getStyle('R4')
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('337ab7');

			$objPHPExcel->getActiveSheet()
			    ->getStyle('P2')
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('5cb85c');
			$objPHPExcel->getActiveSheet()
			    ->getStyle('P3')
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('337ab7');
			$objPHPExcel->getActiveSheet()
			    ->getStyle('P4')
			    ->getFill()
			    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			    ->getStartColor()
			    ->setARGB('d9534f');
			//font size
			$objPHPExcel->getActiveSheet()
				->getStyle("P1:t4")
				->getFont()->setSize(10);
			// allignment
	        $objPHPExcel->getActiveSheet()
	        	->getStyle('B'.$start_row)
	            ->getAlignment()
	            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	        $objPHPExcel->getActiveSheet()
	            ->getStyle('B4')
	            ->getAlignment()
	            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	        //bold
	        $objPHPExcel->getActiveSheet()
	            ->getStyle('A1:T1')
	            ->getFont()
	            ->setBold(true);
	        $objPHPExcel->getActiveSheet()
	            ->getStyle('A4:N4')
	            ->getFont()
	            ->setBold(true);
	        //freezepane
	        $objPHPExcel->getActiveSheet()
	            ->freezePane('O5');

	        //cell value
	        $objPHPExcel->setActiveSheetIndex(0)
	        			->setCellValue('A'.$start_row, $ranking)
	                    ->setCellValue('B'.$start_row, ucwords(strtolower($item->name)))
	                    ->setCellValue('C'.$start_row, ($item->score != 0) ? $item->min_speed : '--')
	                    ->setCellValue('D'.$start_row, ($item->score != 0) ? $item->ave_speed : '--')
	                    ->setCellValue('E'.$start_row, ($item->score != 0) ? $item->max_speed : '--')
	                    ->setCellValue('F'.$start_row, ($item->score != 0) ? $item->total_trans : '--')
	                    ->setCellValue('G'.$start_row, ($m2[$item->team_id]->score != 0) ? $m2[$item->team_id]->min_speed : '--')
	                    ->setCellValue('H'.$start_row, ($m2[$item->team_id]->score != 0) ? $m2[$item->team_id]->ave_speed : '--')
	                    ->setCellValue('I'.$start_row, ($m2[$item->team_id]->score != 0) ? $m2[$item->team_id]->max_speed : '--')
	                    ->setCellValue('J'.$start_row, ($m2[$item->team_id]->score != 0) ? $m2[$item->team_id]->total_trans : '--')
	                    ->setCellValue('K'.$start_row, ($m3[$item->team_id]->score != 0) ? $m3[$item->team_id]->min_speed : '--')
	                    ->setCellValue('L'.$start_row, ($m3[$item->team_id]->score != 0) ? $m3[$item->team_id]->ave_speed : '--')
	                    ->setCellValue('M'.$start_row, ($m3[$item->team_id]->score != 0) ? $m3[$item->team_id]->max_speed : '--')
	                    ->setCellValue('N'.$start_row, ($m3[$item->team_id]->score != 0) ? $m3[$item->team_id]->total_trans : '--');
	        $start_row++;
	    }
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  

	    $filename='PPR-report- '.$month.'.xlsx';

	    header('Content-Type: application/vnd.ms-excel'); 
	    header('Content-Disposition: attachment;filename="'.$filename.'"'); 
	    header('Cache-Control: max-age=0');
	    
	    $objWriter->save('php://output');
	    exit;

	}

	public function send_monthly_report() {
		$smtp_host = 'localhost';
	    $smtp_port = '25';
	    $smtp_user = 'marktech.uni@gmail.com';
	    $smtp_pass = 'asxz4521';
	    $send_from = 'marktech.uni@gmail.com';
	    $send_to = 'bekikemerut@gmail.com';
	    $send_tocc = 'lou.dulguime@gmail.com';
	    $ishtml = true;
	    $subject = 'subject';
	    $body = 'body';
	    $altbody = 'altbody';


		$postfields = array('field1'=>'value1', 'field2'=>'value2');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://foo.com');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		// Edit: prior variable $postFields should be $postfields;
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
		$result = curl_exec($ch);
		print_r($result);
	}
}
