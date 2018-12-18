<link rel="stylesheet" href="/assets/store/css/bootstrap.min.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">发布动态</div>
                </div>
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <div class="panel panel-default am-form-group">
                            <div class="panel-body">
                                <textarea class="form-control" name="data[content]"  rows="3" placeholder="动态内容"></textarea>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">动态图片 </label>
                            <div class="am-u-sm-9 am-u-end">
                                <div class="am-form-file">
                                    <button type="button"
                                            class="upload-file am-btn am-btn-secondary am-radius">
                                        <i class="am-icon-cloud-upload"></i> 选择图片
                                    </button>
                                    <div class="uploader-list am-cf">
                                    </div>
                                </div>
                                <div class="help-block am-margin-top-sm">
                                    <small>尺寸750x750像素以上，大小2M以下 (可拖拽图片调整显示顺序 )</small>
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                            </fieldset>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}
<script src="assets/store/js/ddsort.js"></script>
<script>
    $(function () {
        // 选择图片
        $('.upload-file').selectImages({
            name: 'data[images][]'
            , multiple: true
        });

        // 图片列表拖动
        $('.uploader-list').DDSort({
            target: '.file-item',
            delay: 100, // 延时处理，默认为 50 ms，防止手抖点击 A 链接无效
            floatStyle: {
                'border': '1px solid #ccc',
                'background-color': '#fff'
            }
        });
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            // form data
            buildData: function () {
                return {
                    data: {

                    }
                };
            },
            // 自定义验证
            validation: function () {

                return true;
            }
        });
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
    })
</script>

