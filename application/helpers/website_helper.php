<?php
/**
 * Created by PhpStorm.
 * User: takor
 * Date: 7/16/2018
 * Time: 1:15 AM
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

    global $WEBSITE_LIST;
    global $DIVISION_LIST;
    global $DIVISION_WEBSITE_LIST;

    $WEBSITE_LIST = [
        '0' => '',
        '1' => 'BKK999',
        '2' => 'BOLA WORLD',
        '3' => 'SAWASDEE88',
        '4' => '7BET',
        '5' => 'Liga Judi',
        '6' => 'Surga Judi',
        '7' => 'Bandar Premium',
        '8' => 'Kiper Bola',
        '9' => 'Royal Bandar',
        '10' => 'Helo Bola',
        '11' => 'SC Bet',
        '12' => 'Raja Bola'
    ];

    $DIVISION_LIST = [
      '0' => '',
      '1' => 'Super-Tech',
      '2' => 'Super-T',
      '3' => 'Super-I',
      '4' => 'Super-K'
    ];

    $DIVISION_WEBSITE_LIST = [
        '0' => array(),
        '1' => array(0),
        '2' => array(1,3),
        '3' => array(2,4,5,6,7,8,9,10,12),
        '4' => array(11)
    ];

    function get_website_name_by_id($website_id){
        global $WEBSITE_LIST;
        return $WEBSITE_LIST[$website_id];
    }

    function get_division_name_by_id($division_id){
        global $DIVISION_LIST;
        return $DIVISION_LIST[$division_id];
    }

    // function get_division_name_by_website_id($website_id){
    //     global $DIVISION_LIST;
    //     global $DIVISION_WEBSITE_LIST;
    //     return $DIVISION_LIST[array_search ($website_id, $DIVISION_WEBSITE_LIST, true)];
    // }

    // function get_division_name_by_website_id($website_id){
    //     global $DIVISION_LIST;
    //     global $DIVISION_WEBSITE_LIST;
    //     return array_search ($website_id, $DIVISION_WEBSITE_LIST, true);
    // }

    function get_division_name_by_website_id($website_id){
        global $DIVISION_LIST;
        global $DIVISION_WEBSITE_LIST;
        $value = '';
        $return = 0;
        foreach ($DIVISION_WEBSITE_LIST as $key => $item) {
            $value .= array_search ($website_id, $item);
            if ($value != ''){
                $return = $key;
                break;
            }
        }
        // print_r($value);
        // exit();
        return $DIVISION_LIST[$return];
    }
?>
