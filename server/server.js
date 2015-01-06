#!/usr/bin/env node

// Set requirements.
var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var Tail = require('tail').Tail;
var child_process = require('child_process');
var channel = 'cli';

// Set log file.
var log_file = '/tmp/merge.log';
fs.existsSync(log_file) || fs.appendFile(log_file, '');

// Set config file.
var current_path = fs.realpathSync('.');
var config_file = current_path + '/config.json';

if (fs.existsSync('/tmp/jm.json')) {
    config_file = '/tmp/jm.json';
}

config_text = fs.readFileSync(config_file, 'utf8');
config_json = eval('(' + config_text+ ')');

// Set URL.
app.use("/www", express.static(__dirname + '/www'));

app.get('/', function(req, res) {
    var str = fs.realpathSync('.');
    res.sendFile(str + '/index.html');
});

app.get('/list', function(req, res) {
    child_process.exec('ls ' + config_json.code_path, function(err, stdout, stderr){
        res.send(stdout);
    });
});

function generate_log_file (log_file) {
    var d = new Date();
    year = d.getFullYear();
    mon = d.getMonth() + 1;
    day = d.getDate();
    hour = d.getHours();
    min = d.getMinutes();
    sec = d.getSeconds();
    return log_file + year + mon + day + hour + min + sec;
}

// Set websocket.
io.on('connection', function(socket) {
    log_file_path = generate_log_file(log_file);
    fs.openSync(log_file_path, 'w');
    tail = new Tail(log_file_path);

    socket.on(channel, function(data) {
        var cli = config_json.command + ' "' + data.repolist + '" ' + data.branch + ' log  > ' + log_file_path;
        console.log(cli);
        child_process.exec(cli);
        socket.emit(channel, {msg: 'Log File : ' + log_file_path});

        tail.on("line", function(data) {
            var msg = data.toString('utf-8');
            socket.emit(channel, {msg: msg});
        });
    });   
});

http.listen(config_json.port, function() {
    console.log('listening on *:' + config_json.port);
});

// end of this file.
