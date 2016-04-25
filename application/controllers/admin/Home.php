<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	protected $layout = 'admin_default';

	function __construct() {
		parent::__construct();
		$this->ensure_group(['admin']);
	}

	public function index() {
		$this->dashboard();
	}
	public function dashboard() {
		$data = [];
		$data['page_title'] = 'Dashboard';
		$data['page_header'] = 'Dashboard';
		$this->template->load($this->layout, 'admin/home/dashboard', $data);
	}
	public function profile() {
		$data = [];
		$this->template->load($this->layout, 'admin/home/profile', $data);
	}

}
