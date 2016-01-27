<div id="dates" class="container">
	<div class="panel panel-default">
	  <!-- Default panel contents -->
	  <div class="panel-heading">Transaction Dates <a href="http://www.timeanddate.com/date/dateadd.html" target="_blank">Date Calculator</a></div>

	  <!-- Table -->
	  <table class="table">
	      <tr>
	        <td colspan="7">
	          <a id = "add_item" href="#">+Dates</a>
	          <div id = "item" style="display: none;">
		          	
				<!-- Enter form to add transaction name  -->
				<?php echo validation_errors(); ?>

				<?php echo form_open('proc/processing/dates/'.$_SESSION['transaction_id'].'/add_date'); ?>
					<select class="form-control" name= "date_type">
						<!-- display dates  -->
						<?php foreach ($date_types as $date_type): ?> 
							<option <?php echo 'value ="'. $date_type ['id'] .'"'; ?>><?php echo $date_type ['date']; ?></option>
						<?php endforeach ?>	  
					</select>

					<div class="form-group">
					    <label for="date">Date</label>
					    <input name="date" type="text" class="form-control" id="datepicker" title="enter date">
					</div>
				    <button type="submit" class="btn btn-default">Add Date</button>
				</form>	

	          	
	          </div>
	        </td>
	      </tr>
  		<tr>
	  		<th>
	  			Type
	  		</th>
	  		<th>
	  			Date
	  		</th>
	  		<th>
	  			X
	  		</th>
	  	</tr>
	  	<!-- start list -->
	  	<?php foreach ($dates as $date): ?>
	  	<tr>
	  		<td title = "Date Type">
	  			<?php echo $date['date']; ?> 
	  		</td>
	  		<td title = "Date">
	  			<?php echo $date['calendar_date']; ?>
	  		</td>
	  		<td title = "Edit">
	  			Edit
	  		</td>
  			<td>
  				<a href="<?php echo site_url('proc/processing/dates/del_item/' . $date['id']); ?>"><span title = "Click to Delete this item" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
  			</td>
	  	</tr>
	  	<!-- end list  -->
	  <?php endforeach; ?>
	  </table>
	</div>
</div>