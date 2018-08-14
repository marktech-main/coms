<?php
class Admin_model extends CI_Model{
  public function get_user_list($data)
  {
    $return_val = '';
    $data = format_string( $data ); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_user_list(' . $data . ')'); // do call get_user_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_user($data)
  {
    $return_val = '';
    $data = format_string( $data ); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_user_list(' . $data . ')'); // do call get_user_list stored procedure
    $return_val = $qry_res->row();
    $qry_res->next_result();
    $qry_res->free_result();
    return $return_val;
  }

  public function get_user_role_list(){
    $return_val = '';
    # user_role_id, user_role_name
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_user_role_list()'); // do call get_user_role_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_division_list(){
    $return_val = '';
    # division_id, division_name
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_division_list()'); // do call get_division_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_website_list($data){
    $return_val = '';
    # website_id, website_name
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_websites_list(' . $data . ')'); // do call get_website_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function is_username_exist( $data ){
    $return_val = '';
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL is_username_exist( ' . $data . ' )'); // do call get_division_list stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function user_do_save( $data ){
    $return_val = '';
    $data = format_string( $data ); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL user_do_save( ' . $data . ' )'); // do call get_division_list stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_customer_bank_account_list( $data ){
    $return_val = '';
    $data = format_string($data); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_customer_bank_account_list(' . $data . ')'); // do call get_customer_bank_account_list stored procedure
    $return_val = $qry_res->result_array();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function get_customer_bank_account( $data ){
    $return_val = '';
    $data = format_string($data); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_customer_bank_account_list(' . $data . ')'); // do call get_customer_bank_account_list stored procedure
    $return_val = $qry_res->row();
    $qry_res->next_result();
    $qry_res->free_result();
    return $return_val;
  }

  public function customer_bank_account_do_save($data){
    $return_val = '';
    $data = format_string($data);
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL customer_bank_account_do_save(' . $data . ')'); // do call customer_bank_account_do_save stored procedure
    return $qry_res;
  }

  public function is_customer_bank_account_exist($data){
    $return_val = '';
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL is_customer_bank_account_exist( "' . $data . '" )'); // do call is_customer_bank_account_exist stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function change_user_active( $data ){
    $return_val = '';
    $data = format_string( $data ); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL change_user_active( ' . $data . ' )'); // do call change_user_active stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    // $qry_res->next_result();
    // $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function update_announcement( $data ){
    $return_val = '';
    $data = format_string( $data );
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL update_announcement( ' . $data . ' )'); // do call update_announcement stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    return $return_val;
  }
}
