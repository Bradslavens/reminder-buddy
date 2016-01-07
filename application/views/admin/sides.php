			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			Side
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
			  	<?php foreach ($sides as $side): ?> 
			  	<tr>
					<!-- Enter form to add transaction name  -->
					<?php echo validation_errors(); ?>
					<?php $action = site_url('admin/index/'.strtolower($title).'/update_item'); ?>
					<?php echo form_open($action); ?>
				  		<td class="id"><?php echo $side['id']; ?></td>
				  		<td><input style="display:none;" name="side" type="text" class="show_form" value="<?php echo $side['side']; ?>"><span class="show_form"><?php echo $side['side']; ?></span></td>
				  		<td><a class="edit" href="#">Edit</a></td>
				  		<td><a class="del" href="#">Del</a></td>
				  		<td>
			  				<input type="hidden" name= "id" value = "<?php echo $side['id']; ?>"/>

			  				<input type= "submit" class = "show_form" style = "display: none;" value="Update">
				  		</td>
			  		</form>
			  	</tr>
			  	<?php endforeach; ?>