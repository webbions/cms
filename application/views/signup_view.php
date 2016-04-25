<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login Form</title>
     <!--link the bootstrap css file-->
     <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
     
     

     <style type="text/css">
		
		form
		{
			width:400px;
		}
		h1
		{
			padding: 3px;
			margin-right: 10px;
		}
		form ul
		{
			list-style-type:none;
		}
		form ul li
		{
			margin: 15px 0;
		}
		form label 
		{
		   display:block;
		   font-size: 1em;
		}
		form input
		{
		   font-size: 1em;
 	       padding: 5px;
        }
        form select
        {
        	font-size: 1em;
        	padding: 5px;
        }

	</style>
	
</head>   
<body>
<div class="container">
     <div class="row">
          <div class="col-lg-6 col-sm-6">
               <h1>Signup</h1>
          </div>
          <div class="col-lg-6 col-sm-6">
               
               <ul class="nav nav-pills pull-right" style="margin-top:20px">
                    <li ><a href="<?php echo base_url(); ?>index.php/Client/index">Login</a></li>
                    <li class="active"><a href="<?php echo base_url(); ?>index.php/Client/Signup">Signup</a></li>
               </ul>
               
          </div>
     </div>
</div>
<hr/>
			
	

<body>
<form name="form" action="<?php echo base_url();?>index.php/Client/Signup" method="post">
	
<ul>
	<li>
	<label for="name">User Name</label>
	<input type="text" name="username" id="username">
	<font color="#FF0000">
	<?php echo form_error('username'); ?>
	</font>
 	</li>
	<li>
	<label for="password">Password</label>
	<input type="password" name="password" id="password">
	<font color="#FF0000">
	<?php echo form_error('password'); ?>
	</font>
	</li>
	
	

	<li>
	<label for="user">User Type</label>
	<input type="radio" name="user" value="Members">Members
        <input type="radio" name="user" value="Client">Client
	</li>

	<li>
	<input type="submit" value="Submit" name="submit">
	<input type="reset" value="Reset">

	</li>
</ul>
</form>


			
	</body>
</html>