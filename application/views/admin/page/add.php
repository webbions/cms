<link rel="stylesheet" href="<?php echo base_url("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"); ?>">
<style>
.red{
	color:#F00;		
}
</style>
<section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">CMS Pages</h3>
                    <a class="btn btn-primary btn-flat pull-right" href="<?php echo base_url()?>admin/page/index"><i class="fa fa-arrow-left"></i> Back </a>
                </div><!-- /.box-header -->
                
                
          <div  class="content">
         
          

            <div class="red">* Mandatory field(s)</div>    
            <br /> 
            <?php if( $this->session->flashdata('errors'))
				{
					echo '<div class="alert alert-error">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
							<strong>Errors!</strong> '.$this->session->flashdata('errors'). '
						  </div>';	
				}
				?>
              
          
           	 <form enctype="multipart/form-data" method="post" id="pageForm" name="pageForm" action="" class="form-horizontal group-border-dashed">
           
             <input type="hidden" name="mode" value="" />  
             <br>        
            <div class="form-group">
            	<label class="col-sm-3 control-label">Page Title <span class="red">*</span>:</label>
                <?php echo form_error('title') ?>
            <div class="col-sm-6">
            	<input type="text" placeholder="Page Title" name="title" id="title" class="form-control"  value="">
                <br>
           	</div>
            </div>
            
             <div class="form-group">
            	<label class="col-sm-3 control-label">Page Slug <span class="red">*</span>:</label>
            <div class="col-sm-6">
            	<input type="text" placeholder="Page Slug" name="slug" id="slug" class="form-control"  value="" readonly>
                <br>
           	</div>
            </div>

             <div class="form-group">
	         	<label class="col-sm-3 control-label">Page Content <span class="red">*</span>:</label>
                 <div class="col-sm-8">
                <textarea class="textarea form-control" name="content" id="content"  placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                <br>
             <div id="page_content_msg"></div>
             </div>
             </div>   
             
             <div class="form-group">
             	<label class="col-sm-3 control-label">Status <span class="red">*</span>:</label>
             <div class="col-sm-6">
                <input type="radio" name="status" id="active_status" value="1" class="flat-red" checked> Active
                <input type="radio" name="status" id="inactive_status" value="0" class="flat-red"> Inactive
                <!-- <select class="form-control" name="status" id="status">
            	<option value="1">Active</option>
             	<option value="0">Inactive</option>
             	</select>	 -->
                <br>								
             </div>
             </div>    
             
             <div class="form-group">
             <div class="col-sm-offset-3 col-sm-10">
              	<input name="submit" class="btn btn-primary" type="submit" value="Submit" />
                <a href="<?php echo base_url()?>admin/page/index" class="btn btn-default">Cancel</a>
             </div>
             </div>        
   
                    </form>
                  </div>
			 </div>
        </div>
    </div>
<!--Slug Script -->   	
<script>
$(function () {
    $('#inactive_status').iCheck({
        radioClass: 'iradio_flat-red'
    });
    //bootstrap WYSIHTML5 - text editor
    $("#content").wysihtml5();
});
$("#title").keyup(function(){
        var Text = $(this).val();
        Text = Text.toLowerCase();
        var regExp = /\s+/g;
        Text = Text.replace(regExp,'-');
        $("#slug").val(Text);        
});
</script>
<!--Editor -->   
<script src="<?php echo base_url("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js");?>"></script>