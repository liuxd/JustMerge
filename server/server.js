#!/usr/bin/env node

// for dependence.
var express = require('express');
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var Tail = require('tail').Tail;
var child_process = require('child_process');

// for business.
var app = express();
var channel = 'cli';
var output_log = '/tmp/just-for-merge.log';
var cli = 'ls -alh > ' + output_log;

// for web server.
app.use("/www", express.static(__dirname + '/www'));
app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

// for websocket.
io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        child_process.exec(cli);
        var tail = new Tail(output_log);

        tail.on("line", function(data) {
            socket.emit(channel, {msg: data.toString('utf-8')});
        });
    });
});

http.listen(3000, function() {
    console.log('listening on *:3000');
});