
<div id="outer" style="font-family: Arial, Helvetica, sans-serif;background: #fff;padding: 2em;float: left;display: block;">
		Hi <?php echo $first_name; ?>, <br>
	<div id="inner" style="background: #fff;padding: 10px;">
			<?php foreach ($items as $item): ?>
			<p><?php echo $item['body']; ?></p>
			<?php endforeach; ?>
	</div>
</div>