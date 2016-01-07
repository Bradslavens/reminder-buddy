<div class="container"  id="registration">

	<?php echo validation_errors(); ?>

	<?php echo form_open('welcome/register_user'); //<form>
	?>

	<?php
	    	// set up form
	    	$first_name = array(
	    		'class'=>'form-control',
	    		'id'=>'first_name',
	    		'placeholder'=>'First Name',
	    		'name'=>'first_name');

	    	$last_name = array(
	    		'class'=>'form-control',
	    		'id'=>'last_name',
	    		'placeholder'=>'Last Name',
	    		'name'=>'last_name');

	    	$email = array(
	    		'class'=>'form-control',
	    		'placeholder'=>'email',
	    		'type' => 'email',
	    		'name'=>'email');

	    	$password = array(
	    		'class' => 'form-control',
	    		'name'=>'password');

	    	$checkbox = array(
	    		'type'=>'checkbox',
	    		'name'=>'checkbox',
	    		'id'=>'checkbox');

	    	$submit = array(
	    		'class'=> 'btn btn-default',
	    		'name' => 'submit',
	    		'content' => 'Submit',
	    		'type' => 'submit');
	    		?>
	  <div class="form-group">
	    <label for="First Name">First Name</label>	    
	    <?php echo form_input($first_name); //<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
	  	?>
	  </div>
	  <div class="form-group">
	    <label for="Last Name">Last Name</label>
	    <?php echo form_input($last_name); ?><!-- <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> -->
	  </div>
	  <div class="form-group">
	    <label for="Email">Email</label>
	    <?php echo form_input($email); ?><!-- <input type="file" id="exampleInputFile"> -->
	    <!-- <p class="help-block">Example block-level help text here.</p> -->
	  </div>
	  <div class="form-group">
	    <label for="Password">Create Password</label>
	    <?php echo form_password($password); ?><!-- <input type="file" id="exampleInputFile"> -->
	    <!-- <p class="help-block">Example block-level help text here.</p> -->
	  </div>

	  <div class="checkbox">
	    <label>
	      <?php echo form_input($checkbox); ?>Add me to your mailing list for news and updates
	    </label>
	  </div>
	  <?php echo form_button($submit); ?>
	  <!-- <button type="submit" class="btn btn-default">Submit</button> -->
	</form>	

</div> <!-- reg -->