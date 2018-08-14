<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seniorverify_model extends CI_Model {


	/** VERIFYING **/
	public function start_verify($trans_id, $verify_json_log, $senior_json_log) {
		$sql = "UPDATE `transactions`
			SET
				`senior_verifying` = IF(`senior_verifying` IS NULL, '$verify_json_log', CONCAT(`senior_verifying`,CONCAT(',','$verify_json_log'))),
				`senior_log` = IF(`senior_log` IS NULL, '$senior_json_log', CONCAT(`senior_log`,CONCAT(',','$senior_json_log')))
		    WHERE `id` = '$trans_id'";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}

	public function select_verify($trans_id) {
	    $data[] = $trans_id;
		$sql = 'SELECT concat("[",`senior_verifying`,"]") as verify
			FROM `transactions`
		    WHERE `id` = ?';
		$this->db->cache_off();
		$query = $this->db->query($sql, $trans_id);
		$result = $query->result();
		return $result;
	}

	public function end_verify($trans_id, $verify_json_log, $senior_json_log) {
	   	$data[] = $verify_json_log;
	    $data[] = $senior_json_log;
	    $data[] = $trans_id;

		$sql = 'UPDATE `transactions`
			SET
				`senior_verifying` = ?,
				`senior_log` = CONCAT(`senior_log`,CONCAT(",",?))
		    WHERE `id` = ?';
		$this->db->cache_off();
		$query = $this->db->query($sql, $data);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}


	/** FIXING **/
	public function start_fix($trans_id, $fix_json_log, $senior_json_log) {
		$sql = "UPDATE `transactions`
			SET
				`senior_fixing` = IF(`senior_fixing` IS NULL, '$fix_json_log', CONCAT(`senior_fixing`,CONCAT(',','$fix_json_log'))),
				`senior_log` = IF(`senior_log` IS NULL, '$senior_json_log', CONCAT(`senior_log`,CONCAT(',','$senior_json_log')))
		    WHERE `id` = '$trans_id'";
		$this->db->cache_off();
		$query = $this->db->query($sql);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}

	public function select_fix($trans_id) {
	    $data[] = $trans_id;
		$sql = 'SELECT concat("[",`senior_fixing`,"]") as fix
			FROM `transactions`
		    WHERE `id` = ?';
		$this->db->cache_off();
		$query = $this->db->query($sql, $trans_id);
		$result = $query->result();
		return $result;
	}

	public function end_fix($trans_id, $fix_json_log, $senior_json_log) {
	   	$data[] = $fix_json_log;
	    $data[] = $senior_json_log;
	    $data[] = $trans_id;

		$sql = 'UPDATE `transactions`
			SET
				`senior_fixing` = ?,
				`senior_log` = CONCAT(`senior_log`,CONCAT(",",?))
		    WHERE `id` = ?';
		$this->db->cache_off();
		$query = $this->db->query($sql, $data);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows;
	}

}

