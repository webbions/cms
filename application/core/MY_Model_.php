<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class:  DB_Model
 * Desc:   Base class for database model
 * Author: Julian Tanjung <gudangsoft@gmail.com>
 */

class MY_Model extends CI_Model implements JsonSerializable {

    protected $data_set;
    protected $fields;
    protected $primary_key = 'id';
    protected $table_name;
    protected $hasOne = array();
    protected $hasMany = array();
    protected $secret_fields = array();
    protected $query_cache = array();
    private $relations;
    private $format;
    private $join_tables = '*';
    private $join_dir = 'left';
    private $data_id;
    private $foreign_data;

    public function __construct($id = NULL) {
        parent::__construct();

        $this->data_id = $id;
        $this->format = $this->relations = array();

        if (!$this->table_name) {
            $this->table_name = strtolower(get_class($this));
        }

        $this->reset_fields();

        if (is_numeric($this->data_id)) {
            $this->get_by_id($this->data_id);
        }
    }

    public function __get($key) {
        if ($key == 'db') {
            return parent::__get('db');
        }

        if (array_key_exists($key, $this->fields)) {
            return $this->fields[$key];
        }

        if (array_key_exists($key, $this->format)) {
            return $this->format[$key];
        }

        $has_one = $this->has_one($key);
        if ($has_one) {
            return $has_one;
        }

        $has_many = $this->has_many($key);
        if ($has_many) {
            return $has_many;
        }

        return NULL;
    }

    public function __set($key, $val) {
        $this->fields[$key] = $val;
    }

    public function jsonSerialize() {
        foreach ($this->hasOne as $key => $val) {
            if (is_string($val)) {
                $this->has_one($val);
                continue;
            }

            if (is_array($val)) {
                $this->has_one($key);
            }
        }

        foreach ($this->hasMany as $key => $val) {
            if (is_string($val)) {
                $this->has_many($val);
                continue;
            }

            if (is_array($val)) {
                $this->has_many($key);
            }
        }

        $relation = array_map(array($this, "serialize_child"), $this->relations);
        $__array = method_exists($this, "__toArray") ? $this->__toArray() : array();
        $fields = $this->get_properties();

        return array_merge($fields, $this->format, $relation, $__array);
    }

    private function do_format_fields($key, $value) {
        if (method_exists($this, 'format_' . $key)) {
            $this->format['formatted_' . $key] = $this->{ 'format_' . $key }();
        }

        return $value;
    }

    private function serialize_child($object) {
        return $object->get_properties();
    }

    public function get_properties() {
        if (!sizeof($this->secret_fields)) {
            return $this->fields;
        }

        $fields = array();
        foreach ($this->fields as $key => $val) {
            if (in_array($key, $this->secret_fields)) {
                continue;
            }

            $fields[$key] = $val;
        }

        return $fields;
    }

    public function get_info() {
        return array_to_json(array(
            'id' => $this->primary_key,
            'name' => $this->table_name,
            'relation' => $this->table_name . '.' . $this->primary_key,
            'columns' => array_map(array($this, 'column_alias'), $this->fields)
        ));
    }

    private function column_alias($value) {
        return $this->table_name . '.' . $value . ' as ' . $this->table_name . '_' . $value;
    }

    protected function reset_fields() {
        $this->fields = array_flip($this->db->list_fields($this->table_name));
        $this->fields = array_map(create_function('$n', 'return null;'), $this->fields);
    }

    protected function before_get() {
        
    }

    protected function after_get() {
        
    }

    public function row($index = 0) {
        if (!sizeof($this->data_set)) {
            return FALSE;
        }

        return $this->data_set[$index];
    }

    private function queue_query_cache() {
        $args = func_get_args();
        $function = $args[0];
        unset($args[0]);

        $query = array(
            'function' => $function,
            'argument' => $args
        );

        array_push($this->query_cache, $query);
        $this->run_query_cache($query);
    }

    private function run_query_cache($query) {
        call_user_func_array(array($this->db, $query['function']), $query['argument']);
    }

    public function select($fields = '*') {
        $field_select = is_string($fields) ? explode(',', $fields) : $fields;
        if (!is_array($field_select)) {
            $field_select = array($this->table_name . '.*');
        }

        $field_select = array_map('trim', $field_select);

        if ($fields != '*') {
            if (array_search($this->primary_key, $field_select) == -1) {
                array_push($field_select, $this->primary_key);
            }
        }

        $self_all = array_search('*', $field_select);
        if ($self_all > 0) {
            $field_select[$self_all] = $this->table_name . '.*';
        }

        $this->queue_query_cache('select', implode(',', $field_select));
        return $this;
    }

    public function select_max($field, $alias = FALSE) {
        $this->queue_query_cache('select_min', $field, $alias);
        return $this;
    }

    public function select_min($field, $alias = FALSE) {
        $this->queue_query_cache('select_min', $field, $alias);
        return $this;
    }

    public function select_avg($field, $alias = FALSE) {
        $this->queue_query_cache('select_avg', $field, $alias);
        return $this;
    }

    public function select_sum($field, $alias = FALSE) {
        $this->queue_query_cache('select_sum', $field, $alias);
        return $this;
    }

    public function get($limit = FALSE, $offset = FALSE, $as_array = FALSE ) {
        $this->data_set = $this->relations = array();
        $this->before_get();

        if ($this->data_id) {
            $this->db->where($this->primary_key, $this->data_id);
        }
        if (isset($this->foreign_data['key'])) {
            $this->db->where($this->foreign_data['key'], $this->foreign_data['value']);
        }

        //$this->select();
        $query = $this->db->get($this->table_name, $limit, $offset);
        if (!$query->num_rows()) {
            $this->reset_fields();
            return $this;
        }
		
		if( $as_array ){
			$this->after_get();
			$this->query_cache = array();
			$this->data_set = $query->result_array();
			return $this->data_set;
		}
		
        $this->data_set = $query->result();
        $this->assign_data(json_object_to_array($this->data_set[0]));

        $this->after_get();
        $this->query_cache = array();

        return $this;
    }

    public function set_foreign_data($key, $value) {
        $this->foreign_data = [
            'key' => $key,
            'value' => $value
        ];
        return $this;
    }

    public function get_by_id($id) {
        $this->where($this->primary_key, $id);
        return $this->get(1);
    }

    public function get_as_list($fields) {
        if (!is_array($fields)) {
            return FALSE;
        }
        if (sizeof($fields) < 2) {
            return FALSE;
        }

        $query = $this->db->get($this->table_name);

        if (!$query->num_rows()) {
            $this->reset_fields();
            return FALSE;
        }

        $data_set = $query->result();
        $result = [];
        foreach ($data_set as $data) {
            $result[$data->{ $fields[0] }] = $data->{ $fields[1] };
        }

        return $result;
    }

    private function construct_dataset($data_set) {
        $object = get_class($this);
        $object = new $object;
        $object->assign_data(json_object_to_array($data_set));
        return $object;
    }

    public function result() {
        if (!is_array($this->data_set)) {
            return array($this);
        }

        return array_map(array($this, "construct_dataset"), $this->data_set);
    }

    public function result_count() {
        return sizeof($this->data_set);
    }

    public function is_empty() {
        return $this->result_count() == 0;
    }

    public function result_array() {
        return json_object_to_array( $this->data_set );
    }

    public function assign_data($data) {
        $this->reset_fields();
        $this->fields = array_merge($this->fields, $data);
        $this->format = array();
        array_map(array($this, "do_format_fields"), array_keys($this->fields), $this->fields);
    }

    public function save() {
        $data_id = $this->fields[$this->primary_key] ? $this->fields[$this->primary_key] : FALSE;
        if ($data_id) {
            $this->db->where($this->primary_key, $data_id);
            return $this->update($this->fields);
        }

        return $this->insert($this->fields);
    }

    public function insert($dataset) {
        $this->db->insert($this->table_name, $dataset);
        return $this->db->insert_id();
    }

    public function update($dataset) {
        if (isset($dataset[$this->primary_key])) {
            unset($dataset[$this->primary_key]);
        }

        $result = $this->db->update($this->table_name, $dataset);
        $this->query_cache = array();
        return $result;
    }

    public function delete() {
        if( ! sizeof( $this->query_cache ) && ! $this->fields[$this->primary_key] ) {
            return $this->db->truncate($this->table_name);
        }

        if( sizeof( $this->query_cache ) ) {
			$result = $this->db->delete($this->table_name);
			$this->query_cache = array();
			return $result;
        }

        if ($this->result_count() > 1) {
            foreach ($this->result() as $row) {
                $row->delete();
            }
        }

        $this->db->where($this->primary_key, $this->fields[$this->primary_key]);
        $result = $this->db->delete($this->table_name);
        $this->query_cache = array();
        return $result;
    }

    public function truncate() {
		return $this->db->truncate($this->table_name);
	}

    public function where($key, $value = FALSE, $backtick = TRUE) {
        $this->queue_query_cache('where', $key, $value, $backtick);
        return $this;
    }

    public function or_where($key, $value = FALSE, $backtick = TRUE) {
        $this->queue_query_cache('or_where', $key, $value, $backtick);
        return $this;
    }

    public function where_in($key, $value) {
        $this->queue_query_cache('where_in', $key, $value);
        return $this;
    }

    public function or_where_in($key, $value) {
        $this->queue_query_cache('or_where_in', $key, $value);
        return $this;
    }

    public function where_not_in($key, $value) {
        $this->queue_query_cache('where_not_in', $key, $value);
        return $this;
    }

    public function or_where_not_in($key, $value) {
        $this->queue_query_cache('or_where_not_in', $key, $value);
        return $this;
    }

    public function like($key, $value = FALSE, $wildcard = NULL) {
        $this->queue_query_cache('like', $key, $value, $wildcard);
        return $this;
    }

    public function or_like($key, $value = FALSE, $wildcard = NULL) {
        $this->queue_query_cache('or_like', $key, $value, $wildcard);
        return $this;
    }

    public function not_like($key, $value = FALSE, $wildcard = NULL) {
        $this->queue_query_cache('not_like', $key, $value, $wildcard);
        return $this;
    }

    public function or_not_like($key, $value = FALSE, $wildcard = NULL) {
        $this->queue_query_cache('or_not_like', $key, $value, $wildcard);
        return $this;
    }

    public function group_by($key) {
        $this->queue_query_cache('group_by', $key);
        return $this;
    }

    public function order_by($key, $type = FALSE) {
        $this->queue_query_cache('order_by', $key, $type);
        return $this;
    }

    public function limit($limit, $offset = FALSE) {
        $this->queue_query_cache('limit', $limit, $offset);
        return $this;
    }

    public function offset($value) {
        $this->queue_query_cache('offset', $value);
        return $this;
    }

    public function distinct($key) {
        $this->queue_query_cache('distinct', $key);
        return $this;
    }

    public function having($key, $value = FALSE) {
        $this->queue_query_cache('having', $key, $value);
        return $this;
    }

    public function or_having($key, $value = FALSE) {
        $this->queue_query_cache('or_having', $key, $value);
        return $this;
    }

    public function count_all_results($reset = FALSE) {
        if (isset($this->foreign_data['key'])) {
            $this->db->where($this->foreign_data['key'], $this->foreign_data['value']);
        }

        $result = $this->db->count_all_results($this->table_name);
        if ($reset) {
            $this->query_cache = array();
            return $result;
        }

        if (sizeof($this->query_cache)) {
            array_map(array($this, 'run_query_cache'), $this->query_cache);
        }

        return $result;
    }

    public function count_all() {
        return $this->db->count_all($this->table_name);
    }

    public function last_query() {
        return $this->db->last_query();
    }

    private function join_one($key, $value) {
        $relation_name = $relation = NULL;
        switch (gettype($value)) {
            case 'string':

                $continue = $this->join_tables == '*' ? TRUE : (!is_array($this->join_tables) ? FALSE : in_array($value, $this->join_tables) );
                if (!$continue) {
                    return NULL;
                }

                $table_object = new $value;
                $info = $table_object->get_info();
                $relation_name = $info->name . ' as ' . $info->name;
                $relation = $info->relation . '=' . $this->table_name . '.' . $info->name . '_id';
                break;
            case 'array':

                $continue = $this->join_tables == '*' ? TRUE : (!is_array($this->join_tables) ? FALSE : in_array(strtolower($value['class']), $this->join_tables) );
                if (!$continue) {
                    return NULL;
                }

                $table_object = new $value['class'];
                $info = $table_object->get_info();
                $foreign_key = isset($value['foreignkey']) ? $value['foreignkey'] : $info->name . '_id';

                $relation_name = $info->name . ' as ' . $key;
                $relation = $key . '.' . $info->id . '=' . $this->table_name . '.' . $foreign_key;

                break;
        }

        if ($relation_name && $relation) {
            $this->queue_query_cache('join', $relation_name, $relation, $this->join_dir);
        }

        return NULL;
    }

    private function join_many($key, $value) {
        $relation_name = $relation = NULL;
        switch (gettype($value)) {
            case 'string':

                $continue = $this->join_tables == '*' ? TRUE : (!is_array($this->join_tables) ? FALSE : in_array($value, $this->join_tables) );
                if (!$continue) {
                    return NULL;
                }

                $table_object = new $value;
                $info = $table_object->get_info();
                $relation_name = $info->name . ' as ' . $info->name;
                $relation = $info->name . '.' . $this->table_name . '_id=' . $this->table_name . '.' . $this->primary_key . '_id';

                break;
            case 'array':

                $continue = $this->join_tables == '*' ? TRUE : (!is_array($this->join_tables) ? FALSE : in_array(strtolower($value['class']), $this->join_tables) );
                if (!$continue) {
                    return NULL;
                }

                $table_object = new $value['class'];
                $info = $table_object->get_info();
                $foreign_key = isset($value['foreignkey']) ? $value['foreignkey'] : $this->table_name . '_id';

                $relation_name = $info->name . ' as ' . $key;
                $relation = $key . '.' . $foreign_key . '=' . $this->table_name . '.' . $this->primary_key . '_id';

                break;
        }

        if ($relation_name && $relation) {
            $this->queue_query_cache('join', $relation_name, $relation, $this->join_dir);
        }

        return NULL;
    }

    public function join($tables = '*', $dir = 'left') {
        $this->join_tables = $tables;
        $this->join_dir = $dir;

        if ($this->join_tables != '*' && is_string($this->join_tables)) {
            $this->join_tables = explode(',', $this->join_tables);
        }

        if (is_array($this->join_tables)) {
            $this->join_tables = array_map('trim', $this->join_tables);
        }

        array_map(array($this, "join_one"), array_keys($this->hasOne), $this->hasOne);
        array_map(array($this, "join_many"), array_keys($this->hasMany), $this->hasMany);

        return $this;
    }

    private function has_one($model) {
        if (isset($this->relations[$model])) {
            return $this->relations[$model];
        }

        $index = array_search($model, $this->hasOne);
        if (!$index && !isset($this->hasOne[$model])) {
            return NULL;
        }

        $model_name = $index ? $model : $this->hasOne[$model]['class'];
        $foreign_id = $index ? $model . '_id' : $this->hasOne[$model]['foreignkey'];

        $this->relations[$model] = new $model_name($this->fields[$foreign_id]);
        return $this->relations[$model];
    }

    private function has_many($model) {
        if (isset($this->relations[$model])) {
            return $this->relations[$model];
        }

        $index = array_search($model, $this->hasMany);
        if (!$index && !isset($this->hasMany[$model])) {
            return NULL;
        }

        $model_name = $index ? $model : $this->hasMany[$model]['class'];
        $foreign_id = $index ? $model . '_id' : $this->hasMany[$model]['foreignkey'];

        $this->relations[$model] = new $model_name;

        if (isset($this->hasMany[$model]['orderby'])) {
            switch (gettype($this->hasMany[$model]['orderby'])) {
                case 'string':
                    $this->relations[$model]->order_by($this->hasMany[$model]['orderby']);
                    break;
                case 'array':
                    $this->relations[$model]->order_by(
                            $this->hasMany[$model]['orderby'][0], $this->hasMany[$model]['orderby'][1]
                    );
                    break;
            }
        }

        $this->relations[$model]->set_foreign_data($foreign_id, $this->fields[$this->primary_key]);
        return $this->relations[$model]->get();
    }

    /**
     * @param array $cond
     * @param array $data
     * @return bool
     */
    public function update_all($cond = [], $data = array()) {
        $this->db->where($cond);

        if ($this->db->update($this->table_name, $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function count($conditions = array()) {
        $this->db->where($conditions);
        return $this->db->count_all_results($this->table_name);
    }

}
