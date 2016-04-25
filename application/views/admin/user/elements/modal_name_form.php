<!-- Name Modal -->
<div class="modal fade" id="modal_name_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New User</h4>
            </div>
       <!--<h1><?php echo lang('create_user_heading');?></h1>
<p><?php echo lang('create_user_subheading');?></p> -->
<style>
.red{
  color:#F00;   
}

</style>
<section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <!--<div class="box-header">
                  <h3 class="box-title">User Pages</h3>
                    <a class="btn btn-primary btn-flat pull-right" href="<?php echo base_url()?>admin/user/index"><i class="fa fa-arrow-left"></i> Back </a>
                </div><!-- /.box-header -->
               
                 <div class="content">
                   <div id="infoMessage" class="modal-body"></div>
                   <div class="red">* Mandatory field(s)</div>
                   <form method="post" enctype="multipart/form-data" action="<?php echo base_url();?>admin/user/add" class="form-horizontal group-border-dashed" id="addUserForm" name="addUserForm">
                    
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
                            <select class="form-control" name="group">
                                <?php  foreach($goupoptions as $gRow){ if($gRow['id'] == 1){ continue;} ?>
                                <option value="<?php echo $gRow['id'];?>"><?php echo $gRow['name'];?></option>
                                <?php }?>
                            </select>
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
                          <button type="button" id="btn_create_action" class="btn btn-success">Submit</button>
                          <input type="reset"  class="btn btn-primary" />
                          <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>
                      </div>
                    </div>

                  </form>
                            
                </div>
              </div>
        </div>
    </div>
</section>
        </div>
    </div>
</div><!-- /.Name Modal -->

<script>
    $(function() {

        $('input[type="radio"].flat-green').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-green'
        });
        $('input[type="radio"].flat-red').iCheck({
            radioClass: 'iradio_flat-red'
        });


        var btn = $('#btn_create_action');

        btn.click(function() {
			
            btn.button('loading');

            var action_url = '<?php echo base_url("/admin/user/add") ?>';
            var form_data = $("#addUserForm").serialize();

            var fnCallback = function(result) {
                if (result.success == false) {
				    $(".modal-body").html('');
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                } else {
					$(".modal-body").html('');
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                    oTable.draw();

                    setTimeout(function() {
                        $('#btn_create_cancel_modal').click();
                    }, 1000);

                }
                $('#action_modal').animate({scrollTop: 0}, 'slow');
            }

            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': action_url,
                'data': form_data,
                'success': fnCallback
            });
        });
    })
</script>