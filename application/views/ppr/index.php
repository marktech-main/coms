<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">
  <title>PPR</title>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/main.css" rel="stylesheet">


  <!-- Report chart -->
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

  <!-- load checking ip js -->
  <script src="/js/check_local_ip.js"></script>

  <script async src="/js/bootstrap.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>

  <script type="text/javascript" src="https://pubnub.github.io/eon/v/eon/1.0.0/eon.js"></script>
  <link type="text/css" rel="stylesheet" href="https://pubnub.github.io/eon/v/eon/1.0.0/eon.css"/>
  </head>
  <style type="text/css">
    .container {
      width: 80%;
      margin: 20px auto;
    }

    .p {
      text-align: center;
      font-size: 14px;
      padding-top: 140px;
    }
  </style>
</head>
<body>
  <div id="mainWrapper">
    <div class="pull-right date_range">
      <!-- <label><span class="startdate"></span> - <span class="enddate"></span></label> -->
      <label><span class="datenow"></span></label>
    </div>
    <div class="logo">
      <img src="/images/ppr_logo.png"><!-- <label>PPR</label> --><small>Payment Performance Rating</small>
    </div>
    <!-- Online user-->
  <div class="online_user">
    <p><span></span> team online</p>
    <img src="/images/online2.svg">
  </div>
  <!-- End of online user-->
  <div class="col-md-3 top_per">
    <div class="col-md-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <td colspan="2">Highest Rate</td>
          </tr>
        </thead>
        <tbody id="top_1stw">
        </tbody>
        <tbody id="top_2ndw">
        </tbody>
      </table>
    </div>

    <div class="col-md-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <td colspan="2">Lowest Rate</td>
          </tr>
        </thead>
        <tbody id="poor_1stw">
        </tbody>
        <tbody id="poor_2ndw">
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-9" align="right">
    <!-- Monthly comparison -->
      <div class="container_comparison">
        <table class="table table-striped comparison_data_table">
        <thead>
          <tr>
            <td class="label_last_2month">May 2017</td>
            <td class="mid_month label_last_1month">Jun 2017</td>
            <td>Current Month</td>
          </tr>
        </thead>
        <tbody id="comparison_data">
          <tr>
            <td class="last_2month_data">avg <span class="ave_min"></span> min / transaction <br> <span class="tot_min"></span> min / <span class="tot_trans"></span> transaction</td>
            <td class="mid_month last_1month_data">avg <span class="ave_min"></span> min / transaction <br> <span class="tot_min"></span> min / <span class="tot_trans"></span> transaction</td>
            <td class="cur_month_data">avg <span class="ave_min"></span> min / transaction <br> <span class="tot_min"></span> min / <span class="tot_trans"></span> transaction</td>
          </tr>
        </tbody>
      </table>
      </div>
      <!-- End of monthly comparison -->
    <table class="table table-striped">
      <thead>
        <tr>
          <td colspan="2">Rate</td>
          <td>Name</td>
          <td>Min</td>
          <td>Avg</td>
          <td>Max</td>
          <td>Transaction</td>
        </tr>
      </thead>
      <tbody id="rate_data">
      </tbody>
      <tbody>
      </tbody>
    </table>
  </div>


</div><!-- #mainWrapper -->
</body>
<script src='https://cn1.super7tech.com/socket.io/socket.io.js'></script>
<script type="text/javascript">

// transaction
$(document).ready(function () {
  $('.datenow').text('<?=date("F d, Y")?>');
  setInterval(function(){
     $('.datenow').text('<?=date("F d, Y")?>');
  }, 60000);

  get_date_now();

  setInterval(function(){ 
    get_date_now();
  }, 60000);

  function get_date_now() {
    var d = new Date();
    var month = get_month();
    var day = d.getDate();
    var year = d.getFullYear();
    var time = d.getTime();
    $('.datenow').text( month + ' ' +  day + ', ' + year);
  }

  function get_month() {
    var d = new Date();
    var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];    
    return month[d.getMonth()];
  }

  window.socket = io('https://cn1.super7tech.com');
// update_table_section(); // group init function later
  socket.on('connected', function(message){
    console.log('connected real-time server');
    // calling node server with "initial_script_pps" function
    socket.emit('initial_script_pps');
    // realtime_load(); // use for initial only
    update_table_section(); // group init function later
    update_weekly(); // group init function later
    update_monthly();
    update_currmonth();
    update_online_user();

  });


  socket.on('pps_weekly_update', function(message) {
    update_weekly();
    console.log('cron every monday');
  });

  socket.on('ppr_monthly_update', function(message) {
    update_monthly();
    send_monthly_report();
    console.log('cron every fisrt day of the month');
  });

  socket.on('ppr_online_user_update', function(message) {
    update_online_user();
    console.log('cron every update of user logged status');
  });

  socket.on('pps_end_day_update', function(message) {
    insert_previous();
    console.log('cron every end of the day.');
  });

 socket.on('update_transaction_successful', function(data){
   console.log('new successful transaction');
    // update table data
    update_table_section();
    update_currmonth();
 });

 function update_table_section(){
    console.log('updating update_table_section');
    $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/getAll",
    async    : false,
    success: function(data) {
      var objData = JSON.parse(data);
      var html = "";
      var number = 1;
      var x = 0;
      //var score_value = "";
      if(data!=""){
      $(objData).each(function(i){
        var data = {id:objData[i].team_id, score:objData[i].total_score}
        var newData = JSON.stringify(data);
        var tmp_filename = 'insert_to_json.php';
        $.ajax({
            type: 'POST',
            url: '/json/' + tmp_filename,
            data: {data:newData, x:x},
            async: false,
            success: function(data) {
              html +="<tr>";
              html += "<td class='caret_label'>";
              switch(data) {
                  case '2':
                      html += "<span class='dropup'><span class='caret' id='caret_up'></span></span>";
                      break;
                  case '1':
                      html += "<span class='caret' id='caret_down'></span>";
                      break;
                  case '0':
                       html += "";
              }
              html += "</td>";
              html += "<td class='total_rate' id='"+objData[i].team_id +"'>" + objData[i].total_score +"%</td>";
              html += "<td>" + objData[i].name +"</td>";
              html += "<td id='ave_speed'>" + objData[i].min_speed +"</td>";
              html += "<td id='ave_speed'>" + objData[i].ave_speed +"</td>";
              html += "<td id='ave_speed'>" + objData[i].max_speed +"</td>";
              html += "<td id='tot_trans'>" + objData[i].total_trans +"</td>";
              html +="</tr>";
            },
            error: function(data) {
              console.log('error');
            },
        });

        number++;
        x++;
        $('#rate_data').html(html);
      });
      }
      }
    });
 }

  function update_currmonth() {
    console.log('udpating update_currmonth');
  $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/curr_mon_comparison",
    async    : false,
      success: function(data) {
        var objData = JSON.parse(data);
        $('.cur_month_data .ave_min').text(objData.ave_speed);
        $('.cur_month_data .tot_min').text(objData.tot_min);
        $('.cur_month_data .tot_trans').text(objData.tot_trans);
      }
    });
  }

function insert_previous() {
   $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/getAll",
    async    : false,
    success: function(data) {
      var objData = JSON.parse(data);
      var x = 0;
      $(objData).each(function(i){
        var data = {id:objData[i].team_id, score:objData[i].total_score}
        var newData = JSON.stringify(data);
        var tmp_filename = 'insert_previous.php';
        $.ajax({
            type: 'POST',
            url: '/json/' + tmp_filename,
            data: {data:newData, x:x},
            async: false,
            success: function(data) {
              console.log(data);
            },
            error: function(data) {
              console.log('error');
            },
        });
      });
    }
  });
}

 function update_weekly(){
    console.log('udpating update_weekly');
    /**Last week top performance*/
    $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/top_1stweek",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last week rate</tr>";
        $(objData).each(function(i){
            html += "<tr>";
            html += "<td>" + objData[i].name +"</td>";
            html += "<td>" + objData[i].total_score +"%</td>";
            html +="</tr>";
          $('#top_1stw').html(html);
        });
      }
    });

    /**Last week poor performance*/
    $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/poor_1stweek",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last week rate</tr>";
        $(objData).each(function(i){
          html += "<tr>";
          html += "<td>" + objData[i].name +"</td>";
          html += "<td>" + objData[i].total_score +"%</td>";
          html +="</tr>";
          $('#poor_1stw').html(html);
        });
      }
    });

  }


  function update_monthly(){
    console.log('udpating update_monthly');
    /**Last month poor performance*/
    $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/poor_lastmonth",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last month rate</tr>";
        $(objData).each(function(i){
          html += "<tr>";
          html += "<td>" + objData[i].name +"</td>";
          html += "<td>" + objData[i].total_score +"%</td>";
          html +="</tr>";
          $('#poor_2ndw').html(html);
        });
      }
    });

    /**Last month top performance*/
    $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/top_lastmonth",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last month rate</tr>";
        $(objData).each(function(i){
          html += "<tr>";
          html += "<td>" + objData[i].name +"</td>";
          html += "<td>" + objData[i].total_score +"%</td>";
          html +="</tr>";
          $('#top_2ndw').html(html);
        });
      }
    });

    /** Monthly comparison*/
    $('.label_last_2month').text('<?=date("F Y",strtotime("-2 month"))?>');
    $('.label_last_1month').text('<?=date("F Y",strtotime("-1 month"))?>');
    $.ajax({
    type     :'post',
    url      : "<?=base_url()?>Ppr/pre_mon_comparison",
    async    : false,
      success: function(data) {
        var objData = JSON.parse(data);
        /*last 1 month*/
        $('.last_1month_data .ave_min').text(objData['last1month'].ave_speed);
        $('.last_1month_data .tot_min').text(objData['last1month'].tot_min);
        $('.last_1month_data .tot_trans').text(objData['last1month'].tot_trans);
        /*last 2month*/
        $('.last_2month_data .ave_min').text(objData['last2month'].ave_speed);
        $('.last_2month_data .tot_min').text(objData['last2month'].tot_min);
        $('.last_2month_data .tot_trans').text(objData['last2month'].tot_trans);
      }
    });
  }

  /** count of online users*/
  function update_online_user() {
    $.ajax({
    type     :'post',
    contentType: "application/json; charset=utf-8",
    url      : "<?=base_url()?>Ppr/get_online_users",
    async    : false,
      success: function(data) {
        var objData = JSON.parse(data);
        $('.online_user p span').text(objData[0].tot_online);
      }
    });
  }

  /** monthly auto send report*/
  function send_monthly_report() {
    $.ajax({
    type     :'post',
    url      : '<?=base_url()?>api/pprsendreport',
    async    : false,
    success: function(data) {
        console.log(data);
      }
    });
  }

});


</script>
</html>
