(function() {
    var socket = io();
    var channel = 'cli';
    var terminal = $('#code-output');

    // Show repository list.
    $.get('/get_repo_list', function(data, status) {
        if (status === 'success') {
            var repos = eval('(' + data + ')').repos;
            var repo_container = $('#repo_list');

            for (i in repos) {
                var repo = repos[i];
                var html = "<span><label class='checkbox-inline repo'><input name='repo' type='checkbox' value='" + repo + "'>" + repo + "</label></span>";
                $(html).appendTo(repo_container);
            }
        }
    });

    // Send command message.
    $('#merge').click(function() {
        var repos = [];
        $("input[name='repo']:checked:checked").each(function() {
            repos.push($(this).val());
        });

        if (repos) {
            var msg = repos.join(',');
            socket.emit(channel, {msg: msg});
        }

        $("#merge").prop("disabled", "disabled");
        return false;
    });

    // Use socket.
    socket.on(channel, function(msg) {
        $('<div>' + msg.msg + '</div>').appendTo(terminal);
        terminal.scrollTop(terminal[0].scrollHeight );
    });
})();