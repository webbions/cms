<section class="main-header">
  <div class="container">
    <div class="row">
      
        <div class="col-sm-3">
          <div class="header-logo"> <a href="<?php echo base_url()?>"><img src="<?php echo base_url(); ?>assets/theme/images/logo.png" class="img-responsive"></a></div>
        </div>
        <div class="col-sm-9">
          <div class="header-menu">
            <ul>
              <li><a href="<?php echo base_url()?>how-it-works" title="How it works">How it works</a></li>
				<?php 
                if ($this->ion_auth->logged_in())
                {?>
					<li><a href="<?php echo base_url()?>user/profile"><i class="fa fa-pencil fo-si"></i> Welcome <?php  if($curruser= $this->session->userdata()) { echo $curruser['username']; }  ?></a></li>
               <?php }else{
				?>
              		<li><a href="<?php echo base_url()?>register" title="Sign Up"><i class="fa fa-pencil fo-si"></i>Sign Up</a></li>
                <?php }?>
                
                <?php 
                if ($this->ion_auth->logged_in())
                {?>
					<li><a href="<?php echo base_url()?>user/logout" title="Sign IN"><i class="fa fa-user fo-si"></i>Log Out</a></li>
               <?php }else{
				?>
              		<li><a href="<?php echo base_url()?>login" title="Sign IN"><i class="fa fa-user fo-si"></i>Sign IN</a></li>
                <?php }?>
              
            </ul>
            <?php if (!$this->ion_auth->logged_in()): ?>
              <a href="#" class="btn btn-primary get-lis">Get Listed</a> </div>  
            <?php endif ?>
            
        </div>
      
    </div>
  </div>
</section>