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
		<p>Good Morning!</p>
		<p>I show we need the following forms.</p>
		<p>Note: If you sent a form within the last 24 hours it may not show up here.. please disregard. Thanks :)</p>
		<p><?php echo $transaction['name'];?></p>
		<ul>
			<?php foreach ($sides as $side): ?>
			<li><?php echo $side['side'];  ?>
				<?php if (empty($side['item'])){ echo "Nothing Needed at this time. Thanks:)"; }
				else{ ?>
				<ul>
					<?php  foreach ($side['item'] as $item): ?> 
					<li><?php echo $item['heading']; ?> <br />
						We are missing 1 or more signatures for the following people on this form:<br />
							<?php foreach ($item['parties'] as $p): ?>
								<?php if($p['complete'] == 0){ echo $p['first_name'] . " " . $p['last_name'] . ", " ;} ?>
							<?php endforeach; ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php } ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>