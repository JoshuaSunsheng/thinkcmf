<?php if (!defined('THINK_PATH')) exit(); $portal_index_lastnews="1,2"; $portal_hot_articles="1,2"; $portal_last_post="1,2"; $tmpl=sp_get_theme_path(); $default_home_slides=array( array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/1.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/2.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/3.jpg", "slide_url"=>"", ), ); $CSS_URL = $tmpl."Public/hp/css/"; $IMG_URL = $tmpl."Public/hp/img/"; $JS_URL = $tmpl."Public/hp/js/"; $SITE_URL = $tmpl."Public/hp/js/"; $I_URL = $tmpl."Public/hp/i/"; $CITY_URL = $tmpl."Public/hp/js/"; $DATA_URL = $tmpl."Public/hp/data/"; ?>

<link rel="stylesheet" href="<?php echo ($CSS_URL); ?>amazeui.min.css"/>
<link rel="stylesheet" href="<?php echo ($CSS_URL); ?>admin.css">



<div class="am-cf admin-main">

    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0">
                <div class="am-fl am-cf">
                    <strong class="am-text-primary am-text-lg">医生信息</strong> /
                    <small>info</small>
                </div>
            </div>

            <hr>

            <div class="am-tabs am-margin" data-am-tabs>
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                    <!--<li ><a href="#tab1">基本信息</a></li>-->
                    <li class="am-active"><a href="#tab2">基本信息</a></li>
                    <!--<li><a href="#tab3">SEO 选项</a></li>-->
                </ul>

                <div class="am-tabs-bd">

                    <div class="am-tab-panel am-fade am-in am-active" id="tab2">

                        <form method="post" id="register" name="register" class="am-form">


                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">手机号</div>
                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <input type="text" class="am-input-sm" id="phoneNumber" name="phoneNumber"
                                           maxlength="11" placeholder="手机号" value="<?php echo ($data["phonenumber"]); ?>">
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">姓名</div>
                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <input type="text" class="am-input-sm" id="realName" name="realName"
                                           placeholder="输入您的姓名" value="<?php echo ($data["realname"]); ?>">
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">性别</div>
                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <?php if(($data["sex"] == 0)): ?><label class="am-radio-inline">
                                            <input type="radio" name="sex" checked="true" value="0" disabled> 男
                                            <i class="am-icon-male am-icon-fw"></i>
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="sex" value="1" disabled> 女
                                            <i class="am-icon-female am-icon-fw"></i>
                                        </label>

                                        <?php else: ?>

                                        <label class="am-radio-inline">
                                            <input type="radio" name="sex" value="0" disabled> 男
                                            <i class="am-icon-male am-icon-fw"></i>
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="sex" checked="true" value="1" disabled> 女
                                            <i class="am-icon-female am-icon-fw"></i>
                                        </label><?php endif; ?>
                                </div>

                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">出生日期</div>

                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <input id="birthday" name="birthday" type="text" class="am-input-sm"
                                           value="<?php echo ($data["birthday"]); ?>" placeholder="yyyy-mm-dd" data-am-datepicker required
                                           readonly/>
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">所在医院</div>

                                <div id="address">
                                    <div class="am-u-sm-3 am-u-md-3">
                                        <input type="text" class="am-input-sm" id="province" name="province"
                                               value="<?php echo ($data["province"]); ?>">
                                        <!--<select class="prov" id="province" name="province"></select><span class="am-form-caret"></span>-->
                                    </div>
                                    <div class="am-u-sm-3 am-u-md-3">
                                        <input type="text" class="am-input-sm" id="city" name="city"
                                               value="<?php echo ($data["city"]); ?>">

                                        <!--<select class="city" id="city" name="city" disabled="disabled"></select><span-->
                                        <!--class="am-form-caret"></span>-->
                                    </div>
                                    <div class="am-u-sm-3 am-u-md-3 am-u-end col-end">
                                        <input type="text" class="am-input-sm" id="district" name="district"
                                               value="<?php echo ($data["district"]); ?>">

                                        <!--<select class="dist" id="district" name="district" disabled="disabled"></select><span-->
                                        <!--class="am-form-caret"></span>-->
                                    </div>
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">医院名称</div>
                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <input type="text" id="hospital" name="hospital" class="am-input-sm"
                                           placeholder="请输入医院名称" value="<?php echo ($data["hospital"]); ?>">
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">医院位置</div>
                                <div class="am-u-sm-4 am-u-md-2">
                                    <input type="text" id="x" name="x" class="am-input-sm"
                                           placeholder="X坐标" value="<?php echo ($data["x"]); ?>">
                                </div>
                                <div class="am-u-sm-4 am-u-md-2">
                                    <input type="text" id="y" name="y" class="am-input-sm"
                                           placeholder="Y坐标" value="<?php echo ($data["y"]); ?>">
                                </div>
                                <div class="am-u-sm-4 am-u-md-2 am-u-end col-end">
                                    <button
                                            type="button"
                                            class="am-btn am-btn-primary"
                                            data-am-modal="{target: '#my-alert'}">
                                        查询地图
                                    </button>
                                </div>
                            </div>




                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">职称</div>

                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <input type="text" class="am-input-sm" id="title" name="title"
                                           value="<?php echo ($data["title"]); ?>">
                                    <span class="am-form-caret"></span>
                                </div>
                                <div class="am-u-sm-6">
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">邮箱</div>

                                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                                    <input type="text" class="am-input-sm" id="mail" name="mail" value="<?php echo ($data["mail"]); ?>">
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">
                                    个人简介
                                </div>

                                <div class="am-u-sm-12 am-u-md-10">
                                    <textarea class="" rows="5" id="description" name="description" placeholder="医生简介"><?php echo ($data["description"]); ?></textarea>
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">
                                    上传照片
                                </div>
                                <div class="am-u-sm-12 am-u-md-10">

                                    <!-- Gallery -->
                                    <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
    am-avg-md-3 am-avg-lg-4 am-gallery-default" data-am-gallery="{ pureview: true }">
                                        <li>
                                            <div class="am-gallery-item">
                                                <a href="<?php echo ($data["headlogofile"]); ?>"
                                                   class="">
                                                    <img img-src="<?php echo ($data["headlogofile"]); ?>"
                                                         alt="头像照片"/>
                                                    <h3 class="am-gallery-title">头像照片</h3>
                                                </a>
                                            </div>
                                        </li>
                                        <li style="display: none">
                                            <div class="am-gallery-item">
                                                <a href="<?php echo ($data["idnofile"]); ?>"
                                                   class="">
                                                    <img img-src="<?php echo ($data["idnofile"]); ?>"
                                                         alt="身份证照片"/>
                                                    <h3 class="am-gallery-title">身份证照片</h3>
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="am-gallery-item">
                                                <a href="<?php echo ($data["docfile"]); ?>"
                                                   class="">
                                                    <img img-src="<?php echo ($data["docfile"]); ?>"
                                                         alt="医生资格证或医院工作证"/>
                                                    <h3 class="am-gallery-title">医生资格证或医院工作证</h3>
                                                </a>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">门诊日期</div>
                                <div class="am-u-sm-8 am-u-md-6 am-u-end col-end">
                                    <label class="am-checkbox-inline" style="    margin-left: 10px;">
                                        <input type="checkbox" name="cureTime[]" value="0" <?php echo ($cureTimes[0]); ?> disabled> 周一上午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="1" <?php echo ($cureTimes[1]); ?> disabled> 周一下午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="2" <?php echo ($cureTimes[2]); ?> disabled> 周二上午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="3" <?php echo ($cureTimes[3]); ?> disabled> 周二下午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="4" <?php echo ($cureTimes[4]); ?> disabled> 周三上午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="5" <?php echo ($cureTimes[5]); ?> disabled> 周三下午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="6" <?php echo ($cureTimes[6]); ?> disabled> 周四上午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="7" <?php echo ($cureTimes[7]); ?> disabled> 周四下午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="8" <?php echo ($cureTimes[8]); ?> disabled> 周五上午
                                    </label>
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="cureTime[]" value="9" <?php echo ($cureTimes[9]); ?> disabled> 周五下午
                                    </label>

                                </div>
                            </div>
                            <hr>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <?php if(($statuscode) == "0"): ?><button class="am-btn am-btn-primary "
                                                onclick='PassDoctor("<?php echo ($doctorId); ?>"); return false;'><span class="am-icon-pencil-square-o"></span> 审核通过
                                        </button>
                                        <button class="am-btn am-btn-default "
                                                onclick='CancelDoctor("<?php echo ($doctorId); ?>"); return false;'><span class="am-icon-pencil-square-o"></span>
                                            审核失败
                                        </button><?php endif; ?>

                                    <button class="am-btn am-btn-default "
                                            onclick="window.location.href='table'"><span class="am-icon-pencil-square-o"></span>
                                        返回
                                    </button>
                                    <!--<button type="button" class="am-btn am-btn-primary">保存修改</button>-->

                                </div>
                            </div>


                        </form>

                    </div>


                    <!--<div class="am-tab-panel am-fade" id="tab3">-->

                    <!--</div>-->
                </div>
            </div>

        </div>

        <div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
            <div class="am-modal-dialog">
                <div class="am-modal-hd"> 查询并确认医院地址</div>
                <div class="am-modal-bd">

                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-4 am-text-right">医院名称:</div>

                        <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                            <input id="text_" name="text_" type="text" class="am-input-sm"
                                   value="<?php echo ($data["hospital"]); ?>" />
                        </div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-4 am-text-right">位置:</div>
                        <div class="am-u-sm-4 am-u-md-4">
                            <input id="result_" name="result_" type="text" class="am-input-sm"
                            />
                        </div>
                        <div class="am-u-sm-4 am-u-md-4 am-u-end col-end">
                            <input type="button" value="查询" onclick="searchByStationName();"/>
                        </div>
                    </div>

                    <div id="container"
                         style="
                width: 500px;
                height: 390px;
                border: 1px solid gray;
                ">
                    </div>
                </div>
                <div class="am-modal-footer">
                    <span class="am-modal-btn">确定</span>
                </div>
            </div>
        </div>


        <footer class="admin-content-footer">
            <hr>
            <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
        </footer>
    </div>
    <!-- content end -->

</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu"
   data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<?php $portal_index_lastnews="1,2"; $portal_hot_articles="1,2"; $portal_last_post="1,2"; $tmpl=sp_get_theme_path(); $default_home_slides=array( array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/1.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/2.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/3.jpg", "slide_url"=>"", ), ); $CSS_URL = $tmpl."Public/hp/css/"; $IMG_URL = $tmpl."Public/hp/img/"; $JS_URL = $tmpl."Public/hp/js/"; $SITE_URL = $tmpl."Public/hp/js/"; $I_URL = $tmpl."Public/hp/i/"; $CITY_URL = $tmpl."Public/hp/js/"; $DATA_URL = $tmpl."Public/hp/data/"; ?>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="<?php echo ($JS_URL); ?>amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="<?php echo ($JS_URL); ?>jquery.min.js"></script>
<!--<![endif]-->
<script src="<?php echo ($JS_URL); ?>amazeui.min.js"></script>
<script src="<?php echo ($JS_URL); ?>app.js"></script>


<script>
    jQuery("#logout").on("click", function(){
        $.post("/index.php/Admin/Doctor/../Index/logout",function(data,textStatus){
            if(data['rescode'] == "00" && textStatus == "success"){
//                alert("您已经登出成功!")
//                alert(data['msg']);
                window.location.href = "<?php echo ($CONTROLLER); ?>/../Login/login";
            }
            else{
                alert(data['msg']);
            }
        });
    })
</script>

<script>
    function getDoctor(doctorId) {

        var url = '/index.php/Admin/Doctor/form?doctorId=24&amp;statuscode=0';

        $("<div></div>").load(url, {doctorId: doctorId}, function () {

            var data = $(this).find("#tablelist").html();
            $('#tablelist').html(data);


            $(this).remove();
        });
    }

    $("form input").prop("readonly", true);
    $("form textarea").prop("readonly", true);

    function PassDoctor(doctorId) {

        if (confirm("审核通过?")) {
            var x = $("#x").val();
            var y = $("#y").val();
            if(x == "" || y == ""){
                alert("请确认医院地址!");
                return;
            }
            else{
                $.post("/index.php/Admin/Doctor/passDoctor", {id: doctorId, x: x, y:y}, function (data, textStatus) {
                    window.location.href = "table";
                });
            }

        }
    }

    function CancelDoctor(doctorId) {

        if (confirm("审核失败?")) {
            $.post("/index.php/Admin/Doctor/cancelDoctor", {id: doctorId}, function (data, textStatus) {
                history.go(-1)
            });
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        loadImg();
    });
    var loadImg = function() {
        var imgs = document.querySelectorAll('img');
        for (var i = 0; i < imgs.length; i++) {
            if(imgs[i].getAttribute('img-src')){
                imgs[i].setAttribute('src',imgs[i].getAttribute('img-src'));
            }
            else{
                imgs[i].setAttribute('src',"<?php echo ($IMG_URL); ?>doctor.png");
            }
        }
    };
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>

<script type="text/javascript">
    var hospital = $("#hospital").val();
    var map = new BMap.Map("container");
    map.centerAndZoom(hospital, 12);
    map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
    map.addControl(new BMap.OverviewMapControl()); //添加默认缩略地图控件
    map.addControl(new BMap.OverviewMapControl({ isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT }));   //右下角，打开

    var localSearch = new BMap.LocalSearch(map);
    localSearch.enableAutoViewport(); //允许自动调节窗体大小
    function searchByStationName() {
        map.clearOverlays();//清空原来的标注
        var keyword = document.getElementById("text_").value;
        localSearch.setSearchCompleteCallback(function (searchResult) {
            var poi = searchResult.getPoi(0);
            document.getElementById("result_").value = poi.point.lng + "," + poi.point.lat;
            document.getElementById("x").value = poi.point.lng;
            document.getElementById("y").value = poi.point.lat;
            map.centerAndZoom(poi.point, 13);
            var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
            map.addOverlay(marker);
            var content = document.getElementById("text_").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
            var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
            marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
            // marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        });
        localSearch.search(keyword);
    }
</script>