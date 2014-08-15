#!/usr/bin/env node

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var channel = 'cli';

app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        socket.emit(channel, data);
        console.log(data);
    });
});

http.listen(3000, function() {
    console.log('listening on *:3000');
});