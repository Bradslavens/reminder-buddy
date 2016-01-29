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
				  		<td><?php echo $form['heading']; ?></td>
				  		<td><?php echo $form['body']; ?></td>
				  		<td><a  href="../edit_item/<?php echo $form['id']; ?>">Edit</a></td>
				  		<td><a class="del" href="#">Del</a></td>
				  		<td>
				  		</td>
				  	</form>
			  	</tr>
			  	<?php endforeach; ?>

