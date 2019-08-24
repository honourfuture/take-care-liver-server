
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
      </div>
    <strong>技术支持 &copy; 2014-2017 <a href="http://kuai.ma" target="_blank">快码</a>.</strong>
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->


<div class="modal fade" id="boxModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  
	</div>
  </div>
</div>
<script src="/assets/plugins/select2/select2.full.min.js"></script>

<script src="/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/assets/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js"></script>
<script src="/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="/assets/plugins/chartjs/Chart.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/assets/admin/js/pages/dashboard.js"></script>

<?php
$flash_message_type = $this->session->flashdata('message_type');
$flash_message = $this->session->flashdata('message');

//$this->session->unset_flashdata('message_type');
//$this->session->unset_flashdata('message');
?>
<script>
     $(function(){
			toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "progressBar": true,
			  "preventDuplicates": false,
			  "positionClass": "toast-top-right",
			  "onclick": null,
			  "showDuration": "400",
			  "hideDuration": "1000",
			  "timeOut": "7000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};

		 <?php if(isset($flash_message_type) && $flash_message_type=='error'){ ?>
		     toastr.error("<?=$flash_message?>");
		 <?php }else if(isset($flash_message_type) && $flash_message_type=='warning'){ ?>
		     toastr.warning("<?=$flash_message?>");
		 <?php }else if(!empty($flash_message)){ ?>
			 toastr.success("<?=$flash_message?>");
		 <?php } ?>
	 })
</script>

</body>
</html>
