<!-- Name Modal -->

<script src="http://malsup.github.com/jquery.form.js"></script>
<div class="modal fade" id="modal_name_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Sub Category</h4>
            </div>
        <?php echo form_open_multipart('/admin/Subcategory/add', array('id' => "form_add_action", "enctype" => "multipart/form-data")); ?>
            <div class="modal-body">

                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                       Sub Category Name
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
                        Sub Category Image
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
                        Meta Keyword
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="Meta Keyword" name="m_keyword" id="m_keyword" class="form-control"  value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                        Meta Descripition
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="Meta Descripition" name="m_desc" id="m_desc" class="form-control"  value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 control-label label-txt">
                        Order_ID
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-9">
                        <div class="wrap-validation">
                            <div class="check-val">
                                <input type="text" placeholder="Order_Id" name="o_id" id="o_id" class="form-control"  value="">
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
                                <input type="radio" name="status" id="active_status" value="<?php echo IS_ACTIVE; ?>" class="flat-green" checked> Active
                                <input type="radio" name="status" id="inactive_status" value="<?php echo IS_INACTIVE; ?>" class="flat-red"> Inactive
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_create_cancel_modal"  class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Submit" id="btn_create_action" class="btn btn-success" />  <!-- </button> -->
            </div>
        <?php echo form_close(); ?>
        </div>
    </div>
</div><!-- /.Name Modal -->

<script>
$(document).ready(function()
{
//$("#form_add_action").ajaxForm(options);
});
    $(function() {

        $('input[type="radio"].flat-green').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-green'
        });
        $('input[type="radio"].flat-red').iCheck({
            radioClass: 'iradio_flat-red'
        });


       // var btn = $('#btn_create_action');
        //var btn = $('#form_add_action');

        btn.submit(function() {

            //btn.button('loading');

            var action_url = '<?php echo base_url("/admin/Subcategory/add") ?>';
            //var form_data = $("#form_add_action").serialize();
            // for ajax
            var formData = new FormData($(this)[0]);

            var fnCallback = function(result) 
            {
                if (result.success == false) 
                {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                }
                else 
                {
                    $(".modal-body").prepend(result.json_msg);
                    btn.button('reset');
                    oTable.draw();

                    setTimeout(function() 
                    {
                        $('#btn_create_cancel_modal').click();
                    }, 1000);

                }
                $('#action_modal').animate({scrollTop: 0}, 'slow');
            }

            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': action_url,
                data: formData,
                async: false,
                cache: false,
                processData: false,
                contentType: false,
                'success': fnCallback
            });
            return false;
        });
    })
</script>