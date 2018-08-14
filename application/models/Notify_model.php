<?php
class Notify_model extends CI_Model{

  public function get_total_unread_notify_message( $data ){
    $return_val = '';
    # id, user_id, transaction_id, content, content_status, state, timestamp
    $qry_res  = $this->db->query('CALL get_total_unread_notify_message( ' . $data . ' )'); // do call get_requester_id
    $notify = $qry_res->row();
    $return_val = $notify->total_record;
    // print_r($this->db->last_query());
    // die();
    return $return_val;
  }

  public function get_latest_notify_message( $data ){
    $return_val = '';
    # id, user_id, transaction_id, content, content_status, state, timestamp
    $data     = format_string($data);
    $qry_res = $this->db->query('CALL get_latest_notify_message( ' . $data . ' )'); // do call get_latest_notify_message stored procedure
    $return_val = $qry_res->result_array();
    // print_r($this->db->last_query());
    // die();
		return $return_val;
  }

  public function get_notify_message_list( $data ){
    $return_val = '';
    # id, user_id, transaction_id, content, content_status, state, timestamp
    $qry_res = $this->db->query('CALL get_notify_message_list( ' . $data . ' )'); // do call get_notify_message_list stored procedure
    $return_val = $qry_res->result_array();
    // print_r($this->db->last_query());
    // die();
    return $return_val;
  }

  public function notify_message_do_save( $data ){
    $return_val = '';
    # id, user_id, transaction_id, content, content_status, state, timestamp
    $data     = format_string($data);
    $qry_res  = $this->db->query('CALL notify_message_do_save( ' . $data . ' )'); // do call notify_message_do_save
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    return $return_val;
  }

  public function mark_all_notify_as_read( $data ){
    $return_val = '';
    # id, user_id, transaction_id, content, content_status, state, timestamp
    $qry_res  = $this->db->query('CALL mark_all_notify_as_read( ' . $data . ' )'); // do call notify_message_do_save
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    return $return_val;
  }

}
