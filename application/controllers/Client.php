<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends MY_Controller {
    
    
    	protected $layout = 'admin_default';

	function __construct()
	{
		parent::__construct();
		$this->ensure_group(['admin']);		
		$this->load->model(['ClientModel']);
                $this->load->model(['MemberModel']);
		
	}
        

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	 //get the posted values
      
          $username = $this->input->post("username");
          $password = $this->input->post("password");
         

          //set validations
          $this->form_validation->set_rules("username", "Username", "trim|required");
          $this->form_validation->set_rules("password", "Password", "trim|required");
    

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
               $this->load->view('client');
               
          }
          else
          {
               //validation succeeds
              if ($this->input->server('REQUEST_METHOD') == 'POST') 
               {
                    //check if username and password is correct
                    $clientrecord = $this->ClientModel->get(['username' => $username, 'password'=>$password]);
                    if ($clientrecord === false){
                        
                     $memberrecord = $this->MemberModel->get(['username' => $username, 'password'=>$password]);
                       
                    if ($memberrecord > 0) //active user record is present
                    {
                         //set the session variables
                        
                         $sessiondata = array(
                              'username' => $username, 
                              'loginuser' => TRUE
                         );
                         $this->session->set_userdata($sessiondata);
                         $this->load->view('memberhome');
                        
                    }
                    else
                    {
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid username and password!</div>');
                         redirect('Client/index');
                    }
               }
                        
                    if ($clientrecord > 0) //active user record is present
                    {
                         //set the session variables
                         $sessiondata = array(
                              'username' => $username, 
                              'loginuser' => TRUE
                         );
                         $this->session->set_userdata($sessiondata);
                         $this->load->view('clienthome');
                        
                    }
                    else
                    {
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid username and password!</div>');
                         redirect('Client/index');
                    }
               }
          
               else
               {
                    redirect('Client/index');
               }
          }

     }
     
      public function Signup()
     {
        
     $this->form_validation->set_rules("username", "Username", "trim|required");
     $this->form_validation->set_rules("password", "Password", "trim|required");

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
              $this->load->view('signup_view');
          }
          else
          {
                if ($this->input->post('submit'))
                {
                    $data = array(
                              'username' =>$this->input->post('username'),
                              'password' =>md5($this->input->post('password')),
                              'user'   =>$this->input->post('user')
                                );
          
          $id = $this->ClientModel->insert($data);
          redirect('Client/index');
          }
     }
     
     }
     
      public function logout()
     {
     
          $this->session->sess_destroy();
          redirect('Client/index');
     }

     

     
     
}