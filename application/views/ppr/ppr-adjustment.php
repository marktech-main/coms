<?php $this->load->view("impl/header.php");?>
<?php $is_cs = is_cs_team(decrypt($this->session->userdata('user_data'))['user_role']);?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">PPR Adjustment</h1>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Filter By
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="filter">
                                    <form method="post" id="filter_form">
                                      <div class="form-group">
                                        <label>Payment Name</label>
                                        <select class="form-control input-sm" name="payment_name">
                                          <option value="">All</option>
                                          <?php
                                          foreach ($payment_list as $item) {
                                            echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                                          }
                                          ?>
                                        </select>
                                      </div>
                                      <div class="form-group">
                                        <label for="search">Date:</label>
                                        <div class="input-daterange" id="datepicker">
                                          <input type="text" id="date" name="date" class="form-control" placeholder="Date" value="<?=date('Y-m-d')?>" />
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label>Time</label>
                                          <ul class="time_filter">
                                            <li>
                                              <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                                                  <input name="time_from" type="text" class="form-control" value="">
                                                  <span class="input-group-addon">
                                                      <span class="glyphicon glyphicon-time"></span>
                                                  </span>
                                              </div>    
                                            </li>
                                            <li>
                                              <div class="input-group clockpicker_v2" data-placement="left" data-align="top" data-autoclose="true">
                                                  <input name="time_to" type="text" class="form-control" value="">
                                                  <span class="input-group-addon">
                                                      <span class="glyphicon glyphicon-time"></span>
                                                  </span>
                                              </div>
                                            </li>
                                          </ul>
                                      </div>
                                      <div class="form-group">
                                        <label>Exclude time below:</label>
                                        <select class="form-control input-sm" name="exclude_time">
                                          <option value="0">None</option>
                                          <option value="5">5 mins</option>
                                          <option value="4">4 mins</option>
                                          <option value="3">3 mins</option>
                                        </select>
                                      </div>
                                      <button type="button" class="btn btn-success filter_btn">Filter</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <form method="post" id="form_ppradj">
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-heading trans-list-heading">
                          Transactions today
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="payment_list" width="100%" name="payment_list"
                                       class="table table-striped table-bordered table-hover dataTable no-footer ppr_table" role="grid"
                                       aria-describedby="dt_basic_info">
                                  <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkall" onclick="selectall(this)" <?=(count($today_trans_list)==0) ? 'disable' : ''?>/></th>
										<th>ID</th>
                                        <th>Transaction Date</th>
                                        <th>Payment Name</th>
                                        <th>Completed</th>
                                        <th>Last Adjusted</th>
                                        <th>Time Adjusted</th>
                                        <th>Adjusted by</th>
                                    </tr>
                                  </thead>
                                  <tbody id="main-trans-list">
                                    <?php foreach ($today_trans_list as $item) { ?>
                                      <tr>
                                        <td><input type="checkbox" name="ppra" class="pprcheck" value="<?=$item->trans_id?>" onclick="countChecked(this)" /></td>
										<td><?=$item->trans_id?></td>
                                        <td><?=update_date_format($item->complete_time)?></td>
                                        <td><?=$item->name?></td>
                                        <td><?=str_replace('.000000','',$item->time_completed)?></td>
                                        <td><?=($item->updated_on != '') ? update_date_format($item->updated_on) : ''?></td>
                                        <td class="adj_time"><?='<span>'.action_checker($item->action).' </span>'.$item->time_adjusted?> </td>
                                        <td><?=$item->adjusted_by?></td>
                                      </tr>
                                    <?php } 

                                    function update_date_format($date) {
                                        return date('Y-m-d h:i A', strtotime($date));
                                    }

                                    function action_checker($action) {
                                      if($action == 'deduct') {
                                        $value = '-';
                                      } else if ($action == 'add') {
                                        $value = '+';
                                      } else {
                                        $value = '';
                                      }
                                      return $value;
                                    }

                                    ?>
                                    
                                  </tbody>
                              </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading adjust_heading">
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="time_adjustment">
                                  <form id="time-adjustment-form" method="post">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <label>Action</label>
                                        <select class="form-control input-sm" name="action_adj">
                                          <option value="deduct">Deduct</option>
                                          <option value="add">Add</option>
                                        </select>
                                      </div>
                                      <div class="col-md-6">
                                        <label>Time</label>
                                        <input type="text" name="time_adj" class="form-control timeinput" placeholder="hh:mm" onchange="validateHhMm(this)">    
                                      </div>
                                    </div>
                                    <div class="row reason-con">
                                      <div class="col-md-12 reason">
                                        <label>Reason</label>
                                        <select class="form-control input-sm" name="action_reason" id="action_reason">
                                          <option value=""> -- Select Reason -- </option>
                                          <option>System Failure</option>
                                          <option>COMS speed issue</option>
                                          <option>Accounting System speed issue</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="row reason-con specify-action hidden">
                                      <div class="col-md-12 reason">
                                        <label>Specify System Failure</label>
                                        <textarea class="form-control reason-specify" rows="4" name="reason-specify"></textarea>
                                      </div>
                                    </div>
                                    <button type="button" class="btn btn-success adj_time_btn" onclick="adjust_time()">Submit</button>
                                  </form>
                                </div>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
              </form>
          <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- modal.Multiple delete review -->
      <div id="adjust_confirm" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header label-danger">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Adjust transaction time confirmation</h4>
            </div>
            <div class="modal-body">
              <p>
              Your about to <span class="action"></span> the time of <span class="count_selected"></span> transaction?
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger confirm_adj_btn">Confirm</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end.modal.delete review -->

      <!-- modal.success -->
      <div id="success_adj" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header label-success">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Successful</h4>
            </div>
            <div class="modal-body">
              <p>
              Transaction time has been adjusted.
              </p>
              <p>Reloading in <span class="reload_counter"></span></p> 
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end.modal.success -->

<?php $this->load->view("impl/footer.php");?>
<script src="https://www.datejs.com/build/date.js" type="text/javascript"></script>
<!-- Time Picker -->

<script type="text/javascript">
$(function() {
  $('.time_adjustment').hide(); // hide time adjustment in initial load
});

/**
* AJAX DATA(TIME) ADJUSTMENT
* STORE DATA TIMESTAMP TO TABLE PPR_ADJUSTMENT
*/

var selected = '';
var time = '';
var action = '';
var reason = '';
var updated_by = '<?php echo decrypt($this->session->userdata('user_data'))['username']; ?>';
var url = '<?=base_url()?>PprAdjustment/time_adjust';
var filter_form_data = $('#filter_form').serialize();
var filter_link = '<?=base_url()?>PprAdjustment/filtered_list';

$(".confirm_adj_btn").on("click",function(){
	console.log(selected);
    $(".confirm_adj_btn").attr("disabled", true);
    $.ajax({
        dataType: "json", 
        type: 'POST', 
        url: url, 
        data: {selected:selected,time:time,action:action,reason:reason,updated_by:updated_by},
        success : function(data){
			console.log('end of adjust');
           socket.emit('update_request_transaction_to_successful'); // send data to socktet for realtime update data
            if(data >= 1) {
              $("#adjust_confirm").modal('hide');
              update_table(filter_link);
              clear();
              //$("#success_adj").modal('show');
              var counter = 6;
              setInterval(function(){ 
                counter = counter - 1;
                $('#success_adj .reload_counter').text(counter);
                if(counter == 0) {
                  //location.reload();
                }
              }, 1000);
            }
            console.log(data);
        },
        error: function(error){
            console.log(error);
            console.log('Error occur');
        }
    });
});

function clear() {
  selected = '';
  time = '';
  action = '';
  $('.timeinput').removeClass('valid');
  $('[name="time_adj"]').val('');
  $('#checkall').attr('checked', false);
  $('.specify-action, .time_adjustment').hide();
  $('[name="reason-specify"], #action_reason').val('').removeClass('valid');
  $(".confirm_adj_btn").attr("disabled", false);
}

function update_table(url_path) {
  console.log('update_table trigger');
  var date = moment(); 
  $.ajax({
      dataType: "text", 
      type: 'POST', 
      url: url_path,
      data: $('#filter_form').serialize(),
      success : function(data){
          var objData = JSON.parse(data);
          var html = '';
          $(objData).each(function(index){
            var date_completed = objData[index].time_completed
            html += "<tr>";
            html += "<td><input type='checkbox' name='ppra' class='pprcheck' value='"+objData[index].trans_id+"' onclick='countChecked(this)' /></td>";
			html += "<td>"+objData[index].trans_id+"</td>";
            html += "<td>"+update_date_format(objData[index].complete_time)+"</td>";
            html += "<td>"+objData[index].name+"</td>";
            html += "<td>"+date_completed.replace('.000000',' ')+"</td>";
            html += "<td>"+null_format(objData[index].updated_on)+"</td>";
            html += "<td class='adj_time'><span>"+action_checker(objData[index].action, objData[index].time_adjusted)+" </span>"+null_checker(objData[index].time_adjusted)+"</td>";
            html += "<td>"+null_checker(objData[index].adjusted_by)+"</td>";
            html += "</tr>";
          });
          if(html != '') {
            $('#main-trans-list').html(html);
            $("#checkall").attr("disabled", false);
          } else {
            var ndata = '<tr class="ndata"><td colspan="7">No data found</td></tr>';
            $('#main-trans-list').html(ndata);
            $("#checkall").attr("disabled", true);
          }
          $('.trans-list-heading').text('Filtered transactions');
      },
      error: function(error){
          console.log(error);
          console.log('Error occur');
      }
  });
}

var adjust_time = function(e) {
    var valid = $('.timeinput').attr('validation');
    selected = $('input[name="ppra"]:checked').serializeArray();
    time = $('[name="time_adj"]').val();
    action = $('[name="action_adj"]').val();
    reason = $('[name="action_reason"]').val();
    updated_by = '<?php echo decrypt($this->session->userdata('user_data'))['username']; ?>';

    if(valid != 'true') {
      console.log('time validate');
      $('.timeinput').addClass('invalid');
      time_validate = false;
    } else {
      $('.timeinput').removeClass('invalid');
      $('.timeinput').addClass('valid');
      time_validate = true;
    }

    if(reason == '') {
      console.log('reason validate');
      $('#action_reason').addClass('invalid');
      reason_validate = false;
    } else {
      $('#action_reason').removeClass('invalid');
      $('#action_reason').addClass('valid');
      reason_validate = true;
    }

    if(reason == 'System Failure') {
      reason_specify = $('[name="reason-specify"]').val();
      reason = reason + ': ' + reason_specify;
      if(reason_specify == '') {
        $('.reason-specify').addClass('invalid');
        reason_validate = false;
      } else {
        $('.reason-specify').removeClass('invalid');
        $('.reason-specify').addClass('valid');
        reason_validate = true;
      }
    }

    if(time_validate && reason_validate) {
      console.log('Reason: '+ reason);
      $("#adjust_confirm .action").text(action);
      $("#adjust_confirm .count_selected").text(selected.length);
      $("#adjust_confirm").modal('show');
    }
  
}

/** Time input validation */
function validateHhMm(inputField) {
    var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);
    if (isValid) {
        $(inputField).removeClass('invalid');
        $(inputField).addClass('valid');
        $(inputField).attr('validation', 'true');
    } else {
        $(inputField).removeClass('valid');
        $(inputField).addClass('invalid');
        $(inputField).attr('validation', 'false');
    }
    return isValid;
}

/** Action Reason **/
$( "#action_reason" )
  .change(function() {
    var str = "";
    $( "select option:selected" ).each(function() {
      str += $( this ).text() + " ";
      if($( this ).text() == "System Failure") {
        $('.specify-action').removeClass('hidden');
		$('.specify-action').show();
      } else {
		$('.specify-action').hide();
        $('.specify-action').addClass('hidden');
        $('.reason-specify').removeClass('invalid').removeClass('valid');
      }
    });
  })
  .trigger( "change" );


/** Filter Transaction*/
$('.filter_btn').on('click', function() {
  update_table(filter_link);
});

function null_checker(data) {
  var value = '';
  if (data != null) {
    value = data;
  } 
  return value;
}

function null_format(data) {
  var value = '';
  if (data != null) {
    value = update_date_format(data);
  } 
  return value;
}

function action_checker(action, time) {
  var value = '';
  if(time != null) {
    if(action == 'deduct') {
      value = '-';
    } else if (action == 'add') {
      value = '+';
    }
  }
  return value;
}

function update_date_format(old_date) {
  var date = new Date(old_date);
  var new_date = date.toString('yyyy-MM-dd hh:mm tt');
  return new_date;
}



/** Checked event */
$('.time_adjustment').hide();
$('.adjust_heading').text('Select transaction to adjust time');

var countChecked = function(selected) {  
    console.log('Function trigger');
    $('#checkall').prop('checked', false);        
    var n = $( ".pprcheck:checked" ).length;
    console.log(n);
    if( n > 0) {
        $('.time_adjustment').show();
        $('.adjust_heading').text('Adjust Time');
    } else  {
        $('.time_adjustment').hide();
        $('.adjust_heading').text('Select transaction to adjust time');
    }

    if(selected.checked){
      $(selected).parent().parent().addClass('checked');
    } else {
      $(selected).parent().parent().removeClass('checked');
    }
}; 


$('tbody').on('click', 'tr', function () {
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
    }
    else {
        $(this).addClass('selected');
    }
});        

/** Checkbox select all */
function selectall(source) {
  console.log('selectall trigger');
  checkboxes = document.getElementsByName('ppra');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
  if(source.checked) {
    $('.time_adjustment').show();
    $('#payment_list tbody tr').addClass('checked');
  } else {
     $('.time_adjustment').hide();
     $('#payment_list tbody tr').removeClass('checked');
  }
  
}

/**** PLUGINS ******/

/** Date picker plugin */
$('.input-daterange').datepicker({
    format: "yyyy-mm-dd",
    clearBtn: true,
    orientation: "bottom",
    autoclose: true,
    todayHighlight: true,
    toggleActive: true
});

/** Time picker plugin */
$('.clockpicker').clockpicker()
  .find('input').change(function(){
    $('[name=time_f]').val(this.value);
  });
$('.clockpicker_v2').clockpicker()
.find('input').change(function(){
  $('[name=time_t]').val(this.value);
});
var input = $('#single-input').clockpicker({
  placement: 'bottom',
  align: 'left',
  autoclose: true,
  'default': 'now'
});

$('.clockpicker-with-callbacks').clockpicker({
  donetext: 'Done',
  init: function() { 
    console.log("colorpicker initiated");
  },
  beforeShow: function() {
    console.log("before show");
  },
  afterShow: function() {
    console.log("after show");
  },
  beforeHide: function() {
    console.log("before hide");
  },
  afterHide: function() {
    console.log("after hide");
  },
  beforeHourSelect: function() {
    console.log("before hour selected");
  },
  afterHourSelect: function() {
    console.log("after hour selected");
  },
  beforeDone: function() {
    console.log("before done");
  },
  afterDone: function() {
    console.log("after done");
  }
})
$('.clockpicker-with-callbacks-v2').clockpicker({
  donetext: 'Done',
  init: function() { 
    console.log("colorpicker initiated");
  },
  beforeShow: function() {
    console.log("before show");
  },
  afterShow: function() {
    console.log("after show");
  },
  beforeHide: function() {
    console.log("before hide");
  },
  afterHide: function() {
    console.log("after hide");
  },
  beforeHourSelect: function() {
    console.log("before hour selected");
  },
  afterHourSelect: function() {
    console.log("after hour selected");
  },
  beforeDone: function() {
    console.log("before done");
  },
  afterDone: function() {
    console.log("after done");
  }
})
.find('input').change(function(){
  console.log(this.value);
});

$('#check-minutes').click(function(e){
  e.stopPropagation();
  input.clockpicker('show')
  .clockpicker('toggleView', 'minutes');
});
if (/mobile/i.test(navigator.userAgent)) {
  $('input').prop('readOnly', true);
}

</script>
