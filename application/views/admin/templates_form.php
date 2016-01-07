<div id="add_item_form" style = "display: none;">
	<div class="row">
		<div class="col col-md-2">
		</div>
		<div class="col col-md-8">
	<!-- Enter form to add transaction name  -->
		<?php echo validation_errors(); ?>

		<?php $form_data = array(
			'class' => 'form-inline'); 
		echo form_open(site_url('/admin/index/'.strtolower($title).'/add_item'), $form_data); ?>
			  <div class="form-group">
			    <label for="exampleInputName2">Name</label>
			    <input name="name" type="text" class="form-control" id="exampleInputName2" placeholder="Name">
			  </div>
			  <!-- LIST OF AVAILABLE FORMS -->
				  	<?php foreach ($items as $item):; ?> 
				  		<ul class="list-group">
				  			<li class="list-group-item">
					  			<input type= "checkbox" value= "<?php echo $item['id']; ?>" name="item[]">
					  		    <strong><?php echo $item['heading']; ?></strong>
					  		    <p><?php echo $item['body']; ?> type: <?php echo $item['name']; ?> </p>
					  		    
				  			</li>
				  		</ul>
				  	<?php endforeach; ?>
			  <button type="submit" class="btn btn-default">Add</button>
			</form>
		</div>
		<div class="col col-md-2">
		</div>
	</div>
</div>