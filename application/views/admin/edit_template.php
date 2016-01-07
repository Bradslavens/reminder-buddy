<div id="add_item_form">
	<div class="row">
		<div class="col col-md-2">
		</div>
		<div class="col col-md-8">
	<!-- Enter form to add transaction name  -->
		<?php echo validation_errors(); ?>

		<?php $form_data = array(
			'class' => 'form-inline'); 
		echo form_open(site_url('/admin/index/'.strtolower($title).'/add_template_item/' . $template[0]['id']), $form_data); ?>
			  <div class="form-group">
			    <label for="exampleInputName2">Name</label>
			    <input name="name" type="text" class="form-control" id="exampleInputName2" placeholder="Name" value="<?php echo $template[0]['name']; ?>">
			  </div>
			  <div class="form-group">
			  <!-- LIST OF AVAILABLE FORMS -->
			  	<select name= "item">
				  	<?php foreach ($items as $item):; ?> 
			  			<option title="<?php echo $item['body']; ?> type: <?php echo $item['name']; ?>" value= "<?php echo $item['id']; ?>">
				  		    <strong><?php echo $item['heading']; ?></strong>
			  		    </option>
				  	<?php endforeach; ?>
			  	</select>
			  </div>
			  <p>
			  <button type="submit" class="btn btn-default"><span title ="Add selected item to template" class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
			  </p>
			</form>
		</div>
		<div class="col col-md-2">
		</div>
	</div>
</div>

<?php foreach ($current_items as $ci): ?>
<tr>
	<td><?php echo $ci[0]['id']; ?></td>
	<td><?php echo $ci[0]['heading']; ?></td>
	<td><?php echo $ci[0]['body']; ?></td>
	<td><span title="Delete this Item" class="glyphicon glyphicon-trash" aria-hidden="true"></span></td>
</tr>
<?php endforeach; ?>