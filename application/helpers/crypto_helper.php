<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * encrypt
 *
 * @param data can be string or array
 * @return bool
 * @author Takorn
 **/
function encrypt($data) {
  $output = false;
  // Edit this if you want to change the key.
  $key = 'Zup3r7T3chT3@m!';
  // initialization vector
  // $iv = md5(md5($key));
  if(is_array($data)){
    $string = '';
    foreach($data AS $k => $v){
        $string = urldecode($v);
        $output = trim(base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            $key."\0",
            $string,
            MCRYPT_MODE_ECB,
            mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        $data[$k] = urlencode($output);
    }
    $output = $data;
  }else{
    $output = trim(base64_encode(mcrypt_encrypt(
    MCRYPT_RIJNDAEL_256,
    $key."\0",
    $data,
    MCRYPT_MODE_ECB,
    mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

    $output = urlencode($output);

  }
  return $output;
}

function encrypt_test($data) {
  $output = false;
  // Edit this if you want to change the key.
  $key = 'Zup3r7T3chT3@m!#';
  // initialization vector
  // $iv = md5(md5($key));
  if(is_array($data)){
    $string = '';
    foreach($data AS $k => $v){
        $string = urldecode($v);
        $output = trim(base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            $key."\0",
            $string,
            MCRYPT_MODE_ECB,
            mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        $data[$k] = urlencode($output);
    }
    $output = $data;
  }else{
    $output = trim(base64_encode(mcrypt_encrypt(
    MCRYPT_RIJNDAEL_256,
    $key."\0",
    $data,
    MCRYPT_MODE_ECB,
    mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

    $output = urlencode($output);

  }
  return $output;
}

/**
 * decrypt
 *
 * @param data can be string or array
 * @return bool
 * @author Takorn
 **/
function decrypt($data){
  $output = false;
  // Edit this if you want to change the key.
  $key = 'Zup3r7T3chT3@m!';
  // initialization vector
  // $iv = md5(md5($key));
  if(is_array($data)){
    $string = '';
    foreach($data AS $k => $v){
        $string = urldecode($v);
        $output = trim(mcrypt_decrypt(
        MCRYPT_RIJNDAEL_256,
        $key."\0",
        base64_decode($string),
        MCRYPT_MODE_ECB,
        mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        $data[$k] = $output;
    }
    $output = $data;
  }else{
    $data = urldecode($data);
    $output = trim(mcrypt_decrypt(
    MCRYPT_RIJNDAEL_256,
    $key."\0",
    base64_decode($data),
    MCRYPT_MODE_ECB,
    mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
  }
  return $output;
}
?>
