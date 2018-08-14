<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

use ElephantIO\Client AS ElephantIOClient,
    ElephantIO\Engine\SocketIO\Version1X,
    ElephantIO\Exception\ServerConnectionFailureException;
require $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';

/*
 * Objective : On create request transactions Send all client notification
 * - update DataTable
 * - update summary request data
 * - execute alarm to notify Payment Team
 * Author : Takorn A.
 * Version : 0.01
*/
function on_create_request_transaction($uuid){
  try
  {
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338'));
      $elephant->initialize();
      $elephant->emit('create_request_transaction', ['uuid' => $uuid]);
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

/*
 * Objective : On update request transactions Send specific client notification by user_id
 * - update DataTable
 * - update summary request data
 * - execute alarm to notify specific client by user_id
 * Author : Takorn A.
 * Version : 0.02
*/
function on_update_request_transaction($transaction_id ,$status, $reason, $uuid){
  try
  {
      $user = decrypt($_SESSION['user_data']);
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338?username='.$user['username'].'&role='.$user['user_role']));
      $elephant->initialize();
      $elephant->emit( 'update_request_transaction', ['transaction_id' =>  $transaction_id, 'status' => $status, 'reason' => $reason, 'uuid' => $uuid] );
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

/*
 * Objective : On update pending transactions Send specific client notification by user_id
 * - update DataTable [optional]
 * - update verify-form
 * - execute alarm to notify specific client by user_id
 * Author : Takorn A.
 * Version : 0.01
*/
function on_update_pending_transaction($transaction_id , $status, $uuid){
  try
  {
    $user = decrypt($_SESSION['user_data']);
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338?username='.$user['username'].'&role='.$user['user_role']));
      $elephant->initialize();
      $elephant->emit( 'update_pending_transaction', ['transaction_id' =>  $transaction_id, 'status' => $status, 'uuid' => $uuid] );
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}


/*
 * Objective : On update successful transactions Send PPR notification by user_id
 * - update DataTable [optional]
 * - update verify-form
 * - execute alarm to notify PPR by user_id
 * Author : Takorn A.
 * Version : 0.01
*/
function on_update_transaction_to_successful($transaction_id, $uuid){
  try
  {
    $user = decrypt($_SESSION['user_data']);
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338?username='.$user['username'].'&role='.$user['user_role']));
      $elephant->initialize();
      $elephant->emit( 'update_request_transaction_to_successful', ['transaction_id' =>  $transaction_id, 'uuid' => $uuid] );
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

function on_update_loggedin_user_status(){
  try
  {
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338'));
      $elephant->initialize();
      $elephant->emit( 'update_count_online_user', ['message' => 'user logged out']);
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

function on_login_with_username(){
  try
  {
      $user = decrypt($_SESSION['user_data']);
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338'));
      $elephant->initialize();
      $elephant->emit( 'login_with_username', ['username' =>  $user['username'], 'role' => $user['user_role']] );
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

function on_logout_with_username($username){
  try
  {
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338'));
      $elephant->initialize();
      $elephant->emit( 'logout_with_username', ['username' =>  $username] );
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

function on_update_monitor_user(){
  try
  {
      $elephant = new ElephantIOClient(new Version1X('http://128.199.157.177:1338'));
      $elephant->initialize();
      $elephant->emit( 'on_update_monitor_user', ['message' =>  'request new data from DB'] );
      $elephant->close();
  }
  catch (ServerConnectionFailureException $e)
  {
      echo 'Server Connection Failure!!'.PHP_EOL;
  }
}

?>
