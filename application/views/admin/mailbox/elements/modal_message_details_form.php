<!-- Name Modal -->
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-reply"></i> Reply to Message : <b> <?php echo $threadDetails['retval'][0]['subject'];?> </b></h4>
    </div>
    

		    <?php echo form_open_multipart('/admin/mailbox/reply/'.$threadDetails['retval'][0]['id'], array('id' => "form_reply_action")); ?>

      <div class="modal-body modal-body1">

        <div class="col-md-12">
          <div class="box box-primary">
          
            <div class="box-body">
              <input type="hidden" name="subject" value="<?php echo $threadDetails['retval'][0]['subject'];?>">
              <input type="hidden" name="mdgid" value="<?php echo $threadDetails['retval'][0]['id'];?>">
              <div class="form-group">
                <textarea name="content1" id="content1" class="textarea form-control" style="height: 250px"></textarea>
              </div>
            </div><!-- /.box-body -->
            
          </div><!-- /. box -->
        </div><!-- /.col -->
        <div class="modal-footer">
            <div class="pull-right">
              <button type="button" id="btn_create_cancel_modal1"  class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
              <button type="button" id="btn_reply_action" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
            </div>
        </div>
      </div>

      <?php echo form_close(); ?>

          
    <div class="modal-body">
    <?php foreach($threadDetails['retval'] as $msgRow) {?>
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body no-padding">
            <div class="mailbox-read-info">
              
              <h5>From: <?php echo $msgRow['user_name'];?> <span class="mailbox-read-time pull-right"><?php echo $msgRow['cdate'];?></span></h5>
            </div><!-- /.mailbox-read-info -->
            <div class="mailbox-read-message">
              <?php  echo $msgRow['body'];?>
            </div>

       

            <!-- /.mailbox-read-message -->
          </div><!-- /.box-body -->
        </div><!-- /. box -->
      </div>
    <?php }?>
    <!-- /.col -->
    <div class="modal-footer">
    </div>
  </div>


<!-- Page Script -->

<script>
var action_url = '<?php echo base_url("/admin/mailbox/reply") ?>';
  $(function () {

    //Add text editor
    $('#action_modal').on('shown.bs.modal', function () {

      $('#content1').wysihtml5();
      $.getScript("<?php echo base_url("assets/plugins/addmyscript.js"); ?>");
    });

    

  });
</script>