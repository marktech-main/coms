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
  <script async src="/js/bootstrap.min.js"></script>

  <!-- Report chart -->
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
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
      <label><span class="datenow"><?=date("F d, Y")?></span></label>
    </div>
    <div class="logo">
      <label>PPR</label><small>Payment Performance Rating</small>
    </div>
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
    
    <table class="table table-striped">
      <thead>
        <tr>
          <td colspan="2">Rate</td>
          <td>Name</td>
          <td>Average Speed</td>
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
<script src='<?php echo 'http://'.$_SERVER['HTTP_HOST'].':1337'; ?>/socket.io/socket.io.js'></script>
<script type="text/javascript">
$(document).ready(function () {

 window.socket = io('http://192.168.90.215:1337');
 socket.on('connected', function(message){
   console.log('connected real-time server');


   // calling node server with "initial_script_pps" function
   socket.emit('initial_script_pps');
   // realtime_load(); // use for initial only
   update_table_section(); // group init function later
   update_left_section(); // group init function later
   
 });

 socket.on('pps_weekly_update', function(message) {
  update_left_section();
  console.log('cron every monday');
 });

socket.on('pps_end_day_update', function(message) {
  insert_previous();
  console.log('cron every end of the day.');
 });

 socket.on('update_transaction_successful', function(data){
   console.log('you have new successful transaction');
 
    // update table data

    update_table_section();
 });

 function update_table_section(){
    $.ajax({
    type     :'post',
    url      : "<?=base_url()?>ppr/getAll",
    async    : false,
    success: function(data) {
      var objData = JSON.parse(data);
      var html = "";
      var number = 1;
      var x = 0;
      //var score_value = "";
      if(data!=""){
      $(objData).each(function(i){
        var data = {id:objData[i].cs_id, score:objData[i].total_score}
        var newData = JSON.stringify(data);
        var tmp_filename = 'insert_to_json.php';
        $.ajax({
            type: 'POST',
            url: '/json/' + tmp_filename,
            data: {data:newData, x:x},
            async: false,
            success: function(data) { 
              console.log(data);
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
              html += "<td class='total_rate' id='"+objData[i].cs_id +"'>" + objData[i].total_score +"%</td>";
              html += "<td>" + objData[i].cs_name +"</td>";
              html += "<td id='ave_speed'>" + objData[i].speed_min +" min</td>";
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
      if(data=="[]"){
        $('#rate_data').empty();
      }
      }
    });
 }

function insert_previous() {
   $.ajax({
    type     :'post',
    url      : "<?=base_url()?>ppr/getAll",
    async    : false,
    success: function(data) {
      var objData = JSON.parse(data);
      var x = 0;
      $(objData).each(function(i){
        var data = {id:objData[i].cs_id, score:objData[i].total_score}
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

 function update_left_section(){
    $.ajax({
    type     :'post',
    url      : "<?=base_url()?>ppr/top_1stweek",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last week rate</tr>";
        $(objData).each(function(i){
            html += "<tr>";
            html += "<td>" + objData[i].cs_name +"</td>";
            html += "<td>" + objData[i].total_score +"%</td>";
            html +="</tr>";
          $('#top_1stw').html(html);
        });
      }
    });

    /**Last 2 weeks top performance*/
    $.ajax({
    type     :'post',
    url      : "<?=base_url()?>ppr/top_2ndweek",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last 2 weeks rate</tr>";
        $(objData).each(function(i){
          html += "<tr>";
          html += "<td>" + objData[i].cs_name +"</td>";
          html += "<td>" + objData[i].total_score +"%</td>";
          html +="</tr>";
          $('#top_2ndw').html(html);
        });
      }
    });

    /**Last week poor performance*/
    $.ajax({
    type     :'post',
    url      : "<?=base_url()?>ppr/poor_1stweek",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last week rate</tr>";
        $(objData).each(function(i){
          html += "<tr>";
          html += "<td>" + objData[i].cs_name +"</td>";
          html += "<td>" + objData[i].total_score +"%</td>";
          html +="</tr>";
          $('#poor_1stw').html(html);
        });
      }
    });

    /**Last 2 weeks poor performance*/
    $.ajax({
    type     :'post',
    url      : "<?=base_url()?>ppr/poor_2ndweek",
    async    : false,
      success: function(data) {
        var html = '';
        var objData = JSON.parse(data);
        html += "<tr><td colspan='2' class='week_label'>Last 2 weeks rate</tr>";
        $(objData).each(function(i){
          html += "<tr>";
          html += "<td>" + objData[i].cs_name +"</td>";
          html += "<td>" + objData[i].total_score +"%</td>";
          html +="</tr>";
          $('#poor_2ndw').html(html);
        });
      }
    });
  }

});


</script>
</html>

 
