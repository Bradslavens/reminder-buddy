			  	<tr>
			  		<th>
			  			ID
			  		</th>
			  		<th>
			  			First Name
			  		</th>
			  		<th>
			  			Last Name
			  		</th>
			  		<th>
			  			Edit
			  		</th>
			  	</tr>
			  	<?php foreach ($contacts as $contact): ?> 
			  	<tr>
					<!-- Enter form to add transaction name  -->
			  		<td class="id"><?php echo $contact['id']; ?></td>
			  		<td><?php echo $contact['first_name']; ?></td>
			  		<td><?php echo $contact['last_name']; ?></td>
			  		<td><a  href="<?php echo site_url('admin/index/contacts/edit/' . $contact['id'] ); ?>">Edit</a></td>
			  	</tr>
			  	<?php endforeach; ?>