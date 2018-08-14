var app        = require('express')(),
    server     = require('http').createServer(app),
    io         = require('socket.io')(server),
    logger     = require('winston'),
    port       = 1337;

// Logger config
logger.remove(logger.transports.Console);
logger.add(logger.transports.Console, { colorize: true, timestamp: true });
logger.info('SocketIO > listening on port ' + port);

io.on('connection', function (socket){
    var nb = 0;

    logger.info('SocketIO > Connected socket ' + socket.id);

    socket.on('broadcast', function (message) {
        ++nb;
        logger.info('ElephantIO broadcast > ' + JSON.stringify(message));
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
      logger.info('ElephantIO broadcast > ' + JSON.stringify(message));
    });

    /*
     * Objective : On update request transactions Send specific client notification by user_id
     * - update DataTable
     * - update summary request data
     * - execute alarm to notify specific client by user_id
     * Author : Takorn A.
     * Version : 0.01
    */
    socket.on('update_request_transaction', function(data){
      logger.info('ElephantIO broadcast > ' + JSON.stringify(message));
    });

    // successful state

    // cancelled state

    socket.on('disconnect', function () {
        logger.info('SocketIO : Received ' + nb + ' messages');
        logger.info('SocketIO > Disconnected socket ' + socket.id);
    });
});

server.listen(port);
