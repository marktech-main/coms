var app        = require('express')(),
    mysql      = require('mysql'),
    server     = require('http').createServer(app),
    io         = require('socket.io')(server),
    logger     = require('winston'),
    cron       = require('cron'),
    localIpV4Address = require('local-ipv4-address'),
    port       = 1338;

// Logger config
logger.remove(logger.transports.Console);
logger.add(logger.transports.Console, { colorize: true, timestamp: true });
logger.info('SocketIO > listening on port ' + port);
var uuid = '';
/* Creating POOL MySQL connection.*/
var pool    =    mysql.createPool({
      connectionLimit   :   100,
      host              :   'localhost',
      user              :   'marktech',
      password          :   'Asxz45219',
      database          :   'coms',
      debug             :   false
});
var username = '';
var users = {};
var users_activity = {};
users['tak'] = ['a3aTak','1jGt0i'];

io.on('connection', function (socket){
    var nb = 0;
    var announcement = '';
    var timeStampInMs = new Date();
    pool.getConnection(function(err,connection){
        if (err) {
          return;
        }
    // should have uuid
    connection.query("SELECT id, content, updated_by, timestamp FROM announcement WHERE id = 1 LIMIT 1;",function(err,result){
            connection.release();
            if(!err) {
              announcement = result[0].content;
              socket.emit('connected', { message: 'Connected socket!', id: socket.id, announcement: announcement, uuid: uuid });
            }
        });
    });

    logger.info('SocketIO > Connected socket ' + socket.id);
    logger.info('SocketIO > Current UUID ' + uuid);

    if(socket.nickname == undefined){
      console.log('data from jquery + socket : '+socket.handshake.query.username);

      socket.nickname = socket.handshake.query.username;
      if(socket.nickname in users){
        console.log('found3 '+socket.nickname);
        var obj = users[socket.nickname];
        obj.push(socket.id);
        users[socket.nickname] = obj;
        users_activity[socket.nickname].session = obj;
        users_activity[socket.nickname].last_activity = timeStampInMs;
        users_activity[socket.nickname].role = socket.handshake.query.role;
        if(users_activity[socket.nickname].status == 'OFFLINE'){
          users_activity[socket.nickname].status = 'ONLINE';

        }
        socket.broadcast.emit('received_monitoring_data', users_activity);
      }else{
        if(socket.nickname !== undefined){
          console.log('not found4 '+socket.handshake.query.username);
          socket.nickname = socket.handshake.query.username;
          // users.push(socket.nickname);
          var obj = [];
          obj.push(socket.id);
          users[socket.nickname] = obj;
          users_activity[socket.nickname] = {};
          users_activity[socket.nickname].session = obj;
          users_activity[socket.nickname].last_activity = timeStampInMs;
          users_activity[socket.nickname].role = socket.handshake.query.role;
          if(users_activity[socket.nickname].status == undefined || users_activity[socket.nickname].status == 'OFFLINE'){
            console.log('test');
            users_activity[socket.nickname].status = 'ONLINE';
          }
          socket.broadcast.emit('received_monitoring_data', users_activity);
        }
      }
      console.log(users);
      console.log(users_activity);
    }else{
      console.log('nickname exist : '+socket.nickname);
      var obj = users[socket.nickname];
      obj.push(socket.id);
      users[socket.nickname] = obj;
      users_activity[socket.nickname].session = obj;
      users_activity[socket.nickname].last_activity = timeStampInMs;
      users_activity[socket.nickname].role = socket.handshake.query.role;
      console.log(users);
      console.log(users_activity);
      if(users_activity[socket.nickname].status == 'OFFLINE'){
        users_activity[socket.nickname].status = 'ONLINE';
      }
      socket.broadcast.emit('received_monitoring_data', users_activity);
    }

    localIpV4Address().then(function(ipAddress){
        console.log("My IP address is " + ipAddress);
        // MyIP address is 10.4.4.137
    });
	
	// data : transaction id
    socket.on('disable_verify_button', function(data){
      socket.broadcast.emit('disabled_verify_button', data);
    });

    socket.on('login_with_username', function(data) {
      if(data.username in users){
        console.log('found4 '+data.username);
      }else{
        console.log('not found4 '+data.username);
      }
      socket.nickname = data.username;
      // users.push(socket.nickname);
      var obj = [];
      obj.push(socket.id);
      users[data.username] = obj;
      users_activity[data.username] = {'session' : obj, 'role' :  data.role, 'last_activity' : timeStampInMs, 'status' : 'ONLINE'};
      console.log(users);
      console.log(users_activity);
      socket.broadcast.emit('received_monitoring_data', users_activity);
    });

    socket.on('logout_with_username', function(data) {
      if(data.username in users){
        console.log('found1 '+data.username);
      }else{
        console.log('not found1 '+data.username);
      }
      delete users[data.username];
      delete users_activity[data.username];
      console.log('user logged out');
      console.log(users);
      console.log(users_activity);
      socket.broadcast.emit('received_monitoring_data', users_activity);
    });


    socket.on('disconnect', function () {
      //  clearInterval(tweets);
      if(socket.nickname == undefined){
        console.log('disconnected data from jquery + socket : '+socket.handshake.query.username);
        console.log(users);
        console.log(users_activity);
      }else{
        var obj = users[socket.nickname];
        if(obj != undefined){
          var index = obj.indexOf(socket.id);
          if (index >= 0) {
            obj.splice( index, 1 );
          }
          users[socket.nickname] = obj;
        }
        console.log('user : '+socket.nickname);
        console.log('check data =>     '+users_activity[socket.nickname]);
        var obj2 = users_activity[socket.nickname] != undefined ? users_activity[socket.nickname].session : undefined;
        if(obj2 != undefined){
          console.log('remove session from Node server');
          var index = obj2.indexOf(socket.id);
          if(index >= 0){
            console.log('remove session : '+socket.id);
            obj2.splice( index, 1);
          }
          users_activity[socket.nickname].session = obj2;
          users_activity[socket.nickname].role = socket.handshake.query.role;
          if((!Array.isArray(obj2) || !obj2.length ) & users_activity[socket.nickname].status != 'PROCESSING'){
            users_activity[socket.nickname].status = 'OFFLINE';
          }
        }
        socket.broadcast.emit('received_monitoring_data', users_activity);
        console.log('disconnected from Node Server');
        console.log(users);
        console.log(users_activity);
      }
     });

    socket.on('get_monitoring_data', function(message){
      socket.emit('received_monitoring_data', users_activity);
      logger.info('Sent new monitoring data to Client');
    });
	
    socket.on('broadcast', function (message) {
        ++nb;
        logger.info('ElephantIO broadcast > ' + JSON.stringify(message));
    });

    socket.on('get_statistic', function(data){
      ++nb;
      logger.info('ElephantIO emit get statistic ');
      get_statistic_state(function(statistic){
        // socket.emit('read_notify',data);
        logger.info('ElephantIO return get statistic > ' + JSON.stringify(statistic));
        socket.broadcast.emit('get_statistic', JSON.stringify(statistic));
      });
    });

    /*
     * Objective : On create request transactions Send all client notification
     * - update DataTable
     * - update summary request data
     * - execute alarm to notify Payment Team
     * Author : Takorn A.
     * Version : 0.01
    */
    socket.on('create_request_transaction', function(data){
      ++nb;
      logger.info('ElephantIO broadcast > ' + JSON.stringify(data));
      uuid = data.uuid;
      logger.info('SocketIO > Current UUID ' + data.uuid);
      socket.broadcast.emit('create_request_transaction', data);
    });

    /*
     * Objective : On update request transactions Send specific client notification by user_id
     * - update DataTable
     * - update summary request data
     * - execute alarm to notify specific client by user_id
     * Author : Takorn A.
     * Version : 0.02
    */
    socket.on('update_request_transaction', function(data){
      ++nb;
      logger.info('ElephantIO broadcast > ' + JSON.stringify(data));
      uuid = data.uuid;
      logger.info('SocketIO > Current UUID ' + data.uuid);
      socket.broadcast.emit('update_request_transaction', data);
      // update table
      // update summary request data
      // send notify to user_id

      // monitoring module
      socket.nickname = socket.handshake.query.username;
      if(data.status == 'PROCESSING'){
        logger.info('check > ' + JSON.stringify(users_activity[socket.nickname]));
        users_activity[socket.nickname].status = 'PROCESSING';
        users_activity[socket.nickname].last_activity = timeStampInMs;
        users_activity[socket.nickname].role = socket.handshake.query.role;
        socket.broadcast.emit('received_monitoring_data', users_activity);
      }else{
        logger.info('check > ' + JSON.stringify(users_activity[socket.nickname]));
        users_activity[socket.nickname].status = 'ONLINE';
        // users_activity[socket.nickname].last_activity = timeStampInMs;
        users_activity[socket.nickname].role = socket.handshake.query.role;
        socket.broadcast.emit('received_monitoring_data', users_activity);
      }
    });


    /*
     * Objective : On update pending transactions Send specific client notification by user_id
     * - update DataTable [optional]
     * - update verify-form
     * - execute alarm to notify specific client by user_id
     * Author : Takorn A.
     * Version : 0.01
    */
    socket.on('update_pending_transaction', function(data){
      ++nb;
      logger.info('ElephantIO broadcast > ' + JSON.stringify(data));
      uuid = data.uuid;
      socket.broadcast.emit('update_pending_transaction', data);
      // monitoring module
      socket.nickname = socket.handshake.query.username;
      if(data.status == 'PROCESSING'){
        logger.info('check > ' + JSON.stringify(users_activity[socket.nickname]));
        users_activity[socket.nickname].status = 'PROCESSING';
        users_activity[socket.nickname].last_activity = timeStampInMs;
        users_activity[socket.nickname].role = socket.handshake.query.role;
        socket.broadcast.emit('received_monitoring_data', users_activity);
      }else{
        logger.info('check > ' + JSON.stringify(users_activity[socket.nickname]));
        users_activity[socket.nickname].status = 'ONLINE';
        users_activity[socket.nickname].last_activity = timeStampInMs;
        users_activity[socket.nickname].role = socket.handshake.query.role;
        socket.broadcast.emit('received_monitoring_data', users_activity);
      }
    });


    /*
     * Objective : On read notify message send specific client notification by socket id
     * - update database notify_messages table where id is equal @param
     * - get count all unread by user_id
     * - send notify to socket id
     * Author : Takorn A.
     * Version : 0.01
    */
    socket.on('read_notify', function(data){
      ++nb;

      update_notify_state(data,function(){
        // socket.emit('read_notify',data);
        logger.info('ElephantIO emit read notify id > ' + data);
      });
    });

    socket.on('mark_all_notify_as_read', function(data){
      ++nb;
      logger.info('ElephantIO emit mark all notify as read');
      socket.emit('mark_all_notify_as_read');
    });

    // chat
    socket.on('subscribe', function(room) {
        console.log('joining room', room);
        socket.join(room);
    });

    //update announcement content
    socket.on('update_announcement_content',function(data){
      console.log('update_announcement_content', data);
      // should have uuid
      socket.broadcast.emit('update_announcement_content', data);
    });

    socket.on('active_receiver',function(data,fn){
      console.log('active');
      update_chat_flag(data,function(){
        console.log('room : ',data.room);
        console.log('sender_id : ',data.sender_id);
        console.log('receiver_id : ',data.receiver_id);
        socket.broadcast.emit('seen_new_msg', {
            receiver_id: data.receiver_id,
            sender_id: data.sender_id,
            room: data.room
        });
      });
      fn(true);
    });
	
	socket.on('start_senior_verification',function(data){
      console.log('SERVER trans_id: '+data.trans_id);
      socket.emit('client_start_senior_verification', {
            trans_id: data.trans_id,
            verify_status: data.verify_status,
            pword: data.pword,
            senior_validation_option: data.senior_validation_option
        });
    });

    socket.on('sending_update_start_senior_verify', function(data){
      console.log('sending_update_start_senior_verify trigger');
      io.emit('receiving_update_start_senior_verify',data);
    });

    socket.on('sending_update_end_senior_verify', function(data){
      console.log('sending_update_end_senior_verify trigger');
      io.emit('receiving_update_end_senior_verify',data);
    });

    socket.on('end_senior_verification',function(data){
      console.log('SERVER trans_id: '+data.trans_id);
      socket.emit('client_end_senior_verification', {
            trans_id: data.trans_id,
            senior: data.senior,
            senior_validation_option: data.senior_validation_option
        });
    });

    // socket.on('non_active_receiver',function(data,fn){
    //   console.log('active');
    //   console.log('room : ',data.room);
    //   console.log('filter_id : ',data.receiver);
    //   socket.broadcast.emit('got_new_msg', {
    //       sender: data.sender,
    //       receiver: data.receiver_id,
    //       sender_id: data.sender_id,
    //       room: data.room
    //   });
    //   fn(true);
    // });

    socket.on('send_message', function(data) {
        console.log('sending room post', data.room);
        console.log('is active receiver ID ', data.receiver_id);
        console.log(data);

        // socket.broadcast.to(data.room).emit('is_receiver_active',
        // {
        //     room: data.room,
        //     receiver: data.receiver_id
        // },
        // function(confirmation){
        //   console.log(confirmation);
        //   if(confirmation){
        //     console.log('receiver is active');
        //   }else{
        //     console.log('receiver is not active');
        //   }
        //
        // });


        // socket.broadcast.to(data.room).emit('is_receiver_active', data.receiver_id, function(confirmation){
        //   if(confirmation){
        //     console.log('receiver is active');
        //   }else{
        //     console.log('receiver is not active');
        //   }
        //
        // });
        insert_chat_state(data,function(){
          // socket.emit('read_notify',data);
          logger.info('ElephantIO emit send message > ' + data);
          socket.broadcast.to(data.room).emit('conversation_private_post', {
              message: data.message,
              sender: data.sender,
              receiver: data.receiver_id,
              sender_id: data.sender_id,
              room: data.room
          });
          socket.broadcast.emit('got_new_msg', {
              sender: data.sender,
              receiver_id: data.receiver_id,
              sender_id: data.sender_id,
              room: data.room
          });
        });

    });

    socket.on('rate_payment', function(data, fn){
      console.log('using for update all session on node server to get new data from DB');
      console.log('update transaction ID : ', data);
            console.log('active');
      insert_payment_rate(data,function(){
        console.log('trans_id : ',data.trans_id);
        console.log('rate : ',data.rate);
        console.log('comment : ',data.comment);
      io.emit('update_data_for_rate_payment', {
            trans_id: data.trans_id,
            rate: data.rate,
            comment: data.comment
        });
      });
      fn(true);

    });

    socket.on('update_request_transaction_to_successful', function(data){
      console.log('Its successful');
      io.emit('update_transaction_successful', { message: 'Update transaction to successful', id: socket.id });
    });

	socket.on('update_count_online_user', function(){
      console.log('Online user updated');
      io.emit('ppr_online_user_update', { message: 'Update count user online'});
    });

    /** PPR weekly update*/
    socket.on('initial_script_pps', function(){
      var job1 = new cron.CronJob({
        cronTime: '0 0 * * 1',
        onTick: function() {
          console.log('cron every monday');
          socket.emit('pps_weekly_update');
        },
        start: false,
        timeZone: 'Hongkong'
      });
      job1.start(); // job 1 started
      console.log('cron job1 run', job1.running);

      /** PPR end of the day store data*/
      var jobEndDay = new cron.CronJob({
        cronTime: '59 23 * * *',
        onTick: function() {
          console.log('cron every end of the day');
          socket.emit('pps_end_day_update');
        },
        start: false,
        timeZone: 'Hongkong'
      });
      jobEndDay.start(); // job 1 started
      console.log('cron jobEndDay run', jobEndDay.running);

	  /** PPR first day of the month*/
      var jobMonthly = new cron.CronJob({
        cronTime: '0 9 1 * *',
        onTick: function() {
          console.log('cron every first day of the month');
          socket.emit('ppr_monthly_update');
        },
        start: false,
        timeZone: 'Hongkong'
      });
      jobMonthly.start(); // job 1 started
      console.log('cron jobMonthly run', jobMonthly.running);
    });

    socket.on('disconnect', function () {
        logger.info('SocketIO : Received ' + nb + ' messages');
        logger.info('SocketIO > Disconnected socket ' + socket.id);
    });
});

var update_notify_state = function (id,callback) {
    pool.getConnection(function(err,connection){
        if (err) {
          callback(false);
          return;
        }
    connection.query("CALL update_notify_state('"+id+"')",function(err,rows){
            connection.release();
            if(!err) {
              callback(true);
            }
        });
     connection.on('error', function(err) {
              callback(false);
              return;
        });
    });
}

var insert_chat_state = function (data,callback) {
    pool.getConnection(function(err,connection){
        if (err) {
          callback(false);
          return;
        }
    connection.query("CALL chat_do_save('"+data.room+"','"+data.sender_id+"','"+data.message+"')",function(err,rows){
            connection.release();
            if(!err) {
              callback(true);
            }
        });
     connection.on('error', function(err) {
              callback(false);
              return;
        });
    });
}

var get_statistic_state = function (callback) {
    pool.getConnection(function(err,connection){
        if (err) {
          callback(false);
          return;
        }
    connection.query("CALL get_statistic()",function(err,rows){
            connection.release();
            if(!err) {
              callback(rows[0]);
            }
        });
     connection.on('error', function(err) {
              callback(false);
              return;
        });
    });
}

var update_chat_flag = function (data,callback) {
    pool.getConnection(function(err,connection){
        if (err) {
          callback(false);
          return;
        }
    connection.query("CALL update_chat_flag('"+data.room+"','"+data.receiver_id+"')",function(err,rows){
            connection.release();
            if(!err) {
              callback(true);
            }
        });
     connection.on('error', function(err) {
              callback(false);
              return;
        });
    });
}

var insert_payment_rate = function (data,callback) {
    pool.getConnection(function(err,connection){
        if (err) {
          callback(false);
          return;
        }
    connection.query("CALL insert_rate('"+data.trans_id+"','"+data.rate+"','"+data.comment+"')",function(err,rows){
            connection.release();
            if(!err) {
              callback(true);
            }
        });
     connection.on('error', function(err) {
              callback(false);
              return;
        });
    });
}

server.listen(port);
