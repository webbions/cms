<!-- Add Audio Modal -->
<div class="modal fade" id="modal_add_video_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Video</h4>
            </div>
            <?php echo form_open_multipart("/user/addvideo", array('id' => "form_add_video_action")); ?>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-4 control-label label-txt">
                        Video Name
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-8">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input name="video_name" id="video_name" class="form-control" type="text" value="" required="required" />
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
                                <textarea name="video_code" id="video_code" class="form-control" required="required"></textarea>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_create_video_action" class="btn btn-success">Save</button>
                <button type="button" id="btn_create_video_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>                
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div><!-- /.Name Modal -->
<script>
    $(function() {
        var btn = $('#btn_create_video_action');

        btn.click(function() {
            btn.button('loading');

            var action_url = '<?php echo base_url("/user/addvideo") ?>';
            var form_data = $("#form_add_video_action").serialize();

            var fnCallback = function(result) {
                if (result.success == false) {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                } else {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                    //oTable.draw();
                    //result.audio_data
                    $("#portfolio_videos").fadeOut(800, function(){
                        $("#portfolio_videos").prepend(result.video_data).fadeIn().delay(2000);
                    });
                    $("#video_name").val('');
                    $("#video_code").val('');
                    setTimeout(function() {
                        $('#btn_create_video_cancel_modal').click();
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