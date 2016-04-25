      <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-3">
              <a data-target="#modal_name_form" data-toggle="modal" href="#" class="btn btn-primary btn-block margin-bottom">Compose</a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Folders</h3>
                  <div class="box-tools">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right">12</span></a></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
             <!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Inbox</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <!-- <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button> -->
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right" >
                      <span class="currentpage">0-1</span>/<?php echo $TotalThread;?>
                      <div class="btn-group">
                        <button  class="btn btn-default btn-sm pageprevious"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm pagenext"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                      <tbody class="paginationmessage">
                      <?php  foreach ($allThreads as $key => $value) { ?>
                        <tr>
                          <td><input type="checkbox"></td>
                          <td class="mailbox-name">
                          
                            <a data-target="#modal_name_form1" data-toggle="modal" href="javascript:void(0);" onclick=openEditPopup("<?php echo base_url('admin/mailbox/messageDetails/'.$value['thread_id']); ?>") class="">
							 <!-- <a href="<?php echo base_url('admin/mailbox/messageDetails/'.$value['thread_id']); ?>">-->
							<?php echo $value['messages'][0]['user_name']; ?></a>
                            <!-- <a href="read-mail.html">Alexander Pierce</a> -->
                          </td>
                          <td class="mailbox-subject"><b><?php echo $value['messages'][0]['subject']; ?></b></td> 
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date"><?php echo humanTiming(strtotime($value['messages'][0]['cdate'])); ?> ago</td>
                        </tr>                        
                      <?php  } ?>
                        
                      </tbody>
                    </table><!-- /.table -->
                  </div><!-- /.mail-box-messages -->
                </div><!-- /.box-body -->
                <div class="box-footer no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <!-- <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button> -->
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                    <input type="hidden" value="0_1" name="" id="pagelimit">
                      <span class="currentpage">0-1</span>/<?php echo $TotalThread;?>
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm pageprevious"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm pagenext"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                </div>
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->


<input type="hidden" value="1" id="startpage">
<input type="hidden" value="1" id="limitpage">

<?php $memberData['allMembers'] = $allMembers; ?>
<?php  $this->load->view('/admin/mailbox/elements/modal_compose_form', $memberData);?>
<!-- edit_modal_form Modal-->
<?php $this->load->view("/elements/action_modal");?>
<script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

  });
</script>
<script type="text/javascript">
  var btn = $('.pagenext');

    btn.click(function() {
        btn.button('loading');
        var action_url = '<?php echo base_url("/admin/mailbox/messagePaginationajax") ?>';
		var startpage = $('#startpage').val();
		if(startpage == 0)
		{
			startpage = parseInt(startpage)+1;
		}
		var limitpage = $('#limitpage').val();
        var form_data = 'limit='+limitpage+'&start='+startpage;
        var fnCallback = function(result) {
            if (result != 'Error') {
				$('.paginationmessage').html('');
				$('.paginationmessage').html(result);
				var startpage1 = parseInt(startpage)+1;
				$('.currentpage').text(startpage+"-"+startpage1);
				$('#startpage').val(startpage1);
				
				btn.button('reset');
            } else {
                //alert('Error');
				btn.button('reset');
                //oTable.draw();
                /*setTimeout(function() {
                    $('#btn_create_cancel_modal').click();
                }, 1000);*/
            }
            $('#action_modal').animate({scrollTop: 0}, 'slow');
        }

        $.ajax({
            'dataType': 'html',
            'type': 'POST',
            'url': action_url,
            'data': form_data,
            'success': fnCallback
        });
    });	
	
	/****************Page Previous***********/
  var btn = $('.pageprevious');	
	btn.click(function() {
        btn.button('loading');
        var action_url = '<?php echo base_url("/admin/mailbox/messagePaginationajax") ?>';
		var startpage = $('#startpage').val();
		if(startpage > 1)
		{
			startpage = parseInt(startpage)-2;
		}
		else
		{
			startpage = parseInt(startpage)-1;
		}
		if(startpage == -1)
		{
			startpage = 0;
		}
		var limitpage = $('#limitpage').val();
        var form_data = 'limit='+limitpage+'&start='+startpage;
        var fnCallback = function(result) {
            if (result != 'Error') {
				$('.paginationmessage').html('');
				$('.paginationmessage').html(result);
					var startpage1 = parseInt(startpage)+1;
					$('.currentpage').text(startpage+"-"+startpage1);
					$('#startpage').val(parseInt(startpage));
				btn.button('reset');
            } else {
				
               //alert('Error');
				btn.button('reset');
                //oTable.draw();
                /*setTimeout(function() {
                    $('#btn_create_cancel_modal').click();
                }, 1000);*/
            }
            $('#action_modal').animate({scrollTop: 0}, 'slow');
        }
			$.ajax({
				'dataType': 'html',
				'type': 'POST',
				'url': action_url,
				'data': form_data,
				'success': fnCallback
			});
		
    });	
	
	
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
            "sAjaxSource": '<?php echo base_url(); ?>admin/mailbox/get_ajax_page_listing',
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