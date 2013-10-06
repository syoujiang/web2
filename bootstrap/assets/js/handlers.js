/* Demo Note:  This demo uses a FileProgress class that handles the UI for displaying the file name and percent complete.
The FileProgress class is not part of SWFUpload.
*/


/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileQueued(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Pending...");
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("File is too big.");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("Cannot upload Zero Byte files.");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus("Invalid File Type.");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		default:
			if (file !== null) {
				progress.setStatus("Unhandled Error");
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesSelected > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}

		/* I want auto start the upload and I can do that here */
		this.startUpload();
	} catch (ex)  {
        this.debug(ex);
	}
}

/*
 * 定义一个文件上传前要执行的业务逻辑
 */
function uploadStart(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Uploading...");
		progress.toggleCancel(true, this);

                // 首先，为该文件生成一个唯一ID
                // uniqid() 函数在 public/assets/js/uniqid.js 文件中有定义
                var fileUniqKey = uniqid(file.name);

                // 然后构造 action 表单域的值
                // generate_rs_put_path() 在 public/assets/js/helper.js 中有定义
                var action = generate_rs_put_path($bucket, fileUniqKey, file.type);
                // 给隐形表单添加名为 action 的 input 域（字段）
                this.addPostParam("action", action);

                // 给隐形表单添加名为 params 的 input 域（字段）
                // params 里边的数据，用于文件上传成功后，七牛云存储服务器向我们的业务服务器执行 POST 回调
                this.addPostParam("params", "filename="+file.name+"&filekey="+fileUniqKey+"&filetype="+file.type);
                
                // 给隐形表单添加 名为 auth 的 input 域 （字段）
                this.addPostParam("auth", $upToken);
                
                // 将该文件唯一ID临时保存起来供后续使用
                this.customSettings.fileUniqIdMapping[file.id] = fileUniqKey;
	}
	catch (ex) {}

	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("Uploading...");
	} catch (ex) {
		this.debug(ex);
	}
}

/*
 * 定义一个文件上传成功后要处理的业务逻辑
 */
function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);

                // 取出之前在 uploadStart() 暂存的文件唯一ID
                var fileUniqKey = this.customSettings.fileUniqIdMapping[file.id];
                // 组织要回调给网站业务服务器的数据
                var postData = {
                    "action": "insert",
                    "file_key": fileUniqKey,
                    "file_name": file.name,
                    "file_size": file.size,
                    "file_type": file.type
                };
                // 通过AJAX异步向网站业务服务器POST数据
                $.ajax({
                    type: "POST",
                    url: 'callback',
                    processData: true,
                    data: postData,
                    dataType: "json",
                    beforeSend: function(){},
                    complete: function(xhr, textStatus){
                    	if(xhr.readyState ==4)
                    	{
                    		if(xhr.status ==200)
                    		{
								var objs = JSON.parse(xhr.responseText);
								myform.sum_picture_id.value=objs.sqlid;
								myform.sum_picture_fkey.value=objs.fkey;
								myform.sum_picture_fname.value=objs.fname;
								addImage1(objs.sqlid,objs.fkey);
                    		}
                    	}
                    },
                    success:function(resp){
                    }
                });
	} catch (ex) {
		this.debug(ex);
	}
}
function uploadSuccess2(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);

                // 取出之前在 uploadStart() 暂存的文件唯一ID
                var fileUniqKey = this.customSettings.fileUniqIdMapping[file.id];
                // 组织要回调给网站业务服务器的数据
                var postData = {
                    "action": "insert",
                    "file_key": fileUniqKey,
                    "file_name": file.name,
                    "file_size": file.size,
                    "file_type": file.type
                };
                // 通过AJAX异步向网站业务服务器POST数据
                $.ajax({
                    type: "POST",
                    url: 'callback',
                    processData: true,
                    data: postData,
                    dataType: "json",
                    beforeSend: function(){},
                    complete: function(xhr, textStatus){
                    	if(xhr.readyState ==4)
                    	{
                    		if(xhr.status ==200)
                    		{
								var objs = JSON.parse(xhr.responseText);
								myform.con_picture_id.value=objs.sqlid;
								myform.con_picture_fkey.value=objs.fkey;
								myform.con_picture_fname.value=objs.fname;
								addImage2(objs.sqlid,objs.fkey);
                    		}
                    	}
                    },
                    success:function(resp){
                    }
                });
	} catch (ex) {
		this.debug(ex);
	}
}
function uploadSuccess3(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);

                // 取出之前在 uploadStart() 暂存的文件唯一ID
                var fileUniqKey = this.customSettings.fileUniqIdMapping[file.id];
                // 组织要回调给网站业务服务器的数据
                var postData = {
                    "action": "insert",
                    "file_key": fileUniqKey,
                    "file_name": file.name,
                    "file_size": file.size,
                    "file_type": file.type
                };
                // 通过AJAX异步向网站业务服务器POST数据
                $.ajax({
                    type: "POST",
                    url: '../callback',
                    processData: true,
                    data: postData,
                    dataType: "json",
                    beforeSend: function(){},
                    complete: function(xhr, textStatus){
                    	if(xhr.readyState ==4)
                    	{
                    		if(xhr.status ==200)
                    		{
								var objs = JSON.parse(xhr.responseText);
								myform.sum_picture_id.value=objs.sqlid;
								myform.sum_picture_fkey.value=objs.fkey;
								myform.sum_picture_fname.value=objs.fname;
								addImage3(objs.sqlid,objs.fkey);
                    		}
                    	}
                    },
                    success:function(resp){
                    }
                });
	} catch (ex) {
		this.debug(ex);
	}
}
function uploadSuccess4(file, serverData) {
	try {
		// var imageInfo = eval("("+serverData+")");//接收JSON格式的数据
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete111.");
		progress.toggleCancel(false);

                // 取出之前在 uploadStart() 暂存的文件唯一ID
                var fileUniqKey = this.customSettings.fileUniqIdMapping[file.id];
                // 组织要回调给网站业务服务器的数据
                var postData = {
                    "action": "insert",
                    "file_key": fileUniqKey,
                    "file_name": file.name,
                    "file_size": file.size,
                    "file_type": file.type
                };
                // 通过AJAX异步向网站业务服务器POST数据
                $.ajax({
                    type: "POST",
                    url: '../callback',
                    processData: true,
                    data: postData,
                    dataType: "json",
                    beforeSend: function(){},
                    complete: function(xhr, textStatus){
                    	if(xhr.readyState ==4)
                    	{
                    		if(xhr.status ==200)
                    		{
								var objs = JSON.parse(xhr.responseText);
								myform.con_picture_id.value=objs.sqlid;
								myform.con_picture_fkey.value=objs.fkey;
								myform.con_picture_fname.value=objs.fname;
							   	addImage4(objs.sqlid,objs.fkey);
                    		}
                    	}
                    },
                    success:function(resp){
                    }
                });
	} catch (ex) {
		this.debug(ex);
	}
}
function uploadSuccess5(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);

                // 取出之前在 uploadStart() 暂存的文件唯一ID
                var fileUniqKey = this.customSettings.fileUniqIdMapping[file.id];
                // 组织要回调给网站业务服务器的数据
                var postData = {
                    "action": "insertpic",
                    "file_key": fileUniqKey,
                    "file_name": file.name,
                    "file_size": file.size,
                    "file_type": file.type
                };
                // 通过AJAX异步向网站业务服务器POST数据
                $.ajax({
                    type: "POST",
                    url: 'callback',
                    processData: true,
                    data: postData,
                    dataType: "json",
                    beforeSend: function(){},
                    complete: function(xhr, textStatus){
                    	if(xhr.readyState ==4)
                    	{
                    		if(xhr.status ==200)
                    		{
								var objs = JSON.parse(xhr.responseText);
								addImage5(objs.sqlid,objs.file_key,objs.fname,objs.insertid);
                    		}
                    	}
                    },
                    success:function(resp){
                    }
                });
	} catch (ex) {
		this.debug(ex);
	}
}
function uploadSuccess6(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);

                // 取出之前在 uploadStart() 暂存的文件唯一ID
                var fileUniqKey = this.customSettings.fileUniqIdMapping[file.id];
                // 组织要回调给网站业务服务器的数据
                var postData = {
                    "action": "insertpic",
                    "file_key": fileUniqKey,
                    "file_name": file.name,
                    "file_size": file.size,
                    "file_type": file.type
                };
                // 通过AJAX异步向网站业务服务器POST数据
                $.ajax({
                    type: "POST",
                    url: '../callback',
                    processData: true,
                    data: postData,
                    dataType: "json",
                    beforeSend: function(){},
                    complete: function(xhr, textStatus){
                    	if(xhr.readyState ==4)
                    	{
                    		if(xhr.status ==200)
                    		{
								var objs = JSON.parse(xhr.responseText);
								addImage6(objs.sqlid,objs.fkey,objs.fname,objs.insertid);
                    		}
                    	}
                    },
                    success:function(resp){
                    }
                });
	} catch (ex) {
		this.debug(ex);
	}
}
function uploadError(file, errorCode, message) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("Upload Error: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("Upload Failed.");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("Server (IO) Error");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("Security Error");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus("Upload limit exceeded.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus("Failed Validation.  Upload skipped.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			// If there aren't any files left (they were all cancelled) disable the cancel button
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
			progress.setStatus("Cancelled");
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("Stopped");
			break;
		default:
			progress.setStatus("Unhandled Error: " + errorCode);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function uploadComplete(file) {
	if (this.getStats().files_queued === 0) {
	//	document.getElementById(this.customSettings.cancelButtonId).disabled = true;
    //    window.setTimeout(function(){window.location.href = "index";}, 1500);
	}
}

// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded) {
	// var status = document.getElementById("divStatus");
	// status.innerHTML = numFilesUploaded + " file" + (numFilesUploaded === 1 ? "" : "s") + " uploaded.";
}
this.m = 0;
function addImage1(src,key) {
	if($("#pic_list li").length>=5)   
	{   
		alert('最多只能上传5张图片！');   
		return false;   
	}
	if(!src)
	{
		return false;
	}
	$("#divMsg1").hide();
	//var newElement = "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../images/fancy_close.png'></li>";   
	
	$("#pic_list1").append( "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
						"<input id='"+key+"' name='fkey' type=\"hidden\" value='"+key+"''></li>");      
	
	$("#pic_list1 li").bind("click",del1);    
	//this.m++;      
}
function addImage2(src,key) {
	if($("#pic_list li").length>=5)   
	{   
		alert('最多只能上传5张图片！');   
		return false;   
	}
	if(!src)
	{
		return false;
	}
	$("#divMsg2").hide();
	//var newElement = "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../images/fancy_close.png'></li>";   
	
	$("#pic_list2").append( "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
						"<input id='"+key+"' name='fkey' type=\"hidden\" value='"+key+"''></li>");      
	
	$("#pic_list2 li").bind("click",del2);    
	//this.m++;      
}
function addImage3(src,key) {
	if($("#pic_list li").length>=5)   
	{   
		alert('最多只能上传5张图片！');   
		return false;   
	}
	if(!src)
	{
		return false;
	}
	$("#divMsg1").hide();
	//var newElement = "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../images/fancy_close.png'></li>";   
	
	$("#pic_list1").append( "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
						"<input id='"+key+"' name='fkey' type=\"hidden\" value='"+key+"''></li>");      
	
	// $("#li"+m).bind("click",del3);   
	$("#pic_list1 li").bind("click",del3);  
	//this.m++;      
}
function addImage4(src,key) {
	if($("#pic_list li").length>=5)   
	{   
		alert('最多只能上传5张图片！');   
		return false;   
	}
	if(!src)
	{
		return false;
	}
	$("#divMsg2").hide();
	//var newElement = "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../images/fancy_close.png'></li>";   
	
	$("#pic_list2").append( "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
						"<input id='"+key+"' name='fkey' type=\"hidden\" value='"+key+"''></li>");      
	
	$("#pic_list2 li").bind("click",del4);   
	//this.m++;      
}
this.m5 = 0;
function addImage5(src,key,name,insertid) {
	if($("#pic_list li").length>=10)   
	{   
		alert('最多只能上传5张图片！');   
		return false;   
	}
	if(!src)
	{
		return false;
	}
 //   alert(src);
//	$("#divMsg3").hide();
	//var newElement = "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../images/fancy_close.png'></li>";   
	
	$("#pic_list3").append( "<li id='li"+m5+"'><img class='content'  src='" + src + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
						"<input id='"+insertid+"' name='"+name+"' type=\"hidden\" value='"+key+"''></li>");      
	
	$("#pic_list3 li").bind("click",del5);   
	this.m5++;
	//alert("asdfasdfa");
	$('#pic_list3 li input').each(function(index,val){
	   // alert(this.id);
	   // alert(this.name);
	  //  alert(this.value);
	 })      
}
var del5 = function () {  
        var fid=$(this).prevAll("li").length+1; 
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
			url: './callback',
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
						$("#divMsg2").show();
						$("#divMsg2").html("删除成功！");
					}
				}
			},
			success:function(resp){
			}
		});   
        $(this).remove();      
}
this.m6 = 0;
function addImage6(src,key,name,insertid) {
	if($("#pic_list li").length>=10)   
	{   
		alert('最多只能上传5张图片！');   
		return false;   
	}
	if(!src)
	{
		return false;
	}
//	$("#divMsg2").hide();
	//var newElement = "<li id='li"+m+"'><img class='content'  src='" + src + "'><img class='button' src='../images/fancy_close.png'></li>";   
	
	$("#pic_list3").append( "<li id='li"+m6+"'><img class='content'  src='" + src + "'><img class='button' src='../../bootstrap/assets/images/fancy_close.png'>"+
						"<input id='"+insertid+"' name='"+name+"' type=\"hidden\" value='"+key+"''></li>");      
	
	$("#li"+m6).bind("click",del6);  
	this.m6++;   
}
var del5 = function () {  
        var fid=$(this).prevAll("li").length+1; 
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
            url: '../callback',
            processData: true,
            data: postData,
            dataType: "json",
            beforeSend: function(){},
            complete: function(xhr, textStatus){
                if((xhr.readyState ==4)&&(xhr.status ==200))
                {
                    $("#divMsg2").show();
                    $("#divMsg2").html("删除成功！");
                }
            },
            success:function(resp){
            }
        });   
        $(this).remove();      
}
var del6 = function () {  
        var fid=$(this).prevAll("li").length+1; 
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
			url: '../callback',
			processData: true,
			data: postData,
			dataType: "json",
			beforeSend: function(){},
			complete: function(xhr, textStatus){
			if((xhr.readyState ==4) &&(xhr.status ==200))
			{
				$("#divMsg2").show();
				$("#divMsg2").html("删除成功！");
			}
			},
			success:function(resp){
			}
		});   
        $(this).remove();      
}
var del1 = function () {   
        var fid=$(this).prevAll("li").length+1; 
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
			url: './callback',
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
var del2 = function () {   
        var fid=$(this).prevAll("li").length+1; 
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
			url: './callback',
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
						$("#divMsg2").show();
						$("#divMsg2").html("删除成功！");
					}
				}
			},
			success:function(resp){
			}
		});   
        $(this).remove();      
} 
var del3 = function () {   
        var fid=$(this).prevAll("li").length+1; 

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
			url: '../callback',
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
var del4 = function () {  
        var fid=$(this).prevAll("li").length+1; 
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
			url: '../callback',
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
						$("#divMsg2").show();
						$("#divMsg2").html("删除成功！");
					}
				}
			},
			success:function(resp){
			}
		});   
        $(this).remove();      
}  
function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}