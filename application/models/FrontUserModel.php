<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FrontUserModel extends MY_Model
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
	public function getCategory(){
		try{
            $this->db->select('id,name');
	        $this->db->from('categories');
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    }

    public function categoryBatchInsert($data)  {
        try{
            $this->db->insert_batch('user_interests', $data);
        } catch (Exception $ex) {
            return '';
        }
    }
	
	public function profileInsert($data)  {
		
        try{
            $this->db->insert('user_profile', $data);
        } catch (Exception $ex) {
            return '';
        }
    }
	
	public function profileUpdate($data)  {
		$cond = array('user_id' => $data['user_id']);
        try{
			$this->db->where('user_id', $data['user_id']);
   		    $this->db->update('user_profile',$data);
            //$this->db->update('user_profile', $data, $cond);
        } catch (Exception $ex) {
            return '';
        }
    }
	
	public function getprofile($id)  {
        try{
		   $this->db->select('*');
	        $this->db->from('user_profile');
            $this->db->where('user_id', $id);
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    }
	
	public function gettalentcatforcheck($uid,$catid)  {
        try{
		   $this->db->select('*');
	        $this->db->from('user_interests');
            $this->db->where('userID', $uid);
			$this->db->where('categoryID', $catid);
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    }
	public function gettalentcategory($id)  {
        try{
		   $this->db->select('*');
	        $this->db->from('user_interests');
            $this->db->where('userID', $id);
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    }
	
	public function deleteCatFromTalent($id)  {
        try{
			$this->db->where('userInterestID', $id);
			$this->db->delete('user_interests'); 
        } catch (Exception $ex) {
            return '';
        }
    }



}