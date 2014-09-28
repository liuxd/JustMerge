#!/usr/bin/env node

var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var Tail = require('tail').Tail;
var child_process = require('child_process');
var channel = 'cli';
var current_path = fs.realpathSync('.');
var config_file = current_path + '/config.json';

if (fs.existsSync('/tmp/jm.json')) {
    config_file = '/tmp/jm.json';
}

var port = 3000;

// handle log file.
var log_file = '/tmp/merge.log';
var r = fs.existsSync(log_file);

if (!r) {
    fs.appendFile(log_file, '');
}

var tail = new Tail(log_file);

app.use("/www", express.static(__dirname + '/www'));

app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

app.get('/get_repo_list', function(req, res) {
    fs.readFile(config_file, "utf8", function(err, data) {
        res.send(data);
    });
});

io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        fs.readFile(config_file, "utf8", function(err, cfg) {
            var cli = eval('(' + cfg + ')').command + ' "' + data.repolist + '" ' + data.branch + ' log  > ' + log_file;
            console.log(cli);
            child_process.exec(cli);
        });

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
