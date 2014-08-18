#!/usr/bin/env node

var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var Tail = require('tail').Tail;
var child_process = require('child_process');

var channel = 'cli';
var tail = new Tail('/tmp/test.log');
var cli = 'tree -L 3 > /tmp/test.log';
var current_path = fs.realpathSync('.');
var port = 3000;

app.use("/www", express.static(__dirname + '/www'));
app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

app.get('/get_repo_list', function(req, res) {
    fs.readFile(current_path + '/config.json', "utf8", function(err, data) {
        res.send(data);
    });
});

io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        child_process.exec(cli);

        tail.on("line", function(data) {
            var msg = data.toString('utf-8');
            console.log(msg);
            socket.emit(channel, {msg: msg});
        });
    });
});

http.listen(port, function() {
    console.log('listening on *:' + port);
});