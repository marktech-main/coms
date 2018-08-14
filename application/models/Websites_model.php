<?php
class Websites_model extends CI_Model{

  public function get_websites_list($data)
  {
    $return_val = '';
    # id, divisions_id, name, token, is_active, created_by, created_date, updated_by, updated_date
    $data = format_string($data); // format array to string
    $qry_res = $this->db->query('CALL get_websites_list( ' . $data . ' )'); // do call request_transaction_list stored procedure
    $return_val = $qry_res->result_array();
    // print_r($this->db->last_query());
    // die();
    $qry_res->next_result();
    $qry_res->free_result();

		return $return_val;
  }

}
