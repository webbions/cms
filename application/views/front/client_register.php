<section class="middel">
	<div class="page-title">
    	<div class="container">
        	<h2>Register as a casting professional</h2>
        </div>
    </div>

    <form method="post" enctype="multipart/form-data" action=""  id="addUserForm" name="addUserForm">
		<div class="container">
        <?php echo renderMessage()?>
    	<div class="signup">
        	<p style="margin-bottom:30px;">Create your membership to contact members or list Jobs & Auditions (free).<br>
Want to create a free online profile and apply to auditions & jobs? <a href="<?php echo base_url(); ?>talent/register">Register as talent</a></p>
        	<div class="login-with">
        	   <a href="<?php echo base_url() . 'south/login/Facebook/2';?>"><img src="<?php echo base_url('assets/theme/images/facebook-login.png'); ?>" width="230" height="40" alt=""></a>
                <a href="<?php echo base_url() . 'south/login/LinkedIn/2';?>"><img src="<?php echo base_url('assets/theme/images/linkedinButton.png'); ?>" width="230" height="40" alt=""></a>
            </div>

            <div class="clearfix"></div>
        	<div class="heading">
            	<h3>Enter your details</h3>
            </div>
            <div class="form">
            	<div class="row">
                	<div class="col-md-8">
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>First name <span class="req">*</span></label>
                                    <input type="text" value="" name="first_name" id="first_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Last name <span class="req">*</span></label>
                                    <input type="text" value="" name="last_name" id="last_name" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Birth date <span class="req">*</span></label>
                                    <div class="row">
                                    	<div class="col-sm-8">
                                        	<div class="select-dropdown">
                                        	<select class="form-control" name="birthmonth">
                                                <option value="">Month</option>
                                                <?php
                                                for ($m=1; $m<=12; $m++) {
                                                    $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                                    $mothnPre = $m<10 ? '0' : '';
                                                    echo "<option value='".$mothnPre.$m."'>". $month. '</option>';
                                                }?>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                        	<div class="select-dropdown">
                                        	<select class="form-control" name="birthyear">
                                                <option value="">Year</option>
                                                <?php for($i = 2016;$i >1901; $i--){?>
                                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
												<?php }?>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Gender <span class="req">*</span></label>
                                    <div class="radio">
                                        <input type="radio" id="Male" value="Male" name="gender">
                                        <label for="Male">Male</label>
                                        <div class="check"></div>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" id="Female" value="Female" name="gender">
                                        <label for="Female">Female</label>
                                        <div class="check"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Country <span class="req">*</span></label>
                                    <div class="select-dropdown">
                                        <?php echo form_dropdown('country', getAllCountry(), '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label>City <span class="req">*</span></label>
                                    <input type="text" value="" name="city" id="city" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="heading">
            	<h3>Sign in details</h3>
            </div>
            <div class="form">
            	<div class="row">
                	<div class="col-md-8">
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Email address <span class="req">*</span></label>
                                    <input type="text" value="" name="email" id="email" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Choose a password <span class="req">*</span></label>
                                    <input type="password" value="" name="password" id="password" class="form-control" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="heading">
            	<h3>Company info (optional)</h3>
            </div>
            <div class="form">
            	<div class="row">
                	<div class="col-md-8">
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Company name <span class="req">*</span></label>
                                    <input type="text" value="" name="company_name" id="" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Company website <span class="req">*</span></label>
                                    <input type="text" value="" name="company_website" id="" class="form-control" placeholder="http://" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>My position <span class="req">*</span></label>
                                    <input type="text" value="" name="position" id="" class="form-control" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="form">

            	<div class="form-group">
                	<p>By clicking the Register button below, you confirm that you have read and agree with Talents List <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.</p>
                </div>
                <div class="form-group">
                <!--<button type="submit" class="btn btn-info btn-lg">Sign Up</button>-->
                <input type="submit" name="regisuteruser" value="Sign Up" class="btn btn-info btn-lg">
                </div>



            </div>
        </div>
    </div>
    </form>
</section>

<script>
$(document).ready(function($) {
    $("#addUserForm").validate({
        rules: {
            group :  "required",
            "ucategory[]" :  "required",
            first_name :  "required",
            last_name :  "required",
            birthmonth :  "required",
            birthyear :  "required",
            gender :  "required",
            country :  "required",
            city :  "required",
            email : {
                required : true,
                email : true
            },
            password :  "required",
        },
		messages: {
			 group: "Please select at least one",
			 first_name : "Frist name is required",
			 last_name : "Last name is required",
			 city :  "City is required",
			 email : "Email is required",
			 password : "Password is required",
		 },
    });
});
</script>