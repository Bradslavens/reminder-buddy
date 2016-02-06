<div id="audit_home" class="container">
	<div class="row">
		<div class="col-md-12">
			<p>Select the transaction you would like to Audit</p>
				<?php echo validation_errors(); ?>
				<?php echo form_open('audit/home/start_audit'); ?>
				<select name="transaction" class="form-control">
					<?php foreach ($t as $ts): ?>
				  		<option value="<?php echo $ts['id']; ?>"><?php echo $ts['name']; ?></option>
					<?php endforeach; ?>
				</select>
			  <button type="submit" class="btn btn-default">Submit</button>
			</form>
			<a href="/audit/audit_report"><button>Run Comparison Report</button></a>
			
		</div>
	</div>
</div>

