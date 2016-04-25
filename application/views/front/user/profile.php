<style type="text/css" media="screen">
.none{ display:none;}
.upload_div{ margin-bottom:50px;}
.uploading{ margin-top:15px;}  
</style>
<section class="middel">
<div class="container">
<div class="col-sm-9">
  <div class="search-list box">
    <div class="clearfix">
        <div class="row">
        <div class="profileupdate" id="profileupadatetab1">
        <!-- Profile tab 1 Display -->
        </div>
        </div>
		<?php 
			$talcatids = array();
			foreach($talentcategory as $tcatRow){ 
				array_push($talcatids,$tcatRow['categoryID']);
			}
			$categorys = '';
			foreach($categorydata as $cRow){
				if(in_array($cRow['id'],$talcatids))
				{
					$categorys .= $cRow['name'].',';
				}
			}
		?>
      <div class="avtar profile"> <img src="<?php echo base_url()?>assets/theme/images/avtar.jpg" width="266" height="226" alt=""> </div>
      <div class="profile-left displayinput" > 
      	<a href="#" class="btn btn-default pull-right edittab1"><i class="fa fa-pencil"></i> Edit</a>
        <div class="ajaxtohtmltab1" > 
        <h3><?php  if($curruser= $this->session->userdata()) { echo $curruser['username']; }  ?></h3>
        <h4><?php echo trim($categorys,',');?></h4>
        <h4 class="mar-top">Current:<span> <?php echo isset($profileData[0]['current'])?$profileData[0]['current']:'';?></span></h4>
        <h4>Availed to Travel:<span> <?php echo isset($profileData[0]['availedtotravel'])?$profileData[0]['availedtotravel']:'';?></span></h4>
        <h4>Education:<span> <?php echo isset($profileData[0]['education'])?$profileData[0]['education']:'';?></span></h4>
        <h4 class="mar-top">About Me</h4>
        <p><?php echo isset($profileData[0]['aboutme'])?$profileData[0]['aboutme']:'';?></p>
        </div>
      </div>
      
      <!------------------ Edit Profoile tab 1 start ---------------> 
       
      <div class="profile-left editinput" style="display:none;">
            <div class="heading">
                <h3>Select Category</h3>
            </div>
            <form action="" method="post" id="updateprofileform" name="updateprofileform">
                <div class="form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                    <?php $i = 0; 
											 foreach($categorydata as $cRow){?>
                                        <div class="col-sm-4"><div class="form-group">
                                            <div class="checkbox">
                                            	
                                                <input type="checkbox" id="cat_<?php echo $i;?>" value="<?php echo $cRow['id'];?>" <?php echo in_array($cRow['id'],$talcatids)?'checked':''; ?>  name="ucategory[]">
                                                <label for="cat_<?php echo $i;?>"><?php echo $cRow['name'];?></label>
                                                <div class="check"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                            </div>
                                        </div>
                                      <?php $i++; }?>  
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Current<span class="req">*</span></label>
                                        <input type="text" name="current" id="current" value="<?php echo isset($profileData[0]['current'])?$profileData[0]['current']:'';?>" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
                                            <label>Availed to Travel<span class="req">*</span></label>
                                            <div class="select-dropdown">
                                            <select class="form-control" name="availedtotravel">
                                                <option value="">Choose One</option>
                                                <option value="Yes"  <?php echo isset($profileData[0]['availedtotravel']) && $profileData[0]['availedtotravel'] == 'Yes'? 'selected="selected"': '';?>>Yes</option>
                                                <option value="No" <?php echo isset($profileData[0]['availedtotravel']) && $profileData[0]['availedtotravel'] == 'No'? 'selected="selected"': '';?>>No</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Education<span class="req">*</span></label>
                                        <input type="text" name="education" id="education" value="<?php echo isset($profileData[0]['education'])?$profileData[0]['education']:'';?>" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>About Me<span class="req">*</span></label>
                                         <input type="text" name="aboutme" id="aboutme" value="<?php echo isset($profileData[0]['aboutme'])?$profileData[0]['aboutme']:'';?>" class="form-control" />
                                        
                                    </div>
                                </div>
                                
                                 <div class="col-sm-12">
                                 
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <a class="btn btn-default pull-right" id="updateprofile">Update</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <button class="btn btn-default pull-right" id="cancleupdate">Cancle</button>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                      </div>
                    </div>
                </div>
            </form>            
          
      </div>
      <!------------------ Edit Profoile tab 1 end ---------------> 
      
      
    </div>
  </div>
  <div class="search-list box box-pa-0">
   <div class="profileupdate" id="profileupadatetab2">
        <!-- Profile tab 1 Display -->
        </div>
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> <a href="#" class="btn btn-default pull-right edittab2"><i class="fa fa-pencil"></i> Edit</a>
          <h2><img src="<?php echo base_url()?>assets/theme/images/education.png" width="24" height="27">My Experience</h2>
          <!-- <p>Lorem Ipsum is simply dummy text</p> -->
        </div>
      </div>
      <div class="eduction-text displayinput2">
        <div class="ajaxtohtmltab2"> 
            <h3><?php echo isset($profileData[0]['experience_title']) ? $profileData[0]['experience_title'] : '';?></h3>
            <h4><?php echo isset($profileData[0]['experience_company']) ? $profileData[0]['experience_company'] : '';?></h4>
            <h5><?php echo isset($profileData[0]['experience_location']) ?$profileData[0]['experience_location'] : '';?><i class="fa fa-map-marker"></i></h5>
            <p><?php echo isset($profileData[0]['experience_location']) ? $profileData[0]['experience_desc'] : '';?></p>
        </div>
      </div>
      
       <!------------------ Edit Profoile tab 2 start ---------------> 
       
      <div class="profile-left editinput2" style="display:none;">
            <form action="" method="post" id="updateprofileform2" name="updateprofileform2">
                <div class="form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Experiene Title<span class="req">*</span></label>
                                        <input type="text" name="experience_title" id="experience_title" value="<?php echo isset($profileData[0]['experience_title'])?$profileData[0]['experience_title'] : '';?>" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Experience Company<span class="req">*</span></label>
                                        <input type="text" name="experience_company" id="experience_company" value="<?php echo isset($profileData[0]['experience_company']) ? $profileData[0]['experience_company'] : '';?>" class="form-control" />
                                    </div>
                                </div>
                                
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Experience Year and Location<span class="req">*</span></label>
                                        <input type="text" name="experience_location" id="experience_location" value="<?php echo isset($profileData[0]['experience_location']) ? $profileData[0]['experience_location'] : '';?>" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Experience Description<span class="req">*</span></label>
                                         <input type="text" name="experience_desc" id="experience_desc" value="<?php echo isset($profileData[0]['experience_desc']) ? $profileData[0]['experience_desc'] : '';?>" class="form-control" />
                                        
                                    </div>
                                </div>
                                
                                 <div class="col-sm-12">
                                 
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <a class="btn btn-default pull-right" id="updateprofile2">Update</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <button class="btn btn-default pull-right" id="cancleupdate2">Cancle</button>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                      </div>
                    </div>
                </div>
            </form>            
          
      </div>
      <!------------------ Edit Profoile tab 2 end ---------------> 
      
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="upload-image-messages"></div>
        <form method="post" name="multiple_upload_form" id="multiple_upload_form" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/ajaxFileUpload">
        <input type="hidden" name="image_form_submit" value="1"/>
        <div class="page-heading page-margin"> 
          <input type="file" id="images" name="images[]" <?php echo (8 <= $portfolio_image_count) ? "data-disabled='true'" : ""; ?>class="filestyle" data-input="false" multiple>
          <h2><i class="fa fa-camera"></i> Photos</h2>          
        </div>
        <div class="uploading none">
            <label>&nbsp;</label>
            <img src="<?php echo base_url(); ?>assets/theme/images/uploading.gif"/>
        </div>
        </form>
      </div>
         <div class="row">
            <div class="photos" id="portfolio_images">
             <!-- Image will be displayed via Ajax here. -->
            </div>
          </div>
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> 
        <a data-target="#modal_add_audio_form" data-toggle="modal" href="#" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Audio</a>
          <h2><i class="fa fa-music"></i> Audio</h2>
          <!-- <p>Lorem Ipsum is simply dummy text</p> -->
        </div>
      </div>
      <div class="row">
        <div class="photos" id="portfolio_audios">
          <!-- <div class="col-sm-3">  
              <embed serc="YourMusic.mp3" autostart="true" loop="true" width="2" height="0"></embed>
              <p>Audio</p>
          </div> -->
          <!-- <div class="col-sm-3">
          	<p>No Audio</p>
          </div> -->          
        </div>
      </div>
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> 
        <a data-target="#modal_add_video_form" data-toggle="modal" href="#" class="btn btn-default pull-right"><i class="fa fa-plus"></i></i> Add Video</a>
          <h2><i class="fa fa-video-camera"></i> Video</h2>
          <!-- <p>Lorem Ipsum is simply dummy text</p> -->
        </div>
      </div>
      <div class="row">
        <div class="photos" id="portfolio_videos">
         
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-3">
<a href="#" class="btn btn-info btn-block btn-lg btn-big">BOOK ME</a>
<a href="#" class="btn btn-success btn-block btn-lg btn-big">MESSAGE ME</a>
<a href="#" class="btn btn-warning btn-block btn-lg btn-big">ADD TO MY LIST</a>
<div class="add-<?php echo base_url()?>assets/theme/images">
	<img src="<?php echo base_url()?>assets/theme/images/add.png" class="img-responsive">
</div>
</div>
</div>
</section>
<?php $this->load->view('/front/user/elements/modal_add_audio_form');?>
<?php $this->load->view('/front/user/elements/modal_add_video_form');?>
<script type="text/javascript">
$(document).ready(function(){
	$('.edittab1').click(function(){
		$('.displayinput').hide();
		$('.editinput').show();
	});

	$('.edittab2').click(function(){
		$('.displayinput2').hide();
		$('.editinput2').show();
	});
/* Profile Tab 1 Ajax*/
    $(function() {
        var btn = $('#updateprofile');

        btn.click(function() {
            btn.button('loading');
            var action_url = '<?php echo base_url("/user/profileupdate") ?>';
            var form_data = $("#updateprofileform").serialize();
			
            var fnCallback = function(result) {
				
                if (result.success == false) {
					$('.close').click();
                    $("#profileupadatetab1").prepend(result.json_msg);
					btn.button('reset');
                    //$('.displayinput').show();
					//$('.editinput').hide();	
                } else {
					//alert(result.responsehtml);
					$('.ajaxtohtmltab1').html('');
					$('.ajaxtohtmltab1').html(result.responsehtml);
					$("#profileupadatetab1").prepend(result.json_msg);
                   setTimeout(function() {
                        $('.close').click();
                    }, 1000);
					btn.button('reset');
					$('.displayinput').show();
					$('.editinput').hide();
                }
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

/* Profile Tab 2 Ajax*/
    $(function() {
        var btn = $('#updateprofile2');
        btn.click(function() {
            btn.button('loading');
            var action_url = '<?php echo base_url("/user/profileupdate2") ?>';
            var form_data = $("#updateprofileform2").serialize();
            var fnCallback = function(result) {
                if (result.success == false) {
					$('.close').click();
                    $("#profileupadatetab2").prepend(result.json_msg);
					btn.button('reset');
                    //$('.displayinput').show();
					//$('.editinput').hide();	
                } else {
					//alert(result.responsehtml);
					$('.ajaxtohtmltab2').html('');
					$('.ajaxtohtmltab2').html(result.responsehtml);
					$("#profileupadatetab2").prepend(result.json_msg);
                   setTimeout(function() {
                        $('.close').click();
                    }, 1000);
					btn.button('reset');
					$('.displayinput2').show();
					$('.editinput2').hide();
                }
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

	
	
  //Load images via Ajax
  $.ajax({
      type : 'POST',
      url  : "<?php echo base_url(); ?>user/protfolioImages", 
      success: function(msg) {
        var obj = $.parseJSON(msg);
        $("#portfolio_images").fadeOut(800, function(){
            $("#portfolio_images").html(obj.response).fadeIn().delay(2000);
        });
      }
  });
  //Load Audio via Ajax
  $.ajax({
      type : 'POST',
      url  : "<?php echo base_url(); ?>user/protfolioAudios", 
      success: function(msg) {
        var obj = $.parseJSON(msg);
        $("#portfolio_audios").fadeOut(800, function(){
            $("#portfolio_audios").html(obj.response).fadeIn().delay(2000);
        });
      }
  });

  //Load Audio via Ajax
  $.ajax({
      type : 'POST',
      url  : "<?php echo base_url(); ?>user/protfolioVideos", 
      success: function(msg) {
        var obj = $.parseJSON(msg);
        $("#portfolio_videos").fadeOut(800, function(){
            $("#portfolio_videos").html(obj.response).fadeIn().delay(2000);
        });
      }
  });

  $('#images').on('change',function(e){
    e.preventDefault();
    $('#multiple_upload_form').ajaxForm({
      beforeSubmit:function(e){
        $('.uploading').show();
      },
      success:function(msg){
        $('.uploading').hide();
        
        var obj = $.parseJSON(msg);
        
        if (obj.success == false) {
            $(".upload-image-messages").html(ojb.message);
        } else {
          $("#portfolio_images").fadeOut(800, function(){
            $("#portfolio_images").prepend(obj.message);
            $("#portfolio_images").append(obj.response).fadeIn().delay(2000);
            $("#images").filestyle('clear');
          });
        }        
      },
      error:function(e){
      }
    }).submit();
  });

});




</script>