<section class="middel">
	<div class="page-title">
    	<div class="container">
        	<h2>Profile</h2>
        </div>
    </div>
  <form action="" enctype="multipart/form-data" name=="userimageform" id="userimageform" method="post">
		<div class="container">
    	<div class="signup">
        
        	<div class="login-with">
        	<a href="#"><img src="<?php echo base_url('assets/theme/images/facebook-login.png'); ?>" width="230" height="40" alt=""></a>
            <a href="#"><img src="<?php echo base_url('assets/theme/images/linkedinButton.png'); ?>" width="230" height="40" alt=""></a>
            </div>
          
            
                <div class="heading">
                    <h3>Upload Image</h3>
                </div>
                <div class="form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Choose image<span class="req">*</span></label>
                                        <input type="file" value="" name="userimages" id="userimages" class="form-control" />
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form">
                    <div class="form-group">
                    <input type="submit" name="userimages" value="Upload" class="btn btn-info btn-lg">
                    </div>
                </div>
        </div>
    </div>
    </form>
</section>

<script>
$("#userimageform").validate({
    rules: {
		userimages: {
			required: true,
			 accept: "jpg|jpeg|png|JPG|JPEG|PNG"
		}
    }
});
</script>