<div id="audit_home" class="container">
	<div class="row">
		<a href="/audit/home"><button>home</button> </a>
		<div class="col-md-12">
			<p>Here is the list of discrepencies: NOTE: please check the forms and correct the original list.</p>
			<p>Missing forms:</p>
			<?php foreach ($audit_list['forms'] as $form): ?>
				<?php echo $form['heading'] . " " . $form['body']; ?> <br>
			<?php endforeach; ?>

			<p>Signature Discrepencies:</p>
			<?php foreach ($audit_list['tips'] as $tip): ?>
				<?php echo $tip[0]['heading'] . " " . $tip[0]['body'] . " party: " .$tip[0]['first_name']. " " . $tip[0]['last_name']; ?> <br>
			<?php endforeach; ?>


		</div>
	</div>
</div>

