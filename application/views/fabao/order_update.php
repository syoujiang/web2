  <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/utf8_encode.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/utf8_decode.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/base64_encode.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/base64_decode.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/uniqid.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/helper.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/swfupload/swfupload.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/swfupload.queue.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/fileprogress.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('bootstrap/assets/js/handlers.js'); ?>"></script>
    <script type="text/javascript">
</script>
<script>
        var editor,editor2;
        KindEditor.ready(function(K) {
            K('input[name=getText]').click(function(e) {
                    myform.submit();
                });
        });
</script>
<div id="content"><?php echo validation_errors(); ?>

<?php
    $attributes = array('name' => 'myform');
    echo form_open('fabao/update_order',$attributes);
    echo form_hidden('order_id',$order_id); 
?>
    <div class="container-fluid">
        <legend>更新订单</legend>
        <table class="table table-striped">  
        <thead>  
            <tr>  
                <th><label class="control-label" for="input01">工单号:</label></th>  
                <th><?php echo $order_id ?></th>  
            </tr>  
        </thead>  
        <tbody>  
         <tr>  
            <td><label class="control-label" for="input01">缘友:</label></td>  
            <th><input type="input" name="auth" value="<?php echo $name ?>"/></th>  
        </tr>  
        <tr>  
            <td><label class="control-label" for="input01">下单时间:</label></td>  
            <th><input type="input" name="language_type" value="<?php echo $order_time ?>" readonly /></th>  
        </tr>
        <tr>  
            <td><label class="control-label" for="input01">清单:</label></td>  
            <th>
                <table>
                <tr>
                <td>序号</td>
                <td>书名</td>
                <td>数量</td>
                </tr>
                <?php 
                $i=1;
                    foreach ($fabao_info as $value) {
                    ?>    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $value['fabao_id'] ?></td>
                        <td><?php echo $value['number'] ?></td>
                        </tr>
                   <?php $i++;}
                ?>
                </table>
            </th>  
        </tr>
        <tr>  
            <td><label class="control-label" for="input01">状态:</label></td>  
            <th><?php 
            $options = array(
                  '0'  => '处理中',
                  '1'    => '发货中',
                  '2'   => '完成'
                );

            echo form_dropdown('shirts', $options, $status); ?></th>  
        </tr>
        <tr>  
            <td></td>  
            <td><input type="button" name="getText" class="btn" value="更新"></td>  
        </tr>  
        </tbody>  
        </table>    
    </div>
</div>