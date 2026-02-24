<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:98:"/www/wwwroot/zhaopin.zzlanshan.site/public/../application/admin/view/zpwxsys/companyrole/edit.html";i:1768209970;s:78:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/layout/default.html";i:1746006160;s:75:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/meta.html";i:1746006160;s:77:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/script.html";i:1746006160;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="referrer" content="never">
<meta name="robots" content="noindex, nofollow">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo htmlentities(\think\Config::get('site.version') ?? ''); ?>" rel="stylesheet">

<?php if(\think\Config::get('fastadmin.adminskin')): ?>
<link href="/assets/css/skins/<?php echo htmlentities(\think\Config::get('fastadmin.adminskin') ?? ''); ?>.css?v=<?php echo htmlentities(\think\Config::get('site.version') ?? ''); ?>" rel="stylesheet">
<?php endif; ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config ?? ''); ?>
    };
</script>

    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav') && \think\Config::get('fastadmin.breadcrumb')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <?php if($auth->check('dashboard')): ?>
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                    <?php endif; ?>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo htmlentities($vo['url'] ?? ''); ?>"><?php echo htmlentities($vo['title'] ?? ''); ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">



    <input type="hidden" value="<?php echo htmlentities($row['id'] ?? ''); ?>" name="row[id]"/>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('sort'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sort" data-rule="required" class="form-control" name="row[sort]" value="<?php echo htmlentities($row['sort'] ?? ''); ?>" type="number">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" data-rule="required" class="form-control" name="row[title]" value="<?php echo htmlentities($row['title'] ?? ''); ?>" type="text">
        </div>
    </div>




    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('money'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-money" data-rule="required" class="form-control" name="row[money]" value="<?php echo htmlentities($row['money'] ?? ''); ?>" type="text">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('jobnum'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-jobnum" data-rule="required" class="form-control" name="row[jobnum]" value="<?php echo htmlentities($row['jobnum'] ?? ''); ?>" type="number">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('notenum'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-notenum" data-rule="required" class="form-control" name="row[notenum]" value="<?php echo htmlentities($row['notenum'] ?? ''); ?>" type="number">
        </div>
    </div>

    
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('topnum'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-topnum" data-rule="required" class="form-control" name="row[topnum]" value="<?php echo htmlentities($row['topnum'] ?? ''); ?>" type="number">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('days'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-days" data-rule="required" class="form-control" name="row[days]" value="<?php echo htmlentities($row['days'] ?? ''); ?>" type="number">
        </div>
    </div>




    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('isinit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[isinit]-normal"><input  name="row[isinit]" type="radio" value="1" <?php echo $row['isinit']==1?'checked' :''; ?>> 是</label>
                <label for="row[isinit]-hidden"><input  name="row[isinit]" type="radio" value="0" <?php echo $row['isinit']==0?'checked' :''; ?>> 否</label>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" data-toggle="addresspicker"><?php echo __('status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[status]-normal"><input  name="row[enabled]" type="radio" value="1" <?php echo $row['enabled']==1?'checked' :''; ?>> 启用</label>
                <label for="row[status]-hidden"><input  name="row[enabled]" type="radio" value="0" <?php echo $row['enabled']==0?'checked' :''; ?>> 禁用</label>
            </div>
        </div>
    </div>






    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require.min.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version'] ?? ''); ?>"></script>

    </body>
</html>
