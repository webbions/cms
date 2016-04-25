<!-- Name Modal -->
<div class="modal fade" id="modal_name_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Category</h4>
            </div>
        <?php echo form_open_multipart('/admin/pregistration/add', array('id' => "form_add_action", "enctype" => "multipart/form-data")); ?>
        
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                        Client First Name
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="First Name" name="first_name" id="first_name" class="form-control"  value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                        Client Last Name
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="Last Name" name="last_name" id="last_name" class="form-control"  value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                        Company Name
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="Company Name" name="company_name" id="company_name" class="form-control"  value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                        Client Email
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="Email Address" name="email" id="email" class="form-control"  value="">
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_create_action" class="btn btn-success">Send</button>
            </div>
        <?php echo form_close(); ?>
        </div>
    </div>
</div><!-- /.Name Modal -->

<script>
    $(function() {

        
        //var btn = $('#btn_create_action');
        var btn = $('#btn_create_action');

        btn.click(function() {

            btn.button('loading');

            var action_url = '<?php echo base_url("/admin/pregistration/add") ?>';
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