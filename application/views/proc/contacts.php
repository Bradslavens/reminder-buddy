<div id="contacts" class="container">
	<div class="panel panel-default">
	  <!-- Default panel contents -->
	  <div class="panel-heading">Transaction Parties</div>

	  <!-- Table -->
	  <table class="table">
	      <tr>
	        <td colspan="7">
	          <a id = "add_item" href="#">+Party</a>
	          <div id = "item" style="display: none;">

				<!-- Enter form to add transaction name  -->
				<?php echo validation_errors(); ?>

				<?php echo form_open('proc/processing/contacts/'.$_SESSION["transaction_id"].'/add_transaction_party'); ?>

					<!-- NAME AND EMAIL  -->
					<div class="form-group">
					    <label  for="first_name">First Name</label>
					    <input autocomplete="off" id="first_name" name="first_name" type="text" class="form-control" id="first_name" placeholder="First Name">
					</div>
					<div id="search_item_results">
					</div>

					<div class="form-group">
					    <label for="first_name">Last Name</label>
					    <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Last Name">
					</div>

					<div class="form-group">
					    <label for="email">Email address</label>
					    <input name="email" type="email" class="form-control" id="email" placeholder="Email">
					</div>

	                <div class="form-group">
	                  <label for="party">Select this contacts role: </label>
	                  <select name="party" class="form-control">
	                    <?php foreach ($parties as $party): ?> 
	                      <option value ="<?php echo $party['id'];?>"><?php echo $party['name']; ?></option>  
	                    <?php endforeach; ?>
	                  </select>
	                </div>

					<input type= "hidden" name= "party_id" value= "1" />

						<!-- SUBMIT 	 -->
				    <button type="submit" class="btn btn-default">Submit</button>

				    <a class="btn btn-default" href="add_reminders">Next</a>
			    </form>	
	          	
	          </div>
	        </td>
	      </tr>
	  	<tr>
	  		<th>
	  			First Name
	  		</th>
	  		<th>
	  			Last Name
	  		</th>
	  		<th>
	  			Role
	  		</th>
	  		<th>
	  			Primary
	  		</th>
	  		<th>
	  			X
	  		</th>
	  	</tr>
	  	<!-- start list -->
	  	<?php foreach ($transaction_parties as $party): ?> 
	  	<tr>
	  		<td>
	  			<?php echo $party['first_name']; ?>
	  		</td>
	  		<td>
	  			<?php echo $party['last_name']; ?>
	  		</td>
	  		<td>
	  			<?php echo $party['party']; ?>
	  		</td>
	  		<td>
	  			<?php
	  				if ($party['is_primary'] == 0)
	  				{
	  					echo "No";
	  				} 
	  				else
	  				{
	  					echo "Yes";
	  				}
	  			?>
	  		</td>
	  		<td>
	  			<a id="delete_contact" href="<?php echo site_url('proc/processing/contacts/delete_contact/'. $party['id']); ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
	  		</td>
	  	</tr>
	  	<!-- end list -->
	  <?php endforeach; ?>
	  </table>
	</div>
</div>