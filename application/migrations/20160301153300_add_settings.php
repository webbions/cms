<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_settings extends CI_Migration {

	public function up() {
		// Drop table 'groups' if it exists
		$this->dbforge->drop_table('settings', TRUE);

		// Table structure for table 'groups'
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
				'constraint' => '11',

			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'value' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'created_at' => array(
				'type' => 'DATETIME',
			),
			'updated_at' => array(
				'type' => 'DATETIME',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('settings');

	}

	public function down() {
		$this->dbforge->drop_table('settings', TRUE);
	}
}
