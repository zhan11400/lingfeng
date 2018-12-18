<link rel="stylesheet" href="/assets/store/css/bootstrap.min.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">提现记录</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>姓名</th>
                                <th>账号</th>
                                <th>金额</th>
                                <th>状态</th>
                                <th>申请时间</th>
                                <th>处理时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['user_name'] ?></td>
                                    <td class="am-text-middle"><?= $item['account'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['money'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['status_str'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle"><?= $item['check_time']?date("Y-m-d H:i:s",$item['check_time']):'-' ?></td>
                                    <td class="am-text-middle"><? if($item['status']==0){ ?>
                                    <button type="button" class="btn btn-success" data-id="<?= $item['id'] ?>" data-status="1">打款</button>
                                            <button type="button" class="btn btn-warning" data-id="<?= $item['id'] ?>" data-status="-1">拒绝</button>                       <? } ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="am-u-lg-12 am-cf">
                        <div class="am-fr"><?= $list->render() ?> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(".btn").click(function () {
            var id=$(this).data("id");
            var status=$(this).data("status")
            var text=$(this).html()
            layer.confirm('确认'+text+'?',function(){
                $.ajax({
                    type:'post',
                    url:"<?= url('finance/pay') ?>",
                    data:{'id':id,'status':status},
                    dataType:'json',
                    success:function(result){
                        if(result.code==1){
                            layer.msg(result.msg);
                            location.reload();
                        }else{
                            layer.alert(result.msg);
                        }

                }});
            })
        })
    });
</script>

