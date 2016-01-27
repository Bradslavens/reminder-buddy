<div id="reminders" class="container">
	<div class="panel panel-default">
  	<!-- Default panel contents -->
  		<div class="panel-heading">Reminders 
		          <a class = "pull-right" title= "Add Reminder" id = "add_item" href="#"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
  		</div>
	  	<!-- Table -->
	  	<table class="table">
		      <tr>
		        <td colspan="7"> <div id = "item" style="display: none;">
			            <!-- add new form  -->
			            <?php echo validation_errors(); ?>
			            <?php $form_attributes = array('id'=>'add_form'); ?>
			            <?php echo form_open('proc/processing/reminders/'.$_SESSION['transaction_id'].'/add_form', $form_attributes); ?>

			         	<!-- NAME AND EMAIL  -->
			            <div class="form-group">
			                <label for="description">Body</label>
			                <textarea name="body" class="form-control" id="description" placeholder="description" title="Message Body -> use this field to search"></textarea>
			            </div>

			            <div class="form-group">
			                <label for="short_name">Subject</label>
			                <input name="heading" type="text" class="form-control" id="short_name" placeholder="Subject">
			            </div>


		              <!-- show item search results -->
		                <ul id =  "search_item_results">
		                </ul>
		                <!-- show due date and from date -->
			              <div class="form-group">
			                  <label for="days">Form Due <input name = "days" size = "10" type = "text" title = "Enter the number of days from the specified date that the form is due"> Days From:</label>
			                  <select form ="add_form" name = "date_type" class="form-control">
			                    <?php foreach ($date_types as $date_type): ?> 
			                      <option value = "<?php echo  $date_type['id']; ?>"><?php echo $date_type['date']; ?></option>
			                    <?php endforeach; ?>
			                  </select>
			              </div>

						  <div class="form-group">
			                  <label for="parties">Select All Signers</label>
			                  <select form ="add_form" multiple name = "parties[]" class="form-control">
			                    <?php foreach ($parties as $party): ?> 
			                      <option value ="<?php echo $party['id'];?>"><?php echo $party['name']; ?></option>  
			                    <?php endforeach; ?>
			                  </select>
			              </div>
						<!-- hidden inputs  -->
			            <!-- set item type to 2 forms -->
			            <input name= "item_type" type = "hidden" value= "2" /> 
			            <input name= "tc_id" type = "hidden" value= "<?php echo $_SESSION['user_id']; ?>" /> 
			            <input name= "transaction_id" type = "hidden" value= "<?php echo $_SESSION['transaction_id']; ?> " /> 
			            <input name= "default_queue" type="hidden" value= "1" />
			            <!-- form id -->

			            <input id="item_id" name= "item_id" type = "hidden" value="1" /> 
			            <!-- SUBMIT    -->
			            <button type="submit" class="btn btn-success">Submit</button>
			            
			         </form>
		          </div> <!-- item -->
		        </td>
		      </tr>

	  		<tr>
		  		<th>
		  			X
		  		</th>
		  		<th>
		  			Subject
		  		</th>
		  		<th>
		  			Message
		  		</th>
	  		</tr>

	  		<?php echo form_open(site_url('proc/processing/mail_reminders')); ?>
	  		<!-- start list  -->
	  		<?php foreach ($items as $item): ?>
	  		<tr>
	  			<td>
				  <div class="checkbox">
				    <label>
				      <input name= "reminder[]" value="<?php echo $item['id']; ?>" type="checkbox">
				    </label>
				  </div>
	  			</td>
	  			<td>
	  				<?php echo $item['heading']; ?>
	  			</td>
	  			<td>
	  				<?php echo $item['body']; ?>
	  			</td>
	  			<td>
	  				<a href="<?php echo site_url('proc/processing/reminders/del_item/' . $item['id']); ?>"><span title = "Click to Delete this item" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
	  			</td>
	  		</tr>
	  		<!-- end list -->
	  		<?php endforeach; ?>
	  	</form>
	  	</table>
	</div>
</div>