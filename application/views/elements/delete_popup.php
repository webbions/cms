<!-- Delete Modal -->
<div class="modal fade modal-danger" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
			</div>
            <?php echo form_open('', array('id' => "mass_delete_form")); ?>
			<div class="modal-body">
				<div class="text-center">
					<h4>Are you sure want to Delete <i class="fa fa-question"></i></h4>

				</div>
			</div>
			<div class="modal-footer">
                <span id="delete_anchor_tag_content"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

