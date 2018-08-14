<?php
class Users_model extends CI_Model{
  protected $username;
  protected $password;

  /**
   * do_login
   *
   * @param username, password
   * @return bool
   * @author Takorn
   **/
	public function do_login( $username , $password , $remember = FALSE){
    $return_val = '';
	$this->db->cache_off();
    # id, divisions_id, user_roles_id, username, password, complete_name, email, token, password_try, last_login, is_active, is_login, created_by, created_date, updated_by, updated_date
		$this->db->select('id , divisions_id , user_roles_id, username, password , complete_name, email, last_login');
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->where('password', encrypt($password));
    $this->db->where('is_active', TRUE);
    $this->db->limit(1);
		$query = $this->db->get();
    if ($query->num_rows() == 1)
		{
      $user = $query->row();

      //  ====STEP1=== check attemp exceed limit
      // code ..
      //  ====STEP2=== check is_logged_in != 1 for prevent login paralell ATM
      // code ..

			$this->update_last_login($user->id);
			$this->set_session($user, $remember);
      $return_val = TRUE;
    }else{
      $return_val = FALSE;
    }
		return $return_val;
	}

  /**
   * do_logut
   *
   * @param username
   * @author Takorn
   **/
  public function do_logout( $username )
  {
    # code...
    $this->db->update('users', array('is_logged_in' => 0), array('username' => $username));
  }

  /**
   * update_last_login
   *
   * @param id
   * @return bool
   * @author Takorn
   **/
  public function update_last_login( $id ){
    $this->load->helper('date');
    $this->db->update('users', array('last_login' => date('Y-m-d H:i:s'), 'is_logged_in' => 1), array('id' => $id));

    // print_r($this->db->last_query());
    // die();
    return $this->db->affected_rows() == 1;
  }

  /**
   * set_session
   *
   * @return bool
   * @author Takorn
   **/
  public function set_session($user, $remember = FALSE)
  {
    if($remember){
      $expire = (60*60*24*365); // 1 year
      $session_data = array(
          'user_id'              => $user->id,
          'division'             => $user->divisions_id,
          'user_role'            => $user->user_roles_id,
          'username'             => $user->username,
          'email'                => $user->email,
          'last_login'           => $user->last_login,
          'expire'               => $expire
      );
    }else{
      $session_data = array(
          'user_id'              => $user->id,
          'division'             => $user->divisions_id,
          'user_role'            => $user->user_roles_id,
          'username'             => $user->username,
          'email'                => $user->email,
          'last_login'           => $user->last_login
      );
      //<!-- Additional feature for keep User_role_id session on localStorage -->
      echo '<script type="text/javascript">';
      echo 'localStorage["ur"] = '.$user->user_roles_id;
      echo '</script>';
    }
    $test = encrypt($session_data);
    // echo '<pre>';
    // print_r($session_data);
    // print_r(encrypt($session_data));
    // print_r(decrypt($test));
    // echo '</pre>';
    // die();
    $this->session->set_userdata('user_data', encrypt($session_data));
    return TRUE;
  }

  public function get_user_profile($data){
    $return_val = '';
    # id, division_name, user_role_name, username, complete name, email
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL get_user_profile( ' . $data . ' )'); // do call request_transaction_list stored procedure
    $return_val = $qry_res->row();
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function check_current_password($data){
    $return_val = '';
    $data = format_string($data); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL check_current_password( ' . $data . ' )'); // do call check_current_password stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function user_change_password($data){
    $return_val = '';
    $data = format_string($data); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL user_change_password( ' . $data . ' )'); // do call user_change_password stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    return $return_val;
  }

  public function is_username_exist( $data ){
    $return_val = '';
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL is_username_exist( "' . $data . '" )'); // do call is_username_exist stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function is_username_exist_except_id( $data ){
    $return_val = '';
    $data = format_string($data); // format array to string
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL is_username_exist_except_id( ' . $data . ' )'); // do call is_username_exist_except_id stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    $qry_res->next_result();
    $qry_res->free_result(); // flush query
    return $return_val;
  }

  public function user_do_save( $data ){
    $return_val = '';
    $data = format_string($data);
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL user_do_save( ' . $data . ' )'); // do call user_do_save stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    return $return_val;
  }

  public function user_do_update( $data ){
    $return_val = '';
    $data = format_string($data);
	$this->db->cache_off();
    $qry_res = $this->db->query('CALL user_do_update( ' . $data . ' )'); // do call user_do_update stored procedure
    $return_val = ($this->db->affected_rows() != 1) ? false : true;
    return $return_val;
  }

}
