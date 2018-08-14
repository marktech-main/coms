<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pprautoreport_model extends CI_Model {

	const __TABLE_NAME = 'transactions';

	public function get_month_data($date, $id) {
		$sql = '';
		$sql .= "SELECT
		u.`id` AS team_id,
		u.`complete_name` AS name,
		ifnull(SUM(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0))),0) as time_completed,
		(
		    SELECT
		    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
		    FROM `transactions` xt
		    LEFT JOIN `ppr_adjustment` ipa ON xt.`id` = ipa.`trans_id`
		    WHERE ipa.`action`='deduct'";
		    if($date == 'month1') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
			} else if ($date == 'month2') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
			} else if ($date == 'month3') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
			} else if ($date == 'month4') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)";		
			}
		$sql .= " AND xt.`status` = 'successful'
		    AND xt.`updated_by` = $id
		) as deduct,
		(
		    SELECT
		    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
		    FROM `transactions` xt
		    LEFT JOIN `ppr_adjustment` ipa ON xt.`id` = ipa.`trans_id`
		    WHERE ipa.`action`='add'";
		    if($date == 'month1') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
			} else if ($date == 'month2') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
			} else if ($date == 'month3') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
			} else if ($date == 'month4') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)";		
			}
		$sql .= " AND xt.`status` = 'successful'
		AND xt.`updated_by` = $id
		) as add_action,
		(
			SELECT
		    COUNT(xt.`id`)
		    FROM `transactions` xt
		    WHERE xt.`updated_by` = u.`id`";
			if($date == 'month1') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
			} else if ($date == 'month2') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
			} else if ($date == 'month3') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
			} else if ($date == 'month4') {
				$sql .= " AND MONTH(xt.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)";		
			}
		$sql .= ") as total_trans
		FROM `users` u
		LEFT JOIN `transactions` it ON u.`id` = it.`updated_by`
		LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`";
		if($date == 'month1') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
		} else if ($date == 'month2') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
		} else if ($date == 'month3') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
		} else if ($date == 'month4') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)";		
		}
		
		$sql .= " AND u.`user_roles_id` IN (5, 7)
		AND u.`id` NOT IN (69, 31, 16, 74, 70)
		AND u.`username` NOT LIKE '%tester%'
		AND u.`username` NOT LIKE '%trial%'
		AND u.`is_active` = 1
		AND u.`is_ppr_payment` = 1
		AND u.`id` = $id
		GROUP BY u.`id`
		";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function get_speed_id($id, $date) {
		$sql = '';
		$sql .= "SELECT
		(
			CASE pa.`action`
	            WHEN 'deduct' THEN
	                TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`)
	            WHEN 'add' THEN
	                TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`)
	        	ELSE 
	        		TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0))
	        END
	    ) as speed
		FROM `users` u
		LEFT JOIN `transactions` it ON u.`id` = it.`updated_by`
		LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`";
		if($date == 'month1') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
		} else if ($date == 'month2') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
		} else if ($date == 'month3') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
		} else if ($date == 'month4') {
			$sql .= " WHERE MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)";		
		}	

		$sql .= " AND u.`user_roles_id` IN (5, 7)
		AND u.`id` NOT IN (69, 31, 16, 74, 70)
		AND u.`username` NOT LIKE '%tester%'
		AND u.`username` NOT LIKE '%trial%'
		AND u.`is_active` = 1
		AND u.`is_ppr_payment` = 1
		AND u.`id` = $id";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function mon_comparison($date) {
		$sql = "";
		$sql .= "SELECT
			SUM(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))) as tot_min,
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='deduct'";
			    if($date == 'month1') {
					$sql .= " AND MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
				} else if ($date == 'month2') {
					$sql .= " AND MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
				} else if ($date == 'month3') {
					$sql .= " AND MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
				}
		$sql .= " AND it.`status` = 'successful'
			) 'deduct',
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='add'";
			    if($date == 'month1') {
					$sql .= " AND MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
				} else if ($date == 'month2') {
					$sql .= " AND MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
				} else if ($date == 'month3') {
					$sql .= " AND MONTH(it.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
				}	
		$sql .= " AND it.`status` = 'successful'
			) 'add',
			COUNT(t.`id`) as tot_trans
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			LEFT JOIN `ppr_adjustment` pa ON t.`id` = pa.`trans_id`";
			if($date == 'month1') {
					$sql .= " WHERE MONTH(t.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";	
				} else if ($date == 'month2') {
					$sql .= " WHERE MONTH(t.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";		
				} else if ($date == 'month3') {
					$sql .= " WHERE MONTH(t.`complete_time`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)";		
				}
		$sql .= "  AND u.`user_roles_id` IN (5, 7)
			AND t.`status` = 'successful'
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1
			AND u.`is_ppr_payment` = 1";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function get_payments() {
		$sql = "SELECT
			u.`id` AS 'id',
			u.`complete_name` AS 'name'
			FROM `users` u
			WHERE u.`user_roles_id` IN (5, 7)
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
			AND u.`username` NOT LIKE '%tester%'
			AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1
			AND u.`is_ppr_payment` = 1
			GROUP BY u.`id`";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		$this->db->last_query();
		return $result;
	}


	/** END TEST */

	

	public function get_payment_total_trans($date) {
		$sql = "SELECT COUNT(it.`id`) as total_trans
			FROM `transactions` it
	        INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
	        WHERE MONTH(it.`complete_time`) = $date
	        AND iu.`user_roles_id` IN (5, 7)
	        AND iu.`id` NOT IN (69, 31, 16, 74, 70)
	        AND iu.`username` NOT LIKE '%tester%'
	        AND iu.`username` NOT LIKE '%trial%'
	        AND iu.`is_active` = 1
	        AND iu.`is_ppr_payment` = 1";
		$query = $this->db->query($sql);
		$result = $query->result();
		$this->db->last_query();
		return $result;
	}

	public function get_payment_total_speed($date) {
		$sql = "SELECT 
			CASE pa.`action`
	            WHEN 'deduct' THEN
	                SUM(ifnull(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`)/60, 0))
	            WHEN 'add' THEN
	                SUM(ifnull(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`)/60, 0))
	        	ELSE 
	        		SUM(ifnull(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0))/60, 0))
	        END as total_speed
	        FROM `transactions` it
	        LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`
	        INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
	        WHERE MONTH(it.`complete_time`) = $date
	        AND iu.`user_roles_id` IN (5, 7)
	        AND iu.`id` NOT IN (69, 31, 16, 74, 70)
	        AND iu.`username` NOT LIKE '%tester%'
	        AND iu.`username` NOT LIKE '%trial%'
	        AND iu.`is_active` = 1
	        AND iu.`is_ppr_payment` = 1";
		$query = $this->db->query($sql);
		$result = $query->result();
		$this->db->last_query();
		return $result;
	}

	public function get_payments_sorted($date, $total_trans, $total_speed) {
		$result = '';
	    $query = $this->db->query('CALL get_payment_list('.$date .','.$total_trans.','.$total_speed.' )');
	    $result = $query->result();
	    $query->next_result();
	    $query->free_result();
		return $result;
	}

	public function filter_speed_based($id, $date, $rank) {
		$sql = "SELECT
				$rank as rank,
				(
			    	SELECT 
			    	(
			    		CASE pa.`action`
				            WHEN 'deduct' THEN
				                AVG(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`))
				            WHEN 'add' THEN
				                AVG(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`))
				        	ELSE 
				        		AVG(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))
				        END
			    	)
			    	FROM `transactions` it
			    	LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`
			    	WHERE it.`updated_by` = $id
			    	AND MONTH(it.`complete_time`) = $date - 1
			    ) AS last_month_speed,
			    SEC_TO_TIME(
			    	ROUND(CASE pa.`action`
			            WHEN 'deduct' THEN
			                MIN(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`))
			            WHEN 'add' THEN
			                MIN(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`))
			        	ELSE 
			        		MIN(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))
			        END, 0)
			    ) as min_speed,
			    SEC_TO_TIME(ROUND(CASE pa.`action`
		            WHEN 'deduct' THEN
		                AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`))
		            WHEN 'add' THEN
		                AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`))
		        	ELSE 
		        		AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))
		        END, 0)) as ave_speed,
			    CASE pa.`action`
		            WHEN 'deduct' THEN
		                AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`))
		            WHEN 'add' THEN
		                AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`))
		        	ELSE 
		        		AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))
		        END as sec_ave_speed,
			    SEC_TO_TIME(ROUND(CASE pa.`action`
			            WHEN 'deduct' THEN
			                MAX(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) - TIME_TO_SEC(pa.`time_adjusted`))
			            WHEN 'add' THEN
			                MAX(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)) + TIME_TO_SEC(pa.`time_adjusted`))
			        	ELSE 
			        		MAX(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))
			        END, 0)) as max_speed,
			    COUNT(t.`id`) as total_trans
			    FROM `transactions` t
			    LEFT JOIN `ppr_adjustment` pa ON t.`id` = pa.`trans_id`
			    INNER JOIN `users` u ON t.`updated_by` = u.`id`
			    WHERE MONTH(t.`complete_time`) = $date
			    AND u.`id` = $id
			    AND u.`is_ppr_payment` = 1
			    GROUP BY t.`updated_by` 
				";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function sum1month($date) {
		$data[] = $date;
		$sql = "SELECT
			ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as ave_speed,
			COUNT(t.`id`) as tot_trans,
			ROUND(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))/60, 1) as tot_min
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			WHERE MONTH(t.`complete_time`) = ?
			AND t.`status` = 'successful'
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
            AND u.`is_active` = 1
		    AND u.`is_ppr_payment` = 1";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	
	}

	public function sum2month($date) {
		$data[] = $date;
		$sql = "SELECT
			ifnull(ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1), 0) as ave_speed,
			COUNT(t.`id`) as tot_trans,
			ifnull(ROUND(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))/60, 1), 0) as tot_min
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			WHERE MONTH(t.`complete_time`) = ?
			AND t.`status` = 'successful'
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
            AND u.`is_active` = 1
		    AND u.`is_ppr_payment` = 1";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	
	}

	public function sum3month($date) {
		$data[] = $date;
		$sql = "SELECT
			ifnull(ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1), 0) as ave_speed,
			COUNT(t.`id`) as tot_trans,
			ifnull(ROUND(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))/60, 1), 0) as tot_min
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			WHERE MONTH(t.`complete_time`) = ?
			AND t.`status` = 'successful'
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
            AND u.`is_active` = 1
		    AND u.`is_ppr_payment` = 1";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	
	}
}