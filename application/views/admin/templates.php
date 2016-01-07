			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			Templates
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
			  	<?php foreach ($templates as $template): ?> 
			  	<tr>
				  		<td class="id"><?php echo $template['id']; ?></td>
				  		<td><?php echo $template['name']; ?></td>
				  		<td><a href="<?php echo site_url('/admin/index/edit_template/'.$template['id']); ?>"><span title="Edit Template and Item List" class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
				  		<td><a class="del" href="#"><span title="Delete Template" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			  	</tr>
			  	<?php endforeach; ?>			  