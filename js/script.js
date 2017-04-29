var txtCodeEditor = null;
function cd(path) {
    console.log('cd '+path);
    oldHtml = $('#lstFiles').html();
    $('#lstFiles').html('<img src="img/loader.gif">');
    path += '/';
    actualPath += '/';
    actualPath = actualPath.replace('//', '/')
    $('#titlePath').val(path.replace('//', '/'));
    $.post("include/cd.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
        refreshList(data);
    });
}
function refreshList(data) {
    json = JSON.parse(data);
    if (json.error == 0) {
        html = actualPath == '/' ? '' : '<a class="list-group-item list-up">Folder up</a>';
        $.each(json.data, function(index, value) {
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

function cmd(path, command) {
    $('#dialogCommandResult').html('<img src="img/loader.gif">');
    console.log('cmd '+command);
    $.post("include/cmd.php", {host:host, port:port, username:username, password:password, path:path, command:command}, function( data ) {
        json = JSON.parse(data);
        console.log(json.data.res);
        $('#dialogCommandResult').html(json.data.res);
    });
}

function saveText(path, text) {
    console.log('save '+path);
    $.post("include/saveText.php", {host:host, port:port, username:username, password:password, path:path, text:text}, function( data ) {
        console.log(data);
      	alert('Salvado');
        //$('#textEditor').modal('hide');
    });
}

function touch(path, file) {
    console.log('touch '+path+file);
    $.post("include/touch.php", {host:host, port:port, username:username, password:password, path:path, file:file}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    });
}

function mkdir(path, file) {
    console.log('mkdir '+path+file);
    $.post("include/mkdir.php", {host:host, port:port, username:username, password:password, path:path, file:file}, function( data ) {
        $('#dialogInput').modal('hide');
        refreshList(data);
    });
}

function cat(path) {
    console.log('cat '+path);
    $.post("include/cat.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
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
            txtCodeEditor.setValue('');
            $('#textEditor').modal('show');
            $('#textEditor').on('shown.bs.modal', function () {
                setMode($('#editor').attr("data-file"));
                txtCodeEditor.setValue($('#editor').attr("data-val"));
            });
        }
    });
}

function getImage(path, mimetype) {
    console.log('base64 '+path);
    $('#viewer').prop('src', 'img/loader.gif');
    $('#imageViewer').modal('show');
    $.post("include/base64.php", {host:host, port:port, username:username, password:password, path:path, mimetype:mimetype}, function( data ) {
        json = JSON.parse(data);
        if (json.error == 0) {
            $('#viewer').prop('src', json.data);
        }
    });
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
        } else if (mime1 == 'text' || mime2 == 'x-empty') {
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





