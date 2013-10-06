<link rel="stylesheet" type="text/css" href="<?php echo site_url() ?>res/uploadify.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo site_url() ?>res/jquery.uploadify.min.js"></script>
<script type='text/javascript'>
Array.prototype.remove = function(b) { 
var a = this.indexOf(b); 
if (a >= 0) { 
this.splice(a, 1); 
return true; 
} 
return false; 
}; 
$(document).ready(function(){
  load();
});
 
function del2(type,num) {   
  var myVal;
  if(type==0)
  {
    $("#pic_list1 > li>input").each(function(){
      // alert(this.value);
      myVal = this.value;
    }) 
  }
  else if(type==1)
  {
    $("#pic_list2 > li>input").each(function(){
      // alert(this.value);
      myVal = this.value;
    }) 
  }
  else
  {
      var list="#"+num+"> input";
      $(list).each(function(){
      // alert(this.value);
      myVal = this.value;
    }) 
  }
  var postData = {
    "action": "update_delete",
    "file_key": myVal,
    "id":"<?php echo $id ?>",
    "dbname":"hhs_huodong",
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
                else if(type==1)
                {
                  $("#pic_list2 li").remove();
                  myform.con_picture_fkey.value="";
                }
                else
                {
                    picArray.remove(myVal);
                    myform.huodong_pic.value=picArray;
                    // alert(myform.huodong_pic.value);
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
  var key2="<?php echo $con_fkey ?>";
  var key1name="<?php echo $summary_fname ?>";
  var key2name="<?php  echo $con_fname ?>";
    var postData = {
      "action": "show",
      "file1_key": key1,
      "file2_key": key2,
      "id": "<?php echo $id ?>"
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
          // alert(xhr.responseText);
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
                        myform.sum_picture_fkey.value=key1;
            myform.sum_picture_fname.value=key1name;  
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
            myform.con_picture_fkey.value=key2;
            myform.con_picture_fname.value=key2name;
          }
        }
      }
    });  
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

    'fileTypeExts' : '<?php echo $upload_format2 ?>',
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
            myform.sum_picture_fname.value=objs.name;
            $("#pic_list1 li").remove();
            $("#pic_list1").append( "<li id='li'><img class='content'  src='" + obj.preview + 
            "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
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

    'fileTypeExts' : '<?php echo $upload_format2 ?>',
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
            myform.con_picture_fname.value=objs.name;
            $("#pic_list2 li").remove();
            $("#pic_list2").append( "<li id='li'><img class='content'  src='" + obj.preview + 
            "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
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
                    myform.zx_content_phone.value=editor2.text();
                    myform.submit();
                });
        });
</script>
<div id="content">
	<?php echo validation_errors(); ?>
	<?php 
    $attributes = array('name' => 'myform');
    echo form_open('wuzhong/commit/update',$attributes); 
    echo form_hidden('news_id',$news_id); ?>
    <input type="hidden" name="sum_picture_id" value="<?php echo $summary_url ?>">
    <input type="hidden" name="sum_picture_fkey" value="<?php echo $summary_fkey ?>">
    <input type="hidden" name="sum_picture_fname" value="<?php echo $summary_fname ?>">
    <input type="hidden" name="con_picture_id" value="<?php echo $con_url ?>">
    <input type="hidden" name="con_picture_fkey" value="<?php echo $con_fkey ?>">
    <input type="hidden" name="con_picture_fname" value="<?php echo $con_fname ?>">
    <input type="hidden" name="zx_content_phone" value="">
	<div class="container-fluid">
        <legend>编辑放生物种</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">物种名称</label></th>  
                <th><input type="input" name="title" value="<?php echo $name ?>"/></th>  
            </tr>  
        </thead>  
        <tbody>  
         <tr>  
            <td><label class="control-label" for="input01">拉丁学名</label></td>  
            <th><input type="input" name="xueming" value="<?php echo $xueming ?>"/></th>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">分布区域</label></td>  
            <th><input type="input" name="quyu" value="<?php echo $quyu ?>"/></th>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">物种概述</label></td>  
            <td><textarea name="gaishu"><?php echo $gaishu ?></textarea></td>  
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
            <td><label class="control-label" for="input01">外形特征</label></td>  
            <td><textarea name="tezheng"><?php echo $tezheng ?></textarea></td>   
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">生活习性</label></td>  
            <td><textarea style="width:700px;height:200px;visibility:hidden;" name="text"><?php echo $xixing ?></textarea></td>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">适宜放生地点</label></td>  
            <td><textarea name="fangshengdidian"><?php echo $fangshengdidian ?></textarea></td>   
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">买生价格</label></td>  
            <td><textarea name="jiage"><?php echo $jiage ?></textarea></td>   
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">上传内容图片</label></td>  
            <td>        
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list2" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue2"></div>
                <input type="file" name="file" id="upload_btn2" />   
            </td>  
        </tr>
        <tr>  
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="更新"></td>  
        </tr>  
        </tbody>  
        </table>  	
	</div>
</div>



