<!-- Name Modal -->
<link rel="stylesheet" href="<?php echo base_url("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"); ?>">
<div class="modal fade" id="modal_name_form" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Compose New Message</h4>
            </div>
            <?php echo form_open_multipart('/admin/mailbox/compose', array('id' => "form_add_action")); ?>

            <div class="modal-body">

              <div class="col-md-12">
                <div class="box box-primary">
                
                  <div class="box-body">
                    
                    <div class="form-group">
                          <select name="to" id="to" class="form-control select2" style="width: 100%;">
                          <option disabled="disabled" selected="selected">To:</option>
                          <?php

                          foreach ($allMembers as $key => $value) {
                          ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                          <?php
                          }
                          ?>
                          </select>                                  
                          
                    </div>
                    
                    <div class="form-group">
                      <input class="form-control" placeholder="Subject: *" name="subject" id="subject">
                    </div>
                    <div class="form-group">
                      <textarea name="content" id="compose-textarea" class="textarea form-control" style="height: 250px">                        
                      </textarea>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="pull-right">
                      <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                      <button type="button" id="btn_create_action" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                    </div>

                  </div><!-- /.box-footer -->
                  <?php echo form_close(); ?>
                </div><!-- /. box -->
              </div><!-- /.col -->
              <div class="modal-footer">
              </div>

            </div>

        </div>
    </div>
</div><!-- /.Name Modal -->
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"); ?>"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
    /*$('#modal_name_form').on('shown.bs.modal', function () {
      $('#compose-textarea').wysihtml5();
    });*/
    /*$('#modal_name_form').on('hidden', function(){
        $('.wysihtml5-sandbox, .wysihtml5-toolbar').remove();
        $('#compose-textarea').show();
    });*/
    $(".select2").select2();

    var btn = $('#btn_create_action');

    btn.click(function() {

        btn.button('loading');

        var action_url = '<?php echo base_url("/admin/mailbox/compose") ?>';
        var form_data = $("#form_add_action").serialize();

        var fnCallback = function(result) {
            if (result.success == false) {
                $(".modal-body").prepend(result.json_msg);
                btn.button('reset');
            } else {
                $(".modal-body").prepend(result.json_msg);
                btn.button('reset');
                //oTable.draw();

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

  });
</script>