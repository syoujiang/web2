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
    indexform.mymethod.value = "delete";
  //  alert(indexform.deleteid.value);
    indexform.submit();
}
function submitFormAdd(){
    indexform.mymethod.value = "add";
  //  alert(indexform.deleteid.value);
    indexform.submit();
}
        KindEditor.ready(function(K) {
                K('#create1').click(function() {
                    var dialog = K.dialog({
                        width : 500,
                        title : '测试窗口',
                        body : '<div style="margin:10px;"><strong>内容</strong></div>',
                        closeBtn : {
                            name : '关闭',
                            click : function(e) {
                                dialog.remove();
                            }
                        },
                        yesBtn : {
                            name : '确定',
                            click : function(e) {
                                alert(this.value);
                            }
                        },
                        noBtn : {
                            name : '取消',
                            click : function(e) {
                                dialog.remove();
                            }
                        }
                    });
                });
            });
</script>
<?php echo $formurl; ?>
<div id="content">
  <div class="container-fluid">
    <legend>轮播图管理</legend>
            <thead>  
            <tr>  
                <th>

                    <label class="control-label" for="input01">轮播图名称</label>
                </th>  
                <th>              
                <?php 
                $options = array();
                foreach ($fabao as $value) {
                # code...
                    $options[$value['id']]=$value['fbname'];
                }
                echo form_dropdown('lunbotu', $options, 'large');
                ?>
                </th>  
            </tr>  
        </thead>  
    <input class="btn btn-primary" type="button" value="添加" onclick="submitFormAdd()">
</br>
    <input class="btn btn-primary" type="button" value="删除" onclick="submitForm()">
    <input class="btn btn-primary" type="button" value="全选" id="selectAll">
    <input class="btn btn-primary" type="button" value="反选" id="unSelect">
    <table class="table table-striped table-bordered table-condensed">
      <tbody>
        <tr>
          <th></th>
          <th>序号</th>
          <th>轮播图名称</th>
          <th>显示顺序</th>
        </tr>
        <?php $index=1; ?>
        <?php foreach ($news as $news_item): ?>
        <tr>
            <td>
              <label class="checkbox">
                <?php echo form_checkbox('range[]', $news_item['fabao_id']); ?>
              </label>
            </td>
            <td><?php echo anchor('fabao/update/'.$news_item['fabao_id'],$index); $index++?></td>
            <td><?php echo $news_item['fb_name'] ?></td>
            <td><a href="<?php 
                    $segments = array('fabao/lunbo', 'up', $news_item['weight'],$news_item['fabao_id']);
                    echo site_url($segments);
                    ?>">上移</a>
                <a href="<?php 
                    $segments = array('fabao/lunbo', 'down', $news_item['weight'],$news_item['fabao_id']);
                    echo site_url($segments);
                    ?>">下移</a></td> 
        </tr>
      <?php endforeach ?>
      </tbody>

    </table>
<div class="page">
    <?php echo $this->mypage->show(1); ?>
</div>
</div>
</div>