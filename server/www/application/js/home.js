(function() {
//    var socket = io();
//    var channel = 'cli';
//    $('#send').click(function() {
//        var msg = $('#m').val();
//        socket.emit(channel, {msg: msg});
//        $('#m').val('');
//        return false;
//    });
//    socket.on(channel, function(msg) {
//        console.log(msg);
//        $('#messages').append($('<li>').text(msg.msg));
//    });

    $.get('/get_repo_list', function(data, status){
        if (status === 'success') {
            var repos = eval('(' + data + ')').repos;
            var repo_container = $('#repo_list');

            for (i in repos) {
                var repo = repos[i];
                var html = "<span><label class='checkbox-inline repo'><input type='checkbox' value='" + repo +"'>" + repo +"</label></span>";
                $(html).appendTo(repo_container);
            }
        }
    });
})();