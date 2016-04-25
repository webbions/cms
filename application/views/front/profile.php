<section class="middel">
<div class="container">
<div class="col-sm-9">
  <div class="search-list box">
    <div class="clearfix">
      <div class="avtar profile"> <img src="<?php echo base_url()?>assets/theme/images/avtar.jpg" width="266" height="226" alt=""> </div>
      <div class="profile-left"> <a href="#" class="btn btn-default pull-right"><i class="fa fa-pencil"></i> Edit</a>
        <h3><?php  if($curruser= $this->session->userdata()) { echo $curruser['username']; }  ?></h3>
        <h4>Actor, Model, Musician, Dancer</h4>
        <h4 class="mar-top">Current:<span>H.L. Seifert, Remedia</span></h4>
        <h4>Availed to Travel:<span> Yes</span></h4>
        <h4>Education:<span>KEA - Copenhagen School of Dance & Music</span></h4>
        <h4 class="mar-top">About Me</h4>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.</p>
      </div>
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> <a href="#" class="btn btn-default pull-right"><i class="fa fa-pencil"></i> Edit</a>
          <h2><img src="<?php echo base_url()?>assets/theme/images/education.png" width="24" height="27">My Education</h2>
          <p>Lorem Ipsum is simply dummy text</p>
        </div>
      </div>
      <div class="eduction-text">
        <h3>Lorem Ipsum is simply dummy text of the printing</h3>
        <h4>Dubai International University of Dancer</h4>
        <h5>2015 - 2016 in Dubai,<i class="fa fa-map-marker"></i> UAE</h5>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
      </div>
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> <a href="<?php echo base_url()?>user/addphotos" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Photos</a>
          <h2><i class="fa fa-camera"></i> Photos</h2>
          <p>Lorem Ipsum is simply dummy text</p>
        </div>
      </div>
      <div class="row">
        <div class="photos">
         <?php if(count($userimages) > 0){
			 	foreach($userimages as $imgRow){
			 ?>
          <div class="col-sm-3"> <img src="<?php echo base_url()?>assets/userimages/<?php echo $curruser['user_id'].'/'.$imgRow['upiimage'];?>" class="img-responsive"> </div>
          <?php } //foreach 
		  	}else{?>
          	<div class="col-sm-3">No Images</div>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> <a href="<?php echo base_url()?>user/addaudio" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Audio</a>
          <h2><i class="fa fa-music"></i> Audio</h2>
          <p>Lorem Ipsum is simply dummy text</p>
        </div>
      </div>
      <div class="row">
        <div class="photos">
          <?php if(count($useraudios) > 0){
			 	foreach($useraudios as $adoRow){
			 ?>
              <div class="col-sm-3">  <embed serc="YourMusic.mp3" autostart="true" loop="true" width="2" height="0">
  </embed>
            <p>Audio</p>
          </div>
          
          <?php } //foreach 
		  	}else{?>
                <div class="col-sm-3">
                	<p>No Audio</p>
                </div>
          <?php }?>
         
        </div>
      </div>
    </div>
  </div>
  <div class="search-list box box-pa-0">
    <div class="clearfix">
      <div class="education-heading">
        <div class="page-heading page-margin"> <a href="<?php echo base_url()?>user/addvideo" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Video</a>
          <h2><i class="fa fa-video-camera"></i> Video</h2>
          <p>Lorem Ipsum is simply dummy text</p>
        </div>
      </div>
      <div class="row">
        <div class="photos">
         <?php if(count($uservideos) > 0){
			 	foreach($uservideos as $vdoRow){
			 ?>
            <div class="col-sm-3"> <iframe class="img-responsive" src="<?php echo $vdoRow['upvvideo'];?>">
            </iframe>
            <p>Lorem Ipsum is simply</p>
          </div>
          <?php } //foreach 
		  	}else{?>
          	<div class="col-sm-3">
            <p>No Video</p>
          </div>
          <?php }?>
          
        
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