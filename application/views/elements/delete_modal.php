<!-- Mass Delete Modal -->
<div class="modal fade modal-danger" id="multiple_delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> <?php echo lang('confirm_delete', 'general'); ?></h4>
            </div>
            <?php echo form_open('', array('id' => "mass_delete_form")); ?>
            <div class="modal-body">
                <input type="hidden" id="mass_value" name="massValue" />
                <input type="hidden" id="library_type_id_form" name="library_type_id" value="0" />
                <input type="hidden" id="delete-all-flag" name="delete-all-flag" value="no"/>
                <input type="hidden" id="deleteWhereStatement" name="deleteWhereStatement" />
                <?php echo lang('confirm_delete', 'general'); ?> <span class="massCount"></span> <?php echo lang('Records', 'general'); ?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" id="btn_mass_delete_cancel_modal" class="btn bg-red btn-flat btn-block" data-dismiss="modal"><?php echo lang('No', 'general'); ?></button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="btn_mass_delete_action" class="btn bg-red btn-flat btn-block"><?php echo lang('Yes', 'general'); ?></button>
                    </div>
                </div>
            </div> 
            <?php echo form_close(); ?>
        </div>
    </div>
</div><!-- /.Mass Delete Modal -->

<script>
    $(function() {
        var btn = $('#btn_mass_delete_action');

        btn.click(function() {
            btn.button('loading');

            var action_url = $('#mass_delete_form').attr('action');
            var form_data = $("#mass_delete_form").serialize();

            var fnCallback = function(result) {
                if (result.success == false) {
                    if(result.logout == true){
                        $('#btn_mass_delete_cancel_modal').click();
                        location.reload();
                    }
                    $(".box-body").prepend(result.json_msg);
                    btn.button('reset');
                } else {
                    $(".box-body").prepend(result.json_msg);
                    btn.button('reset');
                    oTable.draw();
                }
                setTimeout(function() {
                    $('#btn_mass_delete_cancel_modal').click();
                }, 100);
                $('#apply_mass_deletes').text('<?php echo lang('Apply', 'general'); ?> (0)');
                $('html, body').animate({scrollTop: 0}, 'fast');
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