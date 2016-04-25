<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i>Update Setting</h4>
</div>
<?php echo form_open_multipart("/admin/setting/edit/". $result['id'],array('id' => 'form_edit_action')); ?>
 
<div class="modal-body">
    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Setting Name
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Name" name="name" id="name" class="form-control"  value="<?php echo $result['name']; ?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Setting Value 
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Value" name="value" id="value" class="form-control"  value="<?php echo $result['value']; ?>">
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
            var action_url = '<?php echo base_url("/admin/setting/edit/".$result['id'] ) ?>';
            var form_data = $("#form_edit_action").serialize();
            var fnCallback = function(result) {
                if (result.success == false) {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                } else {
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


