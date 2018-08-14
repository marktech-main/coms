<?php $this->load->view("impl/header.php");?>
<?php $is_cs = is_cs_team(decrypt($this->session->userdata('user_data'))['user_role']);?>

        <div id="page-wrapper">

            <button data-toggle="collapse" data-target="#tasks-title" class="btn btn-collapse">Collapsible</button>

            <?php if($is_cs){?>
              <div class="row collapse" id="tasks-title">
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="all">
                        <a class="queue-table-filter" data-filter="">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-bank fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_cs_total_request"><?php echo $request_transaction_statistic->total_request; ?></div>
                                      <div>All</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="deposit">
                        <a class="queue-table-filter" data-filter="QUEUE">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-credit-card fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_cs_total_queue"><?php echo $request_transaction_statistic->total_queue; ?></div>
                                      <div>Queue</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="QUEUE">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="withdraw">
                        <a class="queue-table-filter" data-filter="PROCESSING">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-money fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_cs_total_processing"><?php echo $request_transaction_statistic->total_processing; ?></div>
                                      <div>Processing</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="PROCESSING">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="transfer">
                        <a class="queue-table-filter" data-filter="PENDING">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-shopping-cart fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_cs_total_pending"><?php echo $request_transaction_statistic->total_pending; ?></div>
                                      <div>Pending</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="PENDING">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="new-register">
                        <a class="queue-table-filter" data-filter="SUCCESSFUL">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-support fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_cs_total_successful"><?php echo $request_transaction_statistic->total_successful; ?></div>
                                      <div>Successful</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="SUCCESSFUL">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="cancelled">
                        <a class="queue-table-filter" data-filter="CANCELLED">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-exclamation-triangle fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_cs_total_cancelled"><?php echo $request_transaction_statistic->total_cancelled; ?></div>
                                      <div>Cancelled</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="CANCELLED">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
              <!-- /.row -->
            <?php }else{ ?>
              <div class="row collapse" id="tasks-title">
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="all">
                        <a class="queue-table-filter" data-filter="">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-bank fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_total_request"><?php echo $request_transaction_statistic->total_request; ?></div>
                                      <div>All</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="deposit">
                        <a class="queue-table-filter" data-filter="<?php echo $transaction_type->deposit; ?>">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-credit-card fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_total_deposit"><?php echo $request_transaction_statistic->total_deposit; ?></div>
                                      <div>Deposit</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="<?php echo $transaction_type->deposit; ?>">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="withdraw">
                        <a class="queue-table-filter" data-filter="<?php echo $transaction_type->withdraw; ?>">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-money fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_total_withdrawal"><?php echo $request_transaction_statistic->total_withdrawal; ?></div>
                                      <div>Withdraw</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="<?php echo $transaction_type->withdraw; ?>">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="transfer">
                        <a class="queue-table-filter" data-filter="<?php echo $transaction_type->transfer; ?>" >
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-shopping-cart fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_total_transfer"><?php echo $request_transaction_statistic->total_transfer; ?></div>
                                      <div>Transfer</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="<?php echo $transaction_type->transfer; ?>" >
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                    <div class="panel" id="new-register">
                      <a class="queue-table-filter" data-filter="SUCCESSFUL">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3 tasks-icon">
                                    <i class="fa fa-support fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" name="div_cs_total_successful"><?php echo $request_transaction_statistic->total_successful; ?></div>
                                    <div>Successful</div>
                                </div>
                            </div>
                        </div>
                      </a>
                      <a class="queue-table-filter" data-filter="SUCCESSFUL">
                          <div class="panel-footer">
                              <span class="pull-left">View</span>
                              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                              <div class="clearfix"></div>
                          </div>
                      </a>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                      <div class="panel" id="cancelled">
                        <a class="queue-table-filter" data-filter="CANCELLED">
                          <div class="panel-heading">
                              <div class="row">
                                  <div class="col-xs-3 tasks-icon">
                                      <i class="fa fa-exclamation-triangle fa-4x"></i>
                                  </div>
                                  <div class="col-xs-9 text-right">
                                      <div class="huge" name="div_total_cancelled"><?php echo $request_transaction_statistic->total_cancelled; ?></div>
                                      <div>Cancelled</div>
                                  </div>
                              </div>
                          </div>
                        </a>
                        <a class="queue-table-filter" data-filter="CANCELLED">
                            <div class="panel-footer">
                                <span class="pull-left">View</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                      </div>
                  </div>
                  <div class="clearfix"></div>
              </div>
              <!-- /.row -->
            <?php } ?>

            <div class="clearfix"></div>

            <div class="row">
              <div class="table-responsive">
                  <table id="dt_request_transaction" width="100%" name="dt_request_transaction"
                         class="table table-striped table-bordered table-hover dataTable no-footer" role="grid"
                         aria-describedby="dt_basic_info">
                      <thead>
                      <tr>
                          <th>ID</th>
                          <th>Website</th>
                          <th>Type</th>
                          <th>Customer ID</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th>Create By</th>
                          <th>Request on</th>
                          <th>Update By</th>
                          <th>Process on</th>
                          <th>Updated on / Done</th>
                          <th>Come From</th>
                          <th></th>
                      </tr>
                      </thead>
                      <tbody>

                      </tbody>
                  </table>
              </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->


<?php $this->load->view("impl/footer.php");?>
<script>
//
// DataTables initialisation
//
$(document).ready(function() {

  /* Responsive Table */
  var responsiveHelper_dt_basic = undefined;
  var responsiveHelper_datatable_fixed_column = undefined;
  var responsiveHelper_datatable_col_reorder = undefined;
  var responsiveHelper_datatable_tabletools = undefined;

  var breakpointDefinition = {
      tablet : 1024,
      phone : 480
  };
  window.filter_transaction = '';
  window.filter_division = '';
  window.filter_uuid = '';
  window.order = 'asc';
  $.fn.dataTable.ext.errMode = 'throw';

  // event trigger excute when click return from success page
  if(localStorage.fromSuccess){
    localStorage.removeItem("fromSuccess");
    filter_transaction = 'SUCCESSFUL';
    order = 'desc';
  }
  if(localStorage.uuid){
    filter_uuid = localStorage.uuid;
  }

  <?php
        if(is_cs_team(decrypt($this->session->userdata('user_data'))['user_role'])){
  ?>
          var dt_toolbar = "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
          "t"+
          "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>";
  <?php
        }else{
  ?>
          var dt_toolbar = "<'dt-toolbar'<'col-xs-12 col-sm-7'f><'col-sm-5 col-xs-12 hidden-xs'l>r>"+
          "t"+
          "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>";
  <?php
        }
  ?>

  window.oTable = $('#dt_request_transaction').dataTable({
      "sDom": dt_toolbar,
      "order": [[ 0, order ]],
      "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, 'ALL'] ],
      "autoWidth" : false,
      processing: true,
      serverSide: true,
      "ajax": {
          url: 		'<?php echo base_url()?>main/get_json_transaction_list',
          type: 		'POST',
          dataType: 	'json',
          data: function(d) {
                    d.filter_transaction = filter_transaction;
                    d.filter_division = filter_division;
                    d.filter_uuid = filter_uuid;
                }
      },
      "aoColumns": [
          { "mData": "transaction_code" },
          { "mData": "website_name" },
          {
            "mData": "transaction_type_name",
            "render":function(data, type, full, meta){
              $html='';
              $html +='<b>'+full.transaction_type_name+'</b>';
              return $html;
            }
          },
          { "mData": "customer_id" },
          // {
          //   "mData": "customer_id",
          //   "render": function (data, type, full) {
          //       $html = '';
          //       if(full.priority == '1'){
          //         $html += '<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true"> ';
          //       }
          //       $html += full.customer_id;
          //       return $html;
          //   }
          // },
          {
            "mData": "amount",
            "sClass": "dt-right",
            "render": function (data, type, full) {
                if(full.transaction_type_name == "RESET-PASSWORD" || full.transaction_type_name == "OTHERS"){
                  var amount = 'N/A';
                }else{
                  var amount = Number(full.amount).formatMoney(0);
                }
                return amount;
            }
          },
          {
            "mData": "status",
            "sClass": "dt-center",
            "render":function(data, type, full, meta){
                $class = '';
                switch (data) {
                    case 'QUEUE' :
                        $html='';
                        if(full.priority == '1'){
                          $html += '<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true"> ';
                        }
                        if(localStorage["new_ms_"+full.transaction_code]){
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"><img src="/images/blue-talk-icon.png" alt="" width="24" height="24" data-pin-nopin="true"></span>';
                        }else{
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"></span>';
                        }
                        $html +='<div class="label label-info">';
                        $html +='<i class="fa fa-spinner fa-fw fa-pulse"></i> <b>'+full.status+'</b>';
                        $html +='</div>';
                        return $html;
                        break;
                    case 'PROCESSING' :
                        $html='';
                        if(full.priority == '1'){
                          $html += '<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true"> ';
                        }
                        if(localStorage["new_ms_"+full.transaction_code]){
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"><img src="/images/blue-talk-icon.png" alt="" width="24" height="24" data-pin-nopin="true"></span>';
                        }else{
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"></span>';
                        }
                        $html +='<div class="label label-primary">';
                        $html +='<i class="fa fa-spinner fa-fw fa-pulse"></i> <b>'+full.status+'</b>';
                        $html +='</div>';
                        return $html;
                        break;
                    case 'PENDING' :
                        $html='';
                        if(full.priority == '1'){
                          $html += '<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true"> ';
                        }
                        if(localStorage["new_ms_"+full.transaction_code]){
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"><img src="/images/blue-talk-icon.png" alt="" width="24" height="24" data-pin-nopin="true"></span>';
                        }else{
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"></span>';
                        }
                        $html +='<div class="label label-warning">';
                        $html +='<i class="fa fa-exclamation-triangle"></i> <b>'+full.status+'</b>';
                        $html +='</div>';
                        return $html;
                        break;
                    case 'SUCCESSFUL' :
                        $html='';
                        if(full.priority == '1'){
                          $html += '<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true"> ';
                        }
                        if(localStorage["new_ms_"+full.transaction_code]){
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"><img src="/images/blue-talk-icon.png" alt="" width="24" height="24" data-pin-nopin="true"></span>';
                        }else{
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"></span>';
                        }
                        $html +='<div class="label label-success">';
                        $html +='<i class="fa fa-check"></i> <b>'+full.status+'</b>';
                        $html +='</div>';
                        return $html;
                        break;
                    case 'CANCELLED' :
                        $html='';
                        if(full.priority == '1'){
                          $html += '<img alt="priority" title="priority" class="shake-vertical-slow shake-constant shake-constant--hover" src="/images/vip.png" alt="" width="24" height="24" data-pin-nopin="true"> ';
                        }
                        if(localStorage["new_ms_"+full.transaction_code]){
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"><img src="/images/blue-talk-icon.png" alt="" width="24" height="24" data-pin-nopin="true"></span>';
                        }else{
                          $html +='<span style="width: 24px; height: 24px;" class="new-pm id'+full.transaction_code+'"></span>';
                        }
                        $html +='<div class="label label-danger">';
                        $html +='<i class="fa fa-times"></i> <b>'+full.status+'</b>';
                        $html +='</div>';
                        return $html;
                        break;
                    default :
                        return 'N/A';
                        break;
                }
              }
          },
          { "mData": "created_by_name" },
          { "mData": "request_time" },
          { "mData": "updated_by_name" },
          { "mData": "process_time" },
          { "mData": "complete_time" },
          // {
          //     "mData": "process_time",
          //     "render":function(data, type, full, meta){
          //         $return_val = ( full.status == 'SUCCESSFUL' || full.status == 'CANCELLED' ) ? full.complete_time : full.process_time;
          //         return $return_val;
          //     }
          // },
          { "mData": "come_from", "sClass": "dt-center" },
          {
              "mData": "transaction_id",
              "render":function(data, type, full, meta){
                  $html='';
                  $html +='<a class="btn btn-success btn-xs"  action="edit" data-target="'+full.transaction_id+'"><i class="fa fa-cog fa-fw fa-pulse"></i>Verify</a>';
                  return $html;
              },
              "sClass": "center",
              targets: 'no-sort',
              orderable: false
          }
      ],
      "preDrawCallback" : function() {
          // Initialize the responsive datatables helper once.
          if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_request_transaction'), breakpointDefinition);
          }
      },
      "rowCallback" : function(nRow) {
          responsiveHelper_dt_basic.createExpandIcon(nRow);
      },
      "drawCallback" : function(oSettings) {
          responsiveHelper_dt_basic.respond();
      },
      language: {
          searchPlaceholder: "Search records"
      }
  });

  <?php
        if(!is_cs_team(decrypt($this->session->userdata('user_data'))['user_role'])){
  ?>
  $('#dt_request_transaction_filter').prepend('<label>Division: <select name="division_filter"><option value=""> ALL </option></label>');

  // do ajax call for Initialize division data filter
  $.ajax({
    method: "POST",
    url: 'main/get_division_filter',
    dataType: "TEXT",
    success: function(data){
      if(data){ // if true display notification
        $('[name="division_filter"]').append(data);
      }
    }
  });
  <?php
        }
  ?>

  socket.on('got_new_msg', function(message){
    console.log(message);
    console.log('aaaa');
    var room = message.room;
    var my_id = '<?php echo decrypt($this->session->userdata('user_data'))['user_id'] ?>';
    var receiver_id = message.receiver_id;
    console.log('m.id : '+my_id);
    console.log('d.id : '+receiver_id);
    if(my_id == receiver_id){
      $('.id'+message.room).html('<img src="/images/blue-talk-icon.png" alt="" width="24" height="24" data-pin-nopin="true">');
      console.log('creadte localStorage');
      localStorage.setItem("new_ms_"+message.room, true);
      // create and play new msg audio
      play_new_msg_audio();
    }
  });

  socket.on('seen_new_msg', function(message){
    console.log(message);
    $('.id'+message.room).html('');
  });
} );

$(document).on('click', '.queue-table-filter', function(){
  filter_transaction = $(this).data('filter');
  if(filter_transaction == 'SUCCESSFUL' || filter_transaction == 'CANCELLED'){;
    oTable.fnSort( [ [0,'desc'] ] );
  }else{
    oTable.fnSort( [ [0,'asc'] ] );
  }
  // oTable.fnDraw(false);
});
// redirect to verify-form
$(document).on('click', '[action="edit"]', function(){
 var redirect = 'update';
 $.redirectPost(redirect, {transaction_id: $(this).data('target'), state: '1'});
});
// division filter on changed
$(document).on('change', '[name="division_filter"]', function(e){
  // set divison filter variable to selected value
  filter_division = $(this).val();
  // force datatable to draw with division filter
  oTable.fnPageChange(0,true);
});

var hidden, visibilityChange;
if (typeof document.hidden !== "undefined") {
	hidden = "hidden";
	visibilityChange = "visibilitychange";
} else if (typeof document.mozHidden !== "undefined") {
	hidden = "mozHidden";
	visibilityChange = "mozvisibilitychange";
} else if (typeof document.msHidden !== "undefined") {
	hidden = "msHidden";
	visibilityChange = "msvisibilitychange";
} else if (typeof document.webkitHidden !== "undefined") {
	hidden = "webkitHidden";
	visibilityChange = "webkitvisibilitychange";
}
function handleVisibilityChange() {
  if (document[hidden]) {
		// videoElement.pause();
    console.log('non-active');
	}else{
    console.log('active');
  }
}
document.addEventListener(visibilityChange, handleVisibilityChange, false);

</script>
