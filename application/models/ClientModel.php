<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ClientModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->pk = "id";
        $this->tablename = "clients_info";
    }
    
	
	
}