<!--<h1><?php echo lang('create_user_heading');?></h1>
<p><?php echo lang('create_user_subheading');?></p> -->
<style>
.red{
  color:#F00;   
}
#infoMessage p{ color:#F00;; }
</style>
<section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">User Pages</h3>
                    <a class="btn btn-primary btn-flat pull-right" href="<?php echo base_url()?>admin/user/index"><i class="fa fa-arrow-left"></i> Back </a>
                </div><!-- /.box-header -->
                
                
                 <div class="content">
                   <div id="infoMessage"><?php echo $message;?></div>
                   <div class="red">* Mandatory field(s)</div>
                   <form method="post" enctype="multipart/form-data" action="<?php echo base_url();?>admin/auth/create_user" class="form-horizontal group-border-dashed" id="addUserForm" name="addUserForm">
                    
                    <input type="hidden" name="mode" value="" />
                     
                    <div class="form-group">
                        <label class="col-sm-3 control-label" ><?php echo lang('create_user_fname_label', 'first_name');?><span class="red">*</span>:</label>
                        <div class="col-sm-6">
                           <?php echo form_input($first_name);?>
                          <br>
                        </div>
                    </div>  
                          
                    <div class="form-group">
                      <label class="col-sm-3 control-label" ><?php echo lang('create_user_lname_label', 'last_name');?><span class="red">*</span>:</label>
                      <div class="col-sm-6">
                       <?php echo form_input($last_name);?>
                        <br>
                      </div>
                    </div>  
                     
                    <div class="form-group">
                      <label class="col-sm-3 control-label" ><?php echo lang('create_user_email_label', 'email');?>  <span class="red">*</span>:</label>
                      <div class="col-sm-6">
                       <?php echo form_input($email);?>
                        <br>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-3 control-label" > <?php echo lang('create_user_password_label', 'password');?><span class="red">*</span>:</label>
                      <div class="col-sm-6">
                          <?php echo form_input($password);?>
                        <br>
                      </div>
                    </div> 
                     
                    <div class="form-group">
                      <label class="col-sm-3 control-label" ><?php echo lang('create_user_password_confirm_label', 'password_confirm');?><span class="red">*</span>:</label>
                      <div class="col-sm-6">
                         <?php echo form_input($password_confirm);?>
                      </div>
                    </div> 

                    <div class="form-group">
                      <label class="col-sm-3 control-label">Seletct Group</label>
                      <div class="col-sm-6">
                           <?php echo form_dropdown('group', $goupoptions, '', 'class="form-control"'); ?>                           
                      </div>
                    </div>    
                      
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Status</label>
                      <div class="col-sm-6">
                           <?php echo form_dropdown('status', $statusoptions, '', 'class="form-control"'); ?>
                      </div>
                    </div>        
                      
                    <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-10">
                          <input name="submit" class="btn btn-primary" type="submit" value="Submit" />
                          <input type="reset"  class="btn btn-primary" />
                          <a href="<?php echo base_url()?>admin/user/index" class="btn btn-default">Cancel</a>
                      </div>
                    </div>

                  </form>
                            
                </div>
              </div>
        </div>
    </div>
</section>