<link rel="stylesheet" type="text/css" href="<?php echo site_url() ?>res/uploadify.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo site_url() ?>res/jquery.uploadify.min.js"></script>
<script type='text/javascript' >
Array.prototype.remove = function(b) { 
var a = this.indexOf(b); 
if (a >= 0) { 
this.splice(a, 1); 
return true; 
} 
return false; 
}; 
var del1 = function (type,num) {   
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
                    alert(myform.huodong_pic.value);
                }
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

    'fileTypeExts' : '<?php echo $upload_format ?>',
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
             del1("0",0);
            }
            myform.sum_picture_fkey.value=objs.hash;
            myform.sum_picture_fname.value=objs.name;
            $("#pic_list1 li").remove();
            $("#pic_list1").prepend( "<li id='li'><a href='" + obj.preview + "'>"+objs.name+"</a><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+objs.hash+"' name='fkey' type=\"hidden\" value='"+objs.hash+"''></li>");      
            $("#pic_list1 li"+m).live("click",function()
            {
              del1("0",0);
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

    'fileTypeExts' : '<?php echo $upload_format ?>',
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
             del1("1",0);
            }
            myform.con_picture_fkey.value=objs.hash;
             myform.con_picture_fname.value=objs.name;
            $("#pic_list2 li").remove();
            $("#pic_list2").prepend( "<li id='li'><a href='" + obj.preview + "'>"+objs.name+"</a><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+objs.hash+"' name='fkey' type=\"hidden\" value='"+objs.hash+"''></li>");      
            $("#pic_list2 li img").live("click",function()
            {
              del1("1",0);
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
m=0;
var picArray=new Array();
$(function() 
{
 $('#upload_btn3').uploadify({
    'debug'   : false,

    'swf'   : '<?php echo site_url() ?>res/uploadify.swf',
    'uploader'  : 'http://up.qiniu.com/',
    'cancelImage' : '<?php echo site_url() ?>res/uploadify-cancel.png',
    'queueID'  : 'file-queue3',
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
            if($('#pic_list3 li').length >9)
            {
             alert("上传最大数为10.")
              return;
            }
            picArray.push(objs.hash);
            myform.huodong_pic.value=picArray;
            // alert(myform.huodong_pic.value);
            // $("#pic_list3 li").remove();
            $("#pic_list3").append( "<li id='li"+m+"'><img class='content'  src='" + obj.preview + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
            "<input id='"+objs.hash+"' name='fkey' type=\"hidden\" value='"+objs.hash+"''></li>");      
            $("#li"+m).live("click",function(e)
            // $('#pic_list3 li').live('click',function()
            {
              // alert("ddd");
              //alert($(this).closest('li').attr("id"));
              del1("3",$(this).closest('li').attr("id"));
              $(this).closest('li').remove();
            });    
            m++; 
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
            editor = K.create('textarea[name="text"]', {
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
            editor2 = K.create('textarea[name="text2"]', {
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
            var groupTypeId=""; 
            K('input[name=getText]').click(function(e) {
                    myform.mingxi_phone.value=editor.text();
                    myform.gongde_phone.value=editor2.text();
            //   //      alert(myform.gongde_phone.value);
            //         $('#pic_list3 li input').each(function(index,val){
            //             groupTypeId += this.value+"|";  
            //         })
            //         myform.huodong_pic.value =(groupTypeId); 
              //     alert(myform.huodong_pic.value);
                    myform.submit();
                });
        });
</script>
<div id="content">
	<?php echo validation_errors(); ?>
	<?php 
    $attributes = array('name' => 'myform');
    echo form_open('huodong/create',$attributes); ?>
    <input type="hidden" name="sum_picture_id" value="">
    <input type="hidden" name="sum_picture_fkey" value="">
    <input type="hidden" name="sum_picture_fname" value="">
    <input type="hidden" name="con_picture_id" value="">
    <input type="hidden" name="con_picture_fkey" value="">
    <input type="hidden" name="con_picture_fname" value="">
    <input type="hidden" name="mingxi_phone" value="">
    <input type="hidden" name="gongde_phone" value="">
    <input type="hidden" name="huodong_pic" value="">
	<div class="container-fluid">
        <legend>添加活动详情</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">选择活动</label></th>  
                <th>
                <?php 
                $options = array();
                foreach ($gonggao as $value) {
                # code...
                    $options[$value['id']]=$value['title'];
                }
                echo form_dropdown('shirts', $options, 'large');
                ?>
            </th>  
            </tr>  
        </thead>  
        <tbody>  
         <tr>  
            <td><label class="control-label" for="input01">投入基金</label></td>  
            <th><input type="input" name="jijin" /></th>  
        </tr>  
              <tr>  
            <td><label class="control-label" for="input01">放生明细</label></td>  
            <td><textarea style="width:700px;height:200px;visibility:hidden;" name="text"></textarea></td>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">上传放生明细</label></td>  
            <td>        
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list1" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue"></div>
                <input type="file" name="file" id="upload_btn" /> 
            </td>  
        </tr>
                      <tr>  
            <td><label class="control-label" for="input01">功德回向</label></td>  
            <td><textarea style="width:700px;height:200px;visibility:hidden;" name="text2"></textarea></td>  
        </tr> 
        <tr>  
            <td><label class="control-label" for="input01">上传功德回向明细</label></td>  
            <td>        
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list2" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue2"></div>
                <input type="file" name="file" id="upload_btn2" />   
            </td>  
        </tr>
        <tr>  
            <td><label class="control-label" for="input01">上传活动照片，最多10张</label></td>  
            <td>        
                <table role="presentation" class="table table-striped">
                    <ul id="pic_list3" style="margin:5px;"></ul>
                 </table>
                <div class="uploadify-queue" id="file-queue3"></div>
                <input type="file" name="file" id="upload_btn3" />   
            </td>  
        </tr>
        <tr>
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="创建"></td>  
        </tr>  
        </tbody>  
        </table>  	
	</div>
</div>

