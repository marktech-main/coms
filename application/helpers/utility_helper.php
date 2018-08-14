<?php
/**
 * Created by PhpStorm.
 * User: takorn.aek
 * Date: 1/18/2016
 * Time: 1:32 PM
 */
function format_string ( $data )
{

  $data = implode("', '", array_values( $data ) );
  $data = "'" . $data . "'";
  return $data;

}

/**
 * array_hashmap
 *
 * @param object_array, string, string
 * @return array
 * @author Takorn
 **/
function array_hashmap($object_array, $filter_name, $filter_value){
 $return_val = array();
 foreach($object_array as $k => $v) {
    if($v[$filter_name] == $filter_value)
       $return_val = $v;
 }
 return $return_val;
}
?>
