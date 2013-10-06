
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/utf8_encode.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/utf8_decode.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/base64_encode.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/base64_decode.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/uniqid.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/helper.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/swfupload/swfupload.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/swfupload.queue.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/fileprogress.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/handlers.js'); ?>"></script>
<script>
        var editor,editor2;
        KindEditor.ready(function(K) {
            editor2 = K.create('textarea[name="text"]', {
                resizeType : 1,
                allowPreviewEmoticons : false,
                allowImageUpload : false,
                items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
                afterCreate : function() { 
                    this.sync(); 
                }, 
                afterBlur:function(){ 
                    this.sync(); 
                }
            });
            K('input[name=getText]').click(function(e) {
                    myform.zx_content_phone.value=editor2.text();
                    myform.submit();
                });
        });
</script>
<div id="content">
	<?php echo validation_errors(); ?>
	<?php 
        $attributes = array('name' => 'myform');
        echo form_open('fabao/yunfei',$attributes); 
    ?>
    <input type="hidden" name="mymethod" value="update">
    <input type="hidden" name="zx_content_phone" value="">
	<div class="container-fluid">
        <legend>法宝运费管理</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">当前运费说明</label></th>  
                <th><?php echo $info ?></th>  
            </tr>  
        </thead>  
        <tbody>  
        <tr>  
            <td><label class="control-label" for="input01">运费说明</label></td>  
            <td><textarea style="width:700px;height:200px;visibility:hidden;" name="text"><?php echo $info ?></textarea></td>  
        </tr> 
        <tr>  
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="更新"></td>  
        </tr>  
        </tbody>  
        </table>  	
	</div>
</div>

