<div id="audit_home" class="container">
	<div class="row">
		<div class="col-md-12">
			<p>Select the transaction you would like to Audit</p>
				<?php echo validation_errors(); ?>
				<?php echo form_open('audit/home'); ?>
				<select class="form-control">
				  <option>Transaction #1</option>
				  <option>Transaction #2</option>
				  <option>Transaction #3</option>
				  <option>Transaction #4</option>
				  <option>Transaction #5</option>
				</select>
			  <button type="submit" class="btn btn-default">Submit</button>
			</form>

			<button>Run Comparison Report</button>
		</div>
	</div>
</div>

