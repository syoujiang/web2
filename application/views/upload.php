<html>
<body>

<form method="post" action="http://up.qiniu.com/" enctype="multipart/form-data">
  <!-- <input name="key" type="hidden" value="<?php echo $filename ?>"> -->
  <input name="token" type="hidden" value="<?php echo $uptoken ?>" >
  <input name="file" type="file" />
  <input type="submit" name="submit" value="submit">
</form>

</body>
</html>