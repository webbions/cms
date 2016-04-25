<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_add_catimg_categories extends CI_Migration {

    public function up()
    {
        $this->db->query(" ALTER TABLE `categories` ADD COLUMN `catimage`  text NULL AFTER `status`;");


    }

}
