<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
		protected $layout = 'front_master';
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
		//$this->ensure_group(['admin']);
		$this->load->model(['AdminCategoryModel']);
	}


	public function index()
	{
		$data = [];
		$data['page_title'] = 'Home';
		$data['page_header'] = 'Home';
		$categories = ['Top Level'];
		$data['categories']  = $this->AdminCategoryModel->get_list([], ['id', 'name']);

		//$data['categories'] = array_merge($categories, $dbCategories);
		$this->template->load($this->layout, 'front/home/index',$data);
		//$this->load->view('');
	}
}
