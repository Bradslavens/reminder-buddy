<div id="add_item_form" style = "display: none;">
	<div class="row">
		<div class="col col-md-2">
		</div>
		<div class="col col-md-8">
	<!-- Enter form to add transaction name  -->
		<?php echo validation_errors(); ?>

		<?php $form_data = array(
			'class' => 'form-inline'); 
		echo form_open(site_url('/admin/index/'.strtolower($title).'/add_item'), $form_data); ?>
			  <div class="form-group">
			    <label for="subject">Subject</label>
			    <input name="heading" type="text" class="form-control" id="heading" placeholder="subject">
			  </div>
			  <div class="form-group">
			    <label for="subject">Body</label>
			    <textarea name="body" rows="10" class="form-control" id="body" placeholder="body"></textarea>
			  </div>

			  <!-- DO DAYS -->
			  <div class="form-group">
			    <label for="subject">Due Days</label>
			    <input name="days" type="text" class="form-control" id="days" title= "Enter the number of days the reminder is due from the date below." placeholder="due days from x">
			  </div>
			  <!-- DROP DOWNS FOR ADDITIONAL INFO -->

			  <!-- get due from date -->
			  <label for= "date_type">From what date is the reminder due?</label><br />

			  <select name="date_type">
			  	<?php foreach ($date_types as $date_type):; ?>
			    	<option value= "<?php echo $date_type['id']; ?>"><a href="#"><?php echo $date_type['date']; ?></a></option>
				<?php endforeach; ?>
			  </select><br />

				  <!-- get parties -->
			  <label for= "parties">Who will receive this reminder?</label><br />
			  <select size="10" name="party[]" multiple>
			  	<?php foreach ($parties as $party): ; ?> 
				   <option value="<?php echo $party['id']; ?>"><?php echo $party['name']; ?></option>
				<?php endforeach; ?>
			  </select> 
			  <br />
			  <!-- what type of item is this -->
			  <input type="hidden" value="1" name="item_type"><!--  1 is reminder -->
			  <input type="hidden" value="1" name="queue">
			  <input type="hidden" value="<?php echo $_SESSION['user_id']; ?>" name="tc_id">
			  <!-- SUBMIT FORM -->
			  <button type="submit" class="btn btn-primary">Add</button>
			</form>
		</div>
		<div class="col col-md-2">
		</div>
	</div>
</div>