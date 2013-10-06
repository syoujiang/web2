<script type="text/javascript">
function $(id){
return document.getElementById(id);
}
window.onload=function(){
    var pan;
    var conf;
    var selectAll = $("selectAll"),
    unSelect = $("unSelect"),
    del = $("del"),
    inputs=document.getElementsByName('range[]'),
    len = inputs.length;
    selectAll.onclick=function(){
        for(var i=0; i<len;i++){
            inputs[i].checked=true;
        }
    }
    unSelect.onclick=function(){
        for(var i=0; i<len;i++){
            var o = inputs[i];
            o.checked?o.checked=false:o.checked=true;
        }
    }
 
    $("form1").onsubmit=function(){
        for(var i=0; i<len;i++){
            var o = inputs[i];
            if(o.checked){
                pan=1;
                break;
            }else{
                pan=0;
            }
        }
 
        if(!pan){
            alert("请选择");
            return false;
        }else{
            conf=confirm("确定删除");
        }
 
        if(conf){
            return true;
            }else{
                return false;
                }
    }
}
function submitForm(){
    var boxes = document.getElementsByName("range[]");  
    var groupTypeId=""; 
    for (i = 0; i < boxes.length; i++)  
    {  
        if (boxes[i].checked)  
        {  
            groupTypeId += boxes[i].value+",";  
        }
    }
    if(groupTypeId.length<1)
    {
        alert("请选择需要删除的一项");
        return false;
    }
    indexform.deleteid.value=groupTypeId;
  //  alert(indexform.deleteid.value);
    indexform.submit();
}
</script>
<style type="text/css">
.ctl{table-layout:fixed}
.ctl td{text-overflow:ellipsis;overflow:hidden;white-space:nowrap;padding:2px}
</style>
<?php echo $formurl; ?>
<div id="content">
  <div class="container-fluid">
  	 <legend>反馈意见管理</legend>
<input class="btn btn-primary" type="button" value="删除" onclick="submitForm()">
<input class="btn btn-primary" type="button" value="全选" id="selectAll">
<input class="btn btn-primary" type="button" value="反选" id="unSelect">
<table class="table table-striped table-bordered table-condensed" class="ctl">
<tbody><tr>
<th></th>
<th>序号</th>
<th>Email</th>
<th>手机</th>
<th>预览</th>

</tr>
	<?php $index_id=1; ?>
<?php foreach ($news as $news_item): ?>

	<tr>
	<td>  
        <label class="checkbox">
	   <?php echo form_checkbox('range[]', $news_item['id']); ?>
	   </label>
    </td>
	<td><?php echo  $index_id++;?></td>
	<td><?php echo $news_item['mail']; ?></td>
	<td><?php echo $news_item['phone'];?></td>
	<td><?php echo anchor_popup('gonggao/show/'.$news_item['id'],'详情'); ?></td>
	
	</tr>
<?php endforeach ?>
</tbody>
</table>
<div class="page">
<?php echo $this->mypage->show(1); ?>
</div>
</div>
</div>
</form>