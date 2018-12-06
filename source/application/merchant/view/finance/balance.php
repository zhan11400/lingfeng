<link rel="stylesheet" href="/assets/store/css/bootstrap.min.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">我的余额</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                我的余额：<?= $money ?>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <input type="number" class="form-control" id="money"
                                       placeholder="要提现的金额">
                            </div>
                        </div>
                        <div class="btn-group">
                            <?php if($money<=0): ?>
                                <button type="button" class="btn btn-error">我要提现</button>

                                <?php  else: ?>
                                <button type="button" class="btn btn-success">我要提现</button>
                           <?php endif; ?>

                        </div>
                        <div class="alert alert-warning">
                               <strong>注意：</strong>最低提现金额100，每个月最多可以提现两次,，没有配置支付宝信息，请<a href="<?= url('setting.alipay/index') ?>">前往设置</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        $(".btn-success").click(function () {
            $.ajax({
                type :"post",
                url : "<?= url('finance/apply') ?>",
                data : {'money':$("#money").val()},
                dataType : "json",
                success : function(data) {
                        layer.msg(data.msg)
                            if(data.code==1){
                                location.reload();
                            }
                },
                error : function(err) {
                    layer.alert(err.info,8);
                }
            });
        })
</script>

