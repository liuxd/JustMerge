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
var current_path = fs.realpathSync('.');

// for public resources.
app.use("/www", express.static(__dirname + '/www'));

// page : home page.
app.get('/', function(req, res) {
    res.sendFile(current_path + '/index.html');
});

// ajax : get repository list in json.
app.get('/get_repo_list', function(req, res){
    fs.readFile(current_path + '/config.json', "utf8", function(err, data){
        res.send(data);
    });
});

// for websocket.
io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        console.log(data);
        child_process.exec(cli);
        var tail = new Tail(output_log);

        tail.on("line", function(data) {
            socket.emit(channel, {msg: data.toString('utf-8')});
        });
    });
});

var port = 3000;
app.listen(port, function() {
    console.log('listening on *:' + port);
});