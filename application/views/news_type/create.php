<div id="content">
	<?php echo validation_errors(); ?>
	<?php echo form_open('news_type/create'); ?>
	<div class="container-fluid">
		<legend>添加新的资讯类别</legend>

		<label class="control-label" for="input01">类别</label> 
				 <?php echo form_input('title'); ?>
				 <br>

		<button type="submit" class="btn">Submit</button>
	</div>
</div>

