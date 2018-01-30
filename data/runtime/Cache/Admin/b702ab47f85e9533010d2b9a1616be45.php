<?php if (!defined('THINK_PATH')) exit(); $portal_index_lastnews="1,2"; $portal_hot_articles="1,2"; $portal_last_post="1,2"; $tmpl=sp_get_theme_path(); $default_home_slides=array( array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/1.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/2.jpg", "slide_url"=>"", ), array( "slide_name"=>"ThinkCMFX2.2.0发布啦！", "slide_pic"=>$tmpl."Public/assets/images/demo/3.jpg", "slide_url"=>"", ), ); $CSS_URL = $tmpl."Public/hp/css/"; $IMG_URL = $tmpl."Public/hp/img/"; $JS_URL = $tmpl."Public/hp/js/"; $SITE_URL = $tmpl."Public/hp/js/"; $I_URL = $tmpl."Public/hp/i/"; $CITY_URL = $tmpl."Public/hp/js/"; $DATA_URL = $tmpl."Public/hp/data/"; ?>

<link rel="stylesheet" href="<?php echo ($CSS_URL); ?>amazeui.min.css"/>
<link rel="stylesheet" href="<?php echo ($CSS_URL); ?>admin.css">


<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->


<div class="am-cf admin-main">

  <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">医生列表</strong> / <small>list</small></div>
      </div>

      <hr>

      <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
          <div class="am-btn-toolbar">
            <div class="am-btn-group am-btn-group-xs">
            </div>
          </div>
        </div>
        <div class="am-u-sm-12 am-u-md-3">
        </div>
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-input-group am-input-group-sm">
            <input type="text" class="am-form-field" id="queryStr">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" onclick="Jumppage()">搜索</button>
          </span>
          </div>
        </div>
      </div>

      <div class="am-g">
        <div class="am-u-sm-12">
          <form class="am-form">
            <div id="tablelist">

            <table class="am-table am-table-striped am-table-hover table-main">
              <thead>
              <tr>
                <th class="table-check"><input type="checkbox" id="inputChooseAll"/></th>
                <th class="table-id">ID</th>
                <th class="table-title">名字</th>
                <th class="table-type">性别</th>
                <th class="table-author am-hide-sm-only">省份</th>
                <th class="table-author am-hide-sm-only">积分</th>
                <th class="table-date am-hide-sm-only">修改日期</th>
                <th class="table-date am-hide-sm-only">状态</th>
                <th class="table-set" style="display: none">操作</th>
              </tr>
              </thead>
              <tbody>
              <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$doctor): $mod = ($i % 2 );++$i;?><tr>
                <td><input type="checkbox" value="<?php echo ($doctor["id"]); ?>"/></td>
                <td><?php echo ($doctor["id"]); ?></td>
                <td><a href='form?doctorId=<?php echo ($doctor["id"]); ?>&statuscode=<?php echo ($doctor["statuscode"]); ?>'><?php echo ($doctor["realname"]); ?></a></td>
                <?php if(($doctor["sex"] == 0)): ?><td>男</td>
                  <?php elseif(($doctor["sex"] == 1)): ?>
                  <td>女</td>
                  <?php else: ?>
                  <td>未知</td><?php endif; ?>
                <td class="am-hide-sm-only"><?php echo ($doctor["province"]); ?></td>
                <td class="am-hide-sm-only"><?php echo ($doctor["score"]); ?></td>
                <td class="am-hide-sm-only"><?php echo ($doctor["createtime"]); ?></td>
                <td class="am-hide-sm-only"><?php echo ($doctor["status"]); ?></td>
                <td style="display: none">
                  <div class="am-btn-toolbar">
                    <div class="am-btn-group am-btn-group-xs">
                      <?php if(($doctor["statuscode"]) == "0"): ?><button class="am-btn am-btn-default am-btn-xs am-text-secondary"
                                onclick='PassDoctor("<?php echo ($doctor["id"]); ?>")'><span class="am-icon-pencil-square-o"></span> 审核通过
                        </button>
                        <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"
                                onclick='CancelDoctor("<?php echo ($doctor["id"]); ?>")'><span class="am-icon-pencil-square-o"></span>
                          审核失败
                        </button>
                        <?php else: ?>
                        已处理<?php endif; ?>

                    </div>
                  </div>
                </td>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>

              </tbody>
            </table>
              </div>

            <div class="am-cf" id="tableFooter">
              共 <?php echo ($recordnum); ?> 条记录
              <div class="am-fr">
                <div id="pages">

                <ul class="am-pagination">
                  <li class="am-disabled"><a href="#">«</a></li>
                    <?php $__FOR_START_557684802__=1;$__FOR_END_557684802__=$pagenum+1;for($i=$__FOR_START_557684802__;$i < $__FOR_END_557684802__;$i+=1){ if(($i == 1)): ?><li class="am-active"><a onclick="Jumppage(this);" href="#"><?php echo ($i); ?></a></li>
                        <?php else: ?>
                        <li><a onclick="Jumppage(this);" href="#"><?php echo ($i); ?></a></li><?php endif; } ?>

                  <!--<li><a href="#">2</a></li>-->
                  <!--<li><a href="#">3</a></li>-->
                  <!--<li><a href="#">4</a></li>-->
                  <!--<li><a href="#">5</a></li>-->
                  <li><a href="#">»</a></li>
                </ul>
                  </div>
              </div>
            </div>
            <hr />
            <p>注：.....</p>
          </form>
        </div>

      </div>
    </div>

    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
    </footer>

    <!-- 页码 -->
    <!--<div id="pages">-->
      <!--<?php $__FOR_START_1389280795__=1;$__FOR_END_1389280795__=$pagenum;for($i=$__FOR_START_1389280795__;$i < $__FOR_END_1389280795__;$i+=1){ ?><span><a-->
              <!--onclick="Jumppage(<?php echo ($i); ?>);" href="#"><?php echo ($i); ?></a></span><?php } ?>-->
    <!--</div>-->
    <!-- 内容 -->
    <!--<div id="tablelist">-->
      <!--<table border=1>-->
        <!--<tr>-->
          <!--<td>uid</td>-->
          <!--<td>名称</td>-->
        <!--</tr>-->
        <!--<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$patient): $mod = ($i % 2 );++$i;?>-->
          <!--<tr>-->
            <!--<td><?php echo ($patient["id"]); ?></td>-->
            <!--<td><?php echo ($patient["id"]); ?></td>-->
          <!--</tr>-->
        <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
      <!--</table>-->
    <!--</div>-->

  </div>
  <!-- content end -->
</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

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
  function Jumppage(page){
    var pagesize = 10;
    var pageNum = 1;
    var url = '/index.php/Admin/Doctor/table';
    var queryStr = $('#queryStr').val()
    if(page)  pageNum = page.innerHTML;


    jQuery(".am-active").removeClass("am-active");
    jQuery(page).parent("li").addClass("am-active");

    $("<div></div>").load(url,{queryStr:queryStr,page:pageNum,pagesize:pagesize},function(){

      var data = $(this).find("#tablelist").html();
      $('#tablelist').html(data);

      if(!page){
        var tableFooter = $(this).find("#tableFooter").html();
        $('#tableFooter').html(tableFooter);
      }

      $(this).remove();
    });
  }

  function PassDoctor(doctorId) {

    if (confirm("审核通过?")) {
      $.post("/index.php/Admin/Doctor/passDoctor", {id: doctorId}, function (data, textStatus) {
        Jumppage();
      });
    }
  }

  function CancelDoctor(doctorId) {

    if (confirm("审核失败?")) {
      $.post("/index.php/Admin/Doctor/cancelDoctor", {id: doctorId}, function (data, textStatus) {
//      alert(data);
        Jumppage();
      });
    }
  }

//  jQuery("#inputChooseAll").on("click", function(){
//    jQuery("form input")
//  })
</script>