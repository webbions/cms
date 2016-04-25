
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i>Update CMS Page</h4>
</div>
<?php echo form_open_multipart("/admin/page/edit/". $result['id'] ,array('id' => 'form_edit_action')); ?>
<div class="modal-body">
    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Page Title
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">                  
                <input type="text" placeholder="Page Title" name="title" id="title" class="form-control"  value="<?php echo $result['title'] ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Page Slug 
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Page Slug" name="slug" id="slug" class="form-control"  value="<?php echo $result['slug'] ?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
            Page Content
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <textarea placeholder="Page Content" name="content" id="content_<?php echo $result['id']?>" class="form-control ckeditor"><?php echo $result['content'] ?></textarea>
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
                    <div class="radio">
                        <label>
                            <?php
                            $isActive = ($result['status'] == 1) ? TRUE : FALSE;
                            $isAvailableYesData = array('name' => 'status', 'class' => 'flat-green', 'value' => 1, 'checked' => $isActive);
                            ?>
                            <?php echo form_radio($isAvailableYesData); ?> Active
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <?php
                            $isInactive = ($result['status'] == 0) ? TRUE : FALSE;
                            $isAvailableNoData = array('name' => 'status', 'class' => 'flat-red', 'value' => 0, 'checked' => $isInactive);
                            ?>
                            <?php echo form_radio($isAvailableNoData); ?> Inactive
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">                
    <button type="button" id="btn_edit_action" class="btn btn-success">Save</button>
    <button type="button" id="btn_edit_cancel_modal" class="btn btn-default" data-dismiss="modal">Cancel</button>
    
</div>
<?php echo form_close(); ?>

<script>
$("#title").keyup(function(){
        var Text = $(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp,'-');
        $("#slug").val(Text);        
});
</script>


<script>
    
    $(function() {
        var btn = $('#btn_edit_action');
        
        btn.click(function() {
            btn.button('loading');
            var action_url = '<?php echo base_url("/admin/page/edit/".$result['id'] ) ?>';
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

        $('#action_modal').on('hidden.bs.modal', function () {
        
            CKEDITOR.replace( "editor<?=$result['id']; ?>");
        });
    })
</script>

<!--Editor -->   
<script src="<?php echo base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>
