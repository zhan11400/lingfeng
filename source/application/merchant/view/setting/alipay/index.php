<link rel="stylesheet" href="/assets/store/css/bootstrap.min.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">支付宝配置</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                我的支付宝
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <input type="text" class="form-control" id="ali_name" value="<?= $info['ali_name'] ?>"
                                       placeholder="姓名">
                            </div>
                            <div class="panel-body">
                                <input type="text" class="form-control" id="ali_account" value="<?= $info['ali_account'] ?>"
                                       placeholder="支付宝账号">
                            </div>
                        </div>
                        <div class="btn-group">
                                <button type="button" class="btn btn-success">我要绑定</button>
                        </div>
                        <div class="alert alert-warning">

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
                url : "<?= url('setting.alipay/set') ?>",
                data : {'ali_name':$("#ali_name").val(),'ali_account':$("#ali_account").val(),},
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

