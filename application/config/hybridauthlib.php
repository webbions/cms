<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =
	array(
		// set on "base_url" the relative url that point to HybridAuth Endpoint
		'base_url' => '/south/endpoint',

		"providers" => array (
			// openid providers

			"Facebook" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "1696109153983041", "secret" => "80b40836712dffbd1a95fd069867dc2c" ),
			),

			"LinkedIn" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "75py2x06hl4n2r", "secret" => "2kO5VcnVuCtcs2LY" ),
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ENVIRONMENT == 'development'),

		"debug_file" => APPPATH.'/logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */