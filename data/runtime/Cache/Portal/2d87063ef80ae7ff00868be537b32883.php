<?php if (!defined('THINK_PATH')) exit(); $portal_index_lastnews="1,2"; $portal_hot_articles="1,2"; $portal_last_post="1,2"; $tmpl=sp_get_theme_path(); $default_home_slides=array( array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/1.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/2.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/3.jpg", "slide_url"=>"", ), ); $CSS_URL = $tmpl."Public/hp/css/"; $IMG_URL = $tmpl."Public/hp/img/"; $JS_URL = $tmpl."Public/hp/js/"; $SITE_URL = $tmpl."Public/hp/js/"; $I_URL = $tmpl."Public/hp/i/"; $CITY_URL = $tmpl."Public/hp/js/"; $DATA_URL = $tmpl."Public/hp/data/"; ?>

<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>hp</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="<?php echo ($CSS_URL); ?>amazeui.min.css">
    <link rel="stylesheet" href="<?php echo ($CSS_URL); ?>app.css">


<!--<link rel="stylesheet" type="text/css" href="<?php echo ($CSS_URL); ?>planeui.css" />-->

    <!--<link href="<?php echo ($CSS_URL); ?>bootstrap.css" rel="stylesheet">-->
    <link href="<?php echo ($CSS_URL); ?>mbase.css" rel="stylesheet">



    <style>
        html, body {
            background-color: #EBEBEB;
        }

        .col-xs-4 {
            padding-left: 0;
            padding-right: 0;
        }
        .btn {
            padding: 26px 12px;
        }
        .col-xs-6 {
            padding-left: 0;
            padding-right: 0;
        }
        .sms {
            position: absolute;
            top: 13px;
            left: 32%
        }
        .first {
            margin: auto;
            height: 420px;
        }
    </style>
</head>
<body>
<!-- 导航	-->

<!-- Bootstrap 公众账号首页已经引入bootstrap.css-->
<!-- 新 Bootstrap 核心 CSS 文件 -->
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->


<script>
    var jQueryf = jQuery.noConflict();
    //mootools中嵌入jQuery 需要启动jQuery无冲突模式
</script>
<script type="text/javascript">

    (function() {

        isBind = false;
        //普通页面不需要验证
        ret =[];
        ret.resCode = "00";
        if(ret) {
            if(ret && ret.resCode == "00"){
                isBind = true;
            }
            else {
                Portal.error("请先app账号")
                //alert(window.location.pathname)
                window.location.href="/wechat/register/wbinduser.htm?userName=" + ret.openId + "&appUserName=" +appid + "&backPath="+window.location.pathname;
            }
        }

        //使用js原始方法，勿改
        var muObj = document.getElementById("index");
        if(muObj){
            muObj.className = "active";
        }

        /* 注销 */
        function doLogout(){
            DWR.call("userService.logout",function(){
                document.location.href="../index.htm"
            });
        }
    })(jQueryf)


</script>

<div id="preloader">
    <div class="pui-loading pui-loading-double-circle-rotation loder_status">
        <span></span>
        <span></span>
    </div>
</div>
<!-- Fixed navbar -->


    <div class="am-g doc-am-g" style="margin-top: 40%;">
    <!-- center-->
    <!--<center><h1>注册入口</h1></center>-->

<!--        <div class="col-xs-6" style="display: none">
            <a id="patient" href="#" type="button" style="background-color: #EBEBEB;border-color: #EBEBEB;" class="btn btn-default border_radius0 btn-block ">
                <i class="imooc-icon"><img src="<?php echo ($IMG_URL); ?>patient.png" width="96" height="96"></i>
                <p class="color_grey font_size18"> 公众</p>
            </a>
        </div>-->
        <div class="am-u-sm-12">
            <center>
                <i class="imooc-icon"><img src="<?php echo ($IMG_URL); ?>assistant.png" width="80" height="80"></i>
                <p class="color_grey font_size18"> 医生</p>

            </center>

        </div>

</div>

</br>
</br>
</br>
<div class="am-g doc-am-g">

            <form id="register" method="post" class="am-form" style="    margin-left: 40px;
    margin-right: 40px;">
                <div class="am-g">
                    <div class="am-u-lg-12">
                            <input type="number" id="phoneNumber" name="phoneNumber" class="am-form-field" maxlength="11" placeholder="手机号" style="border-radius:9px" pattern="[0-9]*">
                    </div>
                </div>

                <br>
                <div class="am-g">
                    <div class="am-u-sm-7">
                        <input name="msgCode" id="msgCode"  type="number"
                               class="am-form-field" maxlength="6"
                               placeholder="验证码" style="border-radius:9px" pattern="[0-9]*">
                    </div>
                    <div class="am-u-sm-5">
                        <button id="submit" class="am-btn am-btn-secondary" type="button" style="border-radius:9px; font-size:1.1em">获取验证码
                        </button>
                    </div>

                </div>

                <br>
                <div class="am-g">
                    <div class="am-u-sm-6 am-u-sm-centered">
                        <input type="submit" name="" value="登 录"
                               class="am-btn am-btn-primary am-fl"
                               onclick="login();return false;" style="
                                                                   width: 100%;;">
                    </div>
                </div>
            </form>


</div>



<!--[if (gte IE 9)|!(IE)]><!-->
<script src="<?php echo ($JS_URL); ?>jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="<?php echo ($JS_URL); ?>amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="<?php echo ($JS_URL); ?>amazeui.min.js"></script>

<!-- center -->
<!-- JavaScript-->
<script type="text/javascript">
    jQuery(window).load(function () { // makes sure the whole site is loaded
        jQuery("#status").fadeOut(); // will first fade out the loading animation
        jQuery("#preloader").delay(350).fadeOut("slow"); // will fade out the white DIV that covers the website.
    })

    jQueryf(document).ready(function(){
//        var openid = '<?php echo ($openid); ?>';
//        if(openid){
//            jQueryf("#patient").attr("href", "../Patient/login");
//            jQueryf("#assistant").attr("href", "../Doctor/register");
//        }
//        else{
//            alert('未能获取微信id')
//        }
    });


    function login() {

        if(!jQuery("#phoneNumber").val()){
            alert("手机号码不能为空!");
            return;
        }

        if (!$("#msgCode").val()) {
            alert("验证码不能为空!");
            return;
        }

        var tourl = $("#register").attr("action");
        var application = $("#register").serialize();

        $.post("/index.php/Portal/Join/../Patient/verifyCode",{to:$("#phoneNumber").val(), msgCode:$("#msgCode").val()},function(data,textStatus){
            if(data['rescode'] == "00" && textStatus == "success"){
                $.post("/index.php/Portal/Join/checkUser",{to:$("#phoneNumber").val()},function(data,textStatus){

                    if(data['rescode'] == "00" && textStatus == "success") {
                        window.location.href = "../Doctor/register?functionName=register&phoneNumber=" + $("#phoneNumber").val();
                    }
                    else if(data['rescode'] == "01" && textStatus == "success"){
                        window.location.href = "../Doctor/index";
                    }
                    else{
                        alert("登录出错, 请联系管理员!");
                    }
                });

            }
            else{
                alert(data['msg']);
            }
        });
    }

    jQuery(function(){

        //发送短信
        $("#submit").click(function(){
            if(!jQuery("#phoneNumber").val()){
                alert("手机号码不能为空!");
                return;
            }

            sendCode(this);
            var tourl = $("#form").attr("action");
            $.post("/index.php/Portal/Join/../Patient/send",{to:$("#phoneNumber").val()},function(data,textStatus){
//                alert(data);
            });
        })

        var wait = 60;
        function sendCode(o) {
            if (wait == 0) {
                o.removeAttribute("disabled");
                o.innerHTML = "获取验证码";
                wait = 60;
            } else {
                o.setAttribute("disabled", true);
                o.innerHTML = "重新发送(" + wait + ")";
                wait--;
                setTimeout(function () {
                            sendCode(o)
                        },
                        1000)
            }
        }
    })
</script>
</body>
</html>