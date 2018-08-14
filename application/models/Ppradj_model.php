<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppradj_model extends CI_Model {


	public function get_trans_list_today() {
	    $sql = "SELECT
			it.`id` AS trans_id,
			u.`id` AS payment_id,
			u.`complete_name` AS name,
			it.`complete_time`,
			SEC_TO_TIME(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0))) AS time_completed,
			(
			    SELECT ipa.`time_adjusted`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as time_adjusted,
			(
			    SELECT ipa.`adjusted_by`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as adjusted_by,
			(
			    SELECT ipa.`updated_on`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as updated_on,
			(
			    SELECT ipa.`action`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as action
			FROM `transactions` it
			LEFT JOIN `users` u ON it.`updated_by` = u.`id`
			LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`
			WHERE DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
			GROUP BY it.`id`
			ORDER BY it.`id` ASC";
	    $this->db->cache_off();
	    $query = $this->db->query($sql);
		$result = $query->result();
		return $result;
	}

	public function check_selected($id) {
		$data[] = $id;
		$sql = "SELECT COUNT(*) as isset
			FROM `ppr_adjustment`
			WHERE `trans_id` = ?";
		$this->db->cache_off();
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;
	}

	public function update_selected($id, $time, $updated_by, $action) {
		$data[] = $time;
		$data[] = $updated_by;
		$data[] = $action;
		$data[] = $id;

		$sql = "UPDATE `ppr_adjustment`
			SET
				`time_adjusted` = ?,
				`adjusted_by` = ?,
				`action` = ?
		    WHERE `trans_id` = ?";
		$this->db->cache_off();
		$query = $this->db->query($sql, $data);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}

	public function add_selected($data) {
		$sql = "INSERT INTO `ppr_adjustment`
		    (`trans_id`,`time_adjusted`,`adjusted_by`,`action`,`reason`)
		VALUES
		    $data";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}

	public function payment_list() {
		$sql = "SELECT
			u.`id` AS 'id',
			u.`complete_name` AS 'name'
			FROM `users` u
			WHERE u.`user_roles_id` = 5
			AND u.`id` NOT IN (69, 31, 16, 74, 70)
			AND u.`username` NOT LIKE '%tester%'
			AND u.`username` NOT LIKE '%trial%'
			AND u.`is_active` = 1
			AND u.`is_ppr_payment` = 1
			GROUP BY u.`id`";
		$query = $this->db->query($sql);
		$result = $query->result();
		$this->db->last_query();
		return $result;
	}

	public function filter_list($payment_name, $date, $time_from, $time_to, $exclude_time) {
		$sql = '';
		$sql .= "SELECT *
			FROM (
			SELECT
			it.`id` AS trans_id,
			u.`id` AS payment_id,
			u.`complete_name` AS name,
			it.`complete_time`,
			SEC_TO_TIME(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0))) AS time_completed,
			(TIMESTAMPDIFF(SECOND,it.`process_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60 AS min_time_completed,
			(
			    SELECT ipa.`time_adjusted`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as time_adjusted,
			(
			    SELECT ipa.`adjusted_by`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as adjusted_by,
			(
			    SELECT ipa.`updated_on`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as updated_on,
			(
			    SELECT ipa.`action`
			    FROM `ppr_adjustment` ipa
			    WHERE ipa.`trans_id` = it.`id`
			    ORDER BY ipa.`updated_on` DESC
			    LIMIT 1
			) as action
			FROM `transactions` it
			LEFT JOIN `users` u ON it.`updated_by` = u.`id`
			LEFT JOIN `ppr_adjustment` pa ON it.`id` = pa.`trans_id`
			WHERE it.`status` = 'successful'";
			if($payment_name) {
				$data[] = $payment_name;
				$sql .= " AND u.`id` = ?";
			}
			if($date) {
				$data[] = $date;
				$sql .= " AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(?, '%Y-%m-%d')";
			}
			if($time_from != "" && $time_to != "") {
				$data[] = '2016-01-01 '.$time_from;
				$data[] = '2016-01-01 '.$time_to;
				$sql .= " AND (DATE_FORMAT(it.`complete_time`, '%H:%i') >= DATE_FORMAT(?, '%H:%i')
				AND DATE_FORMAT(it.`complete_time`, '%H:%i') <= DATE_FORMAT(?, '%H:%i'))";
			}
			$sql .= " GROUP BY it.`id`
				ORDER BY it.`id` ASC) as temp
			WHERE min_time_completed > $exclude_time
			ORDER BY trans_id ASC";
	    $this->db->cache_off();
	    $query = $this->db->query($sql, $data);
		$result = $query->result();
		//$sql_result = $this->db->last_query();
		return $result;
	}

}
