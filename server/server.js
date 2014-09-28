#!/usr/bin/env node

var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var Tail = require('tail').Tail;
var child_process = require('child_process');
var channel = 'cli';
var port = 3000;

var current_path = fs.realpathSync('.');
var config_file = current_path + '/config.json';

if (fs.existsSync('/tmp/jm.json')) {
    config_file = '/tmp/jm.json';
}

app.use("/www", express.static(__dirname + '/www'));

app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

app.get('/get_repo_list', function(req, res) {
    fs.readFile(config_file, "utf8", function(err, data) {
        var code_path = eval('(' + data + ')').code_path
        child_process.exec('ls ' + code_path, function(err, stdout, stderr){
            res.send(stdout);
        });
    });
});

io.on('connection', function(socket) {
    socket.on(channel, function(data) {
        var d = new Date();
        var dt = d.getFullYear().toString() + (d.getMonth() + 1) + d.getDate() + d.getHours() + d.getMinutes() + d.getSeconds();
        var log_file = '/tmp/' + dt + 'merge.log';
        var r = fs.existsSync(log_file);

        if (!r) {
            fs.appendFile(log_file, '');
        }

        var tail = new Tail(log_file);

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
