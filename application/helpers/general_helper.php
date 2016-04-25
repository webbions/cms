<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
function triggerMail( $to_email, $subject, $message ){
	$CI = & get_instance();

	$CI->load->library('email');

	$emailCon['mailtype'] = "html";
	$CI->email->initialize($emailCon);

	$CI->email->from(SITE_EMAIL, SITE_NAME);
	$CI->email->to( $to_email );
	$CI->email->subject( $subject );
	$CI->email->message( $message );

	if(!$CI->email->send()){
		return $CI->email->print_debugger();
	}
	return true;
}

function getAllCountry(){
	$CI = & get_instance();
	$cntry = $CI->db->get('countries')->result_array();
	$countryArr = [];
	foreach($cntry as $key => $value) {
		$countryArr[$value['id']] = $value['countryName'];
	}

	return $countryArr;
}

function pr($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function renderMessage(){
	$CI = & get_instance();
	$message = "";
	if ($CI->session->flashdata('error')) {
		$message = '<div class="alert alert-danger">
				      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				      <i class="fa fa-check sign"></i><strong>Error!</strong> ' . $CI->session->flashdata('error') . '
				     </div>';
	}

	if ($CI->session->flashdata('success')) {
		$message = '<div class="alert alert-success">
				      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				      <i class="fa fa-check sign"></i><strong>Success!</strong> ' . $CI->session->flashdata('success') . '
				     </div>';
	}
	if(validation_errors() != false) {
		$message = '<div class="alert alert-danger">
				    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				    ' . validation_errors()  . '
				    </div>';
	}
	return $message;
}

function validateErrors(){
	if (validation_errors()) {
		echo "<div class='ci_error_msg'>";
		echo validation_errors();
		echo "</div>";
	}
}

function do_upload($con){
	$CI = & get_instance();
	$config['file_name'] = time() . '_' . $_FILES[$con['fileName']]["name"];
	$config['upload_path'] = $con['path'];
	$config['allowed_types'] = $con['allowType'];

	// todo cleanup remove commented code
	// $config['max_size'] = $con['maxSize'];
	// $config['max_width'] = '2000';
	// $config['max_height'] = '2000';

	$CI->load->library('upload', $config);
	$CI->upload->initialize($config);
	unset($config);
	if (!$CI->upload->do_upload($con['fileName'])) {
		return array(
			'error' => $CI->upload->display_errors() ,
			'status' => 0
		);
	}
	else {
		return array(
			'status' => 1,
			'upload_data' => $CI->upload->data()
		);
	}
}

if (!function_exists('humanTiming')) {
	function humanTiming($time)
	{
		$time = time() - $time; // to get the time since that moment
		$tokens = array(
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		foreach($tokens as $unit => $text) {
			if ($time < $unit) {
				continue;
			}

			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
		}
	}
}

function proxy_test($params, $optionalCheck_ssl = true, $optionalTimeout = null, $optionalTime = 1){
	$proxyIP = $params['ip_address'];
	$proxyPort = $params['port'];
	$proxyUsername = $params['username'];
	$proxyPassword = $params['password'];
	$url = "http://google.com";
	$ch = curl_init(); // Initialise a cURL handle
	if ($ch) {
		if ($optionalCheck_ssl) {
			$proxy = $proxyUsername . ':' . $proxyPassword . '@' . $proxyIP . ':' . $proxyPort;

			// Setting proxy option for cURL

			if (isset($proxy)) { // If the $proxy variable is set, then
				curl_setopt($ch, CURLOPT_PROXY, $proxy); // Set CURLOPT_PROXY with proxy in $proxy variable
			}

			// Set any other cURL options that are required

			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, $optionalTimeout);
			curl_setopt($ch, CURLOPT_URL, $url);
			$results = curl_exec($ch); // Execute a cURL request
			$response = curl_getinfo($ch);
			curl_close($ch); // Closing the cURL handle

			// todo cleanup remove commented code
			// print_r($response);

			if ($response['http_code'] == 200) {
				return json_encode(array(
					'status' => 1,
					'response_code' => '1'
				));
			}
			else {
				return json_encode(array(
					'status' => 0,
					'response_code' => '-2'
				));
			}
		}
		else {
			return json_encode(array(
				'status' => 1,
				'response_code' => '1'
			));
		}
	}
	else {
		if ($optionalTime < 4) {
			return proxy_test($proxyIP, $proxyPort, $proxyUsername, $proxyPassword, $optionalCheck_ssl, 2, $optionalTime + 1);
		}
		else {
			return json_encode(array(
				'status' => 0,
				'response_code' => '-2'
			));
		}

		return json_encode(array(
			'status' => 0,
			'response_code' => '-2'
		));
	}
}

function getInArray($array, $field){
	$arr = array();
	foreach($array as $val) {
		$arr[] = $val->$field;
	}

	return $arr;
}

function initialize_elfinder($value = ''){
	$CI = & get_instance();
	$CI->load->helper('path');
	$opts = array(

		// todo cleanup remove commented code
		// 'debug' => true,

		'roots' => array(
			array(
				'driver' => 'LocalFileSystem',
				'path' => './uploads/files/',
				'URL' => site_url('uploads/files') . '/'

				// more elFinder options here

			)
		)
	);
	return $opts;
}

function check_for_identity_file($file_id){
	$challengeFile = APPPATH . "../vendor/whatsapp/chat-api/src/wadata/nextChallenge." . $file_id . ".dat";
	$identityFile = APPPATH . "../vendor/whatsapp/chat-api/src/wadata/id." . $file_id . ".dat";
	$dbFile = APPPATH . "../vendor/whatsapp/chat-api/src/wadata/axolotl-" . $file_id . ".db";
	$result = array();
	if (file_exists($identityFile)) {
		if (!unlink($identityFile)) {

			// return 0 if operation failed

			$result['identityFile'] = 0;
		}
	}

	if (file_exists($dbFile)) {
		if (!unlink($dbFile)) {

			// return 0 if operation failed

			$result['dbFile'] = 0;
		}
	}

	if (file_exists($challengeFile)) {
		if (!unlink($challengeFile)) {

			// return 0 if operation failed

			$result['challengeFile'] = 0;
		}
	}

	return $result;
}

// This function only needed to show how eventmanager works.

function onGetProfilePicture($from, $target, $type, $data){
	$filename = FCPATH . 'uploads/files/Images/' . $target . '.jpg';
	file_put_contents($filename, $data);
}
function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if( $action == 'e' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'd' ){
    	//decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
?>