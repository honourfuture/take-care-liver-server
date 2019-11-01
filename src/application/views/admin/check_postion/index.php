<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '检测点' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/check_postion/index"><?php echo $page_title ?></a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
                <a href="/admin/check_postion/save" class="btn btn-block btn-primary btn-flat"><i
                  class="fa fa-plus"></i> 添加</a>
            </h3>

            <div class="box-tools">
              <form action="/admin/check_postion/index" method="get">
                <div class="input-group input-group" style="width: 250px;">
                  <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索"
                         value="<?= $keyword ?>">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
            <div class="box-header">

                <h3 class="box-title">
                        <div class="input-group input-group" style="width: 250px;">
                            <select name="hospital_id" id="hospital_id"  class="form-control">
                                <option value="">请选择</option>
                                <?php if ($hospitals != null) : ?>
                                    <?php foreach ($hospitals as $key=>$value) : ?>
                                        <option value="<?php echo $value->id; ?>" <?php if ($data['hospital_id'] === $value->id ) : ?>selected="selected"<?php endif; ?>><?php echo $value->id; ?>-<?php echo $value->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <div class="input-group-btn">
                                <button type="submit" id="selectBtn" class="btn btn-success"><i class="fa fa-remove"></i>批量选择</button>
                            </div>
                        </div>
                </h3>
            </div>

          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-condensed table-hover">
              <thead>
              <tr>
                  <th>
                      <input id="dataCheckAll" type="checkbox" onclick="checkAll(this)" class="checkbox"
                             placeholder="全选/取消">
                  </th>
                            <th><b>ID</b></th>
                            <th>检测点</th>
                            <th>医院id</th>
                            <th>总金额</th>
                            <th>备注</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>状态</th>
                            <th width="280">操作</th>
              </tr>
              </thead>
              <tbody>
                        <?php if ($result != null) : ?>
                            <?php foreach ($result as $value) : ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" onclick="checkThis(this)" name="data-checkbox"
                                               data-id="<?php echo $value['id']; ?>" class="checkbox data-list-check" value="<?php echo $value['id']; ?>"
                                               placeholder="选择/取消">
                                    </td>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo $value['check_postion']; ?></td>
                                    <td><?php echo $value['hospital_id']; ?></td>
                                    <td><?php echo $value['money']; ?></td>
                                    <td><?php echo $value['remark']; ?></td>
                                    <td><?php echo $value['create_time']; ?></td>
                                    <td><?php echo $value['update_time']; ?></td>
                                    <td><?php echo $statuss[$value['status']]; ?></td>
                                    <td>
                                        <button data-toggle="modal" data-target="#boxModal"
                                                onclick="loadModal('/admin/check_postion/del/<?php echo $value['id']; ?>')"
                                                class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i> 删除
                                        </button>
                                        <a href="/admin/check_postion/save/<?php echo $value['id']; ?>" class="btn btn-primary btn-sm pull-right"
                                           style="margin-right: 5px;"><i class="fa fa-edit"></i> 编辑</a>
                                        <a href="/admin/check_position_record/index?id=<?php echo $value['id']; ?>" class="btn btn-primary btn-sm pull-right"
                                           style="margin-right: 5px;"><i class="fa fa-edit"></i> 记录</a>
                                         <a href="/admin/check_postion/view/<?php echo $value['id']; ?>"
                                           class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 详情</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                     <?php if (empty($result)) { ?>
                                <tr>
                                    <td colspan="6" class="no-data">没有数据</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                    <!-- 显示<?/*= $administrators_show_begin */?>---><?/*= $administrators_show_end */?>
                                    <!-- 条，-->共<?= $total_rows ?>条
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <ul class="pagination pagination no-margin pull-right">
                                    <?php echo $this->pagination->create_links(); ?>
                                </ul>
                            </div>
                        </div><!-- /.row -->

                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function () {

        //批量删除
        $("#selectBtn").click(function(){
            poId = $("#hospital_id").val();
            if(AdminCommon.dataSelectIds==null || AdminCommon.dataSelectIds.length ==0 || poId == null){
                alert("请选择数据");
                return;
            }
            AdminCommon.confirm({
                title: "提示信息",
                message: "确定设置所选检测点的医院为选择的医院？",
                operate: function (reselt) {
                    if (reselt) {
                        $.ajax({
                            type: 'POST',
                            url: "/admin/check_postion/manage",
                            data: {ids:JSON.stringify(AdminCommon.dataSelectIds),hospital_id:poId},
                            success: function(result){
                                alert(result.message);
                                if(result.status == -1){

                                }else{
                                    setTimeout(function(){
                                        window.location.reload();
                                    },1500)
                                }

                            },
                            dataType: "json",
                            error:function(err){
                                alert("操作失败，请重试");
                                //alert(JSON.stringify(err));
                            }
                        });
                    } else {

                    }
                }
            });
        });

    });
</script>