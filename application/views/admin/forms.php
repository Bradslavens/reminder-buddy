			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			Abbreviation
			  		</th>
			  		<th>
			  			Description
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
			  	<?php foreach ($forms as $form): ?> 
			  	<tr>
					<!-- Enter form to add transaction name  -->
					<?php echo validation_errors(); ?>
					<?php $action = site_url('admin/index/'.strtolower($title).'/update_item'); ?>
					<?php echo form_open($action); ?>
				  		<td class="id"><?php echo $form['id']; ?></td>
				  		<td><input style="display:none;" name="heading" type="text" class="show_form" value="<?php echo $form['heading']; ?>"><span class="show_form"><?php echo $form['heading']; ?></span></td>
				  		<td><input style="display:none;" name="body" type="text" class="show_form" value="<?php echo $form['body']; ?>"><span class="show_form"><?php echo $form['body']; ?></span></td>
				  		<td><a class="edit" href="#">Edit</a></td>
				  		<td><a class="del" href="#">Del</a></td>
				  		<td>
			  				<input type="hidden" name= "id" value = "<?php echo $form['id']; ?>"/>

			  				<input type= "submit" class = "show_form" style = "display: none;" value="Update">
				  		</td>
				  	</form>
			  	</tr>
			  	<?php endforeach; ?>

