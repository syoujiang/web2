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
<?php echo $formurl; ?>
<div id="content">
  <div class="container-fluid">
    <legend>法宝类别管理</legend>
    <a class="btn btn-primary" href="<?php echo site_url("fabao/create_type") ?>">添加</a>
    <input class="btn btn-primary" type="button" value="删除" onclick="submitForm()">
    <input class="btn btn-primary" type="button" value="全选" id="selectAll">
    <input class="btn btn-primary" type="button" value="反选" id="unSelect">
    <table class="table table-striped table-bordered table-condensed">
      <tbody>
        <tr>
          <th></th>
          <th>序号</th>
          <th>法宝类别</th>
        </tr>
        <?php $index=1; ?>
        <?php foreach ($news as $news_item): ?>
        <tr>
            <td>
              <label class="checkbox">
                <?php echo form_checkbox('range[]', $news_item['id']); ?>
              </label>
            </td>
            <td><?php echo anchor('fabao/edit_type/'.$news_item['id'],$index); $index++?></td>
            <td><?php echo $news_item['fabao_type'] ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>

    </table>
<div class="page">
    <?php echo $this->mypage->show(1); ?>
</div>
</div>
</div>