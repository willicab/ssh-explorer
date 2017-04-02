function cd(path) {
    console.log('cd '+path);
    oldHtml = $('#lstFiles').html();
    $('#lstFiles').html('<img src="img/loader.gif">');
    $('#titlePath').val(path);
    $.post("include/cd.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
        console.log(data);
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
            html += '<a class="list-group-item list-file '+className1+' '+className2+'" ';
            html += 'data-link="'+value.link+'" ';
            html += 'data-rights="'+value.rights+'" ';
            html += 'data-owner="'+value.owner+'" ';
            html += 'data-group="'+value.group+'" ';
            html += 'data-size="'+value.size+'" ';
            html += 'data-accessDate="'+value.accessDate+'" ';
            html += 'data-modificationDate="'+value.modificationDate+'" ';
            html += 'data-mimeType="'+value.mimeType+'" ';
            html += 'data-name="'+value.name+'">';
            html += '<input class="file-check" type="checkbox" style="float:right" ';
            html += 'data-link="'+value.link+'" ';
            html += 'data-perms="'+value.rights+'" ';
            html += 'data-owner="'+value.owner+'" ';
            html += 'data-group="'+value.group+'" ';
            html += 'data-size="'+value.size+'" ';
            html += 'data-accessDate="'+value.accessDate+'" ';
            html += 'data-modificationDate="'+value.modificationDate+'" ';
            html += 'data-mimeType="'+value.mimeType+'" ';
            html += 'data-name="'+value.name+'">';
            html += value.name+'</a>\n';
        });
        $('#lstFiles').html(html);
        refreshListFile();
        return true;
    } else {
        $('#lstFiles').html(oldHtml);
        return false;
    }
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
        if (json.error == 0) {
            $('#editor').val(json.data);
            $('#textEditor').modal('show');
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
        html = '<h1>'+$(this).attr('data-file')+'</h1>';
        html += '<strong>Mime-Type: </strong>'+$(this).attr('data-mimeType')+'<br>';
        html += '<strong>Permissions: </strong>'+$(this).attr('data-rights')+'<br>';
        html += '<strong>Owner: </strong>'+$(this).attr('data-owner')+'<br>';
        html += '<strong>Group: </strong>'+$(this).attr('data-group')+'<br>';
        html += '<strong>Size: </strong>'+formatBytes($(this).attr('data-size'), 1)+'<br>';
        html += '<strong>Acceded: </strong>'+$(this).attr('data-accessDate')+'<br>';
        html += '<strong>Modified: </strong>'+$(this).attr('data-modificationDate')+'<br>';
        html += $(this).attr('data-link') != '' ? '<strong>Link to: </strong>'+$(this).attr('data-link')+'\n' : '';
        $('#panelDetail').html(html);
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
        newPath = actualPath.split('/');
        newPath.pop();
        newPath.pop();
        actualPath = newPath.join('/') + '/';
        cd(actualPath);
    })
}

function formatBytes(bytes,decimals) {
   if(bytes == 0) return '0 Bytes';
   var k = 1000,
       dm = decimals + 1 || 3,
       sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
       i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
