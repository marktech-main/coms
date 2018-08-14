/**
 * Created by takorn.aek on 10/02/2015.
 * Updared by takorn.aek on 07/11/2016.
 */
 // common data

 // connect to socket server
 var common_payment_team_list = ['2','3','5','99'];
 window.username = localStorage['username'];
 window.user_role = localStorage['ur'];
 window.socket = io('https://cn1.super7tech.com',{query: 'username='+username+'&role='+user_role+''});
 console.log('username : '+username);
 console.log('role : '+user_role);
 socket.on('connected', function(message){
  //  console.log(JSON.stringify(message));
   $.notify("connected to socket server", {className: 'success',autoHideDelay: 3000});
   update_notification_bar();
   update_announcement_content(message.announcement);

   if(message.uuid != ''){
     localStorage['uuid'] = message.uuid;
   }

  //  socket.emit('get_statistic');
 });
 socket.on('get_statistic', function(message){
   console.log(message);
 });

 socket.on('create_request_transaction', function(message){
   if($('#dt_request_transaction').length){
     console.log(message);
     console.log(message.uuid);
     localStorage['uuid'] = message.uuid;
     filter_uuid = message.uuid;
     oTable.fnDraw(false); // re-rendering datatable
   }
   update_header_statistic(); // to do update header statistic
   // check if user_id is payment team
   console.log('checking is payment ?');
   console.log(jQuery.inArray(localStorage['ur'], common_payment_team_list));
   if(jQuery.inArray(localStorage['ur'], common_payment_team_list) != -1){
     var noti_status = 'info';
     var noti_message = 'You have new request';
     $.notify(noti_message, {className: noti_status});
   	 console.log('play sound new request');
     play_new_request_audio(); // play sound for new request
   }
  //  $.ajax({
  //    method: "POST",
  //    url: 'transaction/am_i_payment_team',
  //    data: message,
  //    dataType: "JSON",
  //    success: function(data){
  //     //  console.log(data);
  //      if(data.state){ // if true display notification
  //        $.notify(data.message, {className: data.message_status.toLowerCase()});
	// 	 console.log('play sound new request');
  //        play_new_request_audio(); // play sound for new request
  //      }
  //    }
  //  });
 });
 socket.on('update_request_transaction', function(message){
   localStorage['uuid'] = message.uuid;
   if($('#dt_request_transaction').length){
     filter_uuid = message.uuid;
     oTable.fnDraw(false); // re-rendering datatable
   }
   console.log('check point ----- stop 1 ');
   update_header_statistic(); // to do update header statistic
   // check if user_id  is equal current user_id then do post to controller for insert notification to DB
   console.log('debugging function am_i_requester is working or not ');
   $.ajax({
     method: "POST",
     url: 'transaction/am_i_requester',
     data: message,
     dataType: "JSON",
     success: function(data){
      //  console.log(data);
       if(data.state){ // if true display notification
         console.log('test ------------- log');
         $.notify(data.message, {className: data.message_status.toLowerCase()});
         update_notification_bar();
         play_update_request_audio(); // play sound for update request

         if($('[name="confirm_pending"]').length > 0 && data.transaction_status == 'PENDING'){
           console.log(data);
          var confirm_content = '';
          confirm_content += '<span>'+data.reason+'</span>';
          confirm_content += '<button type="button" class="btn btn-danger pull-right" name="n_confirm_pending_btn" action="decline-pending" data-target="'+message.transaction_id+'">Decline</button>';
          confirm_content += '<button type="button" class="btn btn-success pull-right" name="y_confirm_pending_btn" action="accept-pending" data-target="'+message.transaction_id+'" style="margin-right:5px;">Accept</button>';
          $('[name="confirm_pending"]').html(confirm_content);
         }else{
           console.log('false') ;
         }

       }
     }
   });
 });

 socket.on('update_pending_transaction', function(message){
   console.log('on update_pending_transaction');
   console.log(message);
   localStorage['uuid'] = message.uuid;
   if(message.status == 'DECLINE'){
     if($('#dt_request_transaction').length){
       filter_uuid = message.uuid;
       oTable.fnDraw(false); // re-rendering datatable
     }
   }
   if(jQuery.inArray(localStorage['ur'], common_payment_team_list) !== -1){
     $.notify('CS '+message.status+' your request for pending transaction', {className: 'info'});
      console.log('play sound new request');
      play_new_request_audio(); // play sound for new request
   }
   // check if viewing verify-form & display notification message then check if user_id is payment team
  //  $.ajax({
  //    method: "POST",
  //    url: 'transaction/am_i_payment_team',
  //    dataType: "JSON",
  //    success: function(data){
  //     //  console.log(data);
  //      if(data.state){ // if true display notification
  //        $.notify('CS '+message.status+' your request for pending transaction', {className: 'info'});
  //        update_notification_bar();
  //        play_new_request_audio(); // play sound for new request
  //      }
  //    }
  //  });
 });

socket.on('mark_all_notify_as_read', function(message){
  update_notification_bar();
})

 socket.on('error', console.error.bind(console));
 socket.on('message', console.log.bind(console));

// timeago init
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();
});
$('li').on('click', '[action="notify"]', function(){
  if($(this).hasClass('unread-notify')){ // if unread
    $(this).removeClass('unread-notify'); // remove css class
    socket.emit('read_notify',$(this).data('notify-id')); // send data to sockter for update data
  }
  var redirect = 'update';
  $.redirectPost(redirect, {transaction_id: $(this).data('transaction-id'), state: '1'}); // redirect to transaction detail page
});
$('[name="mark_all_notify_as_read"] > a').on('click',function(){
  // check if there is any records then do ajax call for update those records
  socket.emit('mark_all_notify_as_read');
  if($('li.notify-msg').length > 0 && $('[name="notify_counter"]').attr('data-badge') > 0){
    $.ajax({
      method: "POST",
      url: 'notify/mark_all_notify_as_read',
      dataType: "JSON",
      success: function(data){
        if(data.state){ // if true display notification
          socket.emit('mark_all_notify_as_read');
        }
      }
    });
  }
});
// jquery extend function
$.extend(
{
   redirectPost: function(location, args)
   {
       var form = '';
       $.each( args, function( key, value ) {
           value = value.split('"').join('\"')
           form += '<input type="hidden" name="'+key+'" value="'+value+'">';
       });
       $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
   }
});
// cast string to Money format
Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
// highlight selected row
if(typeof window.oTable != undefined){
    $('tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }
        else {
            $('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
}
function update_notification_bar(){
  // get total notify messages
  // get_total_unread_notify_message();
  // get notify message list
  // get_latest_notify_message();
}
// to update total number of notify message that not viewed
function get_total_unread_notify_message(){
  $.ajax({
    method: "POST",
    url: '/notify/get_total_unread_notify_message',
    dataType: "JSON",
    success: function(data){
      // console.log('load data get_total_unread_notify_message');
      if(data.total_record != 0){
        if (!$(".badge1")[0]){
          $('[name="notify_counter"]').addClass('badge1');
        }
      }else{
        $('[name="notify_counter"]').removeClass('badge1');
      }
      $('[name="notify_counter"]').attr('data-badge',data.total_record); // data if 0 then removeClass badge1 data-badge="0"
    }
  });
}
// to update notify message list
function get_latest_notify_message(){
  $.ajax({
    method: "POST",
    url: '/notify/get_latest_notify_message',
    dataType: "TEXT",
    success: function(data){
      // console.log('load data get_latest_notify_message');
      $('[name="latest_notify_div"]').html(data);
      jQuery("time.timeago").timeago();
    }
  });
}

// univeral function to update header statistic
function update_header_statistic(){
  var uuid = '';
  if(localStorage.uuid){
    uuid = localStorage['uuid'];
  }
  console.log('current uuid');
  console.log(uuid);
  $.ajax({
    method: "POST",
    url: 'transaction/get_request_transaction_statistic',
    data: {'uuid': uuid},
    dataType: "JSON",
    success: function(data){
      // HTML mod
      $div_total_request = $('[name="div_total_request"]');
      $div_total_deposit = $('[name="div_total_deposit"]');
      $div_total_withdrawal = $('[name="div_total_withdrawal"]');
      $div_total_transfer = $('[name="div_total_transfer"]');
      $div_total_new_register = $('[name="div_total_new_register"]');
      $div_total_cancelled = $('[name="div_total_cancelled"]');

      $div_cs_total_request = $('[name="div_cs_total_request"]');
      $div_cs_total_queue = $('[name="div_cs_total_queue"]');
      $div_cs_total_processing = $('[name="div_cs_total_processing"]');
      $div_cs_total_pending = $('[name="div_cs_total_pending"]');
      $div_cs_total_successful = $('[name="div_cs_total_successful"]');
      $div_cs_total_cancelled = $('[name="div_cs_total_cancelled"]');

      if($div_total_request.text() !== data.total_request){
        $div_total_request.fadeOut(500, function() {
            $(this).text(data.total_request).fadeIn(500); // update total_request
        });
      }
      if($div_total_deposit.text() !== data.total_deposit){
        $div_total_deposit.fadeOut(500, function() {
            $(this).text(data.total_deposit).fadeIn(500); // update total_deposit
        });
      }
      if($div_total_withdrawal.text() !== data.total_withdrawal){
        $div_total_withdrawal.fadeOut(500, function() {
            $(this).text(data.total_withdrawal).fadeIn(500); // update total_deposit
        });
      }
      if($div_total_transfer.text() !== data.total_transfer){
        $div_total_transfer.fadeOut(500, function() {
            $(this).text(data.total_transfer).fadeIn(500); // update total_transfer
        });
      }
      if($div_total_new_register.text() !== data.total_new_register){
        $div_total_new_register.fadeOut(500, function() {
            $(this).text(data.total_new_register).fadeIn(500); // update total_new_register
        });
      }
      if($div_total_cancelled.text() !== data.total_cancelled){
        $div_total_cancelled.fadeOut(500, function() {
            $(this).text(data.total_cancelled).fadeIn(500); // update total_cancelled
        });
      }
      if($div_cs_total_request.text() !== data.div_total_request){
        $div_cs_total_request.fadeOut(500, function() {
            $(this).text(data.div_total_request).fadeIn(500); // update div_cs_total_request for cs
        });
      }
      if($div_cs_total_queue.text() !== data.div_cs_total_queue){
        $div_cs_total_queue.fadeOut(500, function() {
            $(this).text(data.div_cs_total_queue).fadeIn(500); // update div_cs_total_queue for cs
        });
      }
      if($div_cs_total_processing.text() !== data.total_processing){
        $div_cs_total_processing.fadeOut(500, function() {
            $(this).text(data.total_processing).fadeIn(500); // update div_cs_total_processing for cs
        });
      }
      if($div_cs_total_pending.text() !== data.total_pending){
        $div_cs_total_pending.fadeOut(500, function() {
            $(this).text(data.total_pending).fadeIn(500); // update div_cs_total_pending for cs
        });
      }
      if($div_cs_total_successful.text() !== data.total_successful){
        $div_cs_total_successful.fadeOut(500, function() {
            $(this).text(data.total_successful).fadeIn(500); // update div_cs_total_successful for cs
        });
      }
      if($div_cs_total_cancelled.text() !== data.total_cancelled){
        $div_cs_total_cancelled.fadeOut(500, function() {
            $(this).text(data.total_cancelled).fadeIn(500); // update div_cs_total_cancelled for cs
        });
      }
    }
  });
}
// create and play new request audio
function play_new_request_audio(){
  if($('#new_request_audio').length === 0){
    $('<audio id="new_request_audio"><source src="audio/new_msg.ogg" type="audio/ogg"><source src="audio/new_msg.mp3" type="audio/mpeg"><source src="audio/new_msg.wav" type="audio/wav"></audio>').appendTo('body');
  }
  $('#new_request_audio')[0].play();
}
// create and play update request audio
function play_update_request_audio(){
  if($('#update_request_audio').length === 0){
    $('<audio id="update_request_audio"><source src="audio/update_request.ogg" type="audio/ogg"><source src="audio/update_request.mp3" type="audio/mpeg"><source src="audio/update_request.wav" type="audio/wav"></audio>').appendTo('body');
  }
  $('#update_request_audio')[0].play();
}

// create and play new msg audio
function play_new_msg_audio(){
  if($('#new_msg_audio').length === 0){
    $('<audio id="new_msg_audio"><source src="audio/new_msg.ogg" type="audio/ogg"><source src="audio/new_msg.mp3" type="audio/mpeg"><source src="audio/new_msg.wav" type="audio/wav"></audio>').appendTo('body');
  }
  $('#new_msg_audio')[0].play();
}

// for notification
var track_page = 0; //track user scroll as page number, right now page number is 1
var loading  = false; //prevents multiple loads
// load_contents(track_page); //initial content load
$('[name="latest_notify_div"]').bind('scroll', function(){
  // console.log(Math.round($(this).scrollTop() + $(this).innerHeight()) == $(this)[0].scrollHeight);
  if(Math.round($(this).scrollTop() + $(this).innerHeight()) == $(this)[0].scrollHeight){ //if user scrolled to bottom of the page
    if(loading === false){
      track_page++; //page number increment
      load_contents(track_page); //load content
    }
  }
});

//Ajax load function
function load_contents(track_page){
    if(loading === false){
        loading = true;  //set loading flag on
        $('.loading-info').show(); //show loading animation
        $.post( 'notify/get_notify_message', {'page': track_page}, function(data){
            if(data.trim().length == 0){
                //notify user if nothing to load
                $('.loading-info').html("No more records!");
                return;
            }
            $('.loading-info').hide(); //hide loading animation once data is received
            $('[name="latest_notify_div"] > div.loading-info').before( data );
            jQuery("time.timeago").timeago();
            loading = false; //set loading flag off once the content is loaded
        }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
            alert(thrownError); //alert with HTTP error
        })
    }
}

$('#announce').editable({
    inputclass: 'input-large',
    select2: {
        tags: ['SBO', 'IBC', 'PINACLE', 'PLAYTECH', 'ASIA855'],
        tokenSeparators: [",", " "],
        allowClear: true
    },
    ajaxOptions: {
        type: 'post',
        dataType: 'json'
    },
    mode: 'inline',
    success: function(response) {
        if(response.state){
          // call socket to update all user
          console.log('update announcement -> ', response.data);
          socket.emit('update_announcement_content',response.data);
        }
    }
});

socket.on('update_announcement_content', function(message){
  console.log('update_announcement_content');
  update_announcement_content(message);
})

function update_announcement_content(data){
  console.log('update content data');
  if($('#announce').text() !== data){
     $('#announce').fadeOut(500, function() {
         $('#announce').editable('setValue', data , true).fadeIn(500); // update announcement content
     });
   }
}
