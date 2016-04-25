<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdminUserModel extends MY_Model
{
	protected $usergrouptable;
	
    public function __construct()
    {
        parent::__construct();
        $this->pk = "id";
        $this->tablename = "users";
        $this->usergrouptable = "users_groups";

    }

    public function getUserByGroup($group_id = MEMBER_GROUP){

    	try{
            $this->db->select('u.id, CONCAT(u.first_name,\' \',u.last_name) as name');
	        $this->db->from($this->tablename. " as u");
	        $this->db->join($this->usergrouptable. " as ug", 'ug.user_id = u.id');
	        $this->db->where('ug.group_id', $group_id);
	        $this->db->where('ug.group_id <>', ADMIN_GROUP);
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    	
    }
	
	
	
}