<?php
class Games_model extends CI_Model{

  public function get_games_list( $data )
  {
    $return_val = '';
    # id, name, logo, category, is_active, created_by, created_date, updated_by, updated_date
    $data = format_string($data); // format array to string
    $qry_res = $this->db->query('CALL get_games_list( ' . $data . ' )'); // do call request_transaction_list stored procedure
    $return_val = $qry_res->result_array();
    // print_r($this->db->last_query());
    // die();
    $qry_res->next_result();
    $qry_res->free_result();

		return $return_val;
  }

  public function get_games_list_by_division( $data )
  {
    $return_val = '';
    # id, name, logo, category, is_active, created_by, created_date, updated_by, updated_date
    $data = format_string($data); // format array to string
    $qry_res = $this->db->query('CALL get_games_list_by_division( ' . $data . ' )'); // do call request_transaction_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result();

    return $return_val;
  }


}
