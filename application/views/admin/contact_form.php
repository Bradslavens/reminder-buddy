<div class="row">
	<div class="col col-md-2">
	</div>
	<?php 
	if(isset($contact)){
		$to_do = 'update_item'; 
		$display ="";
	}else{
		$to_do = 'add_item'; 
		$display="display: none";
	}
	?>
	<div class="col col-md-8"  id ="add_item_form" style="<?php echo $display; ?>;">
<!-- Enter form to add transaction name  -->

	<?php $form_data = array('class' => 'form-inline');  
	echo form_open(site_url('/admin/index/').strtolower($title).'/'. $to_do, $form_data); ?>
		  <div class="form-group">
		    <label for="exampleInputName2">First Name</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['first_name'];} ?>"  name="first_name" type="text" class="form-control" id="exampleInputName2" placeholder="First Name">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputName2">Last Name</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['last_name'];} ?>" name="last_name" type="text" class="form-control" id="exampleInputName2" placeholder="last Name">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputEmail2">Email</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['email'];} ?>" name="email" type="email" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com">
		  </div>
		  <div class="form-group">
		    <label for="company">Company</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['company'];} ?>" name="company" type="text" class="form-control" id="company" placeholder="company">
		  </div>
		  <div class="form-group">
		    <label for="bre license number">Bre License Numbrer</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['bre_license_number'];} ?>" name="bre_license_number" type="text" class="form-control" id="bre_license_number" placeholder="bre_license_number">
		  </div>
		  <div class="form-group">
		    <label for="License Expiration">License Expiration</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['license_expiration'];} ?>" name="license_expiration" type="text" class="form-control" id="license_expiration" placeholder="license_expiration">
		  </div>
		  <div class="form-group">
		    <label for="Commission Percent">Commission Percent</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['commission_percent'];} ?>" name="commission_percent" type="text" class="form-control" id="commission_percent" placeholder="commission_percent">
		  </div>
		  <div class="form-group">
		    <label for="Commission Fee">Commission Fee</label>
		    <input value="<?php if(isset($contact[0])){ echo $contact[0]['commission_fee'];} ?>" name="commission_fee" type="text" class="form-control" id="commission_fee" placeholder="commission_fee">
		  </div>
		  <input type="hidden" name= "id" value="<?php if(isset($contact[0])){ echo $contact[0]['id'];} ?>">
		  <button type="submit" class="btn btn-default">Add/Update</button>
		</form>
	</div>
	<div class="col col-md-2">
	</div>
</div>