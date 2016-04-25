<link rel="stylesheet" href="<?php echo base_url("assets/plugins/iCheck/all.css") ?>">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i>Update Category</h4>
</div>
<?php echo form_open_multipart("/admin/category/edit/" . $categoryDetails['id'], array('id' => 'form_edit_action')); ?>
<input type="hidden" name="old_img" value="<?php echo $categoryDetails['catimage']; ?>" />
<div class="modal-body">
    
    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Category Name
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Name" name="name" id="name" class="form-control"  value="<?php echo $categoryDetails['name']; ?>">
                </div>
            </div>
        </div>
    </div>
    
    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Category Image
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="file" name="catimage" id="catimage" class="form-control">
                </div>
            </div>
        </div>
    </div>


    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Meta Keywords
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Meta Keyword" name="m_keywords" id="m_keywords" class="form-control"  value="<?php echo $categoryDetails['meta_keywords']; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Meta Description
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Meta Description" name="m_desc" id="m_desc" class="form-control"  value="<?php echo $categoryDetails['meta_descs']; ?>">
                </div>
            </div>
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
                <input type="radio" name="status" id="active_status" value="1" class="flat-green" <?php echo ($categoryDetails['status'] == IS_ACTIVE ? 'checked' : '') ?>> Active
                <input type="radio" name="status" id="inactive_status" value="0" class="flat-red" <?php echo ($categoryDetails['status'] == IS_INACTIVE ? 'checked' : '') ?>> Inactive
                </div>
            </div>
        </div>
    </div>


</div>
<div class="modal-footer">
    <button type="submit" id="btn_edit_action" class="btn btn-success">Update</button>
    <button type="button" id="btn_edit_cancel_modal" class="btn btn-default" data-dismiss="modal">Cancel</button>

</div>
<?php echo form_close(); ?>



<script>

    $(function() {
        //var btn = $('#btn_edit_action');
        var btn = $('#form_edit_action');

        btn.submit(function() {
            //btn.button('loading');
            var action_url = '<?php echo base_url("/admin/category/edit/" . $categoryDetails['id']) ?>';
            //var form_data = $("#form_edit_action").serialize();
            var formData = new FormData($(this)[0]);

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
                'data': formData,
                'async': false,
                'cache': false,
                'processData': false,
                'contentType': false,
                'success': fnCallback
            });
            return false;
        });

        $('input[type="checkbox"].flat-red, input[type="radio"].flat-green').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-green'
        });
        $('input[type="radio"].flat-red').iCheck({
            radioClass: 'iradio_flat-red'
        });

    });
</script>


