<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_modify_categories extends CI_Migration {

    public function up()
    {
        // add status column to Table
        $this->dbforge->add_column('categories',array(
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => '4',
                'after' => 'parent_id'

            )
        ));
    }

}
