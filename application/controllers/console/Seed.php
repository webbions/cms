<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Seed extends Console_Controller
{
    private $dummy_email_id;
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->dummy_email_id = 0;
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function index()
    {
        $this->seed_countries();
        $this->seed_cmspages();
        /*$this->seed_proxy();
        $this->seed_countries();
        $this->seed_languages();
        $this->seed_timezones();
        $this->seed_names();
        $this->seed_sms_api();
        $this->seed_bio_categories();
        $this->seed_picture_categories();
        $this->seed_phone_numbers();
        $this->seed_emails();
        $this->seed_bios();
        $this->seed_profile_pictures();
        $this->seed_job_types();
        $this->seed_library_types();
        $this->seed_name_categories();
        $this->seed_email_categories();
        $this->seed_user_agents();

        $this->seed_accounts();
        

        $this->seed_update_proxy();
        $this->seed_update_email();
        $this->seed_update_names();

        $this->update_job_types();
        $this->seed_translation_languages();*/

        //disabled seeds because we need to preserve translations
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_cmspages()
    {
        $this->db->truncate('cmspages');
        $query = file_get_contents(APPPATH . "sql/cmspages.sql");
        if($this->db->simple_query($query)){
            $this->log('cmspages imported');
        }else{
            echo $this->db->last_query();
            $this->log('Error in import');
        }
    }

    public function update_job_types(){
        $this->db->query("UPDATE  `job_types` SET  `show_in_ui` =  '0' WHERE  `job_types`.`job_type_id` =17;");
        $this->db->query("UPDATE  `job_types` SET  `show_in_ui` =  '0' WHERE  `job_types`.`job_type_id` =14;");
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_library_types()
    {
        $this->db->truncate('library_types');
        $query = file_get_contents(APPPATH . "sql/library_types.sql");

        $this->db->simple_query($query);
        $this->log('library types imported');
    }


    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_job_types()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

        $this->db->truncate('job_types');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        $query = file_get_contents(APPPATH . "sql/job_types.sql");

        $this->db->simple_query($query);
        $this->log('job types imported');
    }


    public function _import_dump($filename)
    {
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                try {
                 $this->db->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                }catch(Exception $e){

                }
                // Reset temp variable to empty
                $templine = '';
            }
        }
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_accounts(){
        $this->load->model([
            'TimezoneModel',
            'AccountModel',
            'LanguageModel',
            'CountryModel',
            'AccountInformationModel',
            'TagModel',
            'AccountTagModel'

        ]);

        //default language_id => 32
        //timezone_id => 81
        //country_id => 88
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

        $this->db->truncate('accounts');
        $this->db->truncate('account_information');
        $this->db->truncate('tags');
        $this->db->truncate('account_tags');

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        if(ENVIRONMENT == "development") {
            require_once(APPPATH . "sql/users.php");
        }else{
            require_once(APPPATH."sql/users.php");
            //require_once(APPPATH."sql/users_tw.php");
        }




        foreach($users as $user){

           $timezone_id = 81;
           if($user['timezone'] != ''){
               $cond = ['value' => $user['timezone']];
               $timezone = $this->TimezoneModel->get($cond);
               if($timezone){
                   $timezone_id =  $timezone['timezone_id'];
               }
           }

           $language_id = 32;
           if($user['language'] != ''){
               $cond = ['value' => $user['language']];
               $language = $this->LanguageModel->get($cond);
               if($language){
                   $language_id =  $language['language_id'];
               }
           }

           $country_id = 88;
           if($user['country'] != ''){
               $cond = ['value' => $user['country']];
               $country = $this->CountryModel->get($cond);
               if($timezone){
                   $country_id =  $country['country_id'];
               }
           }


            /*
            -128
            -20 phone number not availble
            -11 incorrect login information
            -10 suspended
            -5 locaked
            -2 active but email required
            1
            */

           $status = $user['status'];

            $userAgentArray = array(
                "Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0",
            );

           $account = [
               'timezone_id' => $timezone_id,
               'language_id' => $language_id,
               'country_id' => $country_id,
               'account_id' => $user['user_id'],
               'proxy_id' => $user['proxy_id'],
               'email_id' => (int)$user['email_id'],
               'phone_number_id' => (int)$user['phone_number_id'],
               'bio_id' => $user['bio_id'],
               'profile_picture_id' =>  $user['profile_picture_id'],
               'gender' => ($user['gender']== "")?"u":$user['gender'],
               'first_name' => $user['firstname'],
               'last_name' => $user['lastname'],
               'created_at' => $user['created_at'],
               'status' => $status,
               'web_useragent' => $userAgentArray[array_rand($userAgentArray)],
               "user_agent" => $user['user_agent_string'],
               'is_email_verified' => $user['is_verified_email'],
               'is_phone_verified' => $user['is_verified_phone'],
               'identification_type' => $user['identification_type']
           ];

           $this->AccountModel->insert($account);

           //insert availble account information
           $account_information = [
               'account_id' => $user['user_id'],
               'username' => $user['twitter_login'],
               'password' => $user['twitter_password'],
               'name' => $user['firstname']." ".$user['lastname']

           ];

            $this->AccountInformationModel->insert($account_information);

            //check for tags and add tag
            if($user['tag'] != ''){
                $tag = $this->TagModel->get(['title' => $user['tag']]);
                if($tag){
                    $account_tag = [
                        'account_id' => $user['user_id'],
                        'tag_id' => $tag['tag_id'],
                        'created_at' =>  date('Y-m-d H:i:s')
                    ];

                    $this->AccountTagModel->insert($account_tag);
                }else{
                    $tag = ['title' => $user['tag'],'created_at' => date('Y-m-d H:i:s')];
                    $tag_id = $this->TagModel->insert($tag);
                    $account_tag = [
                        'account_id' => $user['user_id'],
                        'tag_id' => $tag_id,
                        'created_at' =>  date('Y-m-d H:i:s')
                    ];

                    $this->AccountTagModel->insert($account_tag);
                }
            }


        }

        $this->log("Accounts imported successfully");
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_timezones()
    {
        $this->db->truncate('timezones');
        $query = file_get_contents(APPPATH . "sql/timezones.sql");
        $this->db->simple_query($query);
        $this->log('timezones imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_languages()
    {
        $this->db->truncate('languages');
        $query = file_get_contents(APPPATH . "sql/languages.sql");
        $this->db->simple_query($query);
        $this->log('languages imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_names()
    {
        $this->db->truncate('names');
        $this->_import_dump(APPPATH . "sql/names.sql");
        $this->log('names imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_countries()
    {
        $this->db->truncate('countries');
        $query = file_get_contents(APPPATH . "sql/countries.sql");
        $this->db->simple_query($query);
        $this->log('countries imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_proxy()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $this->db->truncate('proxy');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        $this->_import_dump(APPPATH . "sql/proxy.sql");
        $this->log('proxy imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_bio_categories()
    {
        $this->db->truncate('bio_categories');
        $this->_import_dump(APPPATH . "sql/bio_categories.sql");
        $this->log('bio_categories imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_bios()
    {
        $this->db->truncate('bios');
        $this->_import_dump(APPPATH . "sql/bios.sql");
        $this->log('bios imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_emails()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

        $this->db->truncate('emails');


        $this->_import_dump(APPPATH . "sql/emails.sql");
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        //add dummy email to validate fk check in accounts for phone accounts
        $this->dummy_email_id = $this->db->insert('emails',['proxy_id' => 1,"email"=>"phoneaccount@noemail.com"]);

        $this->log('emails imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_sms_api()
    {
        $this->db->truncate('sms_api');
        $this->_import_dump(APPPATH . "sql/sms_api.sql");
        $this->log('sms_api imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_phone_numbers()
    {
        $this->db->truncate('phone_numbers');
        $this->_import_dump(APPPATH . "sql/phone_numbers.sql");
        $this->log('phone_numbers imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_picture_categories()
    {
        $this->db->truncate('picture_categories');
        $this->_import_dump(APPPATH . "sql/picture_categories.sql");
        $this->log('picture_categories imported');
    }

    /**
     * @author Mrudul Shah <mrudul@webbions.com>
     */
    public function seed_profile_pictures()
    {
        $this->db->truncate('profile_pictures');
        $this->_import_dump(APPPATH . "sql/profile_pictures.sql");
        $this->log('profile_pictures imported');
    }
    
    /**
     * @author Rahul Bhatewara <webassic@gmail.com>
     */
    public function seed_name_categories()
    {
        $this->db->truncate('name_categories');
        $this->_import_dump(APPPATH . "sql/name_categories.sql");
        $this->log('name_categories imported');
    }

     /**
     * @author Rahul Bhatewara <webassic@gmail.com>
     */
    public function   seed_email_categories()
    {
        $this->db->truncate('email_categories');
        $this->_import_dump(APPPATH . "sql/email_categories.sql");
        $this->log('email_categories imported');
    }

    /**
     * @author Ahmed Khan <a2zbits@gmail.com>
     */
    public function seed_user_agents()
    {
        $this->db->truncate('user_agents');
        $query = file_get_contents(APPPATH . "sql/user_agents.sql");

        $this->db->simple_query($query);
        $this->log('User Agents imported');
    }
    
    /**
     * @author Rahul Bhatewara <webassic@gmail.com>
     */
    public function seed_update_proxy() {
        // Update existing proxy category ids 
        $this->db->query('
            Update proxy as P
                inner join (
                  select proxy_category_id, proxy_category
                  from proxy_categories
                ) as PC on P.source = PC.proxy_category
            set P.proxy_category_id = PC.proxy_category_id;
        ');
        $this->log('proxy updated');
    }
    
     /**
     * @author Rahul Bhatewara <webassic@gmail.com>
     */
    public function seed_update_email() {
        // Update existing email category ids 
        $this->db->query('
            UPDATE `emails` SET `email_category_id` = "1" WHERE `emails`.`email_id` > 0
        ');
        
        $this->log('emails updated');
    }
    
    /**
     * @author Rahul Bhatewara <webassic@gmail.com>
     */
    public function seed_update_names() {
        // Update existing names category ids 
        $this->db->query('
            UPDATE `names` SET `name_category_id` = "1" WHERE `names`.`name_id` > 0
        ');
        
        $this->log('names updated');
    }

    /**
    
     */
    public function seed_translation_languages()
    {
    	
    	$this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
    	$this->db->truncate('translation_languages');
    	$this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
    	$this->_import_dump(APPPATH . "sql/translation_languages.sql");
    	$this->log('translation languages imported');
    	
    }

}
