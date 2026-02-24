define([], function () {
    require([], function () {
    //绑定data-toggle=addresspicker属性点击事件

    $(document).on('click', "[data-toggle='addresspicker']", function () {
        var that = this;
        var callback = $(that).data('callback');
        var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
        var lat_id = $(that).data("lat-id") ? $(that).data("lat-id") : "";
        var lng_id = $(that).data("lng-id") ? $(that).data("lng-id") : "";
        var zoom_id = $(that).data("zoom-id") ? $(that).data("zoom-id") : "";
        var lat = lat_id ? $("#" + lat_id).val() : '';
        var lng = lng_id ? $("#" + lng_id).val() : '';
        var zoom = zoom_id ? $("#" + zoom_id).val() : '';
        var url = "/addons/address/index/select";
        url += (lat && lng) ? '?lat=' + lat + '&lng=' + lng + (input_id ? "&address=" + $("#" + input_id).val() : "") + (zoom ? "&zoom=" + zoom : "") : '';
        Fast.api.open(url, '位置选择', {
            callback: function (res) {
                input_id && $("#" + input_id).val(res.address).trigger("change");
                lat_id && $("#" + lat_id).val(res.lat).trigger("change");
                lng_id && $("#" + lng_id).val(res.lng).trigger("change");
                zoom_id && $("#" + zoom_id).val(res.zoom).trigger("change");

                try {
                    //执行回调函数
                    if (typeof callback === 'function') {
                        callback.call(that, res);
                    }
                } catch (e) {

                }
            }
        });
    });
});

require.config({
    paths: {
    	'csmsignin_xcore': '../addons/csmsignin/library/xcore/js/xcore',
        'csmsignin_csminputstyle': '../addons/csmsignin/library/xcore/js/csminputstyle',
        'jquery.simple-color': '../addons/csmsignin/library/xcore/js/jquery.simple-color',
    },
    shim: {
        'csmsignin_xcore': {
            deps: ["css!../addons/csmsignin/library/xcore/css/xcore.css"]
        },
        'csmsignin_csminputstyle': {
            deps: ["css!../addons/csmsignin/library/xcore/css/csminputstyle.css"]
        },
    }
});
//判断系统深色模式变化，修改切换按钮
var matchMedia = window.matchMedia(('(prefers-color-scheme: dark)'));
matchMedia.addEventListener('change', function () {
    var mode = this.matches ? 'dark' : 'light';
    //只有当cookie中无手动定义值时才进行操作
    if (document.cookie.indexOf("thememode=") === -1 && Config.darktheme.mode === 'auto') {
        $("body").toggleClass("darktheme", mode === "dark");
    }
});

if (typeof Config.darktheme !== 'undefined' && Config.darktheme.switchbtn) {

    // 切换模式
    var switchMode = function (mode) {
        // 获取当前深色模式
        if (mode === 'auto') {
            var isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            mode = isDarkMode ? 'dark' : 'light';
        }

        if (mode === 'auto') {
        } else if (mode === 'dark') {
            $("body").addClass("darktheme");
            $(".darktheme-link").removeAttr("media");
        } else {
            $("body").removeClass("darktheme");
            $(".darktheme-link").attr("media", "(prefers-color-scheme: dark)");
        }
    };

    // 创建Cookie
    var createCookie = function (name, value) {
        var date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
        var url = Config.moduleurl.replace(location.origin, "");
        var path = url ? url.substring(url.lastIndexOf("/")) : "/";
        document.cookie = encodeURIComponent(Config.cookie.prefix + name) + "=" + encodeURIComponent(value) + "; path=" + path + "; expires=" + date.toGMTString();
    };

    if (Config.controllername === 'index' && Config.actionname === 'index') {
        var mode = Config.darktheme.mode;
        if (mode === 'auto') {
            var isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            mode = isDarkMode ? 'dark' : 'light';
        }
        var html = '<li class="theme-li">' +
            '<button type="button" title="切换' + (mode === 'dark' ? '浅色' : '深色') + '模式" data-mode="' + (mode === 'dark' ? 'light' : 'dark') + '" class="theme-toggle">' +
            '<svg class="sun-and-moon" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24">\n' +
            '      <circle class="sun" cx="12" cy="12" r="6" mask="url(#moon-mask)" fill="currentColor" />\n' +
            '      <g class="sun-beams" stroke="currentColor">\n' +
            '        <line x1="12" y1="1" x2="12" y2="3" />\n' +
            '        <line x1="12" y1="21" x2="12" y2="23" />\n' +
            '        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />\n' +
            '        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />\n' +
            '        <line x1="1" y1="12" x2="3" y2="12" />\n' +
            '        <line x1="21" y1="12" x2="23" y2="12" />\n' +
            '        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />\n' +
            '        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />\n' +
            '      </g>\n' +
            '      <mask class="moon" id="moon-mask">\n' +
            '        <rect x="0" y="0" width="100%" height="100%" fill="white" />\n' +
            '        <circle cx="24" cy="10" r="6" fill="black" />\n' +
            '      </mask>\n' +
            '    </svg>' +
            '</button>' +
            '</li>';
        $(html).prependTo("#firstnav > div > ul");

        //点击切换按钮
        $(document).on("click", ".theme-toggle", function () {
            var mode = $(this).attr("data-mode");
            switchMode(mode);
            createCookie("thememode", mode);
            $("iframe").each(function () {
                try {
                    $(this)[0].contentWindow.$("body").trigger("swithmode", [mode]);
                } catch (e) {

                }
            });
            $(this).attr("data-mode", mode === 'dark' ? 'light' : 'dark').attr("title", '切换' + (mode === 'dark' ? '浅色' : '深色') + '模式');
        });

        //判断系统深色模式变化，修改切换按钮
        var matchMedia = window.matchMedia(('(prefers-color-scheme: dark)'));
        matchMedia.addEventListener('change', function () {
            var mode = this.matches ? 'dark' : 'light';
            //只有当cookie中无手动定义值时才切换
            if (document.cookie.indexOf("thememode=") === -1 && Config.darktheme.mode === 'auto') {
                $(".theme-toggle").attr("data-mode", mode === 'dark' ? 'light' : 'dark').attr("title", '切换' + (mode === 'dark' ? '浅色' : '深色') + '模式');
            }
        });
    } else {
        //添加事件
        $("body").on("swithmode", function (e, mode) {
            switchMode(mode);
            $("iframe").each(function () {
                try {
                    $(this)[0].contentWindow.$("body").trigger("swithmode", [mode]);
                } catch (e) {

                }
            });
        });
    }
}
require.config({
    paths: {
        'simditor': '../addons/simditor/js/simditor',
        'simple-module': '../addons/simditor/js/module',
        'simple-hotkeys': '../addons/simditor/js/hotkeys',
        'simple-uploader': '../addons/simditor/js/uploader',
        'dompurify': '../addons/simditor/js/dompurify',
    },
    shim: {
        'simditor': [
            'css!../addons/simditor/css/simditor.min.css',
        ]
    }
});
require(['form'], function (Form) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        if ($(Config.simditor.classname || '.editor', form).length > 0) {
            //修改上传的接口调用
            require(['upload', 'simditor', 'dompurify'], function (Upload, Simditor, DOMPurify) {
                var editor, mobileToolbar, toolbar;
                Simditor.locale = 'zh-CN';
                Simditor.list = {};
                toolbar = ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|', 'link', 'image', 'hr', '|', 'indent', 'outdent', 'alignment'];
                mobileToolbar = ["bold", "underline", "strikethrough", "color", "ul", "ol"];

                // 添加 hook 过滤 iframe 来源
                DOMPurify.addHook('uponSanitizeElement', function (node, data, config) {
                    if (data.tagName === 'iframe') {
                        var allowedIframePrefixes = Config.nkeditor.allowiframeprefixs || [];
                        var src = node.getAttribute('src');

                        // 判断是否匹配允许的前缀
                        var isAllowed = false;
                        for (var i = 0; i < allowedIframePrefixes.length; i++) {
                            if (src && src.indexOf(allowedIframePrefixes[i]) === 0) {
                                isAllowed = true;
                                break;
                            }
                        }

                        if (!isAllowed) {
                            // 不符合要求则移除该节点
                            return node.parentNode.removeChild(node);
                        }

                        // 添加安全属性
                        node.setAttribute('allowfullscreen', '');
                        node.setAttribute('allow', 'fullscreen');
                    }
                });
                var purifyOptions = {
                    ADD_TAGS: ['iframe'],
                    FORCE_REJECT_IFRAME: false
                };

                $(Config.simditor.classname || '.editor', form).each(function () {
                    var id = $(this).attr("id");
                    editor = new Simditor({
                        textarea: this,
                        height: isNaN(Config.simditor.height) ? null : parseInt(Config.simditor.height),
                        minHeight: parseInt(Config.simditor.minHeight || 250),
                        toolbar: Config.simditor.toolbar || [],
                        mobileToolbar: Config.simditor.mobileToolbar || [],
                        toolbarFloat: parseInt(Config.simditor.toolbarFloat),
                        placeholder: Config.simditor.placeholder || '',
                        dompurify: {
                            enabled: Config.simditor.isdompurify,
                            options: purifyOptions
                        },
                        pasteImage: true,
                        defaultImage: Config.__CDN__ + '/assets/addons/simditor/images/image.png',
                        upload: {url: '/'},
                        allowedTags: ['div', 'br', 'span', 'a', 'img', 'b', 'strong', 'i', 'strike', 'u', 'font', 'p', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'h1', 'h2', 'h3', 'h4', 'hr'],
                        allowedAttributes: {
                            div: ['data-tpl', 'data-source', 'data-id'],
                            span: ['data-id']
                        },
                        allowedStyles: {
                            div: ['width', 'height', 'padding', 'background', 'color', 'display', 'justify-content', 'border', 'box-sizing', 'max-width', 'min-width', 'position', 'margin-left', 'bottom', 'left', 'margin', 'float'],
                            p: ['margin', 'color', 'height', 'line-height', 'position', 'width', 'border', 'bottom', 'float'],
                            span: ['text-decoration', 'color', 'margin-left', 'float', 'background', 'padding', 'margin-right', 'border-radius', 'font-size', 'border', 'float'],
                            img: ['vertical-align', 'width', 'height', 'object-fit', 'float', 'margin', 'float'],
                            a: ['text-decoration']
                        }
                    });
                    editor.toolbar.wrapper.find(".menu-item-select-image").on('click',  function(){
                        var selectUrl = typeof Config !== 'undefined' && Config.modulename === 'index' ? 'user/attachment' : 'general/attachment/select';
                        parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=image/", __('Choose'), {
                            callback: function (data) {
                                var urlArr = data.url.split(/\,/);
                                $.each(urlArr, function () {
                                    var url = Fast.api.cdnurl(this, true);
                                    var imgHtml = '<img src="' + url + '" />';
                                    editor.insertHTML(imgHtml);
                                });
                            }
                        });
                        return false;
                    });
                    editor.uploader.on('beforeupload', function (e, file) {
                        Upload.api.send(file.obj, function (data) {
                            var url = Fast.api.cdnurl(data.url, true);
                            editor.uploader.trigger("uploadsuccess", [file, {success: true, file_path: url}]);
                        });
                        return false;
                    });
                    editor.on("blur", function () {
                        this.textarea.trigger("blur");
                    });
                    if (editor.opts.height) {
                        editor.body.css({height: editor.opts.height, 'overflow-y': 'auto'});
                    }
                    if (editor.opts.minHeight) {
                        editor.body.css({'min-height': editor.opts.minHeight});
                    }
                    Simditor.list[id] = editor;
                });
            });
        }
    }
});

require.config({
    paths: {
        'summernote': '../addons/summernote/lang/summernote-zh-CN.min',
        'purify': '../addons/summernote/js/purify.min'
    },
    shim: {
        'summernote': ['../addons/summernote/js/summernote.min', 'css!../addons/summernote/css/summernote.min.css'],
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            //绑定summernote事件
            if ($(Config.summernote.classname || '.editor', form).length > 0) {
                var selectUrl = typeof Config !== 'undefined' && Config.modulename === 'index' ? 'user/attachment' : 'general/attachment/select';
                require(['summernote', 'purify'], function (undefined, DOMPurify) {
                    var imageButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file-image-o"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=image/", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            context.invoke('editor.insertImage', url);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    var attachmentButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=*", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            var node = $("<a href='" + url + "'>" + url + "</a>");
                                            context.invoke('insertNode', node[0]);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    if (Config.summernote.isdompurify) {
                        // 添加 hook 过滤 iframe 来源
                        DOMPurify.addHook('uponSanitizeElement', function (node, data, config) {
                            if (data.tagName === 'iframe') {
                                var allowedIframePrefixes = Config.nkeditor.allowiframeprefixs || [];
                                var src = node.getAttribute('src');

                                // 判断是否匹配允许的前缀
                                var isAllowed = false;
                                for (var i = 0; i < allowedIframePrefixes.length; i++) {
                                    if (src && src.indexOf(allowedIframePrefixes[i]) === 0) {
                                        isAllowed = true;
                                        break;
                                    }
                                }

                                if (!isAllowed) {
                                    // 不符合要求则移除该节点
                                    return node.parentNode.removeChild(node);
                                }

                                // 添加安全属性
                                node.setAttribute('allowfullscreen', '');
                                node.setAttribute('allow', 'fullscreen');
                            }
                        });

                        var purifyOptions = {
                            ADD_TAGS: ['iframe'],
                            FORCE_REJECT_IFRAME: false
                        };
                        $.extend($.summernote.plugins, {
                            'dompurify': function (context) {
                                // 重写代码过滤方法
                                const originalPurify = context.options.modules.codeview.prototype.purify;
                                context.options.modules.codeview.prototype.purify = function (html) {
                                    html = DOMPurify.sanitize(html, purifyOptions);
                                    return originalPurify.call(this, html);
                                };
                            }
                        });
                    }

                    $(Config.summernote.classname || '.editor', form).each(function () {
                        $(this).summernote($.extend(true, {}, {
                            height: isNaN(Config.summernote.height) ? null : parseInt(Config.summernote.height),
                            minHeight: parseInt(Config.summernote.minHeight || 250),
                            toolbar: Config.summernote.toolbar,
                            followingToolbar: parseInt(Config.summernote.followingToolbar),
                            placeholder: Config.summernote.placeholder || '',
                            airMode: parseInt(Config.summernote.airMode) || false,
                            lang: 'zh-CN',
                            fontNames: Config.summernote.fontNames || [],
                            fontNamesIgnoreCheck: ["Open Sans", "Microsoft YaHei", '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆'],
                            buttons: {
                                image: imageButton,
                                attachment: attachmentButton,
                            },
                            plugins: {
                                'dompurify': true
                            },
                            dialogsInBody: true,
                            callbacks: {
                                onChange: function (contents) {
                                    if (Config.summernote.isdompurify) {
                                        contents = DOMPurify.sanitize(contents, purifyOptions);
                                    }
                                    $(this).val(contents);
                                    $(this).trigger('change');
                                },
                                onInit: function () {
                                },
                                onImageUpload: function (files) {
                                    var that = this;
                                    //依次上传图片
                                    for (var i = 0; i < files.length; i++) {
                                        Upload.api.send(files[i], function (data) {
                                            var url = Fast.api.cdnurl(data.url, true);
                                            $(that).summernote("insertImage", url, 'filename');
                                        });
                                    }
                                },
                                onPaste: function (e) {
                                    if (Config.summernote.pasteAsPlainText || false) {
                                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                                        e.preventDefault();
                                        setTimeout(function () {
                                            document.execCommand('insertText', false, bufferText);
                                        }, 10);
                                    }
                                }
                            }
                        }, $(this).data("summernote-options") || {}));
                    });
                });
            }
        } catch (e) {

        }

    };
});

require.config({
    paths: {
        'tinymce': '../addons/tinymce/js/tinymce/tinymce.min'
    },
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            //绑定summernote事件
            if ($(".tinymce,.editor", form).size() > 0) {
                require(['tinymce'], function () {
                    var init = {
                        selector: ".tinymce,.editor",//容器可以是id也可以是class
                        language: 'zh_CN',//语言
                        theme: 'silver',//主体默认主题
                        plugins: ['advlist link image lists charmap hr anchor pagebreak searchreplace wordcount visualblocks visualchars code insertdatetime nonbreaking save table contextmenu directionality help autolink autosave print preview spellchecker fullscreen media emoticons template paste textcolor'],//所含插件
                        content_style : '',//编辑器样式只对编辑器试图有效不会提交到html中
                        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | spellchecker help',//工具栏
                        file_picker_types: 'image,media',//文件上传支持类型：file,image,media
                        //图像上传处理
                        convert_urls:false,//关闭url自动检测
                        images_upload_handler: function (blobInfo, success, failure) {
                            Upload.api.send(blobInfo.blob(), function (data) {
                                var url = Fast.api.cdnurl(data.url);
                                success( url);
                                return;
                            },function (data,ret) {
                                Layer && Layer.closeAll('dialog');
                                failure(ret.msg);
                                return;
                            });
                        },
                        image_default_size:{width:'100%',height:''},//图片添加成功后的默认宽高 格式：{width:"",height:''} 允许是百分比或像素
                        media_default_size:{width:'100%',height:''},//音/视频添加成功后的默认宽高 格式：{width:"",height:''} 允许是百分比或像素
                        browser_spellcheck: true,//浏览器检查拼写
                        spellchecker_callback: function(method, text, success, failure) {
                            var words = text.match(this.getWordCharPattern());
                            if (method == "spellcheck") {
                                var suggestions = {};
                                for (var i = 0; i < words.length; i++) {
                                    suggestions[words[i]] = ["First", "Second"];
                                }
                                success(suggestions);
                            }
                        },
                        setup:function (editor) {
                            editor.on('change',function () {
                                editor.save();
                                $(editor.getElement()).trigger("input");
                            });
                        }
                    };
                    if(false){
                        //文件上传处理
                        init.file_picker_callback = function(callback, value, meta) {
                            //为不同插件指定文件类型
                            switch(meta.filetype){
                                case 'image':
                                    filetype='image/*';
                                    break;
                                case 'media':
                                    filetype='audio/*,video/*';
                                    break;
                                case 'file':
                                default:
                            }

                            //模拟出一个input用于添加本地文件
                            var input = document.createElement('input');
                            input.setAttribute('type', 'file');
                            input.setAttribute('accept', filetype);
                            input.click();
                            input.onchange = function() {
                                Upload.api.send(this.files[0], function (data) {
                                    var url = Fast.api.cdnurl(data.url);
                                    callback(url);
                                    return;
                                },function (data,ret) {
                                    Layer && Layer.closeAll('dialog');
                                    alert(ret.msg);
                                    return;
                                });
                            };
                        };
                    }
                    tinymce.init(init);
                    $(document).on("click", ":button[type=submit],input[type=submit]", function () {
                        tinymce.triggerSave();
                    });
                });
            }
        } catch (e) {

        }

    };
});

});