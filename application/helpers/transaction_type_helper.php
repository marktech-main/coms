<?php
/**
 * Created by PhpStorm.
 * User: takor
 * Date: 7/16/2018
 * Time: 1:15 AM
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

    global $TRANSACTION_TYPE_LIST;
    $TRANSACTION_TYPE_LIST = [
        '1' => 'DEPOSIT',
        '2' => 'WITHDRAWAL',
        '3' => 'TRANSFER',
        '4' => 'NEW-REGISTER',
        '5' => 'RESET-PASSWORD',
        '6' => 'OTHERS'
    ];

    function get_transaction_type_name_by_id($transaction_type_id){
        global $TRANSACTION_TYPE_LIST;
        return $TRANSACTION_TYPE_LIST[$transaction_type_id];
    }
?>
