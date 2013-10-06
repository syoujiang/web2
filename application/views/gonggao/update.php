
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
    echo form_open('gonggao/commit/update',$attributes);
    echo form_hidden('news_id',$id);  ?>
   <!--  <input type="hidden" name="sum_picture_id" value="<?php echo $summary_url ?>">
    <input type="hidden" name="sum_picture_fkey" value="<?php echo $summary_fkey ?>">
    <input type="hidden" name="sum_picture_fname" value="<?php echo $summary_fname ?>">
    <input type="hidden" name="con_picture_id" value="<?php echo $con_url ?>">
    <input type="hidden" name="con_picture_fkey" value="<?php echo $con_fkey ?>">
    <input type="hidden" name="con_picture_fname" value="<?php echo $con_fname ?>">
    <input type="hidden" name="zx_content_phone" value="<?php echo $content_phone ?>"> -->
    <div class="container-fluid">
        <legend>修改公告</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">标题</label></th>  
                <th><input type="text" name="title" value="<?php echo $title ?>"/></th>  
            </tr>  
        </thead>  
        <tbody>  
         <tr>  
            <td><label class="control-label" for="input01">公告时间</label></td>  
            <th><input type="text" name="gg_date" value="<?php echo $gg_date ?>"/></th>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">集合时间</label></td>  
            <th><input type="text" name="didian" value="<?php echo $didian ?>"/></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">具体坐标</label></td>  
            <th>x:<input type="text" class="input-small" name="x_site" value="<?php echo $x_site ?>"/>y:<input type="text" class="input-small" name="y_site" value="<?php echo $y_site ?>"/></th>  
        </tr> 
                <tr>  
            <td><label class="control-label" for="input01">联络人</label></td>  
            <th><input type="text" name="lianluo" value="<?php echo $lianluo ?>"/></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">电话</label></td>  
            <th><input type="text" name="telephone" value="<?php echo $telephone ?>"/></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">邮箱</label></td>  
            <th><input type="text" name="mail" value="<?php echo $mail ?>"/></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">QQ</label></td>  
            <th><input type="text" name="qq" value="<?php echo $qq ?>"/></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">主持法师</label></td>  
            <td><textarea class="input-xxlarge" rows="3" name="zhuchi"><?php echo $zhuchi ?></textarea></td>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">参加人员</label></td>  
            <td><textarea class="input-xxlarge" rows="3" name="renyuan"><?php echo $renyuan ?></textarea></td>  
        </tr> 
                <tr>  
            <td><label class="control-label" for="input01">发布时间</label></td>  
            <td><input type="input" name="zx_create" value="<?php echo $gg_push ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" /></td>  
        </tr> 
        <tr>  
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="更新"></td>  
        </tr>  
        </tbody>  
        </table>    
    </div>
</div>

