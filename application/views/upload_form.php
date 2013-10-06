<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="<?php echo site_url() ?>res/uploadify.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="<?php echo site_url() ?>res/jquery.uploadify.min.js"></script>
  <title></title>
</head>
<body>
  <div id="theDiv"></div>
  <div id="theDiv2">2</div>
    <div class="uploadify-queue" id="file-queue"></div>
    <input type="file" name="file" id="upload_btn" />    
    <input type="file" name="file" id="upload_btn2" />  
</div> 
    <script type='text/javascript' >
    $(function() {
      var aa='<?php echo $fileprev ?>';
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
      'formData'  : {'token' : '<?php echo $uptoken;?>'},

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
          url: 'uploadtest/callback',
          processData: true,
          data: postData,
          dataType: "json",
          beforeSend: function(){},
          complete: function(xhr, textStatus){
            if(xhr.readyState ==4)
            {
              if(xhr.status ==200)
              {
                var obj=JSON.parse(xhr.responseText);
                $('#theDiv').prepend('<img id="theImg" src='+obj.preview+' />')
              }
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
 <script type='text/javascript' >
    $(function() {
      var aa='<?php echo $fileprev ?>';
     $('#upload_btn2').uploadify({
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
      'formData'  : {'token' : '<?php echo $uptoken;?>'},

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
          url: 'uploadtest/callback',
          processData: true,
          data: postData,
          dataType: "json",
          beforeSend: function(){},
          complete: function(xhr, textStatus){
            if(xhr.readyState ==4)
            {
              if(xhr.status ==200)
              {
                var obj=JSON.parse(xhr.responseText);
                $('#theDiv2').prepend('<img id="theImg" src='+obj.preview+' />')
              }
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
</body>
</html>