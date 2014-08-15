#!/usr/bin/env node

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var channel = 'cli';
var Tail = require('tail').Tail;
var tail = new Tail('/tmp/test.log');
var child_process = require('child_process');
var cli = '/Users/liuxd/Documents/scripts/test.sh > /tmp/test.log';

app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        child_process.exec(cli);

        tail.on("line", function(data) {
            socket.emit(channel, {msg: data.toString('utf-8')});
        });
    });
});

http.listen(3000, function() {
    console.log('listening on *:3000');
});