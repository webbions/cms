<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Categories</h3>
           <a data-target="#modal_name_form" data-toggle="modal" class="btn btn-flat bg-green wave waves-effect pull-right margin-r-5" href="#"><i class="fa fa-plus"></i><span class="hidden-xs">New Invitation</span></a>
        </div><!-- /.box-header -->

        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
              <tr>
              	<th>Id</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Company Name</th>
                <th>Status</th>
                <th>Invited On</th>
                <th class="no-sort">Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section>
<!-- Delete Modal -->
<?php echo $this->load->view('elements/delete_popup', null, true); ?>

<?php $this->load->view('/admin/pregistrations/elements/modal_name_form');?>
<script type="text/javascript">
var oTable;
$(document).ready(function () {
	    oTable = $('#example2').DataTable({
            "responsive": true,
            "stateSave": true,
            "bLengthChange": false,
            "bFilter": true,
            "bServerSide": true,
            "bFilter": true,
            "bProcessing": true,
            "bServerSide": true,
            "aaSorting": [[1, 'desc']],
            "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }],
            "sAjaxSource": '<?php echo base_url(); ?>admin/pregistration/get_ajax_page_listing',
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayStart ": 20,

            "oLanguage": {
                    "sProcessing":   "Processing...",
                    "sLengthMenu":   "sLengthMenu _MENU_",
                    "sZeroRecords":  "No Records",
                    "sInfo":         "Showing _START_ to _END_ Of Total _TOTAL_ records",
                    "sInfoEmpty":    "Showing  0  to 0  from 0 records",
                    "sInfoFiltered": "(sInfoFiltered _MAX_ records)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Search :",
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
                //$("#example2_filter").hide();

            },
            "fnDrawCallback": function() {
                //Flat red color scheme for iCheck
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-green'
                });
                $('#check-all').iCheck('uncheck');
                //$(".massActionsButton").attr("disabled", "disabled");
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
         $('#example2_length').change(function() {
            oTable.page.len(this.value).draw();
        });
        $('body').on('ifChecked', '.search', function(event) {
            oTable
                    .columns($(this).attr('data-column'))
                    .search($(this).val())
                    .draw();
        });
});
</script>
