<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppr_model extends CI_Model {

	const __TABLE_NAME = 'report';

	public function get_today($date) {
		$sql = '';
		$sql .= "SELECT
		u.`id` AS team_id,
		u.`complete_name` AS name,
		SUM(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0))) as time_completed,
		(
		    SELECT
		    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
		    FROM `transactions` xt
		    LEFT JOIN `ppr_adjustment` ipa ON xt.`id` = ipa.`trans_id`
		    WHERE ipa.`action`='deduct'
		    AND xt.`updated_by` = team_id";
		    if($date == 'today') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";	
			} else if ($date == 'week') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d')
	                        BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
	                        AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)";		
			} else if ($date = 'month') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d') 
			            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
			            	AND LAST_DAY(now() - interval 1 month )";		
			}
		$sql .= " AND xt.`status` = 'successful'
		) as deduct,
		(
		    SELECT
		    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
		    FROM `transactions` xt
		    LEFT JOIN `ppr_adjustment` ipa ON xt.`id` = ipa.`trans_id`
		    WHERE ipa.`action`='add'
		    AND xt.`updated_by` = team_id";
		    if($date == 'today') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";	
			} else if ($date == 'week') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d')
	                        BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
	                        AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)";		
			} else if ($date = 'month') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d') 
			            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
			            	AND LAST_DAY(now() - interval 1 month )";		
			}
		$sql .= " AND xt.`status` = 'successful'
		) as add_action,
		(
			SELECT
		    COUNT(xt.`id`)
		    FROM `transactions` xt
			LEFT JOIN `users` iu ON xt.`updated_by` = iu.`id`    
		    WHERE iu.`id` = u.`id`
			AND xt.`status` = 'successful'";
			if($date == 'today') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";	
			} else if ($date == 'week') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d')
	                        BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
	                        AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)";		
			} else if ($date = 'month') {
				$sql .= " AND DATE_FORMAT(xt.`complete_time`, '%Y-%m-%d') 
			            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
			            	AND LAST_DAY(now() - interval 1 month )";		
			}
			
		$sql .= ") as total_trans
		FROM `users` u
		LEFT JOIN `transactions` it ON u.`id` = it.`updated_by`
		LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`";
		if($date == 'today') {
			$sql .= " WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";	
		} else if ($date == 'week') {
			$sql .= " WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
                        BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
                        AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)";		
		} else if ($date = 'month') {
			$sql .= " WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
		            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
		            	AND LAST_DAY(now() - interval 1 month )";		
		}
		
		$sql .= "
		AND it.`status` = 'successful'
		AND u.`user_roles_id` IN (5, 7)
		AND u.`id` NOT IN (69, 31, 16, 74, 70)
		AND u.`username` NOT LIKE '%tester%'
		AND u.`username` NOT LIKE '%trial%'
		AND u.`is_active` = 1
		AND u.`is_ppr_payment` = 1
		GROUP BY u.`id`
		";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		// $result = $this->db->last_query();
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
		if($date == 'today') {
			$sql .= " WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')";	
		} else if ($date == 'week') {
			$sql .= " WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
                        BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
                        AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)";		
		} else if ($date = 'month') {
			$sql .= " WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
			            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
			            	AND LAST_DAY(now() - interval 1 month )";		
		}
		$sql .= "
		AND it.`status` = 'successful'
		AND u.`user_roles_id` IN (5, 7)
		AND u.`id` NOT IN (69, 31, 16, 74, 70)
		AND u.`username` NOT LIKE '%tester%'
		AND u.`username` NOT LIKE '%trial%'
		AND u.`is_active` = 1
		AND u.`id` = $id";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function last2month() {
		$sql = "SELECT
			SUM(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`successful_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))) as tot_min,
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='deduct'
			    AND DATE_FORMAT(it.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now() - interval 2 month),'%Y-%m-'),'01')
	            	AND LAST_DAY(now() - interval 2 month )
			    AND it.`status` = 'successful'
			) 'deduct',
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='add'
			    AND DATE_FORMAT(it.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now() - interval 2 month),'%Y-%m-'),'01')
	            	AND LAST_DAY(now() - interval 2 month )
			    AND it.`status` = 'successful'
			) 'add',
			COUNT(t.`id`) as tot_trans
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			LEFT JOIN `ppr_adjustment` pa ON t.`id` = pa.`trans_id`
			WHERE DATE_FORMAT(t.`successful_time`, '%Y-%m-%d') 
            	BETWEEN concat(date_format(LAST_DAY(now() - interval 2 month),'%Y-%m-'),'01')
            	AND LAST_DAY(now() - interval 2 month )
			AND t.`status` = 'successful'
			AND u.`user_roles_id` IN (5, 7)
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function last1month() {
		$sql = "SELECT
			SUM(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`successful_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))) as tot_min,
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='deduct'
			    AND DATE_FORMAT(it.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
	            	AND LAST_DAY(now() - interval 1 month )
			    AND it.`status` = 'successful'
			) 'deduct',
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='add'
			    AND DATE_FORMAT(it.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
	            	AND LAST_DAY(now() - interval 1 month )
			    AND it.`status` = 'successful'
			) 'add',
			COUNT(t.`id`) as tot_trans
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			LEFT JOIN `ppr_adjustment` pa ON t.`id` = pa.`trans_id`
			WHERE DATE_FORMAT(t.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now() - interval 1 month),'%Y-%m-'),'01')
	            	AND LAST_DAY(now() - interval 1 month )
			AND t.`status` = 'successful'
			AND u.`user_roles_id` IN (5, 7)
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function currmonth() {
        $sql = "SELECT
			SUM(TIMESTAMPDIFF(SECOND,t.`process_time`,t.`successful_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0))) as tot_min,
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='deduct'
			    AND DATE_FORMAT(it.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now()),'%Y-%m-'),'01')
	            	AND LAST_DAY(now())
			    AND it.`status` = 'successful'
			) 'deduct',
			(
				SELECT
			    IFNULL(SUM(TIME_TO_SEC(ipa.`time_adjusted`)), 0)
			    FROM `transactions` it
			    LEFT JOIN `ppr_adjustment` ipa ON it.`id` = ipa.`trans_id`
			    WHERE ipa.`action`='add'
			    AND DATE_FORMAT(it.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now()),'%Y-%m-'),'01')
	            	AND LAST_DAY(now())
			    AND it.`status` = 'successful'
			) 'add',
			COUNT(t.`id`) as tot_trans
			FROM `transactions` t
			LEFT JOIN `users` u ON t.`updated_by` = u.`id`
			LEFT JOIN `ppr_adjustment` pa ON t.`id` = pa.`trans_id`
			WHERE DATE_FORMAT(t.`successful_time`, '%Y-%m-%d') 
	            	BETWEEN concat(date_format(LAST_DAY(now()),'%Y-%m-'),'01')
	            	AND LAST_DAY(now())
			AND t.`status` = 'successful'
			AND u.`user_roles_id` IN (5, 7)
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
            AND u.`username` NOT LIKE '%tester%'
            AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function onine_users() {
		$sql = "SELECT
			COUNT(u.`id`) as tot_online
			FROM `users` u
			WHERE u.`is_logged_in` = 1
			AND u.`user_roles_id` IN (5, 7)
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
			AND u.`username` NOT LIKE '%tester%'
			AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}
}
