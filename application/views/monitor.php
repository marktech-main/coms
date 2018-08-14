<?php $this->load->view("impl/header.php");?>
<div id="page-wrapper">
  <div class="row" id="tasks-form">
    <div class="col-lg-12">
      <h1 class="page-header">Monitoring User</h1>
      <section class="slide-fade demo-container">
        <div class="table100 ver3 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">Username</th>
									<th class="cell100 column2">Session</th>
									<th class="cell100 column3">Last Active</th>
									<th class="cell100 column4">Status</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="table100-body js-pscroll">
						<table id="content_container">
							<tbody>
              </tbody>
						</table>
					</div>
				</div>

      </section>
    </div>
  </div>
</div>
<?php $this->load->view("impl/footer.php");?>
<style>
/*.add-to-list{background:#fff;border-radius:.5em;padding:1em;display:inline-block;width:auto}.add-to-list li{list-style:none;background:#d1703c;border-bottom:0 solid #fff;color:#fff;height:0;padding:0 .5em;margin:0;overflow:hidden;line-height:2em;width:10em}.add-to-list li:hover{cursor:crosshair}.add-to-list li.show{height:2em;border-width:2px}.add-to-list.fade li{transition:all .4s ease-out;opacity:0;height:2em}.add-to-list.fade li.show{opacity:1}.add-to-list.slide-fade li{transition:all .4s ease-out;opacity:0}.add-to-list.slide-fade li.show{opacity:1}.add-to-list.swing{perspective:100px}.add-to-list.swing li{opacity:0;transform:rotateX(-90deg);transition:all .5s cubic-bezier(.36,-.64,.34,1.76)}.add-to-list.swing li.show{opacity:1;transform:none;transition:all .5s cubic-bezier(.36,-.64,.34,1.76)}.add-to-list.swing-side{perspective:200px}.add-to-list.swing-side li{transform:rotateY(-90deg);transition:all .5s cubic-bezier(.36,-.64,.34,1.76)}.add-to-list.swing-side li.show{opacity:1;transform:none;transition:all .5s cubic-bezier(.36,-.64,.34,1.76)}.demo{background:#f1cbbc}.post-header-container{background:0 0;perspective:800px;position:absolute;left:50%;bottom:0;transform-style:preserve-3d;transform:translateX(-50%) rotateX(-55deg) rotateY(0) skewX(30deg);font-size:64px}.post-header-container .add-to-list{background:0 0}.post-header-container .add-to-list li{border-color:#f1cbbc!important;transform:translateZ(2em) rotateX(90deg);cursor:default;transition:all .8s cubic-bezier(.36,-.64,.34,1);opacity:0}.post-header-container .add-to-list li.show{transform:none;opacity:1}.post-header-container .add-to-list li.hide{opacity:0;height:0;border-width:0;transition:all 1s linear;transform:rotateX(-50deg);transition:all .5s ease-out}@media(max-width:550px){.post-header-container{font-size:40px}}@media(max-width:420px){.post-header-container{font-size:24px}}*/
/*.slide-fade li {
  transition: all 0.3s ease-out;
  opacity: 0;
}
.slide-fade li.show {
  opacity: 1;
}*/

#content_container td{
  display: table-cell !important;
}

#content_container td.cell100{
  transition: all 0.3s ease-out;
  opacity: 0;
}

#content_container td.cell100.show{
  opacity: 1;
}

/*//////////////////////////////////////////////////////////////////
[ Scroll bar ]*/
.js-pscroll {
  position: relative;
  overflow: hidden;
}

.table100 .ps__rail-y {
  width: 9px;
  background-color: transparent;
  opacity: 1 !important;
  right: 5px;
}

.table100 .ps__rail-y::before {
  content: "";
  display: block;
  position: absolute;
  background-color: #ebebeb;
  border-radius: 5px;
  width: 100%;
  height: calc(100% - 30px);
  left: 0;
  top: 15px;
}

.table100 .ps__rail-y .ps__thumb-y {
  width: 100%;
  right: 0;
  background-color: transparent;
  opacity: 1 !important;
}

.table100 .ps__rail-y .ps__thumb-y::before {
  content: "";
  display: block;
  position: absolute;
  background-color: #cccccc;
  border-radius: 5px;
  width: 100%;
  height: calc(100% - 30px);
  left: 0;
  top: 15px;
}


/*//////////////////////////////////////////////////////////////////
[ Table ]*/

.limiter {
  width: 1366px;
  margin: 0 auto;
}

.container-table100 {
  width: 100%;
  min-height: 100vh;
  background: #fff;

  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  padding: 33px 30px;
}

.wrap-table100 {
  width: 1170px;
}

/*//////////////////////////////////////////////////////////////////
[ Table ]*/
.table100 {
  background-color: #fff;
}

table {
  width: 100%;
}

th, td {
  font-weight: unset;
  padding-right: 10px;
}

.column1 {
  width: 33%;
  padding-left: 40px;
}

.column2 {
  width: 13%;
}

.column3 {
  width: 22%;
}

.column4 {
  width: 19%;
}

.column5 {
  width: 13%;
}

.table100-head th {
  padding-top: 18px;
  padding-bottom: 18px;
}

.table100-body td {
  padding-top: 16px;
  padding-bottom: 16px;
}

/*==================================================================
[ Fix header ]*/
.table100 {
  position: relative;
  padding-top: 60px;
}

.table100-head {
  position: absolute;
  width: 100%;
  top: 0;
  left: 0;
}

.table100-body {
  max-height: 585px;
  overflow: auto;
}

.table100.ver3 {
  background-color: #393939;
}

.table100.ver3 th {
  font-family: Lato-Bold;
  font-size: 15px;
  color: #00ad5f;
  line-height: 1.4;
  text-transform: uppercase;
  background-color: #393939;
}

.table100.ver3 td {
  font-family: Lato-Regular;
  font-size: 15px;
  color: #b9b9b9;
  line-height: 1.4;
  background-color: #222222;
}


/*---------------------------------------------*/

.table100.ver3 {
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
  -moz-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
  -webkit-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
  -o-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
  -ms-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
}

.table100.ver3 .ps__rail-y {
  right: 5px;
}

.table100.ver3 .ps__rail-y::before {
  background-color: #4e4e4e;
}

.table100.ver3 .ps__rail-y .ps__thumb-y::before {
  background-color: #00ad5f;
}


</style>

<!-- <script src="https://cssanimation.rocks/javascript/site.js"></script>
<script src="https://cssanimation.rocks/javascript/vendor/wow.min.js"></script>
<script src="https://cssanimation.rocks/javascript/custom/list_items.js"></script> -->
<script>
$( document ).ready(function() {
  var previous_data = {};
  var new_data;
  socket.emit('get_monitoring_data');
  socket.on('force_update_monitoring_user', function(message){
    console.log('force update -> get new data from DB');
  });

  socket.on('received_monitoring_data', function(data){
    console.log('Received update -> get new data from Server');
    // console.log(data);
    new_data = data;
    // console.log(previous_data);
    // console.log(new_data);
    for (var key in new_data) {
        if(new_data[key].role != '5'){
          continue;
        }
        if (previous_data.hasOwnProperty(key)) {
          // just update old record
          var element = $('#content_container tr[data-id="' + key + '"]');
          if(previous_data[key].session.length != new_data[key].session.length ){
            element.find('td:nth-child(2)').html(new_data[key].session.length);

            // add effect to update new data
          }
          if(previous_data[key].status != new_data[key].status ){
            element.find('td:nth-child(3)').html('<time class="timeago" datetime="'+new_data[key].last_activity+'">'+new Date(new_data[key].last_activity)+'</time>');
            element.find('td:nth-child(4)').html(new_data[key].status);
            // add effect to update new data
          }

        }else{
          // new record
          $content = '';
          $content += '<tr data-id="'+key+'" class="row100 body">';
          $content += '<td class="cell100 column1 show">'+key+'</td>';
          $content += '<td class="cell100 column2 show">'+new_data[key].session.length+'</td>';
          $content += '<td class="cell100 column3 show"><time class="timeago" datetime="'+new_data[key].last_activity+'">'+new Date(new_data[key].last_activity)+'</time></td>';
          $content += '<td class="cell100 column4 show">'+new_data[key].status+'</td>';
          $content += '</tr>';
          $('#content_container').append($content);
        }
    }

    // remove previous_data record that not exist in new record
    var new_data_key = Object.keys(new_data);
    var previous_data_key = Object.keys(previous_data);
    console.log(previous_data);
    for (var key in previous_data) {
        if (!new_data.hasOwnProperty(key)) {
          var element = $('#content_container tr[data-id="' + key + '"]');
          console.log('remove record : '+key);
          element.remove();
        }
    }
    previous_data = new_data;
    jQuery("time.timeago").timeago();
  });


  function check_diff(previous_data, new_data){
    var format_data = {};
    for (var key in new_data) {
        if (p.hasOwnProperty(key)) {
            console.log(key + " -> " + new_data[key]);
            // comparation
            if(previous_data[key] != undefined && previous_data[key].status == new_data[key].status){
              new_data.flag = true;
            }else{
              new_data.flag = false;
            }
        }
    }
  }
});
</script>
