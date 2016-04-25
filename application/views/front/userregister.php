<section class="middel">
	<div class="page-title">
    	<div class="container">
        	<h2>Sign Up</h2>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" action=""  id="addUserForm" name="addUserForm" autocomplete="off">
		<div class="container">
        <?php echo renderMessage()?>
    	<div class="signup">
        	<div class="login-with">
                <a href="<?php echo base_url() . 'south/login/Facebook/3';?>"><img src="<?php echo base_url('assets/theme/images/facebook-login.png'); ?>" width="230" height="40" alt=""></a>
                <a href="<?php echo base_url() . 'south/login/LinkedIn/3';?>"><img src="<?php echo base_url('assets/theme/images/linkedinButton.png'); ?>" width="230" height="40" alt=""></a>
            </div>
            <!-- <div class="heading">
            <h3>Are You A</h3>
            </div> -->
            <!-- <div class="are-you">
                <?php foreach ($groupsDetails as $gRow){if($gRow['id'] == 1){ continue;} ?>
                <div class="radio">
                    <input type="radio" id="<?php echo $gRow['name']; ?>" name="group" value="<?php echo $gRow['id']; ?>" <?php echo set_radio('group', $gRow['id'], TRUE); ?> >
                    <label for="<?php echo $gRow['name']; ?>"><strong><?php echo ucfirst( $gRow['name'] ); ?></strong></label>
                    <div class="check"></div>
                </div>
                <?php }?>
            </div> -->
            <input type="hidden" name="group" value="3" >
            <hr>
        	<div class="heading">
           	  <h3>What are your interests?</h3>
            </div>
            <div class="form">
            	<div class="row">
                	<div class="col-md-8">
                        <div class="row">
                        	<div class="col-sm-12">
                                <div class="row interests-error">
                                <?php if(count($categorydata) > 0){?>
                            		<?php $i=0;
										  foreach($categorydata as $catRow) {
                                            echo $i%3 == 0 ? '<div class="col-sm-4"><div class="form-group">' : '';
									 ?>
                                            <div class="checkbox">
                                                <input type="checkbox" id="cat_<?php echo $i;?>" value="<?php echo $catRow['id'];?>" name="ucategory[]" <?php echo set_checkbox('ucategory[]',  $catRow['id']); ?> >
                                                <label for="cat_<?php echo $i;?>"><?php echo $catRow['name'];?></label>
                                                <div class="check"></div>
                                            </div>
                                            <div class="clearfix"></div>

                                    <?php  $i++; echo $i%3 == 0 ? ' </div></div>' : '';      } //foreach?>
                            	<?php } ?>
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
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
                                    <input type="text" value="<?php echo set_value("first_name"); ?>" name="first_name" id="first_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Last name <span class="req">*</span></label>
                                    <input type="text" value="<?php echo set_value("last_name"); ?>" name="last_name" id="last_name" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Birth date <span class="req">*</span></label>
                                    <div class="row">
                                    	<div class="col-sm-6">
                                        	<div class="select-dropdown">
                                        	<select class="form-control" name="birthmonth">
                                                <option value="">Month</option>
                                                <?php
                                                for ($m=1; $m<=12; $m++) {
                                                    $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                                    $mothnPre = $m<10 ? '0' : '';
                                                    $set_select = set_select('birthmonth', ($mothnPre.$m), TRUE); 
                                                    echo "<option value='".$mothnPre.$m."' $set_select>". $month. '</option>';
                                                }?>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                        	<div class="select-dropdown">
                                        	<select class="form-control" name="birthyear">
                                                <option value="">Year</option>
                                                <?php for($i = 2016;$i >1901; $i--){?>
                                                <option value="<?php echo $i;?>" <?php echo set_select('birthyear', $i, TRUE); ; ?>><?php echo $i;?></option>
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
                                        <input type="radio" id="Male" value="Male" name="gender" <?php echo set_radio('gender', 'Male', TRUE); ?> >
                                        <label for="Male">Male</label>
                                        <div class="check"></div>
                                    </div>
                                    <div class="radio gender-msg">
                                        <input type="radio" id="Female" value="Female" name="gender" <?php echo set_radio('gender', 'Female'); ?>>
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
                                        <?php echo form_dropdown('country', getAllCountry(), [set_value('country')], 'class="form-control"'); ?>
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
                                    <input type="text" name="email" id="email" class="form-control" autocomplete="off" value="<?php echo set_value("email"); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label>Choose a password <span class="req">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control "autocomplete="off" value="<?php echo set_value("password"); ?>"/>
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
		errorPlacement: function(error, element) {
           if(element.attr("name") == 'ucategory[]'){
                error.insertAfter($('.interests-error'));
           }else if(element.attr("name") == 'group'){
                error.insertAfter($('.are-you'));
           }else if(element.attr("name") == 'gender'){
                error.insertAfter($('.gender-msg'));
           }else{
                error.insertAfter(element);
           }
        },
    });
});
</script>