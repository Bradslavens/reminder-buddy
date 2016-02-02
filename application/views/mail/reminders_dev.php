<style type="text/css">

#outer {
	font-family: Arial, Helvetica, sans-serif;
	background: #fff;
	padding: 2em;
	float: left;
	display: block;
}

#inner {
	background: #fff;
	padding: 10px;
}


</style>

<div id= "outer">
		Hi <?php echo $first_name; ?>, <br>
	<div id = "inner">
			<?php foreach ($items as $item): ?>
			<p><?php echo $item['body']; ?></p>
			<?php endforeach; ?>
	</div>
</div>


