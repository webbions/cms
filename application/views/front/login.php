<section class="middel">
	<div class="container">
    	<div class="login">
        	<div class="heading">
            	<h3>Login</h3>
            </div>
            <?php echo renderMessage()?>
            <form action="<?php echo base_url()."user/login"?>" name="loginform" id="loginform" method="post">
            	<div class="form">
            	<div class="form-group">
            	<label>Email:</label>
                <input type="text" value="" name="identity" id="identity" class="form-control" />
                </div>
                <div class="form-group">
                <label>Password:</label>
                <input type="password" value="" name="password" id="password" class="form-control" />
                </div>
                <div class="form-group">
                <button type="submit" class="btn btn-info">Login</button>
                <a href="#" class="pull-right forgot-link">Forgot password?</a>
                </div>
                <div class="well">
                Don't have an account? <a href="<?php echo base_url(); ?>register">Create an account!</a>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>

<script>
$("#loginform").validate({
    rules: {
     identity : { required : true},
	 password : { required : true}
    }
});
</script>