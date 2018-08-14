<?php
class Transactions_model extends CI_Model{

  public function get_request_transaction($data)
  {
    $return_val = '';
    $uuid = $data['filter_uuid'];
    if(!empty($uuid)){
      # id, transction_type_id, website_id, customer_id, transaction_data, amount, customer_bank_account_number, customer_bank_account_name, remark, status, created_by, request_time, updated_by, process_time, complete_time
      $data = format_string($data); // format array to string
      // print_r('CALL get_request_transaction( ' . $data . ' )');
      // die();
      $this->db->cache_on();
      $qry_res = $this->db->query('CALL get_request_transaction( ' . $data . ' )'); // do call request_transaction_list stored procedure
      $return_val = $qry_res->row();
      $qry_res->next_result();
      // $qry_res->free_result(); // flush query
    }else{
      # id, transction_type_id, website_id, customer_id, transaction_data, amount, customer_bank_account_number, customer_bank_account_name, remark, status, created_by, request_time, updated_by, process_time, complete_time
      $data = format_string($data); // format array to string
      // print_r('CALL get_request_transaction( ' . $data . ' )');
      // die();
      $this->db->cache_off();
      $qry_res = $this->db->query('CALL get_request_transaction( ' . $data . ' )'); // do call request_transaction_list stored procedure
      $return_val = $qry_res->row();
      $qry_res->next_result();
      // $qry_res->free_result(); // flush query
    }


    return $return_val;
  }

  public function get_request_transaction_list($data)
  {
    $return_val = '';
    # id, transction_type_id, website_id, customer_id, transaction_data, amount, customer_bank_account_number, customer_bank_account_name, remark, status, created_by, request_time, updated_by, process_time, complete_time
    $uuid = $data['filter_uuid'];
    // print_r($data);
    // die();
    if(!empty($uuid)){
      $data = format_string($data); // format array to string
      $this->db->cache_on();
      $qry_res = $this->db->query('CALL get_request_transaction_list( ' . $data . ' )'); // do call request_transaction_list stored procedure
      $return_val = $qry_res->result_array();
      // print_r($this->db->last_query());
      // die();
      $qry_res->next_result();
      // $qry_res->free_result(); // flush query
    }else{
      $data = format_string($data); // format array to string
      $this->db->cache_off();
      $qry_res = $this->db->query('CALL get_request_transaction_list( ' . $data . ' )'); // do call request_transaction_list stored procedure
      $return_val = $qry_res->result_array();
      // print_r($this->db->last_query());
      // die();
      $qry_res->next_result();
      // $qry_res->free_result(); // flush query
    }


		return $return_val;
  }

  public function get_transaction_types_list()
  {
    $return_val = '';
    # id, name, is_active, timestamp
    $this->db->cache_on();
    $qry_res = $this->db->query('CALL get_transaction_types_list()'); // do call request_transaction_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function transaction_do_save($data)
  {
    $return_val = '';
    # id, transaction_type_id, website_game_id, to_website_game_id, customer_id, amount, bank_account_number, bank_account_name, remark, status, created_by, request_time, updated_by, process_time, complete_time
    $data     = format_string($data);
    $this->db->cache_off();
    $qry_res  = $this->db->query('CALL transaction_do_save( ' . $data . ' )'); // do call transaction_do_save
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $this->db->cache_delete('main', 'get_json_transaction_list');
    $this->db->cache_delete('main', 'get_division_filter');
    return $return_val;
  }

  public function transaction_do_update_status($data)
  {
    $return_val = '';
    # id, transaction_type_id, website_game_id, to_website_game_id, customer_id, amount, bank_account_number, bank_account_name, remark, status, created_by, request_time, updated_by, process_time, complete_time
    $data     = format_string($data);
    $this->db->cache_off();
    $qry_res  = $this->db->query('CALL transaction_do_update_status( ' . $data . ' )'); // do call transaction_do_update_status
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $this->db->cache_delete('main', 'get_json_transaction_list');
    $this->db->cache_delete('main', 'get_division_filter');
    return $return_val;
  }

  public function get_request_transaction_statistic($data)
  {
    $return_val = '';
    # total_request, total_deposit, total_withdrawal, total_transfer, total_new_register, total_cancelled
    $uuid = $data['filter_uuid'];
    if(!empty($uuid)){
      $this->db->cache_on();
    }else{
      $this->db->cache_off();
    }
    $data     = format_string($data);
    $qry_res = $this->db->query('CALL get_request_transaction_statistic( ' . $data . ' )'); // do call request_transaction_statistic stored procedure
    $return_val = $qry_res->row();
    $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_cs_request_transaction_statistic($data){
    $return_val = '';
    # total_request, total_deposit, total_withdrawal, total_transfer, total_new_register, total_cancelled
    $uuid = $data['filter_uuid'];
    if(!empty($uuid)){
      $this->db->cache_on();
    }else{
      $this->db->cache_off();
    }
    $data     = format_string($data);
    $qry_res = $this->db->query('CALL get_cs_request_transaction_statistic( ' . $data . ' )'); // do call request_transaction_statistic stored procedure
    $return_val = $qry_res->row();
    $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function am_i_requester($data){
    $return_val = '';
    $data     = format_string($data);
    $this->db->cache_on();
    $qry_res  = $this->db->query('CALL am_i_requester( ' . $data . ' )'); // do call am_i_requester
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_requester_id($data){
    $return_val = '';
    $uuid = $data['filter_uuid'];
    if(!empty($uuid)){
      $this->db->cache_on();
    }else{
      $this->db->cache_off();
    }
    $data     = format_string($data);
    $qry_res  = $this->db->query('CALL get_requester_id( ' . $data . ' )'); // do call get_requester_id
    $return_val = $qry_res->row();
    $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_chat_history($data){
      $return_val = '';
      $this->db->cache_off();
      $qry_res = $this->db->query('CALL get_chat_history( ' . $data . ' )'); // do call get_chat_history
      $return_val = $qry_res->result_array();
      $qry_res->next_result();
      // $qry_res->free_result(); // flush query
      return $return_val;
  }

  public function get_customer_bank_account($data){
      $return_val = '';
      $data     = format_string($data);
      $this->db->cache_off();
      $qry_res = $this->db->query('CALL get_customer_bank_account( ' . $data . ' )'); // do call get_customer_bank_account
      $return_val = $qry_res->result_array();
      $qry_res->next_result();
      // $qry_res->free_result();
      return $return_val;
  }

  public function get_division_list(){
    $return_val = '';
    $this->db->cache_on();
    $qry_res = $this->db->query('CALL get_division_list()');
    $return_val = $qry_res->result_array();
    return $return_val;
  }

  public function get_operator_id($data){
    $return_val = '';
    $this->db->cache_off();
    $qry_res  = $this->db->query('CALL get_operator_id( ' . $data . ' )'); // do call get_operator_id
    $return_val = $qry_res->row();
    $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function update_pending_transaction($data){
    $return_val = '';
    # transaction_id, user_id, type(ACCEPT, DECLINE)
    $data     = format_string($data);
    $this->db->cache_off();
    $qry_res  = $this->db->query('CALL update_pending_transaction( ' . $data . ' )'); // do call update_pending_transaction
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $this->db->cache_delete('main', 'get_json_transaction_list');
    $this->db->cache_delete('main', 'get_division_filter');
    return $return_val;
  }

}
