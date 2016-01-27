<div id="transaction-list" class="container">
	<div class="row">
		<div class="col col-md-2">
		</div>
		<div class="col col-md-8">
			<p>
				<h2>Transaction List</h2>
			</p>
		</div>
		<div class="col-md-2">
		</div>
	</div>

	<div class="row">
			<div class="col-md-2">
			</div>
				<div class="col-md-8">
					<div class="row">
								<a href="<?php echo site_url('/proc/processing/cover'); ?>">
							<div class="col-md-4 new_transaction_box">
									<span class="new_transaction_text">+ New Transaction</span>
							</div>
								</a>
						<?php foreach($transactions as $transaction):;?>
						<div>
							<?php $href = site_url("/proc/processing/cover/". $transaction['id']); ?>
								<a href="<?php echo $href; ?> ">
							<div class="col-md-4 transaction_box">
									<img src="<?php echo $transaction['photo_link']; ?>" alt="Property Photo">
									<p id = "transaction_caption" class="pull-right">
										<?php echo $transaction['id']; ?>
										<?php echo $transaction['name']; ?>
									</p>
							</div>
								</a>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			<div class="col-md-2">
			</div>
	</div>

</div>

<!-- load jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> 

<script type="text/javascript">
	$(document).ready(function(){
		$('.transaction_box a').click(function(){
			$(this).parent().siblings('.edit_link').toggle(300);
		});
	});
</script>


