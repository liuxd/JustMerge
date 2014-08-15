var socket = io();
var channel = 'cli';
$('#send').click(function() {
    var msg = $('#m').val();
    socket.emit(channel, {msg: msg});
    $('#m').val('');
    return false;
});
socket.on(channel, function(msg) {
    console.log(msg);
    $('#messages').append($('<li>').text(msg.msg));
});