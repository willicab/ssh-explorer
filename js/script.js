var txtCodeEditor = null;
function cd(path) {
    $('#modalWait').fadeIn();
    console.log('cd '+path);
    oldHtml = $('#lstFiles').html();
    path += '/';
    actualPath += '/';
    actualPath = actualPath.replace('//', '/')
    $('#titlePath').val(path.replace('//', '/'));
    $.post("include/cd.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}
function refreshList(data) {
    json = JSON.parse(data);
    if (json.error == 0) {
        html = actualPath == '/' ? '' : '<div class="list-group-item row"><a class="col-md-12 list-up">Folder up</a></div>';
        $.each(json.data, function(index, value) {
            if (value.name == '.' || value.name == '..') return true;
            className1 = value.mimeType.split('/')[0];
            className2 = value.mimeType.split('/')[1];
            html += '<div class="list-group-item row">\n';
            //html += '<div class="col-md-2">\n';
            html += '<a class="col-md-2 list-file '+className1+' '+className2+'" ';
            html += 'data-link="'+value.link+'" ';
            html += 'data-rights="'+value.rights+'" ';
            html += 'data-owner="'+value.owner+'" ';
            html += 'data-group="'+value.group+'" ';
            html += 'data-size="'+value.size+'" ';
            html += 'data-accessDate="'+value.accessDate+'" ';
            html += 'data-modificationDate="'+value.modificationDate+'" ';
            html += 'data-mimeType="'+value.mimeType+'" ';
            html += 'data-name="'+value.name+'">';
            //html += '<input class="file-check" type="checkbox" style="float:right" ';
            //html += 'data-link="'+value.link+'" ';
            //html += 'data-perms="'+value.rights+'" ';
            //html += 'data-owner="'+value.owner+'" ';
            //html += 'data-group="'+value.group+'" ';
            //html += 'data-size="'+value.size+'" ';
            //html += 'data-accessDate="'+value.accessDate+'" ';
            //html += 'data-modificationDate="'+value.modificationDate+'" ';
            //html += 'data-mimeType="'+value.mimeType+'" ';
            //html += 'data-name="'+value.name+'">';
            html += value.name;
            html += '</a>\n';
            html += '<div class="col-md-2">'+value.mimeType;
            html += '</div><div class="col-md-2">'+formatBytes(value.size);
            html += '</div><div class="col-md-2">'+value.group+':'+value.owner;
            html += '</div><div class="col-md-2">'+value.rights;
            html += '</div><div class="col-md-2">'+value.modificationDate+"</div></div>";
        });
        $('#lstFiles').html(html);
        refreshListFile();
        return true;
    } else {
        alert("Directory not found");
        $('#lstFiles').html(oldHtml);
        return false;
    }
}

function ps() {
    $('#modalWait').fadeIn();
    console.log('ps ux');
    $.post("include/ps.php", {host:host, port:port, username:username, password:password}, function( data ) {
        json = JSON.parse(data);
        html = '';
        $.each(json.data, function(index, value) {
            html += '<tr>\n';
            html += '<td>'+value.pid+'</td>\n';
            html += '<td>'+value.cpu+'</td>\n';
            html += '<td>'+value.mem+'</td>\n';
            html += '<td>'+value.start+'</td>\n';
            html += '<td>'+value.time+'</td>\n';
            html += '<td>'+value.cmd+'</td>\n';
            html += '<td><input type="checkbox" class="chkProcess" data-pid="'+value.pid+'"></td>\n';
            html += '</tr>\n';
        });
        $('#tblProcesses tbody').html(html);
        $("#btnKillProcesses").prop("disabled", true);
        $(".chkProcess").on("click", countChecked );
        $('#dialogProcesses').modal('show');
    }).always(function() {$('#modalWait').fadeOut();});
}

function kill(processes) {
    $('#modalWait').fadeIn();
    console.log('kill -9 '+processes);
    $.post("include/kill.php", {host:host, port:port, username:username, password:password, processes:processes}, function( data ) {
        json = JSON.parse(data);
        html = '';
        $.each(json.data, function(index, value) {
            html += '<tr>\n';
            html += '<td>'+value.pid+'</td>\n';
            html += '<td>'+value.cpu+'</td>\n';
            html += '<td>'+value.mem+'</td>\n';
            html += '<td>'+value.start+'</td>\n';
            html += '<td>'+value.time+'</td>\n';
            html += '<td>'+value.cmd+'</td>\n';
            html += '<td><input type="checkbox" class="chkProcess" data-pid="'+value.pid+'"></td>\n';
            html += '</tr>\n';
        });
        $('#tblProcesses tbody').html(html);
        $("#btnKillProcesses").prop("disabled", true);
        $(".chkProcess").on("click", countChecked );
        $('#dialogProcesses').modal('show');
    }).always(function() {$('#modalWait').fadeOut();});
}

function chmod(path, file, rights, recursive) {
    $('#modalWait').fadeIn();
    console.log('chmod '+path+' '+file+' '+rights+' '+recursive);
    $.post("include/chmod.php", {host:host, port:port, username:username, password:password, path:path, file:file, rights:rights, recursive:recursive}, function( data ) {

        $('#dialogChmod').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function compress(path, orig, name, type) {
    $('#modalWait').fadeIn();
    console.log('compress '+path+' '+orig+' '+name+' '+type);
    $.post("include/compress.php", {host:host, port:port, username:username, password:password, path:path, orig:orig, name:name, type:type}, function( data ) {
        $('#dialogCompress').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function extract(path, name) {
    $('#modalWait').fadeIn();
    type = (name.substr(-6) == 'tar.gz' ? 'tar.gz' : (name.substr(-3) == 'tar' ? 'tar' : (name.substr(-2) == 'gz' ? 'gz' : (name.substr(-3) == 'zip' ? 'zip' : ''))));
    console.log('extract '+path+' '+name+' '+type);
    $.post("include/extract.php", {host:host, port:port, username:username, password:password, path:path, name:name, type:type}, function( data ) {
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function cmd(path, command) {
    $('#modalWait').fadeIn();
    console.log('cmd '+command);
    $.post("include/cmd.php", {host:host, port:port, username:username, password:password, path:path, command:command}, function( data ) {
        json = JSON.parse(data);
        //console.log(json.data.res);
        $('#dialogCommandResult').html(json.data.res);
    }).always(function() {$('#modalWait').fadeOut();});
}

function sysInfo() {
    $('#modalWait').fadeIn();
    console.log('sysInfo');
    $.post("include/info.php", {host:host, port:port, username:username, password:password}, function( data ) {
        console.log(data);
        json = JSON.parse(data);
        $('#dialogSysInfoLabel').text('System Info \''+json.data.os+'\'');
        $('#itemInfoUptime').text('Uptime: '+json.data.uptime);
        $('#itemInfoKernel').text('Kernel: '+json.data.kernel);
        $('#itemInfoArch').text('Architecture: '+json.data.arch);
        $('#itemInfoHostname').text('Hostname: '+json.data.hostname);
        $('#itemInfoLang').text('Language: '+json.data.lang);
        $('#itemInfoMemTotal').text('Memory Total: '+json.data.memtotal);
        $('#itemInfoMemFree').text('Memory Free: '+json.data.memfree);
        $('#itemInfoSwapTotal').text('Swap Total: '+json.data.swaptotal);
        $('#itemInfoSwapFree').text('Swap Free: '+json.data.swapfree);
        $('#itemInfoDiskTotal').text('Disk Space Total: '+json.data.disktotal);
        $('#itemInfoDiskFree').text('Disk Free Free: '+json.data.diskfree);
        //console.log(json.data);
        //$('#dialogCommandResult').html(json.data.res);
        $('#dialogSysInfo').modal('show');
    }).always(function() {$('#modalWait').fadeOut();});
}
function copy(path, origPath, destPath) {
    $('#modalWait').fadeIn();
    console.log('copy '+origPath+" "+destPath);
    $.post("include/copy.php", {host:host, port:port, username:username, password:password, path:path, origPath:origPath, destPath:destPath}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function move(path, origPath, destPath) {
    $('#modalWait').fadeIn();
    console.log('move '+origPath+" "+destPath);
    $.post("include/move.php", {host:host, port:port, username:username, password:password, path:path, origPath:origPath, destPath:destPath}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function remove(path, rmPath) {
    $('#modalWait').fadeIn();
    console.log('remove '+rmPath);
    $.post("include/remove.php", {host:host, port:port, username:username, password:password, path:path, rmPath:rmPath}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function saveText(path, file, text) {
    console.log('save '+file);
    $('#modalWait').fadeIn();
    $.post("include/saveText.php", {host:host, port:port, username:username, password:password, path:path, file:file, text:text}, function( data ) {
      	refreshList(data);
      	alert('Saved');
    }).always(function() {$('#modalWait').fadeOut();});
}

function touch(path, file) {
    $('#modalWait').fadeIn();
    console.log('touch '+path+file);
    $.post("include/touch.php", {host:host, port:port, username:username, password:password, path:path, file:file}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function mkdir(path, file) {
    $('#modalWait').fadeIn();
    console.log('mkdir '+path+file);
    $.post("include/mkdir.php", {host:host, port:port, username:username, password:password, path:path, file:file}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    }).always(function() {$('#modalWait').fadeOut();});
}

function cat(path) {
    console.log('cat '+path);
    $('#modalWait').fadeIn();
    $.post("include/cat.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
        //console.log(data);
        json = JSON.parse(data);
        var mixedMode = {
           name: "htmlmixed",
           scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                          mode: null},
                         {matches: /(text|application)\/(x-)?vb(a|script)/i,
                          mode: "vbscript"}]
        };
        if (json.error == 0) {
            $('#editor').attr("data-val", json.data)
            $('#editor').attr('data-file', path);
            $('#pathEditor').text(path);
            txtCodeEditor.setValue('');
            setMode($('#editor').attr("data-file"));
            txtCodeEditor.setValue($('#editor').attr("data-val"));
            $('#textEditor').fadeIn();
        }
    }).always(function() {$('#modalWait').fadeOut();});;
}

function getImage(path, mimetype) {
    $('#modalWait').fadeIn();
    console.log('base64 '+path);
    $('#viewer').prop('src', 'img/loader.gif');
    $('#imageViewer').modal('show');
    $.post("include/base64.php", {host:host, port:port, username:username, password:password, path:path, mimetype:mimetype}, function( data ) {
        json = JSON.parse(data);
        if (json.error == 0) {
            $('#viewer').prop('src', json.data);
        }
    }).always(function() {$('#modalWait').fadeOut();});
}

function refreshListFile() {
    $('.list-file').click(function(){
        $('#fileInfo').text($(this).attr('data-name')+($(this).attr('data-mimeType') == 'inode/directory' ? '' : ' ('+formatBytes($(this).attr('data-size'))+')'));
    })
    $('.list-file').dblclick(function(){
        mime1 = $(this).attr('data-mimeType').split('/')[0];
        mime2 = $(this).attr('data-mimeType').split('/')[1];
        if ($(this).attr('data-mimeType') == 'inode/directory') {
            actualPath += $(this).attr('data-name') + '/';
            cd(actualPath);
        } else if (mime1 == 'text' || mime2 == 'x-empty' || mime2 == 'xml') {
            cat(actualPath+$(this).attr('data-name'));
        } else if (mime1 == 'image') {
            getImage(actualPath+$(this).attr('data-name'), $(this).attr('data-mimeType'));
        }
    })
    $('.list-up').dblclick(function(){
        console.log(actualPath)
        newPath = actualPath.split('/');
        newPath.pop();
        newPath.pop();
        actualPath = newPath.join('/') + '/';
        cd(actualPath);
    })
    $(".list-file").on("contextmenu",function(e){
        contextPath = actualPath+$(this).attr('data-name');
        contextChmod = $(this).attr('data-rights');
        console.log(contextPath);
        $('#tooltip').css({
            top: (($(window).height() - 50) > (e.pageY + $('#tooltip').height()) ? e.pageY : (e.pageY + 2 - $('#tooltip').height())) + 'px',
            left: ($(window).width() > (e.pageX + $('#tooltip').width()) ? e.pageX : (e.pageX + 2 - $('#tooltip').width())) + 'px'
        }).fadeIn(250);
       return false;
    });
    $(document).click(function(){
        $('#tooltip').fadeOut(250);
    });
}

function formatBytes(bytes,decimals) {
   if(bytes == 0) return '0 Bytes';
   var k = 1000,
       dm = decimals + 1 || 3,
       sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
       i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function setMode(fileName) {
    var val = fileName, m, mode, spec;
    if (m = /.+\.([^.]+)$/.exec(val)) {
        var info = CodeMirror.findModeByExtension(m[1]);
        if (info) {
            mode = info.mode;
            spec = info.mime;
        }
    } else if (/\//.test(val)) {
        var info = CodeMirror.findModeByMIME(val);
        if (info) {
            mode = info.mode;
            spec = val;
        }
    } else {
        mode = spec = val;
    }
    if (mode) {
        console.log(spec, mode);
        txtCodeEditor.setOption("mode", spec);
        CodeMirror.autoLoadMode(txtCodeEditor, mode);
    } else {
    }
}
