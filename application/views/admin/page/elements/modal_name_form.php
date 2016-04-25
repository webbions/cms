<!-- Name Modal -->

<div class="modal fade" id="modal_name_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Settings</h4>
            </div>
            <?php echo form_open_multipart('/admin/page/add', array('id' => "form_add_action")); ?>
      
                        
           <div class="modal-body">
    <div class="row form-group">
        <label class="col-md-3 control-label label-txt">
          Page Title
            <span class="required">*</span>
        </label>
        <div class="col-md-9">
            <div class="wrap-validation">
                <div class="check-val">
                    <input type="text" placeholder="Page Title" name="title" id="title" class="form-control"  value="">
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
                   <input type="text" placeholder="Page Slug" name="slug" id="slug" class="form-control"  value="" readonly>
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
                    <textarea placeholder="Page Content" name="content" id="content" class="form-control ckeditor" ></textarea>
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
                   <select class="form-control" name="status" id="status">
            	<option value="1">Active</option>
             	<option value="0">Inactive</option>
             	</select>
                </div>
            </div>
        </div>
    </div>
    
</div>            
            <div class="modal-footer"> 
               <button type="button" id="btn_create_action" class="btn btn-success">Submit</button>
               <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>
                
                
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div><!-- /.Name Modal -->

<!--Slug Script -->   	


<script>
    $(function() {
        var btn = $('#btn_create_action');

        btn.click(function() {
            btn.button('loading');

            var action_url = '<?php echo base_url("/admin/page/add") ?>';
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

<script>
$("#title").keyup(function(){
        var Text = $(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp,'-');
        $("#slug").val(Text);        
});
</script>
<script src="<?php echo base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>