<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:93:"/www/wwwroot/zhaopin.zzlanshan.site/public/../application/admin/view/zpwxsys/active/edit.html";i:1765419789;s:78:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/layout/default.html";i:1746006160;s:75:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/meta.html";i:1746006160;s:77:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/script.html";i:1746006160;}*/ ?>
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
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('sort'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sort" data-rule="required" class="form-control"  name="row[sort]" type="number" value="<?php echo htmlentities($row['sort'] ?? ''); ?>" >
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" data-rule="required" class="form-control" name="row[title]" type="text" value="<?php echo htmlentities($row['title'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('mainwork'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-mainwork" data-rule="required"  class="form-control" name="row[mainwork]" type="text" value="<?php echo htmlentities($row['mainwork'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('begintime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-begintime" data-rule="required" data-date-format="YYYY-MM-DD" class="form-control datetimepicker" name="row[begintime]" type="text" value="<?php echo htmlentities($row['begintime'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('endtime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-endtime" data-rule="required" data-date-format="YYYY-MM-DD" class="form-control datetimepicker" name="row[endtime]" type="text" value="<?php echo htmlentities($row['endtime'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"  ><?php echo __('thumb'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" data-rule="" class="form-control" size="50" name="row[thumb]" type="text" value="<?php echo htmlentities($row['thumb'] ?? ''); ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp,image/webp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-image"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div>





    <div class="form-group" data-field="description">
        <label for="c-description" class="control-label col-xs-12 col-sm-2"><?php echo __('content'); ?></label>
        <div class="col-xs-12 col-sm-8">

            <textarea id="c-content" class="form-control editor" name="row[content]" cols="30" rows="10" ><?php echo htmlentities($row['content'] ?? ''); ?></textarea>

        </div>
    </div>




    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[status]-normal"><input id="row[status]-normal" name="row[status]" type="radio" value="1" <?php echo $row['status']==1?'checked' :''; ?>> 启用</label>
                <label for="row[status]-hidden"><input id="row[status]-hidden" name="row[status]" type="radio" value="0" <?php echo $row['status']==0?'checked' :''; ?>> 禁用</label>
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
