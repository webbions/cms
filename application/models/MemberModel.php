<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MemberModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->pk = "id";
        $this->tablename = "members";
    }
    
  /*   function get_user($usr, $pwd)
     {
        
          $sql = "select * from client where username = '" . $usr . "' and password = '" . md5($pwd) . "' and user = 'Members'";
          $query = $this->db->query($sql);
          return $query->num_rows();
     }
	*/
	
	
}