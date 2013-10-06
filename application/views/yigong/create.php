
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
    <script type="text/javascript">
        var swfu,swfu2;
        window.onload = function() {
            var settings = {
                flash_url : "<?php echo base_url('/bootstrap/assets/swfupload/swfupload.swf');?>",
                upload_url: "<?php echo $upload_url; ?>",
                post_params: {},
                use_query_string: false,
                file_post_name: "file",
                file_size_limit : "10 MB",
                file_types : "*.png;*.jpg;*.jpeg;*.gif",
                file_types_description: "Web Image Files",
                file_upload_limit : 100,
                file_queue_limit : 0,
                custom_settings : {
                    fileUniqIdMapping : {},
                    progressTarget : "fsUploadProgress",
                    cancelButtonId : "btnCancel"
                },
                debug: false,

                // Button Settings
                button_image_url : "<?php echo base_url('bootstrap/assets/images/XPButtonUploadText_61x22.png'); ?>",
                button_placeholder_id : "spanButtonPlaceholder1",
                button_width: 61,
                button_height: 22,

                // The event handler functions are defined in handlers.js
                file_queued_handler : fileQueued,
                file_queue_error_handler : fileQueueError,
                file_dialog_complete_handler : fileDialogComplete,
                upload_start_handler : uploadStart,
                upload_progress_handler : uploadProgress,
                upload_error_handler : uploadError,
                upload_success_handler : uploadSuccess,
                upload_complete_handler : uploadComplete,
                queue_complete_handler : queueComplete  // Queue plugin event
        };
            var settings2 = {
            flash_url : "<?php echo base_url('bootstrap/assets/swfupload/swfupload.swf'); ?>",
            upload_url: "<?php echo $upload_url; ?>",
            post_params: {},
            use_query_string: false,
            file_post_name: "file",
            file_size_limit : "10 MB",
            file_types : "*.png;*.jpg;*.jpeg;*.gif",
            file_types_description: "Web Image Files",
            file_upload_limit : 100,
            file_queue_limit : 0,
            custom_settings : {
                fileUniqIdMapping : {},
                progressTarget : "fsUploadProgress2",
                cancelButtonId : "btnCancel2"
            },
            debug: false,

            // Button Settings
            button_image_url : "<?php echo base_url('bootstrap/assets/images/XPButtonUploadText_61x22.png'); ?>",
            button_placeholder_id : "spanButtonPlaceholder2",
            button_width: 61,
            button_height: 22,

            // The event handler functions are defined in handlers.js
            file_queued_handler : fileQueued,
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess2,
            upload_complete_handler : uploadComplete,
            queue_complete_handler : queueComplete  // Queue plugin event
        };
        swfu = new SWFUpload(settings);
        swfu = new SWFUpload(settings2);
        };
    </script>
    <script type="text/javascript">
    var $bucket = '<?php echo $bucket; ?>';
    var $upToken = '<?php echo $upToken;?>';
</script>
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
                <th><input type="input" name="title" /></th>  
            </tr>  
        </thead>  
        <tbody>  
         <tr>  
            <td><label class="control-label" for="input01">公告时间</label></td>  
            <th><input type="input" name="gg_date" /></th>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">集合时间</label></td>  
            <th><input type="input" name="didian" /></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">具体坐标</label></td>  
            <th>x:<input type="input" name="x_site" />y:<input type="input" name="y_site" /></th>  
        </tr> 
                <tr>  
            <td><label class="control-label" for="input01">联络人</label></td>  
            <th><input type="input" name="lianluo" /></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">电话</label></td>  
            <th><input type="input" name="telephone" /></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">邮箱</label></td>  
            <th><input type="input" name="mail" /></th>  
        </tr> 
                     <tr>  
            <td><label class="control-label" for="input01">QQ</label></td>  
            <th><input type="input" name="qq" /></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">主持法师</label></td>  
            <td><textarea name="zhuchi"></textarea></td>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">参加人员</label></td>  
            <td><textarea name="renyuan"></textarea></td>  
        </tr> 
        <tr>
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="创建"></td>  
        </tr>  
        </tbody>  
        </table>  	
	</div>
</div>

