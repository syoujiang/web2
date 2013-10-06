<legend>标题</legend>
<tr>
	<td><?php echo $gonggao['title'] ?></td>
</tr>
<legend></legend>
<legend>时间</legend>
<tr>
	<td><?php echo $gonggao['gg_date'] ?></td>
</tr>
<legend></legend>
<legend>地点</legend>
<tr>
	<td><?php echo $gonggao['didian'] ?></td>
</tr>
<legend></legend>
<legend>主持参与法师</legend>
<tr>
	<td><?php echo $gonggao['zhuchi'] ?></td>
</tr>
<legend></legend>
<legend>参加人员</legend>
<tr>
	<td><?php echo $gonggao['renyuan']; ?></td>
</tr>
<legend></legend>
<legend>投入基金</legend>
<tr>
	<td><?php echo $huodong['jijin'] ?></td>
</tr>
<legend></legend>
<legend>放生明细</legend>
<tr>
	<td><?php echo $huodong['mingxi'] ?></td>
</tr>
<legend></legend>
<legend>明细详情</legend>
<tr>
	<td><a href="<?php echo $huodong['mingxi_url'] ; ?>">下载</a></td>
</tr>
<legend></legend>
<legend>功德回向</legend>
<tr>
	<td><?php echo $huodong['gongde'] ?></td>
</tr>
<legend></legend>
<legend>功德回向详情</legend>
<tr>
	<td><a href="<?php echo $huodong['gongde_url']  ?>"?>下载</a></td>
</tr>
<legend></legend>
<legend>活动照片</legend>
<tr>
	<?php foreach ($pic as $key) {
		# code...
		echo "<td><img src=\"$key\" /></td>";
	}
	 ?>
	
</tr>
<legend></legend>