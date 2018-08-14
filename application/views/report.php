<?php $this->load->view("impl/header.php");?>
<?php $is_cs = is_cs_team(decrypt($this->session->userdata('user_data'))['user_role']);?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reports</h1>
                </div>
                <div class="col-lg-12">
                  <form action="<?php echo base_url()?>Report/export_to_excel" name="form_export" method="post" accept-charset="utf-8">

                  <div class="input-daterange" id="datepicker">
                    <div class="col-lg-offset-4 col-lg-2 col-xs-6" style="margin-bottom: 20px;">
                      <input type="text" id="from_date" name="from_date" class="form-control" placeholder="from date"/>
                    </div>
                    <div class="col-lg-2 col-xs-6" style="margin-bottom: 20px;">
                      <div class="input-group">
                          <input type="text" id="to_date" name="to_date" class="form-control" placeholder="to date">
                          <span class="input-group-btn">
                           <button class="btn btn-default" id="filter_btn" name="filter_btn" type="button">Preview</button>
                          </span>
                      </div>
                    </div>
                    <!-- <button id="export_to_excel" name="export_to_excel" class="btn btn-default" type="button" >Export to excel</button> -->

                  </div><!-- /.input-daterange -->
                  <input type="submit" name="btn_export" id="btn_export" class="btn btn-default" value="Export to excel">
                  </form>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Average time taken to complete
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                  <table id="dt_avg_time_to_complete" width="100%" name="dt_avg_time_to_complete"
                                         class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                                         aria-describedby="dt_basic_info">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Average Time</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Fastest time taken to complete
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                              <table id="dt_fastest_time_to_complete" width="100%" name="dt_fastest_time_to_complete"
                                     class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                                     aria-describedby="dt_basic_info">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transaction Type</th>
                                            <th>Website</th>
                                            <th>PIC</th>
                                            <th>Time Spend</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Longest time taken to complete
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                              <table id="dt_longest_time_to_complete" width="100%" name="dt_longest_time_to_complete"
                                     class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                                     aria-describedby="dt_basic_info">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transaction Type</th>
                                            <th>Website</th>
                                            <th>PIC</th>
                                            <th>Time Spend</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Total completed request
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive table-bordered">
                              <table id="dt_total_completed" width="100%" name="dt_total_completed"
                                     class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                                     aria-describedby="dt_basic_info">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transaction Type</th>
                                            <th>Total Completed</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-6">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                          Statistic of busiest day and time
                      </div>
                      <!-- /.panel-heading -->
                      <div class="panel-body">
                          <div class="table-responsive table-bordered">
                            <table id="dt_statistic_of_busiest_day_and_time" width="100%" name="dt_statistic_of_busiest_day_and_time"
                                   class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                                   aria-describedby="dt_basic_info">
                                  <thead>
                                      <tr>
                                          <th>Hour range</th>
                                          <th>Total Request</th>
                                      </tr>
                                  </thead>
                              </table>
                          </div>
                          <!-- /.table-responsive -->
                      </div>
                      <!-- /.panel-body -->
                  </div>
                  <!-- /.panel -->
              </div>
              <!-- /.col-lg-6 -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php $this->load->view("impl/footer.php");?>
<script>
$(document).ready(function() {
  $('.input-daterange').datepicker({
      format: "yyyy-mm-dd",
      clearBtn: true,
      orientation: "bottom",
      autoclose: true,
      todayHighlight: true,
      toggleActive: true
  });

  var from_date = '';
  var to_date = '';

  // datatable for #1 Average time taken to complete each request
  var dt_avg_time_to_complete =$('#dt_avg_time_to_complete').dataTable({
          "paging":   false,
          "ordering": false,
          "info":     false,
          "sDom": "",
          "aoColumns": [
                        {
                          "mData": "transaction_type_name",
                          "render":function(data, type, full, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                          }
                        },
                        { "mData": "transaction_type_name" },
                        {
                          "mData": "average_time_taken_to_complete",
                          "render": function (data, type, full) {
                              var time = secondsToTime(full.average_time_taken_to_complete);
                              var html = '';
                              if(time.h == 0 && time.m == 0){
                                html = time.s + ' seconds';
                              }else if(time.h == 0){
                                html = time.m + ' minutes ' + time.s + ' seconds';
                              }else{
                                html = time.h + ' hours ' + time.m + ' minutes ' + time.s + ' seconds';
                              }
                              return html;
                          }
                        }
          ]
  });

  // datatable for #2 Fastest time taken to complete each request
  var dt_fastest_time_to_complete =$('#dt_fastest_time_to_complete').dataTable({
          "paging":   false,
          "ordering": false,
          "info":     false,
          "sDom": "",
          "aoColumns": [
                        {
                          "mData": "transaction_type_name",
                          "render":function(data, type, full, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                          }
                        },
                        { "mData": "transaction_type_name" },
                        { "mData": "website_name" },
                        { "mData": "pic_username" },
                        {
                          "mData": "fastest_time_taken_to_complete",
                          "render": function (data, type, full) {
                              var time = secondsToTime(full.fastest_time_taken_to_complete);
                              var html = '';
                              if(time.h == 0 && time.m == 0){
                                html = time.s + ' seconds';
                              }else if(time.h == 0){
                                html = time.m + ' minutes ' + time.s + ' seconds';
                              }else{
                                html = time.h + ' hours ' + time.m + ' minutes ' + time.s + ' seconds';
                              }
                              return html;
                          }
                        }
          ]
  });

  // datatable for #3 Longest time taken to complete each request
  var dt_longest_time_to_complete =$('#dt_longest_time_to_complete').dataTable({
          "paging":   false,
          "ordering": false,
          "info":     false,
          "sDom": "",
          "aoColumns": [
                        {
                          "mData": "transaction_type_name",
                          "render":function(data, type, full, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                          }
                        },
                        { "mData": "transaction_type_name" },
                        { "mData": "website_name" },
                        { "mData": "pic_username" },
                        {
                          "mData": "longest_time_taken_to_complete",
                          "render": function (data, type, full) {
                              var time = secondsToTime(full.longest_time_taken_to_complete);
                              var html = '';
                              if(time.h == 0 && time.m == 0){
                                html = time.s + ' seconds';
                              }else if(time.h == 0){
                                html = time.m + ' minutes ' + time.s + ' seconds';
                              }else{
                                html = time.h + ' hours ' + time.m + ' minutes ' + time.s + ' seconds';
                              }
                              return html;
                          }
                        }
          ]
  });

  // datatable for #4 Total completed request for each request
  var dt_total_completed =$('#dt_total_completed').dataTable({
          "paging":   false,
          "ordering": false,
          "info":     false,
          "sDom": "",
          "aoColumns": [
                        {
                          "mData": "transaction_type_name",
                          "render":function(data, type, full, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                          }
                        },
                        { "mData": "transaction_type_name" },
                        { "mData": "total_completed_request" }
          ]
  });

  // datatable for #4 Total completed request for each request
  var dt_statistic_of_busiest_day_and_time =$('#dt_statistic_of_busiest_day_and_time').dataTable({
          "paging":   false,
          "ordering": false,
          "info":     false,
          "sDom": "",
          "aoColumns": [
                        { "mData": "hour_range" },
                        { "mData": "number_of_request" }
          ]
  });

  // event handler for export Excel
  $('#export_to_excel').on('click',function(){
    $.ajax({
      url:    '<?php echo base_url()?>Report/export_to_excel',
      method: 'POST',
      data:   { from_date : $('#from_date').val() , to_date : $('#to_date').val() }
    });
  });


  // event handler for data filter
  $('#filter_btn').on('click',function(){
    $.ajax({
      url: 		  '<?php echo base_url()?>Report/display_report_data',
      method: 	'POST',
      data:     { from_date : $('#from_date').val() , to_date : $('#to_date').val() },
      dataType: 'json',
      success: function(d){
        // console.log(d.data.report_average_time_taken_to_complete);
        // # 1
        var report_average_time_taken_to_complete = d.data.report_average_time_taken_to_complete;
        if(report_average_time_taken_to_complete != ''){
          dt_avg_time_to_complete.fnClearTable();
          dt_avg_time_to_complete.fnAddData(
            report_average_time_taken_to_complete
          );
        }else{
          dt_avg_time_to_complete.fnClearTable();
        }

        //# 2
        var report_fastest_time_taken_to_complete = d.data.report_fastest_time_taken_to_complete;
        if(report_fastest_time_taken_to_complete != ''){
          dt_fastest_time_to_complete.fnClearTable();
          dt_fastest_time_to_complete.fnAddData(
            report_fastest_time_taken_to_complete
          );
        }else{
          dt_fastest_time_to_complete.fnClearTable();
        }

        //# 3
        var report_longest_time_taken_to_complete = d.data.report_longest_time_taken_to_complete;
        if(report_longest_time_taken_to_complete != ''){
          dt_longest_time_to_complete.fnClearTable();
          dt_longest_time_to_complete.fnAddData(
            report_longest_time_taken_to_complete
          );
        }else{
          dt_longest_time_to_complete.fnClearTable();
        }

        //# 4
        var report_total_completed_request = d.data.report_total_completed_request;
        if(report_total_completed_request != ''){
          dt_total_completed.fnClearTable();
          dt_total_completed.fnAddData(
            report_total_completed_request
          );
        }else{
          dt_total_completed.fnClearTable();
        }

        //# 5
        var report_statistic_of_busiest_day_and_time = d.data.report_statistic_of_busiest_day_and_time;
        if(report_statistic_of_busiest_day_and_time != ''){
          dt_statistic_of_busiest_day_and_time.fnClearTable();
          dt_statistic_of_busiest_day_and_time.fnAddData(
            report_statistic_of_busiest_day_and_time
          );
        }else{
          dt_statistic_of_busiest_day_and_time.fnClearTable();
        }

      }
    });
  });
  // to convert second to time
  function secondsToTime(secs)
  {
      var hours = Math.floor(secs / (60 * 60));

      var divisor_for_minutes = secs % (60 * 60);
      var minutes = Math.floor(divisor_for_minutes / 60);

      var divisor_for_seconds = divisor_for_minutes % 60;
      var seconds = Math.ceil(divisor_for_seconds);

      var obj = {
          "h": hours,
          "m": minutes,
          "s": seconds
      };
      return obj;
  }
});
</script>
