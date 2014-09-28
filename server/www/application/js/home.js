(function() {
    var socket = io();
    var channel = 'cli';
    var terminal = $('#code-output');

    // Show repository list.
    $.get('/get_repo_list', function(data, status) {
        if (status === 'success') {
            var repos = data.split("\n");
            var repo_container = $('#repo_list');

            for (i in repos) {
                if (repos[i].length === 0) continue;
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

        if (repos.length > 0) {
            var repolist = repos.join(' ');
            var branch = $("#branch").val();
            socket.emit(channel, {repolist: repolist, branch: branch});
            $("#merge").prop("disabled", "disabled");
        } else {
            alert('Choose your repositories!');
        }

        return false;
    });

    // Use socket.
    socket.on(channel, function(msg) {
        var classname = '';

        if (msg.msg === 'Merging failed') {
            classname = 'msg_warning';
        }

        if (msg.msg === 'Finished!') {
            classname = 'msg_success';
        }

        $('<div class="' + classname + '" >' + msg.msg + '</div>').appendTo(terminal);
        terminal.scrollTop(terminal[0].scrollHeight);
    });
})();
