<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"/www/wwwroot/zhaopin.zzlanshan.site/public/../application/admin/view/zpwxsys/note/edit.html";i:1765419789;s:78:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/layout/default.html";i:1746006160;s:75:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/meta.html";i:1746006160;s:77:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/script.html";i:1746006160;}*/ ?>
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
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('cityid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="city form-control" name="row[cityid]"  id="selectCity"  >

                <option value="0">请选择城市</option>
                <?php if(is_array($citylist) || $citylist instanceof \think\Collection || $citylist instanceof \think\Paginator): if( count($citylist)==0 ) : echo "" ;else: foreach($citylist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>"  <?php if($row['cityid'] == $v['id']): ?>selected<?php endif; ?>><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('areaid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="area form-control" name="row[areaid]"  id="areaid"  >
                <?php if(is_array($arealist) || $arealist instanceof \think\Collection || $arealist instanceof \think\Paginator): if( count($arealist)==0 ) : echo "" ;else: foreach($arealist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>" <?php if($row['areaid'] == $v['id']): ?>selected<?php endif; ?> ><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </select>
        </div>
    </div>


    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"  ><?php echo __('avatarUrl'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" data-rule="" class="form-control" size="50" name="row[avatarUrl]" type="text" value="<?php echo htmlentities($row['avatarUrl'] ?? ''); ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp,image/webp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-image"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" data-rule="required" class="form-control" name="row[name]" type="text" value="<?php echo htmlentities($row['name'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('jobtitle'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-jobtitle" data-rule="required" class="form-control" name="row[jobtitle]" type="text" value="<?php echo htmlentities($row['jobtitle'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('sex'); ?>:</label>
        <div class="col-xs-12 col-sm-8">


            <div class="radio">
                <label for="row[sex]-normal"><input  name="row[sex]" type="radio" value="1" checked=""> 男</label>
                <label for="row[sex]-hidden"><input  name="row[sex]" type="radio" value="0"> 女</label>


            </div>


        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('birthday'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="birthday form-control" name="row[birthday]"    >

                <option value="" >请选择出生年份</option>
                <?php if(is_array($birthdaylist) || $birthdaylist instanceof \think\Collection || $birthdaylist instanceof \think\Paginator): if( count($birthdaylist)==0 ) : echo "" ;else: foreach($birthdaylist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v ?? ''); ?>" <?php if($row['birthday'] == $v): ?>selected<?php endif; ?> ><?php echo htmlentities($v ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('education'); ?>:</label>
        <div class="col-xs-12 col-sm-8">

            <select class="education form-control" name="row[education]"     >
                <option value="" >请选择学历要求</option>
                <option value="不限" <?php if($row['education'] == '不限'): ?>selected<?php endif; ?> >不限</option>
                <option value="初中以上" <?php if($row['education'] == '初中以上'): ?>selected<?php endif; ?> >初中以上</option>
                <option value="高中以上" <?php if($row['education'] == '高中以上'): ?>selected<?php endif; ?> >高中以上</option>
                <option value="中技以上" <?php if($row['education'] == '中技以上'): ?>selected<?php endif; ?> >中技以上</option>
                <option value="中专以上" <?php if($row['education'] == '中专以上'): ?>selected<?php endif; ?> >中专以上</option>
                <option value="大专以上" <?php if($row['education'] == '大专以上'): ?>selected<?php endif; ?> >大专以上</option>
                <option value="本科以上" <?php if($row['education'] == '本科以上'): ?>selected<?php endif; ?> >本科以上</option>
                <option value="硕士以上" <?php if($row['education'] == '硕士以上'): ?>selected<?php endif; ?> >硕士以上</option>
                <option value="博士以上" <?php if($row['education'] == '博士以上'): ?>selected<?php endif; ?> >博士以上</option>
                <option value="博后" <?php if($row['education'] == '博后'): ?>selected<?php endif; ?> >博后</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('express'); ?>:</label>
        <div class="col-xs-12 col-sm-8">

            <select class="express form-control" name="row[express]"     >
                <option value="" >请选择工作经验</option>
                <option value="无经验" <?php if($row['express'] == '无经验'): ?>selected<?php endif; ?> >无经验</option>
                <option value="1年以下" <?php if($row['express'] == '1年以下'): ?>selected<?php endif; ?> >1年以下</option>
                <option value="1-3年" <?php if($row['express'] == '1-3年'): ?>selected<?php endif; ?> >1-3年</option>
                <option value="3-5年" <?php if($row['express'] == '3-5年'): ?>selected<?php endif; ?> >3-5年</option>
                <option value="5-10年" <?php if($row['express'] == '5-10年'): ?>selected<?php endif; ?> >5-10年</option>
                <option value="10年以上" <?php if($row['express'] == '10年以上'): ?>selected<?php endif; ?> >10年以上</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('address'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-address" data-rule="required" class="form-control" name="row[address]" value="<?php echo htmlentities($row['address'] ?? ''); ?>" type="text">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('tel'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-tel" data-rule="required" class="form-control" name="row[tel]" value="<?php echo htmlentities($row['tel'] ?? ''); ?>" type="text">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('current'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="type form-control" name="row[currentstatus]"    >

                <option value="0">请选择目前状态</option>
                <?php if(is_array($currentlist) || $currentlist instanceof \think\Collection || $currentlist instanceof \think\Paginator): if( count($currentlist)==0 ) : echo "" ;else: foreach($currentlist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['name'] ?? ''); ?>" <?php if($row['currentstatus'] == $v['name']): ?>selected<?php endif; ?>><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('worktypeid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="worktypeid form-control" name="row[worktypeid]"    >

                <option value="0">请选择工作性质</option>
                <?php if(is_array($worktypelist) || $worktypelist instanceof \think\Collection || $worktypelist instanceof \think\Paginator): if( count($worktypelist)==0 ) : echo "" ;else: foreach($worktypelist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>" <?php if($row['worktypeid'] == $v['id']): ?>selected<?php endif; ?> ><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>




    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('jobcateid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">

            <select class="jobcateid  form-control" name="row[jobcateid]"    >

                <option value="0">请选择行业</option>
                <?php if(is_array($jobcatelist) || $jobcatelist instanceof \think\Collection || $jobcatelist instanceof \think\Paginator): if( count($jobcatelist)==0 ) : echo "" ;else: foreach($jobcatelist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>"  <?php if($row['jobcateid'] == $v['id']): ?>selected<?php endif; ?>  ><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>

        </div>
    </div>







    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('money'); ?>:</label>
        <div class="col-xs-12 col-sm-8">

            <select class="type form-control" name="row[jobpriceid]"    >

                <option value="0">请选择薪资待遇</option>
                <?php if(is_array($jobpricelist) || $jobpricelist instanceof \think\Collection || $jobpricelist instanceof \think\Paginator): if( count($jobpricelist)==0 ) : echo "" ;else: foreach($jobpricelist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>" <?php if($row['jobpriceid'] == $v['id']): ?>selected<?php endif; ?> ><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>




        </div>
    </div>
















    <div class="form-group" data-field="description">
        <label for="c-description" class="control-label col-xs-12 col-sm-2"><?php echo __('content'); ?></label>
        <div class="col-xs-12 col-sm-8">

            <textarea id="c-content" class="form-control editor" name="row[content]" cols="30" rows="10" ><?php echo htmlentities($row['content'] ?? ''); ?></textarea>

        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('enabled'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[status]]-normal"><input  name="row[status]" type="radio" value="1"  <?php echo $row['status']==1?'checked' :''; ?>> 启用</label>
                <label for="row[status]]-hidden"><input  name="row[status]]" type="radio" value="0"  <?php echo $row['status']==0?'checked' :''; ?>> 禁用</label>
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
