<style type="text/css">
#outer {
	background: #5ef853;
	padding: 2em;
}

#inner {
	background: 9ae594;
	padding: 10px;
}


</style>

<div id= "outer">
	<div id = "inner">
		<p>Hi <?php echo $first_name; ?>,</p>
		<p>Please take care of the following on <?php echo $transaction_name; ?>:</p>
		<ul>
			<?php foreach ($items as $item): ?>
			<li><?php echo $item['heading'] . " " . $item['body']; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>