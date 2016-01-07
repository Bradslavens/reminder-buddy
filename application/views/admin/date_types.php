			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			Date Type
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
			  	<?php foreach ($date_types as $date_type): ?> 
			  	<tr>
					<!-- Enter form to add transaction name  -->
					<?php $action = site_url('admin/index/'.strtolower($title).'/update_item'); ?>
					<?php echo form_open($action); ?>
				  		<td class="id"><?php echo $date_type['id']; ?></td>
				  		<td><input style="display:none;" name="date" type="text" class="show_form" value="<?php echo $date_type['date']; ?>"><span class="show_form"><?php echo $date_type['date']; ?></span></td>
				  		<td><a class="edit" href="#">Edit</a></td>
				  		<td><a class="del" href="#">Del</a></td>
				  		<td>
			  				<input type="hidden" name= "id" value = "<?php echo $date_type['id']; ?>"/>

			  				<input type= "submit" class = "show_form" style = "display: none;" value="Update">
				  		</td>
			  		</form>
			  	</tr>
			  	<?php endforeach; ?>