<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i>Update User</h4>
</div>
<?php echo form_open_multipart("/admin/user/edit/". $user->id,array('id' => 'form_edit_action')); ?>
 
<div class="modal-body">
    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            First Name
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Fisrt Name" name="first_name" id="first_name" class="form-control"  value="<?php echo $user->first_name; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Last Name
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Last Name" name="last_name" id="last_name" class="form-control"  value="<?php echo $user->last_name; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Email
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Email" name="email" id="email" class="form-control"  value="<?php echo $user->email; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Password
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="password" name="password" id="password" class="form-control"  value="">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Confirm Password
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control"  value="">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Select Group
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
        
            <select class="form-control" name="group">
				<?php  foreach($goupoptions as $gRow){ if($gRow['id'] == 1){ continue;} 
					$selected = $currentGroups[0]->id == $gRow['id']? 'selected="selected"' : '';
				?>
                	<option  value="<?php echo $gRow['id'];?>"  <?php echo $selected;?>><?php echo $gRow['name'];?></option>
                <?php }?>
            </select>

        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Status
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <?php echo form_dropdown('status', $statusoptions, '', 'class="form-control"'); ?>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">                
    <button type="button" id="btn_edit_action" class="btn btn-success">Submit</button>
    <button type="button" id="btn_edit_cancel_modal" class="btn btn-default" data-dismiss="modal">Cancel</button>
    
</div>
<?php echo form_close(); ?>





<script>
    
    $(function() {
        var btn = $('#btn_edit_action');
        
        btn.click(function() {
            btn.button('loading');

           
            var form_data = $("#editUserForm").serialize();
            var action_url = '<?php echo base_url("/admin/user/edit/".$user->id) ?>';
            var form_data = $("#form_edit_action").serialize();
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
                        $('#btn_edit_cancel_modal').click();
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
    });

</script>


