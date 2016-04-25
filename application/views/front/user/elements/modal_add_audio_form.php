<!-- Add Audio Modal -->
<div class="modal fade" id="modal_add_audio_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Audio</h4>
            </div>
            <?php echo form_open_multipart("/user/addaudio", array('id' => "form_add_action")); ?>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-4 control-label label-txt">
                        Audio Name
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-8">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input name="audio_name" id="audio_name" class="form-control" type="text" value="" required="required" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 control-label label-txt">
                        Embed Code
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-8">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <textarea name="audio_code" id="audio_code" class="form-control" required="required"></textarea>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_create_action" class="btn btn-success">Save</button>
                <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>                
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

            var action_url = '<?php echo base_url("/user/addaudio") ?>';
            var form_data = $("#form_add_action").serialize();

            var fnCallback = function(result) {
                if (result.success == false) {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                } else {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                    
                    $("#portfolio_audios").fadeOut(800, function(){
                        $("#portfolio_audios").prepend(result.audio_data).fadeIn().delay(2000);
                    });
                    $("#audio_name").val('');
                    $("#audio_code").val('');
                    
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