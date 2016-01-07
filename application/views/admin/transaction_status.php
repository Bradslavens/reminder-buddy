			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			Transaction Status
			  		</th>
			  		<th>
			  			Edit
			  		</th>
			  		<th>
			  			Del
			  		</th>
			  		<th>
			  		</th>
			  	</tr>
			  	<?php foreach ($transaction_status as $ts): ?> 
			  	<tr>
					<!-- Enter form to add transaction name  -->
					<?php echo validation_errors(); ?>
					<?php $action = site_url('admin/index/'.strtolower($title).'/update_item'); ?>
					<?php echo form_open($action); ?>
				  		<td class="id"><?php echo $ts['id']; ?></td>
				  		<td><input style="display:none;" name="status" type="text" class="show_form" value="<?php echo $ts['status']; ?>"><span class="show_form"><?php echo $ts['status']; ?></span></td>
				  		<td><a class="edit" href="#">Edit</a></td>
				  		<td><a class="del" href="#">Del</a></td>
				  		<td>
			  				<input type="hidden" name= "id" value = "<?php echo $ts['id']; ?>"/>

			  				<input type= "submit" class = "show_form" style = "display: none;" value="Update">
				  		</td>
			  		</form>
			  	</tr>
			  	<?php endforeach; ?>			  