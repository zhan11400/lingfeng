<link rel="stylesheet" href="assets/store/css/goods.css">
<link rel="stylesheet" href="assets/store/plugins/umeditor/themes/default/css/umeditor.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">基本信息</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[shop_name]"
                                           value="" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺分类 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="shop[shop_cate_id]" required
                                            data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'请选择店铺分类'}">
                                        <option value=""></option>
                                        <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                            <option value="<?= $first['category_id'] ?>"><?= $first['name'] ?></option>
                                            <?php if (isset($first['child'])): foreach ($first['child'] as $two): ?>
                                                <option value="<?= $two['category_id'] ?>">
                                                    　　<?= $two['name'] ?></option>
                                                <?php if (isset($two['child'])): foreach ($two['child'] as $three): ?>
                                                    <option value="<?= $three['category_id'] ?>">
                                                        　　　<?= $three['name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            <?php endforeach; endif; ?>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <small class="am-margin-left-xs">
                                        <a href="<?= url('shop.category/add') ?>">去添加</a>
                                    </small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">联系人 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[contacts]"
                                           value="" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">联系方式 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[mobile]"
                                           value="" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">基础设施 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[facilities]"
                                           value="" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">营业时间 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[work_hour]"
                                           value="" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺地址</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select id="s_province" name="shop[province]"></select>&nbsp;&nbsp;
                                    <select id="s_city" name="shop[city]" ></select>&nbsp;&nbsp;
                                    <select id="s_county" name="shop[area]"></select>
                                    <input type="text" id="address" name="shop[address]" placeholder="详细地址" value="">
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺经纬度 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" id="lng" name="shop[lng]"
                                           value="" required style="display: inline-block;width: 30%;">
                                    <input type="text" class="tpl-form-input" id="lat" name="shop[lat]"
                                           value="" required style="display: inline-block;width: 30%;">
                                    <button class="am-btn-primary getLatAndLng" type="button">获取经纬度</button>
                                    <a target="_blank" href="http://api.map.baidu.com/lbsapi/getpoint/index.html">手动获取</a>
                                    <div id="container2" class="am-btn-primary"   style="margin-top:5px;width: 642px;
                                    height: 300px;border: 1px solid gray;overflow:hidden;">
                                    </div>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">图片</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">店铺logo </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button"
                                                class="upload-file_logo am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-cloud-upload"></i> 选择图片
                                        </button>
                                        <div class="uploader-list am-cf">


                                        </div>
                                    </div>
                                    <div class="help-block am-margin-top-sm">
                                        <small>尺寸150x150像素以上，大小2M以下</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">环境图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
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
                            </div>
							 <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">产品图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button"
                                                class="upload-file2 am-btn am-btn-secondary am-radius">
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
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商家信息 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button"
                                                class="upload-file3 am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-cloud-upload"></i> 选择图片
                                        </button>
                                        <div class="uploader-list uploader-pic am-cf">

                                        </div>
                                    </div>
                                    <div class="help-block am-margin-top-sm">
                                        <small>主要用来展示营业执照之类</small>
                                    </div>
                                </div>
                            </div>

                      <!--      <div class="widget-head am-cf">
                                <div class="widget-title am-fl">规格/库存</div>
                            </div>
-->

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">店铺详情</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">店铺详情 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <!-- 加载编辑器的容器 -->
                                    <textarea id="container" name="shop[content]" type="text/plain"></textarea>
                                </div>
                            </div>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">其他</div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="shop[shop_status]" value="10" data-am-ucheck
                                               checked>
                                        <?= config("shop_status_up") ?>
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="shop[shop_status]" value="20" data-am-ucheck>
                                        <?= config("shop_status_down") ?>
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">是否新店 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="shop[is_new]" value="1" data-am-ucheck
                                               checked>
                                        是
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="shop[is_new]" value="0" data-am-ucheck>
                                         否
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">店铺排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" class="tpl-form-input" name="shop[shop_sort]"
                                           value="100" required>
                                    <small>数字越大越靠前</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                    </button>
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
<script src="assets/store/plugins/umeditor/umeditor.config.js"></script>
<script src="assets/store/plugins/umeditor/umeditor.min.js"></script>
<script src="assets/store/js/goods.spec.js"></script>
<script class="resources library" src="assets/store/js/area.js" type="text/javascript"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?= BAIDU_AK?>"></script>
<script>
    _init_area();
    $(function () {
        var map = new BMap.Map("container2");
        map.centerAndZoom("梅州", 12);
        map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
        map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
        map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
        map.addControl(new BMap.OverviewMapControl()); //添加默认缩略地图控件
        map.addControl(new BMap.OverviewMapControl({ isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT }));   //右下角，打开

        var localSearch = new BMap.LocalSearch(map);
        localSearch.enableAutoViewport(); //允许自动调节窗体大小
        $(".getLatAndLng").click(function(){
            map.clearOverlays();//清空原来的标注
            var province=$("#s_province").val();
            var city=$("#s_city").val();
            var area=$("#s_county").val();
            if(city=='地级市'|| area=='市、县级市'||province=='省份'){
                layer.msg('请选择有效的省市区');return false;
            }
            var address = $("#address").val();
            if(!address){
                layer.msg('请填写详细地址!');
                $('#address').focus();
                return false;
            }
            var keyword=province+city+area+address;
            localSearch.search(keyword);
        });
        localSearch.setSearchCompleteCallback(function (searchResult) {
            var poi = searchResult.getPoi(0);
            console.log(poi);
            $("#lng").val(poi.point.lng);
            $("#lat").val(poi.point.lat);
            map.centerAndZoom(poi.point, 19);
            var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
            map.addOverlay(marker);
            var content =$("#address").val() + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
            var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
            marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
            // marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        });
        // 富文本编辑器
        UM.getEditor('container');

        // 选择图片
        $('.upload-file').selectImages({
            name: 'shop[images][]'
            , multiple: true
        });
		 // 选择环境图片
        $('.upload-file2').selectImages({
            name: 'shop[pictures][]'
            , multiple: true
        });
        // 选择环境图片
        $('.upload-file3').selectImages({
            name: 'shop[shop_message][]'
            , multiple: true
        });
        // 选择logo
        $('.upload-file_logo').selectImages({
            name: 'shop[shop_logo]'
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

                };
            },
            // 自定义验证
            validation: function () {
                return true;
            }
        });

    });
</script>
