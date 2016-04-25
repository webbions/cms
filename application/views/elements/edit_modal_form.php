<link rel="stylesheet" href="<?php echo base_url("assets/plugins/fileupload/jquery.fileupload.css"); ?>">
<!-- Edit Form Modal -->
<div class="modal fade" id="edit_modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="location.reload('true')"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i><?php echo lang(' Edit Proxy', 'proxy'); ?> </h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" height="800" id="edit_ifram_id" src=""></iframe>
            </div>
        </div>
    </div>
</div><!-- /.Edit Form Modal -->