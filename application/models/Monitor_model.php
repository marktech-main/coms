<?php
class Monitor_model extends CI_Model{

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

  public function get_monitoring_data($data){
    $return_val = '';
    $uuid = $data['filter_uuid'];
    if(!empty($uuid)){
      $data = format_string($data); // format array to string
      $this->db->cache_on();
      $qry_res = $this->db->query('CALL get_monitoring_data( ' . $data . ' )'); // do call get_monitoring_data stored procedure
      $return_val = $qry_res->result_array();
      $qry_res->next_result();

    }else{
      $data = format_string($data); // format array to string
      $this->db->cache_off();
      $qry_res = $this->db->query('CALL get_monitoring_data( ' . $data . ' )'); // do call get_monitoring_data stored procedure
      $return_val = $qry_res->result_array();
      $qry_res->next_result();
    }
    return $return_val;
  }

}
