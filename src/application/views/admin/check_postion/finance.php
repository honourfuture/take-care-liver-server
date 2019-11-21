<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '检测分成' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/check_postion/finance"><?php echo $page_title ?></a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
            <form action="/admin/check_postion/finance" method="get" id="form">
          <div class="box-header">
                  <div class="form-group">
                      <div class="col-sm-2">
                          <div class="input-group date">
                              <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text"  name="start_date" class="form-control pull-right" id="start_date" value="<?php echo $start_date ?>" />
                          </div>

                      </div>
                      <div class="col-sm-2">
                          <div class="input-group date">
                              <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text"  name="end_date" class="form-control pull-right" id="end_date" value="<?php echo $end_date ?>" />
                          </div>

                      </div>
                  </div>

            <div class="box-tools">

                <div class="input-group input-group" style="width: 250px;">
                  <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索"
                         value="<?= $keyword ?>">
                  <div class="input-group-btn">
                    <button type="button" class="btn btn-default" onclick="query()"><i class="fa fa-search"></i></button>
                  </div>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-success" onclick="export1()">导出数据</button>
                    </div>
                </div>

            </div>
          </div>
            </form>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-condensed table-hover">
              <thead>
              <tr>
                    <th><b>医院编号</b></th>
                    <th>医院名称</th>
                    <th>检测点编号</th>
                    <th>检测点</th>
                    <th>用户编号</th>
                    <th>日期</th>
                    <th>金额</th>
                    <th width="250">操作</th>
              </tr>
              </thead>
              <tbody>
                <?php if ($result != null) : ?>
                    <?php foreach ($result as $value) : ?>
                        <tr>
                            <td><!--<input type="checkbox" name="ids[]" class="ids" value="<?php /*echo $value['id']; */?>" />--> <?php echo $value->hospital_id; ?></td>
                            <td><?php echo $value->name; ?></td>
                            <td><?php echo $value->cp_id; ?></td>
                            <td><?php echo $value->check_position; ?></td>
                            <td><?php echo $value->user_id; ?></td>
                            <td><?php echo $value->date; ?></td>
                            <td><?php echo $value->money; ?></td>
                            <td>
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

        $('#start_date').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#end_date').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

    });


    function export1(){
        $("#form").attr("action","/admin/check_postion/export");
        $("#form").attr("method","post");
        $("#form").submit();
    }

    function query(){
        $("#form").attr("action","/admin/check_postion/finance");
        $("#form").attr("method","get");
        $("#form").submit();
    }
</script>