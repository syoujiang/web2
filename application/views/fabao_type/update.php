<?php echo validation_errors(); ?>

<?php 
echo form_open('fabao/commit_type/update'); 
echo form_hidden('news_type_id',$news_type_id);
?>
<div class="container-fluid">
	<fieldset>
<legend>修改法宝类别</legend>

<div class="control-group">
<label class="control-label" for="title">类别</label>
	<div class="controls">
		<input type="text" class="input-xlarge" name="title" value="<?php echo $fabao_type ?>">
	</div>
</div>

<div class="form-actions">
	<button type="submit" class="btn btn-primary">保存更改</button>
	<button class="btn">取消</button>
</div>
</fieldset>
</form>