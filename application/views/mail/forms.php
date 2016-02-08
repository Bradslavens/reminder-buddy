
<div id= "outer">
	<div id = "inner">
		Good Morning <?php echo ucfirst($contact['first_name']); ?>! <br><br>
		I show we need the following forms: <br><br>
		Note: If you sent a form within the last 24 hours (business day) it may not show up here.. please disregard. Thanks :) <br>
		<br>
		Transaction: <strong><?php echo $transaction['name'];?></strong> <br><br>
		
			<?php foreach ($form_list as $form): ?> 
				&emsp;<strong><?php echo $form['heading']; ?> -- </strong> 
						<em>
						<?php foreach ($form['parties'] as $party): ?>
						 <?php echo $party['first_name' ] . " " . $party['last_name']; ?>,  	
						<?php endforeach; ?>
						&nbsp;need(s) to sign.</em>
						<br>
			<br>
				
			<?php endforeach; ?>
		
	</div>
</div>