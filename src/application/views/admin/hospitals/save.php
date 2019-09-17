<?php $page_title = ($data['id']?'编辑':'新增').' 医院' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/hospitals/index">医院 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/hospitals/index" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <?php if (!empty($message)) { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        <?php echo$message; ?>
                                    </div>
                                <?php } ?>

                                <form action="/admin/hospitals/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>"/>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="name" name="name" value="<?php echo $data['name'] ?>"
                                       data-msg="请填写name"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telphone" class="col-sm-2 control-label">电话</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="telphone" name="telphone" value="<?php echo $data['telphone'] ?>"
                                       data-msg="请填写telphone"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telphone" class="col-sm-2 control-label">位置</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="position" name="position" value="<?php echo $data['position'] ?>"
                                       data-msg="请填写位置"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                    />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="position" class="col-sm-2 control-label">经纬度</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="longitude" name="longitude" value="<?php echo $data['longitude'] ?>"
                                       data-msg="请填写经度"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" id="latitude" name="latitude" value="<?php echo $data['latitude'] ?>"
                                       data-msg="请填写维度"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                    />
                            </div>
                            <div class="col-sm-1">
                               <!-- $('#modal-default').modal('show');-->
                                <button type="button" class="btn btn-success"  onclick="open_win()">选择</button>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="picture_url" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-3">
                                <img src = "<?=$data['pic'] ? qiniu_image($data['pic'],false) : '/assets/images/upload.png';?>" style="cursor: pointer;height:64px;" id="img_imageupload"/>
                                <input type="hidden" name="pic" id="image" class="spec_image" value="<?=$data['pic'] ?>" />
                                <input type="file"  style="display:none;" class="file-btn"  id="image_file"  name="upload_file"  />
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="create_time" class="col-sm-2 control-label">创建时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写create_time" name="create_time" class="form-control pull-right" id="create_time" value="<?php /*echo $data['create_time'] */?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update_time" class="col-sm-2 control-label">更新时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写update_time" name="update_time" class="form-control pull-right" id="update_time" value="<?php /*echo $data['update_time'] */?>" />
                                </div>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="business_type" class="col-sm-2 control-label">营业时间</label>
                            <div class="col-sm-3">
                                <select name="business_type" id="business_type"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($business_types != null) : ?>
                                        <?php foreach ($business_types as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['business_type'] === (string)$key || set_value('business_type') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('business_type'); ?>
                            </div>
                        </div>

                       <!-- <div class="form-group">
                            <label for="detail" class="col-sm-2 control-label">简介</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="detail" name="detail" value="<?php /*echo $data['detail'] */?>"
                                       data-msg="请填写detail"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                    />
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="indate" class="col-sm-2 control-label">简介</label>
                            <!--<div class="col-sm-6">
                                <script id="container" name="detail" type="text/plain"></script>
                            </div>-->
                            <div class="col-sm-3" style="width:800px;">
                                <!-- 加载编辑器的容器 -->
                                <textarea type="text"   name="detail" id="detail" class="form-control"><?php echo $data['detail'] ;?></textarea>
                            </div>
                        </div>
                       <!-- <div class="form-group">
                            <label for="distance" class="col-sm-2 control-label">距离</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="distance" name="distance" value="<?php /*echo $data['distance'] */?>"
                                       data-msg="请填写distance"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>-->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-flat"
                                            style="margin-right: 5px;">提交
                                    </button>
                                    <a href="/admin/hospitals/index" class="btn btn-default btn-flat">取消</a>
                                </div>
                            </div>
                     </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/plugins/pwstrength/pwstrength.min.js"></script>
<script src="/assets/plugins/validate/jquery.validate.min.js"></script>
<script src="/assets/js/ajaxfileupload.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>

<!-- 实例化编辑器 -->
<script type="text/javascript">
    CKEDITOR.replace('detail');
    //$("#createForm").validate();

    $(function () {
        $("#createForm").validate();

        $('#create_time').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#update_time').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    //上传图片
    $("#image_file").on("change",function(){
        $.ajaxFileUpload({
            type: "post",
            url: '/admin/upload/upload_image',
            secureuri: false,
            fileElementId: 'image_file',
            dataType: 'json',
            success: function(res) {
                debugger;
                if(res.status == 1){

                    $('#img_imageupload').attr('src',res.data.url);
                    $('#image').val(res.data.url);//res.data.file_name
                }else{
                    if(res == null || res==false)
                    {
                        alert("上传失败！");
                        return;
                    }
                    alert(res.message);
                }
            },
            error:function(data, error){
                debugger;
                alert("上传失败");
            }
        });
    });
    $("#img_imageupload").click(function(){
        $("#image_file").click();
    });

    function open_win() {
        //myWindow=window.open('/admin/hospitals/map','选择位置','width=400,height=300');
        //myWindow.document.write("<p>这是'我的窗口'</p>");
        //myWindow.focus();
        openwindow('/admin/hospitals/map','选择位置',800,600);
    }

    function openwindow(url,name,iWidth,iHeight)  //window.open新建居中窗口
    {
        // url 转向网页的地址  
        // name 网页名称，可为空  
        // iWidth 弹出窗口的宽度  
        // iHeight 弹出窗口的高度  
        //window.screen.height获得屏幕的高，window.screen.width获得屏幕的宽  
        var iTop = (window.screen.height-30-iHeight)/2; //获得窗口的垂直位置;  
        var iLeft = (window.screen.width-10-iWidth)/2; //获得窗口的水平位置;  
        myWindow = window.open(url,name,'height='+iHeight+',,innerHeight='+iHeight+',width='+iWidth+',innerWidth='+iWidth+',top='+iTop+',left='+iLeft+',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
        myWindow.focus();
    }

</script>

