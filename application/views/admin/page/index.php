<section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">CMS Pages</h3>
                    <a class="btn btn-primary btn-flat pull-right" href="<?php echo base_url()?>admin/page/add"><span class="hidden-xs">Add Page</span> <i class="fa fa-plus"></i> </a>
                </div><!-- /.box-header -->
            	  		
    <?php if( $this->session->flashdata('success'))
        {
            echo '<div class="alert alert-success">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <i class="fa fa-check sign"></i><strong>Success!</strong> ' . $this->session->flashdata('success'). '
                  </div>';

         }
                     
				?>
		
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                      	<th>Id</th>
                        <th>Page Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                       <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Page Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section>



<!-- Delete Modal -->
<?php echo $this->load->view('elements/delete_popup', null, true); ?>

<!-- edit_modal_form Modal--> 
<?php $this->load->view("/elements/action_modal") ?>

        
<script>
function massCallBack(response) {
    $("#where-statement-id").val(response.whereStatement);
    var numberOfChecked = response.iTotalDisplayRecords;
    if (numberOfChecked > 0) {
        $('.all-mass-actions').removeAttr("disabled");
    }
    else {
        $('.all-mass-actions').attr("disabled", "disabled");
    }
    ;
    $(".allResults").text(numberOfChecked);

}
$(document).ready(function () {
	var oTable;
	oTable = $('#example2').DataTable({
            "responsive": true,
            "stateSave": true,
            "bLengthChange": false,
            "bFilter": true,
            "bProcessing": true,
            "bServerSide": true,
            "aaSorting": [[1, 'desc']],
            "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }],
            "sAjaxSource": '<?php echo base_url(); ?>admin/page/get_ajax_page_listing',
            "bJQueryUI": false,
            "sPaginationType": "full_numbers",
            "iDisplayStart ": 20,
            "oLanguage": {
                    "sProcessing":   "sProcessing",
					"sLengthMenu":   "sLengthMenu _MENU_",
					"sZeroRecords":  "sZeroRecords",
					"sInfo":         "Showing _START_ to _END_ from _TOTAL_ records",
					"sInfoEmpty":    "Showing  0  to 0  from 0 records",
					"sInfoFiltered": "(sInfoFiltered _MAX_ records)",
					"sInfoPostFix":  "",
					"sSearch":       "sSearch :",
					"sUrl":          "",
					"oPaginate": {
						"sFirst":    "First",
					    "sPrevious": "Previous",
					    "sNext":     "Next",
					    "sLast":     "Last"
					}
            },
            "fnInitComplete": function() {
                //oTable.fnAdjustColumnSizing();
                $("#example2_filter").hide();
            },
            "fnDrawCallback": function() {
                //Flat red color scheme for iCheck
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-green'
                });
                $('#check-all').iCheck('uncheck');
                $(".massActionsButton").attr("disabled", "disabled");
                //countChecked();
            },
            'fnServerData': function(sSource, aoData, fnCallback)
            {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': [fnCallback, massCallBack]
                });
            }
        });
  
});
</script>