<!-- Name Modal -->
<div class="modal fade" id="modal_name_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Settings</h4>
            </div>
            <?php echo form_open_multipart('/admin/setting/add', array('id' => "form_add_action")); ?>
            <div class="modal-body">
                <div class="row form-group">
                <label class="col-md-3 control-label label-txt">
                    Setting Name
                    <span class="required">*</span>
                </label>
                <div class="col-md-9">
                    <div class="wrap-validation">
                        <div class="check-val">
                            <input type="text" placeholder="Name" name="name" id="name" class="form-control"  value="">
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
                            <input type="text" placeholder="Value" name="value" id="value" class="form-control"  value="">
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_create_action" class="btn btn-success">Submit</button>

            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div><!-- /.Name Modal -->

<script>
    $(function() {

        var btn = $('#btn_create_action');

        btn.click(function() {

            btn.button('loading');

            var action_url = '<?php echo base_url("/admin/setting/add") ?>';
            var form_data = $("#form_add_action").serialize();

            var fnCallback = function(result) {
                if (result.success == false) {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                } else {
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