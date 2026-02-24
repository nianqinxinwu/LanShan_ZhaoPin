<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:94:"/www/wwwroot/zhaopin.zzlanshan.site/public/../application/admin/view/zpwxsys/company/edit.html";i:1765419789;s:78:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/layout/default.html";i:1746006160;s:75:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/meta.html";i:1746006160;s:77:"/www/wwwroot/zhaopin.zzlanshan.site/application/admin/view/common/script.html";i:1746006160;}*/ ?>
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
            <input id="c-sort" data-rule="required" class="form-control" name="row[sort]" value="<?php echo htmlentities($row['sort'] ?? ''); ?>" type="number">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('cityname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="city form-control" name="row[cityid]"  id="selectCity"  >

                <option value="0">请选择城市</option>
                <?php if(is_array($citylist) || $citylist instanceof \think\Collection || $citylist instanceof \think\Paginator): if( count($citylist)==0 ) : echo "" ;else: foreach($citylist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>" <?php if($row['cityid'] == $v['id']): ?>selected<?php endif; ?> ><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('areaname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="area form-control" name="row[areaid]"  id="areaid"   >

                <option value="0">请选择区域</option>
                <?php if(is_array($arealist) || $arealist instanceof \think\Collection || $arealist instanceof \think\Paginator): if( count($arealist)==0 ) : echo "" ;else: foreach($arealist as $key=>$v): ?>
                <option value="<?php echo htmlentities($v['id'] ?? ''); ?>" <?php if($row['areaid'] == $v['id']): ?>selected<?php endif; ?> ><?php echo htmlentities($v['name'] ?? ''); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </select>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('companyname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-companyname" data-rule="required" class="form-control" name="row[companyname]" type="text" value="<?php echo htmlentities($row['companyname'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('shortname'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-shortname" data-rule="required" class="form-control" name="row[shortname]" type="text" value="<?php echo htmlentities($row['shortname'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('companycate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-companycate" data-rule="required" class="form-control" name="row[companycate]" type="text" value="<?php echo htmlentities($row['companycate'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('companytype'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            
            
               <select class="companytype form-control" name="row[companytype]"     >
                <option value="" >请选择企业性质</option>
                <option value="私营" <?php if($row['companytype'] == '私营'): ?>selected<?php endif; ?> >私营</option>
                <option value="国有" <?php if($row['companytype'] == '国有'): ?>selected<?php endif; ?> >国有</option>
                <option value="政府机关" <?php if($row['companytype'] == '政府机关'): ?>selected<?php endif; ?> >政府机关</option>
                <option value="事业单位" <?php if($row['companytype'] == '事业单位'): ?>selected<?php endif; ?> >事业单位</option>
                <option value="股份制" <?php if($row['companytype'] == '股份制'): ?>selected<?php endif; ?> >股份制</option>
                <option value="上市公司" <?php if($row['companytype'] == '上市公司'): ?>selected<?php endif; ?> >上市公司</option>
                <option value="中外合资/合作" <?php if($row['companytype'] == '中外合资/合作'): ?>selected<?php endif; ?> >中外合资/合作</option>
                <option value="外商独资/办事处" <?php if($row['companytype'] == '外商独资/办事处'): ?>selected<?php endif; ?> >外商独资/办事处</option>
                <option value="非盈利机构" <?php if($row['companytype'] == '非盈利机构'): ?>selected<?php endif; ?> >非盈利机构</option>
            </select>
         
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('companyworker'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select class="companyworker form-control" name="row[companyworker]"     >
                <option value="" >请选择人员规模</option>
                <option value="1-5人" <?php if($row['companyworker'] == '1-5人'): ?>selected<?php endif; ?> >1-5人</option>
                <option value="5-10人" <?php if($row['companyworker'] == '5-10人'): ?>selected<?php endif; ?> >5-10人</option>
                <option value="10-20人" <?php if($row['companyworker'] == '10-20人'): ?>selected<?php endif; ?> >10-20人</option>
                <option value="20-50人"  <?php if($row['companyworker'] == '20-50人'): ?>selected<?php endif; ?>>20-50人</option>
                <option value="50人以上" <?php if($row['companyworker'] == '50人以上'): ?>selected<?php endif; ?>>50人以上</option>

            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('mastername'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-mastername" data-rule="required" class="form-control" name="row[mastername]" type="text" value="<?php echo htmlentities($row['mastername'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('tel'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-tel" data-rule="required" class="form-control" name="row[tel]" type="text"  value="<?php echo htmlentities($row['tel'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('weixin'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-weixin" data-rule="required" class="form-control" name="row[weixin]" type="text"  value="<?php echo htmlentities($row['weixin'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('address'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-address" data-rule="required" class="form-control" name="row[address]" type="text"  value="<?php echo htmlentities($row['address'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">

        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('lat'); ?>:</label>

        <div class="col-xs-12 col-sm-8">


            <div class="form-inline">
                <input id="c-lng" class="form-control" name="row[lng]" type="text"  value="<?php echo htmlentities($row['lng'] ?? ''); ?>">

                <input id="c-lat" class="form-control" name="row[lat]" type="text"  value="<?php echo htmlentities($row['lat'] ?? ''); ?>" >

                <a href="javascript:" data-lat-id="c-lat" data-lng-id = "c-lng" data-toggle="addresspicker" class="btn btn-primary btn-select-link" title="获取位置" style="margin-left:3px;"><i class="fa fa-link"></i> 获取位置</a>


            </div>


        </div>



    </div>


    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"  ><?php echo __('thumb'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" data-rule="" class="form-control" size="50" name="row[thumb]"  value="<?php echo htmlentities($row['thumb'] ?? ''); ?>" type="text">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp,image/webp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-image"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div>


    <div class="form-group" data-field="images">
        <label for="c-image" class="control-label col-xs-12 col-sm-2"><?php echo __('companyimg'); ?></label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-images" class="form-control" size="50" name="row[companyimg]" type="text"  value="<?php echo htmlentities($row['companyimg'] ?? ''); ?>" placeholder="组图可以直接从正文进行提取,可以为空">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-images" class="btn btn-danger plupload" data-input-id="c-images" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp,image/webp" data-multiple="true" data-preview-id="p-images"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-images" class="btn btn-primary fachoose" data-input-id="c-images" data-mimetype="image/*" data-multiple="true"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-images"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-images"></ul>
        </div>
    </div>


    <div class="form-group" data-field="images">
        <label for="c-images2" class="control-label col-xs-12 col-sm-2"><?php echo __('cardimg'); ?></label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-images2" class="form-control" size="50" name="row[cardimg]" type="text"  value="<?php echo htmlentities($row['cardimg'] ?? ''); ?>" placeholder="组图可以直接从正文进行提取,可以为空">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-images2" class="btn btn-danger plupload" data-input-id="c-images2" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp,image/webp" data-multiple="true" data-preview-id="p-images2"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                    <span><button type="button" id="fachoose-images2" class="btn btn-primary fachoose" data-input-id="c-images2" data-mimetype="image/*" data-multiple="true"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                </div>
                <span class="msg-box n-right" for="c-images2"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-images2"></ul>
        </div>
    </div>



    <div class="form-group" data-field="description">
        <label for="c-description" class="control-label col-xs-12 col-sm-2"><?php echo __('content'); ?></label>
        <div class="col-xs-12 col-sm-8">

            <textarea id="c-content" class="form-control editor" name="row[content]" cols="30" rows="10" ><?php echo htmlentities($row['content'] ?? ''); ?></textarea>

        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('isrecommand'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[isrecommand]-normal"><input  name="row[isrecommand]" type="radio" value="1" <?php echo $row['isrecommand']==1?'checked' :''; ?>> 启用</label>
                <label for="row[isrecommand]-hidden"><input  name="row[isrecommand]" type="radio" value="0" <?php echo $row['isrecommand']==0?'checked' :''; ?>> 禁用</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2" ><?php echo __('status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
                <label for="row[status]-normal"><input  name="row[status]" type="radio" value="1" <?php echo $row['status']==1?'checked' :''; ?>> 启用</label>
                <label for="row[status]-hidden"><input  name="row[status]" type="radio" value="0" <?php echo $row['status']==0?'checked' :''; ?>> 禁用</label>
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
