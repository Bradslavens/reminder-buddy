<div class="container" id="update_item">
	<div class="row">
		<div class="col-md-12">
		 	<a href="/admin/index/home"><button class="btn btn-default">Back</button></a>	
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">

			<?php echo validation_errors(); ?>
			
			<?php echo form_open('/admin/edit_item'); ?>

			  <div class="form-group">
			    <label for="subject">Abbreviation</label>
			    <input required value = "<?php echo $item_dets['item_dets'][0]['heading']; ?>" name="heading" type="text" class="form-control" id="heading" placeholder="subject">
			  </div>
			  <div class="form-group">
			    <label for="subject">Details</label>
			    <textarea name="body" rows="10" class="form-control" id="body" placeholder="body"><?php echo $item_dets['item_dets'][0]['body']; ?>"</textarea>
			  </div>

			  <!-- DO DAYS -->
			  <div class="form-group">
			    <label for="subject">Due Days</label>
			    <input required  value = "<?php echo $item_dets['item_dets'][0]['days']; ?>" name="days" type="text" class="form-control" id="days" title= "Enter the number of days the reminder is due from the date below." placeholder="due days from x">
			  </div>
			  <!-- DROP DOWNS FOR ADDITIONAL INFO -->

			  <!-- get due from date -->
			  <label for= "date_type">From what date is the reminder due?</label><br />

			  <select required name="date_type" >
			  	<?php foreach ($date_types as $date_type):; ?>
			    	<option 
			    		<?php if($item_dets['item_dets'][0]['date_type'] == $date_type['id']) { echo 'selected';} ?> 
			    		value= "<?php echo $date_type['id']; ?>"><a href="#"><?php echo $date_type['date']; ?></a></option>
				<?php endforeach; ?>
			  </select><br />

				  <!-- get parties -->
			  <label for= "parties">Who Will sign this form?</label><br />
			  <select required size="10" name="party[]" multiple>
			  	<?php  foreach ($parties as $party): ; ?> 
				   <option 
				   	<?php foreach ($item_dets['curr_parties'] as $curr_party) {
				   		if($party['id'] == $curr_party['party']){
				   			echo "selected";
				   		}
				   	} ?>
				    value="<?php echo $party['id']; ?>"><?php echo $party['name']; ?></option>
				<?php endforeach; ?>
			  </select> 
			  <br />
			  <!-- what type of item is this -->
			  <input type="hidden" value="<?php echo $item_dets['item_dets'][0]['id']; ?>" name="item_type"><!--  2 is form -->
			  <input type="hidden" value="1" name="queue">
			  <input type="hidden" value="<?php echo $_SESSION['user_id']; ?>" name="tc_id">
			  <input type="hidden" value="<?php echo $item_dets['item_dets'][0]['id']; ?>" name="id">


			  <!-- SUBMIT FORM -->
			  <button type="submit" class="btn btn-default">Update</button>
			</form>


		</div>
	</div>
</div>