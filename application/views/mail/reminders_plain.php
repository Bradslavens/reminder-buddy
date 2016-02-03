
Hi <?php echo $first_name; ?>, <br>
	<?php foreach ($items as $item): ?>
	<p><?php echo $item['body']; ?></p>
	<?php endforeach; ?>