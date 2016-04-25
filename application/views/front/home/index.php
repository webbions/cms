<?php echo $this->load->view('layouts/front_banner', null, true); ?>
<section class="main-book">
  <div class="container">
    <div class="page-heading text-center">
          <h2>Easily book bands, entertainers, speakers and event services.</h2>
        </div>
    <div class="row">
        <div class="col-sm-4">
          <div class="book-box">
          <img src="<?php echo base_url(); ?>assets/theme/images/book-1.png">
            <h3>EXPLORE YOUR OPTIONS</h3>
            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="book-box"> <img src="<?php echo base_url(); ?>assets/theme/images/book-2.png">
            <h3>COMPARE YOUR RATES</h3>
            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking layout.</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="book-box"> 
          <img src="<?php echo base_url(); ?>assets/theme/images/book-3.png">
            <h3>BOOK WITH CONFIDENCE</h3>
            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.</p>
          </div>
        </div>
    </div>
  </div>
</section>
<section class="main-categories">
  <div class="container">
    <div class="page-heading text-center">
          <h2>Popular Categories</h2>
        </div>
    <div class="row">
      
      
      <?php
	  if(count($categories) > 0)
	  {
		  $i = 0;
		  foreach($categories as $catRow)
				{
				echo $i%4 == 0 ? '<div class="col-sm-12">' : '';			
			?>
			<div class="col-sm-3">
			  <div class="categories">
              <?php if($catRow['catimage'] && $catRow['catimage'] != '') {?>
              	<img src="<?php echo base_url(); ?>assets/categoryimages/<?php echo $catRow['catimage'];?>" class="img-responsive categories-img">
              <?php }else{?>
        	  	<img src="<?php echo base_url(); ?>assets/theme/images/categories-1.png" class="img-responsive categories-img">
			  <?php }?>
               <div class="categories-content">
        <h4><?php echo $catRow['name']?></h4>
				<a href="#" class="btn btn-primary btn-sm">Click Here</a>
        </div>
			  </div>
			</div>
			<?php 
				$i++;
				echo $i%4 == 0 ? '</div>' : '';
			}
			?>
       <?php }else{?>
        <div class="col-sm-12">
            <div class="book-box">
            	<h3>No Categories</h3>
            </div>
        </div>
       <?php }?>
     
      
    </div>
  </div>
</section>
<?php echo $this->load->view('layouts/front_staticBlock_footer', null, true); ?>