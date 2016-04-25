<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_add_name_audio_video_tables extends CI_Migration {

    public function up()
    {
        $this->db->query(" ALTER TABLE `userprofileaudio` ADD COLUMN `audio_name`  VARCHAR(255) NULL AFTER `audio`;");
        $this->db->query(" ALTER TABLE `userprofilevideo` ADD COLUMN `video_name`  VARCHAR(255) NULL AFTER `video`;");
    }

}
