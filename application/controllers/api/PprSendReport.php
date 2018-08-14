<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PprSendReport extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$month = Date('F', strtotime("last month"));
		$link = 'https://coms.super7tech.com/pprautoreport';

	    $smtp_user = 'marktech.uni@gmail.com';
	    $smtp_pass = 'asxz4521';
	    $send_from = 'marktech.uni@gmail.com';
	    $send_to = 'tanotow@oleintl.com,ch@oleintl.com,Cris.kuh@oleintl.com,kisito.ong@oleintl.com,Ivan.lee@oleintl.com';
	    $send_tocc = 'takorn.aek@oleintl.com,SuperI_Supervisor@oleintl.com,lou.dulguime@oleintl.com';
	    $ishtml = true;
	    $subject = 'PPR report for the month of '.$month;
	    $body = '<p>Hi all,</p>
	    <p>Please see the link of the PPR report for the month of '.$month.'.</p>
	    <p>'.$link.'</p>
	    <p>Thank you<br>
	    Payment Performance Rating System</p>
	    <br>---<br><br>
	    (This is an automated send report)';
	    $altbody = 'altbody';

		$postfields = array(
			'send_from'	=>	$send_from,
			'send_to'	=>	$send_to,
			'send_tocc'	=>	$send_tocc,
			'subject'	=>	$subject,
			'body'		=>	$body
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://servebetter.vip/api/mailer/send');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		print_r($result);
		
	}
}
