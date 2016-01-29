			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			Subject
			  		</th>
			  		<th>
			  			Body
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
			  	<?php foreach ($reminders as $reminder): ?> 
			  	<tr>
					<!-- Enter form to add transaction name  -->
					<?php echo validation_errors(); ?>
					<?php $action = site_url('admin/index/'.strtolower($title).'/update_item'); ?>
					<?php echo form_open($action); ?>
				  		<td class="id"><?php echo $reminder['id']; ?></td>
				  		<td><input style="display:none;" name="heading" type="text" class="show_form" value="<?php echo $reminder['heading']; ?>"><span class="show_form"><?php echo $reminder['heading']; ?></span></td>
				  		<td><input style="display:none;" name="body" type="text" class="show_form" value="<?php echo $reminder['body']; ?>"><span class="show_form"><?php echo $reminder['body']; ?></span></td>
				  		<td><a  href="../edit_item/<?php echo $reminder['id']; ?>">Edit</a></td>
				  		<td><a class="del" href="#">Del</a></td>
				  		<td>
			  				<input type="hidden" name= "id" value = "<?php echo $reminder['id']; ?>"/>

			  				<input type= "submit" class = "show_form" style = "display: none;" value="Update">
				  		</td>
				  	</form>
			  	</tr>
			  	<?php endforeach; ?>
			  	