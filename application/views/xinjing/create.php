<link rel="stylesheet" type="text/css" href="<?php echo site_url() ?>res/uploadify.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo site_url() ?>res/jquery.uploadify.min.js"></script>
<script type='text/javascript' >
var del1 = function () {   
  var list=$("ul input");
  var myVal
  list.each(function(){
    myVal = this.value;
  }) 
  var postData = {
    "action": "delete",
    "file_key": myVal
  };
  // 通过AJAX异步向网站业务服务器POST数据
  $.ajax({
      type: "POST",
      url: '<?php echo $callback_path ?>',
      processData: true,
      data: postData,
      dataType: "json",
      beforeSend: function(){},
      complete: function(xhr, textStatus){
          if(xhr.readyState ==4)
          {
              if(xhr.status ==200)
              {
                  myform.con_picture_id.value="";
                  myform.con_picture_fkey.value="";
                  myform.con_picture_fname.value="";
                  $("#divMsg1").show();
                  $("#divMsg1").html("删除成功！");
              }
          }
      },
      success:function(resp){
      }
  });   
  $(this).remove();      
} 

$(function() 
{
 $('#upload_btn').uploadify({
    'debug'   : false,

    'swf'   : '<?php echo site_url() ?>res/uploadify.swf',
    'uploader'  : 'http://up.qiniu.com/',
    'cancelImage' : '<?php echo site_url() ?>res/uploadify-cancel.png',
    'queueID'  : 'file-queue',
    'buttonClass'  : 'button',
    'buttonText' : "Upload Files",
    'multi'   : false,
    'auto'   : true,

    'fileTypeExts' : '*.jpg; *.png; *.gif; *.PNG; *.JPG; *.GIF;',
    'fileTypeDesc' : 'Image Files',

    'method'  : 'post',
    'fileObjName' : 'file',
    'formData'  : {'token' : '<?php echo $upToken;?>'},

    'queueSizeLimit': 40,
    'simUploadLimit': 1,
    'sizeLimit'  : 10240000,
    'onUploadSuccess' : function(file, data, response) {   
      var objs=JSON.parse(data);
      var postData = {
        "action": "insert",
        "file_key": objs.hash
      };
      // 通过AJAX异步向网站业务服务器POST数据
      $.ajax({
        type: "POST",
        url: '<?php echo $callback_path ?>',
        processData: true,
        data: postData,
        dataType: "json",
        beforeSend: function(){},
        complete: function(xhr, textStatus){
          if((xhr.readyState ==4) && (xhr.status ==200))
          {
            console.log(xhr.responseText);
            var obj=JSON.parse(xhr.responseText);
            if($('#pic_list1 li').length >0)
            {
             del1();
            }
            myform.sum_picture_fkey.value=objs.hash;
            myform.sum_picture_fname。value=objs.name;
            $("#pic_list1 li").remove();
            $("#pic_list1").prepend( "<li id='li'><a href='" + obj.preview + "'>"+objs.name+"</a><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+objs.hash+"' name='fkey' type=\"hidden\" value='"+objs.hash+"''></li>");      
            $("#pic_list1 li").bind("click",del1);    
          }
        },
        success:function(resp){
        }
      });   
    }, 
    'onComplete': function(event,queueID,fileObj,response,data) { 
      alert("sdfasdfas");
    },
    'onError'          : function(event, queueID, fileObj)  
    {   
      alert("文件:" + fileObj.name + " 上传失败");   
    }
  });
 });

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
                    myform.zx_content_phone.value=editor2.text();
                    myform.submit();
                });
        });
</script>
<div id="content">
	<?php echo validation_errors(); ?>
	<?php 
    $attributes = array('name' => 'myform');
    echo form_open('xinjing/create',$attributes); ?>
    <input type="hidden" name="sum_picture_id" value="">
    <input type="hidden" name="sum_picture_fkey" value="">
    <input type="hidden" name="sum_picture_fname" value="">
    <input type="hidden" name="zx_content_phone" value="">
	<div class="container-fluid">
        <legend>添加心经</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">名称</label></th>  
                <th><input type="input" name="title" /></th>  
            </tr>  
        </thead>  
        <tbody>  
            <tr>  
                <td><label class="control-label" for="input01">类别</label></td>  
                <td>        
                <?php 
                         $options = array(
                                  '朝课'  => '朝课',
                                  '暮课'    => '暮课',
                                  '佛七'   => '佛七'
                                );

                    echo form_dropdown('shirts', $options, '朝课');
                ?>
                </td>  
            </tr>

        <tr>  
            <td>上传心经文件</td>  
            <td>
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list1" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue"></div>
                <input type="file" name="file" id="upload_btn" />    
            </td>  
        </tr>
        <tr>  
            <td><label class="control-label" for="input01">文件说明</label></td>  
            <td><textarea style="width:700px;height:200px;visibility:hidden;" name="text"></textarea></td>  
        </tr> 
        <tr>  
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="创建"></td>  
        </tr>  
        </tbody>  
        </table>  	
	</div>
</div>

