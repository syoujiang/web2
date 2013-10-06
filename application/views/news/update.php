<link rel="stylesheet" type="text/css" href="<?php echo site_url() ?>res/uploadify.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo site_url() ?>res/jquery.uploadify.min.js"></script>
<script type='text/javascript' >
$(document).ready(function(){
  load();
});
 
function del2(type) {   
  var myVal;
  if(type==0)
  {
    $("#pic_list1 > li>input").each(function(){
      // alert(this.value);
      myVal = this.value;
    }) 
  }
  else
  {
    $("#pic_list2 > li>input").each(function(){
      // alert(this.value);
      myVal = this.value;
    }) 
  }
  var postData = {
    "action": "update_delete",
    "file_key": myVal,
    "id":"<?php echo $id ?>",
    "dbname":"hhs_news",
    "type":type
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
                if(type==0)
                {
                  $("#pic_list1 li").remove();
                  myform.sum_picture_fkey.value="";
                }
                else
                {
                  $("#pic_list2 li").remove();
                  myform.con_picture_fkey.value="";
                }
              }
          }
      },
      success:function(resp){
      }
  });   
  $(this).remove();      
} 

function load()
{  
  var key1="<?php echo $summary_fkey ?>";
  var key2="<?php echo $content_fkey ?>";
  countChar("zx_summary","counter");
  if((key1!="") ||(key2!="")){

    var postData = {
      "action": "show",
      "file1_key": key1,
      "file2_key": key2
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
          var obj=JSON.parse(xhr.responseText);
          if(obj.preview1 != "")
          {
            $("#pic_list1").append( "<li id='li'><img class='content'  src='" + obj.preview1 + 
            "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+key1+"' name='fkey' type=\"hidden\" value='"+key1+"''></li>");      
            $("#pic_list1 li").live("click",function()
            {
              del2("0");
            });  
          }
          if(obj.preview2 != "")
          {
            $("#pic_list2").append( "<li id='li'><img class='content'  src='" + obj.preview2 + 
              "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+key2+"' name='fkey' type=\"hidden\" value='"+key2+"''></li>");      
            $("#pic_list2 li").live("click",function()
              {
                del2("1");
              }); 
          }   
        }
      }
    });  
  }
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
             del2("0");
            }
            myform.sum_picture_fkey.value=objs.hash;
            $("#pic_list1 li").remove();
            $("#pic_list1").append( "<li id='li'><img class='content'  src='" + obj.preview + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+objs.hash+"' name='fkey' type=\"hidden\" value='"+objs.hash+"''></li>");      
            $("#pic_list1 li").live("click",function()
            {
              del2("0");
            });   
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
$(function() 
{
 $('#upload_btn2').uploadify({
    'debug'   : false,

    'swf'   : '<?php echo site_url() ?>res/uploadify.swf',
    'uploader'  : 'http://up.qiniu.com/',
    'cancelImage' : '<?php echo site_url() ?>res/uploadify-cancel.png',
    'queueID'  : 'file-queue2',
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
            if($('#pic_list2 li').length >0)
            {
             del2("1");
            }
            myform.con_picture_fkey.value=objs.hash;
            $("#pic_list2 li").remove();
            $("#pic_list2").append( "<li id='li'><img class='content'  src='" + obj.preview + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+objs.hash+"' name='fkey' type=\"hidden\" value='"+objs.hash+"''></li>");      
            $("#pic_list2 li").live("click",function()
            {
              del2("1");
            });   
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
                    myform.zx_content_phone.value=editor2.text().replace("\r\n","\\r\\n");
                  //  alert(myform.zx_content_phone.value);
                    myform.submit();
                });
        });
function countChar(textareaName,spanName){
    document.getElementById(spanName).innerHTML=document.getElementById(textareaName).value.length;
    if(document.getElementById(textareaName).value.length>=100){
        window.alert("已经是最大字数了哦！")
        return false;
    }
}
</script>
<div id="content"><?php echo validation_errors(); ?>

<?php
    $attributes = array('name' => 'myform');
    echo form_open('news/commit/update',$attributes);
	echo form_hidden('news_id',$news_id); 
?>
    <input type="hidden" name="sum_picture_id" value="">
    <input type="hidden" name="sum_picture_fkey" value="<?php echo $summary_fkey ?>">
    <input type="hidden" name="sum_picture_fname" value="">
    <input type="hidden" name="con_picture_id" value="">
    <input type="hidden" name="con_picture_fkey" value="<?php echo $content_fkey ?>">
    <input type="hidden" name="con_picture_fname" value="">
    <input type="hidden" name="zx_content_phone" value="">  
<div class="container-fluid">
	<fieldset>
	<legend>修改资讯</legend>
	<table class="table table-striped">  
	<thead>  
		<tr>  
			<th><label class="control-label" for="title">标题</label></th>  
			<th><input type="text" name="title" value="<?php echo $zx_title ?>"></th>  
		</tr>  
	</thead>  
	<tbody>  
	    <tr>  
	        <td><label class="control-label" for="input01">类别</label></td>  
	        <td>        
	        <?php 
	        $options = array();
	        foreach ($news_type as $value) {
	        # code...
	        $options[$value['id']]=$value['news_type'];
	        }
	        echo form_dropdown('shirts', $options, $zx_type);
	        ?>
	        </td>  
	    </tr>  
<tr>  
            <td><label class="control-label" for="input01">摘要</label></td>  
            <td><textarea class="input-xlarge" id="zx_summary" name="zx_summary" rows="3" onkeydown='countChar("zx_summary","counter");' onkeyup="countChar('zx_summary','counter')"><?php echo $zx_summary ?></textarea><p>字数:<span id="counter">0</span>个</p></td>        
        </tr>  
        <tr>  
            <td>上传摘要图片</td>  
            <td>
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list1" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue"></div>
                <input type="file" name="file" id="upload_btn" />   
            </td>  
        </tr>
        <tr>  
            <td><label class="control-label" for="input01">内容</label></td>  
            <td><textarea style="width:700px;height:200px;visibility:hidden;" name="text"><?php echo $zx_content ?></textarea></td>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">来源</label></td>  
            <td><input type="input" name="zx_from" value="<?php echo $zx_from ?>" /></td>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">上传图片</label></td>  
            <td>        
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list2" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue2"></div>
                <input type="file" name="file" id="upload_btn2" />   
            </td>  
        </tr>
        <tr>  
            <td><label class="control-label" for="input01">发布时间</label></td>  
            <td><input type="input" name="zx_create" value="<?php echo $zx_create ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" /></td>  
        </tr> 
        <tr>  
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="保存"></td>  
        </tr>  
        </tbody>  
        </table>  	


</fieldset>
</form>
</div>