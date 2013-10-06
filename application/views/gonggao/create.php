
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
                    // myform.zx_content_phone.value=editor2.text();
                    myform.submit();
                });
        });
</script>
<div id="content">
	<?php echo validation_errors(); ?>
	<?php 
    $attributes = array('name' => 'myform');
    echo form_open('gonggao/create',$attributes); ?>
    <input type="hidden" name="sum_picture_id" value="">
    <input type="hidden" name="sum_picture_fkey" value="">
    <input type="hidden" name="sum_picture_fname" value="">
    <input type="hidden" name="con_picture_id" value="">
    <input type="hidden" name="con_picture_fkey" value="">
    <input type="hidden" name="con_picture_fname" value="">
    <input type="hidden" name="zx_content_phone" value="">
	<div class="container-fluid">
        <legend>添加新的公告</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">标题</label></th>  
                <th><input type="text" name="title" /></th>  
            </tr>  
        </thead>  
        <tbody>  
         <tr>  
            <td><label class="control-label" for="input01">公告时间</label></td>  
            <th><input type="text" name="gg_date" /></th>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">集合时间</label></td>  
            <th><input type="text" name="didian" /></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">具体坐标</label></td>  
            <th>x:<input type="text" class="input-small" name="x_site" />y:<input class="input-small"  type="text" name="y_site" /></th>  
        </tr> 
                <tr>  
            <td><label class="control-label" for="input01">联络人</label></td>  
            <th><input type="text" name="lianluo" /></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">电话</label></td>  
            <th><input type="text" name="telephone" /></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">邮箱</label></td>  
            <th><input type="text" name="mail" /></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">QQ</label></td>  
            <th><input type="text" name="qq" /></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">主持法师</label></td>  
            <td><textarea class="input-xxlarge" rows="3" name="zhuchi"></textarea></td>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">参加人员</label></td>  
            <td><textarea class="input-xxlarge" rows="3" name="renyuan"></textarea></td>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">发布时间</label></td>  
            <td><input type="input" name="zx_create"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" /></td>  
        </tr> 
        <tr>
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="创建"></td>  
        </tr>  
        </tbody>  
        </table>  	
	</div>
</div>

