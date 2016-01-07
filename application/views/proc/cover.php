<div class="container">
	<div class="row">

				<?php echo validation_errors(); ?>
				<?php echo form_open(site_url('proc/processing/add_update_cover')); ?>
			
			  <div class="form-group col-md-4">
			  	<div class="row">
			  		<div class="form-group col-md-6">
					    <label title ="See RPA page 1 paragraph 2 b" for="side">Transaction Status</label>
					    <select class="form-control" name="status" >
					    	<?php foreach ($transaction_status as $status): ?>
					    		<option value="<?php echo $status['id']; ?>" <?php if(isset($transaction[0])){if($status['id'] == $transaction[0]['status']){ echo "selected"; }} ?>><?php echo $status['status']; ?></option>
					    	<?php endforeach; ?>
					    </select>
					</div>
			  		<div class="form-group col-md-6">
					    <label title ="See RPA page 1 paragraph 2 b" for="side">Which Side do we represent?</label>
					    <select class="form-control" name="side" placeholder="Side">
					    	<?php foreach ($sides as $side): ?>
					    		<option value="<?php echo $side['id']; ?>" <?php if(isset($transaction[0])){if($side['id'] == $transaction[0]['side']){ echo "selected"; }} ?>><?php echo $side['side']; ?></option>
					    	<?php endforeach; ?>
					    </select>
					</div>
			  		<div class="form-group col-md-12">
					    <label for="address">Street Address</label>
					    <input style="color: red; font-weight: bold;" value="<?php if(isset($transaction)){echo $transaction[0]['name'];} ?>"  type="text" class="form-control" name="name" placeholder="Street Address">
			  		</div>
			  		<div class="form-group col-md-12">
					    <label for="city">City</label>
					    <input value="<?php if(isset($transaction)){echo $transaction[0]['city'];} ?>"   type="text" class="form-control" name="city" placeholder="City">
					</div>
				  	<div class="form-group col-md-12">
				    	<label for="State">State Abbreviation</label>
				   	 	<input value="<?php if(isset($transaction)){echo $transaction[0]['state'];} ?>"   type="text" class="form-control" name="state" placeholder="state">
				  	</div>
				    <div class="form-group col-md-12">
				    	<label for="Zip">Zip</label>
				    	<input value="<?php if(isset($transaction)){echo $transaction[0]['zip'];} ?>"   type="text" class="form-control" name="zip" placeholder="zip">
				    </div>
			   	  	<div class="form-group col-md-12">
				    	<label title ="See email from escrow or ask escrow" for="escrow_number">Escrow Number</label>
				    	<input value="<?php if(isset($transaction)){echo $transaction[0]['escrow_number'];} ?>"   type="text" class="form-control" name="escrow_number" placeholder="escrow #">
			      	</div>
			  	
			   	  	<div class="form-group col-md-12">
				    	<label title ="See MLS" for="mls_number">Mls Number</label>
				    	<input value="<?php if(isset($transaction)){echo $transaction[0]['mls_number'];} ?>"   type="text" class="form-control" name="mls_number" placeholder="mls">
			      	</div>
			  	
			   	  	<div class="form-group col-md-12">
				    	<label title ="Left Click on image in MLS and click copy image address. " for="photo_link">Photo Link</label>
				    	<input value="<?php if(isset($transaction)){echo $transaction[0]['photo_link'];} ?>"   type="text" class="form-control" name="photo_link" placeholder="photo link">
			      	</div>
		  	
			  	</div> <!-- row -->
			  </div> <!-- formgroup -->

		      <!-- home warranty section -->
		      <div class="col-md-4">

			   	  <div class="form-group col-md-4">
			    	<label title="See RPA Page 3 paragraph 7 d 10  or counters " for="hw_yes_no">Was a Home Warranty Requested?</label>
			    	<select class="form-control" name="hw_yes_no" placeholder="hw">
			    		<option value="1">Yes</option>
			    		<option value="0">No</option>
			    	</select>
			      </div>
			  	
			  	
			  	
			   	  <div class="form-group col-md-12">
			    	<label title ="See RPA paragraph 7 d 10 and counter offers." for="hw_who_pays">Who will pay for the Home Warranty</label>
			    	<input value="<?php if(isset($transaction)){echo $transaction[0]['hw_who_pays'];} ?>"  type="text" class="form-control" name="hw_who_pays" placeholder="who pays for the home warranty?">
			      </div>
			  	
			  	
			   	  <div class="form-group col-md-12">
			    	<label title ="See RPA paragraph 7 d 10 and counter offers."  for="hw_company">HW Company</label>
			    	<input value="<?php if(isset($transaction)){echo $transaction[0]['hw_company'];} ?>"   type="text" class="form-control" name="hw_company" placeholder="home warranty company">
			      </div>
			  	
			  	
			   	  <div class="form-group col-md-12">
			    	<label title ="See RPA paragraph 7 d 10 and counter offers."  for="hw_amount">Home Warranty $amount</label>
			    	<input value="<?php if(isset($transaction)){echo $transaction[0]['hw_amount'];} ?>"   type="text" class="form-control" name="hw_amount" placeholder="home warranty $amount requested">
			      </div>
			  	
			   	  <div class="form-group col-md-12">
			    	<label title ="See RPA paragraph 7 d 10 and counter offers."  for="hw_upgrades">Home Warranty Upgrades</label>
			    	<input value="<?php if(isset($transaction)){echo $transaction[0]['hw_upgrades'];} ?>"   type="text" class="form-control" name="hw_upgrades" placeholder="What upgrades are requested on the contract?">
			      </div>
		      </div>

		      <!-- end home warranty section -->
		  	
		  	
		  	
		   	  <div class="form-group col-md-4">
		    	<label title="See commission advertised on the MLS agent copy or RLA if we represent the seller." for="commission">Commission</label>
		    	<input value="<?php if(isset($transaction)){echo $transaction[0]['commission'];} ?>"   type="text" class="form-control" name="commission" placeholder="commission">
		      </div>
		  	
		  	
		   	  <div class="form-group col-md-4">
		    	<label title="See public record or property profile." for="apn">APN</label>
		    	<input value="<?php if(isset($transaction)){echo $transaction[0]['apn'];} ?>"   type="text" class="form-control" name="apn" placeholder="apn #">
		      </div>


		  	
		   	  <div class="form-group col-md-4">
		    	<label title ="See RPA paragraph 7 a 1 and counter offers."  for="nhd_who_pays">Who Will Pay for the NHD Report?</label>
		    	<input value="<?php if(isset($transaction)){echo $transaction[0]['nhd_who_pays'];} ?>"  type="text" class="form-control" name="nhd_who_pays" placeholder="Who pays for the NHD?">
		      </div>
		  	
		  	
		   	  <div class="form-group col-md-4">
		    	<label title ="See RPA paragraph 7 a 1 and counter offers."  title="See RPA Page 3" for="nhd_company">Which NHD Company was requested?</label>
		    	<input value="<?php if(isset($transaction)){echo $transaction[0]['nhd_company'];} ?>"   type="text" class="form-control" name="nhd_company" placeholder="Which NHD company is requested?">
		      </div>
		  	
		  	
		   	  <div class="form-group col-md-4">
		    	<label title ="See public record or PP."  for="year_built">Year Built</label>
		    	<input value="<?php if(isset($transaction)){echo $transaction[0]['year_built'];} ?>"   type="text" class="form-control" name="year_built" placeholder="What year was the house built?">
		      </div>
		  	
		  	<div class="col-md-4">
		  		<button type="submit" class="btn btn-warning">Submit</button>
		  	</div>
		</form>
	</div>

	<div class="row" <?php if(!isset($_SESSION['transaction_id'])){echo "style = 'display: none;'";} ?>>
		<!-- select template form -->
		<?php echo validation_errors(); ?>
		<?php echo form_open(site_url('proc/processing/apply_templates')); ?>
	  		<div class="form-group col-md-12">
			    <label for="template">Select Templates to Apply</label>
			    <select multiple  class="form-control" name="templates[]">
			    	<?php foreach ($templates as $template): ?>
			    		<option value="<?php echo $template['id']; ?>"><?php echo $template['name']; ?></option>
			    	<?php endforeach; ?>
			    </select>
			</div>
		  	<div class="col-md-12">
				<input type="submit" class="btn btn-warning" value="Apply Template">
			</div>
		</form>
				

	</div>
</div>


<!-- 
xaddress
xcity 
xstate
xzip

xmls #
xescrow #
our side
our agent %
xcommission %
xphoto link
xapn
hw who pays what company amount coverage yes no additional items
nhd who pays what company
year built

tool tips

 -->