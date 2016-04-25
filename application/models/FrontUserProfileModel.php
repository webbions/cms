<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FrontUserProfileModel extends MY_Model
{
	protected $usergrouptable;
	
    public function __construct()
    {
        parent::__construct();
        $this->pk = "id";
        $this->tablename = "userprofileimages";
		$this->tablenamevideo = "userprofilevideo";
		$this->tablenameaudio = "userprofileaudio";

    }

    public function getimages($userid){

    	try{
            $this->db->select('*');
	        $this->db->from($this->tablename);
            $this->db->where('userid', $userid);
	        $this->db->order_by('id', 'desc');
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    	
    }
	
	 public function addvideo($data){
    	 if ($this->db->insert($this->tablenamevideo, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    	
    }
	public function getvideos($userid){

    	try{
            $this->db->select('*');
	        $this->db->from($this->tablenamevideo);
	        $this->db->where('userid', $userid);
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    	
    }
	public function addaudio($data){
    	 if ($this->db->insert($this->tablenameaudio, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    	
    }
	public function getaudios($userid){

    	try{
            $this->db->select('*');
	        $this->db->from($this->tablenameaudio);
	        $this->db->where('userid', $userid);
            $result = $this->db->get();
            return $result->result_array();
        } catch (Exception $ex) {
            return '';
        }
    	
    }
	
	
	
	
	
	
}