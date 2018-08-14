<?php
class Report_model extends CI_Model{

  public function get_report_average_time_taken_to_complete( $data ){
    $return_val = '';
    # transaction_type_id, transaction_type_name, average_time_taken_to_complete
    $data     = format_string($data);
    $this->db->cache_off();
    $qry_res  = $this->db->query('CALL get_report_average_time_taken_to_complete( ' . $data . ' )'); // do call get_report_average_time_taken_to_complete
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
		return $return_val;
  }

  public function get_report_fastest_time_taken_to_complete( $data ){
    $return_val = '';
    # transaction_type_name, fastest_time_taken_to_complete, pic_username, pic_complete_name
    $data     = format_string($data);
    // print_r('CALL get_report_fastest_time_taken_to_complete( ' . $data . ' )');
    // die();
    $this->db->cache_off();
    $qry_res  = $this->db->query('CALL get_report_fastest_time_taken_to_complete( ' . $data . ' )'); // do call get_report_average_time_taken_to_complete
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_report_longest_time_taken_to_complete( $data ){
    $return_val = '';
    #transaction_type_name, longest_time_to_complete, pic_username, pic_complete_name
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_longest_time_taken_to_complete( ' . $data . ' )'); // do call get_report_longest_time_taken_to_complete
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_report_total_completed_request( $data ){
    $return_val = '';
    #transaction_type_id, transaction_type_name, total_completed_request
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_total_completed_request( ' . $data . ' )'); // do call get_report_total_completed_request
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_report_statistic_of_busiest_day_and_time( $data ){
    $return_val = '';
    #transaction_type_id, transaction_type_name, total_completed_request
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_statistic_of_busiest_day_and_time( ' . $data . ' )'); // do call get_report_statistic_of_busiest_day_and_time
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_report_each_pic_performance( $data ){
    $return_val = '';
    #transaction_type_id, transaction_type_name, total_completed_request
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_each_pic_performance( ' . $data . ' )'); // do call get_report_each_pic_performance
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_report_each_pic_performance_detail( $data ){
    $return_val = '';
    #transaction_type_id, transaction_type_name, total_completed_request
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_each_pic_performance_detail( ' . $data . ' )'); // do call get_report_each_pic_performance_detail
    $return_val = $qry_res->result_array();
    // $return_val = $this->db->last_query();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }
  
  public function get_report_senior_performance( $data ){
    $return_val = '';
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_senior_performance( ' . $data . ' )'); // do call get_report_senior_performance
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_report_senior_performance_summary( $data ){
    $return_val = '';
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_senior_performance_summary( ' . $data . ' )'); // do call get_report_senior_performance_summary
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  # P9JcAKgpWG8j9joRhnSNtb7%2BG97zop2IM3NzCVRFcI8%3D

  // this function is for export report to excel purpose
  public function export_to_excel_statistic_of_busiest_day_and_time( $data ){
    $return_val = '';
    #transaction_type_id, transaction_type_name, total_completed_request
    $data = format_string($data);
    $this->db->cache_off();
    $qry_res = $this->db->query('CALL get_report_statistic_of_busiest_day_and_time( ' . $data . ' )'); // do call get_report_statistic_of_busiest_day_and_time
    $return_val = $qry_res;
    return $return_val;
  }

}
