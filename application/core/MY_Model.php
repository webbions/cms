<?php

/**
 * Class TA_model
 */
class MY_model extends CI_Model
{
    protected $tablename;
    protected $pk;

    /**
     *
     */
    public function __construct()
    {
        $this->tablename = null;
        parent::__construct();
    }

    public function find_by_id($id){
        $query = $this->db->get_where($this->pk, $id);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function find_by_field($field,$value){
        $query = $this->db->get_where($this->pk, $id);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    /**
     * @param array $cond
     * @return bool
     */
    public function exists($cond = array())
    {
        $query = $this->db->get_where($this->tablename, $cond);
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /**
     * @param $conditions
     * @param $fields
     * @param array $top
     * @return array
     */
    public function get_list($conditions, $fields, $top = array())
    {
        $return = $top;
	
        $query = $this->db->get_where($this->tablename, $conditions);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $l) {
                $return[$l[$fields[0]]] = $l[$fields[1]];
            }
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * @param string $query
     * @param array $parameters
     * @param bool|false $getcount
     * @return array|mixed
     */
    public function query_sql($query = '', $parameters = array(), $getcount = false)
    {
        $result = $this->db->query($query, $parameters);
        if ($result) {
            if ($getcount == true) {
                $r = $result->row_array();
                return array_shift($r);
            } else {
                return $result->result_array();
            }
        } else {
            return array();
        }
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return mixed
     */
    public function execute_sql($query = '', $parameters = array())
    {
        $result = $this->db->query($query, $parameters);
        return $result;
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function count($conditions = array())
    {
        $this->db->where($conditions);
        return $this->db->count_all_results($this->tablename);
    }

    /**
     * @param array $conditions
     * @return bool
     */
    public function get($conditions = array())
    {
        $this->db->limit(1);
        $query = $this->db->get_where($this->tablename, $conditions);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array/bool
     */
    public function gets( $limit = FALSE, $offset = FALSE )
    {
		$query = $this->db->get( $this->tablename, $limit, $offset );
		if( $query->num_rows() > 0 ){
            return $query->result_array();
		}
		
		return FALSE;
    }
	
    /**
     * @return bool
     */
	public function count_all_results()
	{
		return $this->db->count_all_results( $this->tablename );
	}
	
    /**
     * @return bool
     */
	public function count_all()
	{
		return $this->db->count_all( $this->tablename );
	}	
	

    /**
     * @param array $conditions
     * @param array $sorts
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function all($conditions = array(), $sorts = array(), $offset = 0, $limit = 0)
    {
        if ($limit > 0)
            $this->db->limit($limit, $offset);

        $this->db->where($conditions);
        foreach ($sorts as $field => $order) {
            $this->db->order_by($field, $order);
        }
        $query = $this->db->get($this->tablename);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }


    /**
     * @param array $cond
     * @param array $data
     * @return bool
     */
    public function update($cond = [], $data = array())
    {
        $this->db->where($cond);

        if ($this->db->update($this->tablename, $data)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $data
     * @return bool
     */
    public function insert($data)
    {
        if ($this->db->insert($this->tablename, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * @param $cond
     * @return bool
     */
    public function delete($cond)
    {
        $this->db->where($cond);

        if ($this->db->delete($this->tablename)) {
            return true;
        } else {
            return false;
        }
    }

    //below functions are just for filteration purpise

    function get_state_post($var,$default=''){

        //if post and value is empty
        if($this->input->server('REQUEST_METHOD') == "POST" && $this->input->post($var) == null){
            //reset field
            $this->session->unset_userdata('state_'.$var);
            return $default;
        }



        if ($this->input->post($var) !== null){
            $this->session->set_userdata('state_'.$var,$this->input->post($var));
            return $this->input->post($var);
        }else if ($this->session->userdata('state_'.$var) != false){
            return $this->session->userdata('state_'.$var);
        }else{
            return $default;
        }
    }

}


class EL_Model{
    protected $index;
    protected $type;

    protected $index_name;
    protected $type_name;

    protected $client;
    protected $pk;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new \Elastica\Client(array('connections' => array(array('host' => $this->config->item('elastic_host'), 'port' => 9200))));
        if($this->client){
            $this->index = $this->client->getIndex($this->index_name);
            $this->type = $this->index->getType($this->type_name);
        }

    }
}