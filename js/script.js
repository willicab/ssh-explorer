function cd(path) {
    console.log('cd '+path);
    oldHtml = $('#lstFiles').html();
    $('#lstFiles').html('<img src="img/loader.gif">');
    $.get("include/cd.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
        json = JSON.parse(data);
        if (json.error == 0) {
            html = path == '/' ? '' : '<a class="list-group-item list-up">Folder up</a>';
            $.each(json.data, function(index, value) {
                className1 = value.mimetype.split('/')[0];
                className2 = value.mimetype.split('/')[1];
                html += '<a class="list-group-item list-file '+className1+' '+className2+'" ';
                html += 'data-mimetype="'+value.mimetype+'" ';
                html += 'data-perms="'+value.perms+'" ';
                html += 'data-owner="'+value.owner+'" ';
                html += 'data-group="'+value.group+'" ';
                html += 'data-size="'+value.size+'" ';
                html += 'data-date="'+value.date+'" ';
                html += 'data-time="'+value.time+'" ';
                html += 'data-file="'+value.file+'" ';
                html += 'data-link="'+value.link+'">';
                html += '<input class="file-check" type="checkbox" style="float:right" ';
                html += 'data-mimetype="'+value.mimetype+'" ';
                html += 'data-perms="'+value.perms+'" ';
                html += 'data-owner="'+value.owner+'" ';
                html += 'data-group="'+value.group+'" ';
                html += 'data-size="'+value.size+'" ';
                html += 'data-date="'+value.date+'" ';
                html += 'data-time="'+value.time+'" ';
                html += 'data-file="'+value.file+'" ';
                html += 'data-link="'+value.link+'">';
                html += value.file+'</a>\n';
            });
            $('#lstFiles').html(html);
            $('#titlePath').val(path);
            refreshListFile();
        } else {
            $('#lstFiles').html(oldHtml);
        }
    });
}

function cat(path) {
    console.log('cat '+path);
    $.get("include/cat.php", {host:host, port:port, username:username, password:password, path:path}, function( data ) {
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
    $.get("include/base64.php", {host:host, port:port, username:username, password:password, path:path, mimetype:mimetype}, function( data ) {
        json = JSON.parse(data);
        if (json.error == 0) {
            $('#viewer').prop('src', json.data);
        }
    });
}

function refreshListFile() {
    $('.list-file').click(function(){
        html = '<h1>'+$(this).attr('data-file')+'</h1>';
        html += '<strong>MIME-Type: </strong>'+$(this).attr('data-mimetype')+'<br>';
        html += '<strong>Permissions: </strong>'+$(this).attr('data-perms')+'<br>';
        html += '<strong>Owner: </strong>'+$(this).attr('data-owner')+'<br>';
        html += '<strong>Group: </strong>'+$(this).attr('data-group')+'<br>';
        html += '<strong>Size: </strong>'+formatBytes($(this).attr('data-size'), 1)+'<br>';
        html += '<strong>Modified: </strong>'+$(this).attr('data-date')+' '+$(this).attr('data-time')+'<br>';
        html += $(this).attr('data-link') != '' ? '<strong>Link to: </strong>'+$(this).attr('data-link')+'\n' : '';
        $('#panelDetail').html(html);
    })
    $('.list-file').dblclick(function(){
        mime1 = $(this).attr('data-mimetype').split('/')[0];
        mime2 = $(this).attr('data-mimetype').split('/')[1];
        if ($(this).attr('data-mimetype') == 'inode/directory') {
            actualPath += $(this).attr('data-file') + '/';
            cd(actualPath);
        } else if (mime1 == 'text' || mime2 == 'x-empty') {
            cat(actualPath+$(this).attr('data-file'));
        } else if (mime1 == 'image') {
            getImage(actualPath+$(this).attr('data-file'), $(this).attr('data-mimetype'));
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
