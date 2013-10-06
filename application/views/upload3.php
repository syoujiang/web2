<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>jQuery File Upload Example</title>
</head>
<body>
<input name="token" type="hidden" value="<?php echo $uptoken ?>" >
<input id="fileupload" type="file" name="file" data-url="http://up.qiniu.com/" multiple>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="<?php echo base_url('bootstrap/jQuery-File-Upload/js/vendor/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo base_url('bootstrap/jQuery-File-Upload/js/jquery.iframe-transport.js'); ?>"></script>

<script src="<?php echo base_url('bootstrap/jQuery-File-Upload/js/jquery.fileupload.js'); ?>"></script>
<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
});
</script>
</body> 
</html>