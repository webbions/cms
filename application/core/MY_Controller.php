<?php

if ( !defined('BASEPATH') )
	exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
  protected $data;
  protected $_user;
  // Layout set..
  protected $layout = 'layout';
    
  function __construct(){
      parent::__construct();
      $this->_user = null;
      $this->_set_defaults();
  }

  
	// Dynamic content..
	protected function render($content) {
		// Load helper..
		$this->load->helper('breadcrumbs_helper');
		$view_data = array(
			'content' => $content,
		);
		$this->load->view($this->layout, $view_data);
	}

  function _set_defaults(){
      if ($this->ion_auth->logged_in())
      {
          //logged in user for views and controller
          $this->data['_user'] = $this->_user = $this->ion_auth->user()->row_array();
      }
  }
  
  function ensure_group($group = "members"){
	
	 // $group = 'admin';
	//pr($this->ion_auth->in_group($group));exit;	
        if($this->_user == null or !$this->_user['id']){
            if($group == 'admin'){
              redirect('/auth/login');
            }
            else{
              redirect('/user/login');
            }
        }

        if($this->_user != null && !$this->ion_auth->in_group($group)){
			
            $this->session->set_flashdata('error',"You don\'t have permissions to perform this operation!");
            //redirect('/auth/login');
            if($group == 'admin'){
              redirect('/auth/login');
            }
            else{
              redirect('/user/login');
            }
        }
    }
	
	
	
    
    function _render_json(){
        if(isset($this->data['_user']))
        unset($this->data['_user']);

        $this->data['json_msg'] = $this->load->view('/elements/display_message',$this->data,true);

        return $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($this->data));
    }

}

class MY_UserController extends MY_Controller 
{
 
    function __construct()
    {
      parent::__construct();
      $this->load->helper('url');
      if(!$this->input->is_cli_request())
      $this->checkLogin();
      
    }
    function checkLogin()
    {
        $redirectBack=uri_string();
        if(! $this->session->userdata('logged_in') && $redirectBack!='admin/login'){
          $this->session->set_userdata('back_url', $redirectBack);
        }
        $this->load->model('admin_model','',TRUE); 
        $this->admin_model->isLoggedin();
    }
}


class Console_Controller extends MY_Controller {
    public $time_count;
    public $timers;

    function __construct() {
        parent::__construct();
        $this->_set_defaults();
    }

    function _set_defaults(){
        parent::_set_defaults();
        $this->time_count = 0;
        $this->timers = array();

        //do not display notices
        error_reporting(E_ALL & ~E_NOTICE);

        set_time_limit(0);

        if(php_sapi_name() != 'cli'){
           $this->log("This script can not run from browser!");
           die;
        }
    }

    function processtime($id = null){
        if($id !== null){
            $time_taken =  microtime(true) - $this->timers[(int)$id];
            unset($this->timers[$id]);
            return $time_taken;
        }

        $this->timers[$this->time_count] = microtime(true);
        return (int)$this->time_count++;
    }


    function log($log){
        echo $log.PHP_EOL;
        return;
    }

}