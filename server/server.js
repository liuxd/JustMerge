#!/usr/bin/env node

var express = require('express');
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var Tail = require('tail').Tail;
var child_process = require('child_process');

var channel = 'cli';
var tail = new Tail('/tmp/test.log');
var cli = 'ls -alh > /tmp/test.log';

app.use("/www", express.static(__dirname + '/www'));

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