<!DOCTYPE html>
<html lang="esp" ng-app="app" itemscope itemtype="http://schema.org/Product">
    <head>
        <title>Explorer</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="img/logo.png" />
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="js/codemirror.css" rel="stylesheet" type="text/css" />
        <link href="js/theme/neat.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="container-fluid">
            <div class="row" id="login">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div id="title">
                        <img src="img/logo.png">
                        <h1>ssh-explorer</h1>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Login</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8"><input type="text" class="form-control" id="loginHost" placeholder="Host" value=""></div>
                                <div class="col-md-4"><input type="number" class="form-control" id="loginPort" placeholder="Port" value=""></div>
                            </div>
                            <input type="text" class="form-control" id="loginUsername" placeholder="Username" value="">
                            <input type="password" class="form-control" id="loginPassword" placeholder="Password" value="">
                            <button class="form-control btn btn-default" id="loginBtn">Login</button>
                        </div>
                    </div>
                    <div class="alert alert-danger alert-dismissible" role="alert" style="text-align:center;display:none" id="loginError">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Ha habido un error al conectarse, por favor, verifique los datos e intente de nuevo.
                    </div>
                </div>
            </div>
            <div class="row" id="main">
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="navbar-form navbar-left" role="toolbar" style="width:100%">
                        <div class="input-group" style="width:100%">
                            <span class="input-group-btn" style="width:1px">
                                <button id="btnNewFolder" type="button" class="btn btn-default">
                                    <img src="img/folder-new.png" width="16">
                                </button>
                                <button id="btnNewFile" type="button" class="btn btn-default">
                                    <img src="img/file-new.png" width="16">
                                </button>
                                <button id="btnBashCmd" type="button" class="btn btn-default">
                                    <img src="img/console.png" width="16">
                                </button>
                                <button id="btnSysInfo" type="button" class="btn btn-default">
                                    <img src="img/information.png" width="16">
                                </button>
                            </span>
                            <input type="text" class="form-control" id="titlePath" value="/">
                            <span class="input-group-btn" style="width:1px">
                                <button class="btn btn-default" type="button" id="btnCD">
                                    <img src="img/play.png" width="16">
                                </button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                </nav>
                <div style="background-color:#fff;margin-top:50px;margin-bottom:50px;max-height:100%;overflow:hidden">
                    <div id="lstFiles"></div>
                </div>
                <nav class="navbar navbar-default navbar-fixed-bottom">
                    <!--form class="navbar-form navbar-left" role="toolbar">
                        <div class="btn-group" role="toolbar" aria-label="fileActions" id="toolBar">
                        </div>
                    </form-->
                    <p class="navbar-text" id="fileInfo"></p>
                </nav>
            </div>
            <!-- Modal Wait -->
            <div id="modalWait"></div>
            <!-- Modal Editor-->
            <div id="textEditor">
                <div id="headerEditor" style="padding:3px;overflow-x:hidden;">
                    <button type="button" class="btn btn-default" id="btnEditorClose">Close</button>
                    <button type="button" class="btn btn-primary" id="btnEditorSave">Save changes</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span id="pathEditor">/var/www/html/index.php</span>
                </div>
                <div id="bodyEditor">
                    <textarea id="editor"></textarea>
                </div>
            </div>
            <!-- Modal Imager Viewer-->
            <div class="modal fade" id="imageViewer" tabindex="-1" role="dialog" aria-labelledby="imageViewerLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="imageViewerLabel">Image Viewer</h4>
                        </div>
                        <div class="modal-body" style="text-align:center">
                            <img id="viewer" src="img/image.png" style="max-width:100%">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Dialog Input-->
            <div class="modal fade" id="dialogInput" tabindex="-1" role="dialog" aria-labelledby="dialogInputLabel" data-action="">
                <div class="modal-dialog modal-sg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="dialogInputLabel"></h4>
                        </div>
                        <div class="modal-body" style="text-align:center">
                            <input type="text" id="txtDialogInput" placeholder="" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btnDialogInputAccept">Accept</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Command-->
            <div class="modal fade" id="dialogCommand" tabindex="-1" role="dialog" aria-labelledby="dialogCommandLabel" data-action="">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="dialogCommandLabel">Custom Command</h4>
                        </div>
                        <div class="modal-body" style="text-align:center">
                            <div class="input-group" style="width:100%">
                                <input type="text" class="form-control" id="txtCommand" value="" style="height:36.5px">
                                <span class="input-group-btn" style="width:1px">
                                    <button class="btn btn-default" type="button" id="btnCommand">
                                        <img src="img/play.png" width="16">
                                    </button>
                                </span>
                            </div><!-- /input-group -->
                            <pre id="dialogCommandResult" style="text-align:left;background-color:#000;color:#0f0;font-family:monospace;max-height:30rem"></pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal SysInfo-->
            <div class="modal fade" id="dialogSysInfo" tabindex="-1" role="dialog" aria-labelledby="dialogSysInfoLabel" data-action="">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="dialogSysInfoLabel">System Info</h4>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group">
                                <li class="list-group-item" id="itemInfoKernel"></li>
                                <li class="list-group-item" id="itemInfoArch"></li>
                                <li class="list-group-item" id="itemInfoHostname"></li>
                                <li class="list-group-item" id="itemInfoMemTotal"></li>
                                <li class="list-group-item" id="itemInfoMemFree"></li>
                                <li class="list-group-item" id="itemInfoSwapTotal"></li>
                                <li class="list-group-item" id="itemInfoSwapFree"></li>
                                <li class="list-group-item" id="itemInfoDiskTotal"></li>
                                <li class="list-group-item" id="itemInfoDiskFree"></li>
                                <li class="list-group-item" id="itemInfoUptime"></li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Compress-->
            <div class="modal fade" id="dialogCompress" tabindex="-1" role="dialog" aria-labelledby="dialogCompressLabel" data-action="">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="dialogCompress">Compress</h4>
                        </div>
                        <div class="modal-body row">
                            <div class="col-md-10">
                                <input id="txtCompress" type="text" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <select id="selCompress">
                                    <option value="tar.gz">tar.gz</option>
                                    <option value="tar">tar</option>
                                    <option value="gz">gz</option>
                                    <option value="zip">zip</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" data-dismiss="modal" id="btnCompressAccept">Compress</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contextual Menu -->
            <div class="btn-group-vertical" role="group" aria-label="..." id="tooltip" style="position:absolute;display:none;">
                <button id="btnCopy" type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/copy.png"> Copy to</button>
                <button id="btnMove" type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/move.png">Rename/Move to</button>
                <button id="btnRm" type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/delete.png"> Remove</button>
                <button id="btnCompress" type="button" class="btn btn-default action action-folder action-file"><img src="img/compress.png"> Compress</button>
                <button id="btnExtract" type="button" class="btn btn-default action action-zip"><img src="img/extract.png"> Extract</button>
                <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/list-checks.png">Check Rights</button>
                <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/download.png">Download</button>
            </div>
        </div>
        <script src="js/jquery-2.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/codemirror.js"></script>
        <script src="js/mode/meta.js"></script>
        <script src="js/addon/mode/loadmode.js"></script>
        <script src="js/script.js"></script>
        <script>
            var host, port, username, password;
            var p
            var timer;
            oldHtml = '';
            actualPath = '/';
            var txtCodeEditor = '';
            var contextPath = '';
            $(document).ready(function () {
                CodeMirror.modeURL = "js/mode/%N/%N.js";
                var mixedMode = {
                    name: "htmlmixed",
                    scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i, mode: null},
                    {matches: /(text|application)\/(x-)?vb(a|script)/i, mode: "vbscript"}]
                };
                txtCodeEditor = CodeMirror.fromTextArea($('#editor')[0], {
                    theme: 'neat',
                    mode: mixedMode,
                    lineNumbers: true,
                    lineWrapping: true,
                });
                $('#loginBtn').click(function(){
                    console.log("Login")
                    host = $("#loginHost").val();
                    port = $("#loginPort").val();
                    username = $("#loginUsername").val();
                    password = $("#loginPassword").val();
                    $("#loginBtn").prop("disabled", true);
                    $("#loginBtn").html('<img src="img/loader.gif">');
                    $.post("include/login.php", {host:host, port:port, username:username, password:password, path:actualPath}, function( data ) {
                        console.log(data);
                        $("#loginBtn").prop("disabled", false);
                        $("#loginBtn").text("Login");
                        if (refreshList(data)) {
                            $('#login').fadeOut(500, function(){
                                $('#main').fadeIn(500);
                            });
                        } else {
                            $("#loginBtn").prop("disabled", false);
                            $("#loginBtn").text("Login");
                            $('#loginError').fadeIn(500);
                            console.log("Error");
                        }
                    });
                });
                $("#loginHost").keypress(function(e) {if(e.which == 13) {$("#loginPort").focus();}});
                $("#loginPort").keypress(function(e) {if(e.which == 13) {$("#loginUsername").focus();}});
                $("#loginUsername").keypress(function(e) {if(e.which == 13) {$("#loginPassword").focus();}});
                $("#loginPassword").keypress(function(e) {if(e.which == 13) {$("#loginBtn").trigger( "click" );}});
                $("#btnEditorClose").click(function(){
                    $('#textEditor').fadeOut();
                });
                $("#btnCD").click(function(){
                    actualPath = $("#titlePath").val();
                    cd(actualPath);
                });
                $("#titlePath").keypress(function(e) {
                    if(e.which == 13) {
                    	$("#btnCD").trigger( "click" );
                    }
                });
                $("#btnCommand").click(function(){
                    cmd(actualPath, $('#txtCommand').val());
                });
                $("#txtCommand").keypress(function(e) {
                    if(e.which == 13) {
                      	$("#btnCommand").trigger( "click" );
                    }
                });
                $('#btnSysInfo').click(function(){
                    sysInfo();
                });
                $("#txtDialogInput").keypress(function(e) {
                    if(e.which == 13) {
                        $("#btnDialogInputAccept").trigger( "click" );
                    }
                });
                $('#btnDialogInputAccept').click(function(){
                    switch($('#dialogInput').attr("data-action")) {
                        case "mkdir":
                            mkdir(actualPath, $('#txtDialogInput').val());
                            break;
                        case "touch":
                            touch(actualPath, $('#txtDialogInput').val());
                            break;
                        case "copy":
                            copy(actualPath, contextPath, $('#txtDialogInput').val());
                            break;
                        case "move":
                            move(actualPath, contextPath, $('#txtDialogInput').val());
                            break;
                    }
                });
                $("#btnBashCmd").click(function(){
                    $('#dialogCommand').modal('show');
                });
                $('#btnEditorSave').click(function(){
                    //console.log("Guardando", txtCodeEditor.getValue());
                    saveText($('#editor').attr('data-file'), txtCodeEditor.getValue());
                });
                $("#btnCopy").click(function(){
                    $('#dialogInput').attr("data-action", "copy");
                    $('#dialogInputLabel').text("Copy");
                    $('#txtDialogInput').prop('placeholder', 'Path to copy');
                    $('#txtDialogInput').val(contextPath);
                    $('#dialogInput').modal('show');
                });
                $("#btnMove").click(function(){
                    $('#dialogInput').attr("data-action", "move");
                    $('#dialogInputLabel').text("Rename/Move");
                    $('#txtDialogInput').prop('placeholder', 'Path to move');
                    $('#txtDialogInput').val(contextPath);
                    $('#dialogInput').modal('show');
                });
                $("#btnRm").click(function(){
                    if (confirm("Can you remove "+contextPath)) {
                        remove(actualPath, contextPath);
                    }
                });
                $("#btnExtract").click(function(){
                    file = contextPath.split('/');
                    extract(actualPath, file[file.length - 1]);
                });
                $("#btnCompress").click(function(){
                    $('#txtCompress').trigger('change');
                    $('#dialogCompress').modal('show');
                });
                $('#selCompress').change(function(){
                    file = contextPath.split('/');
                    $('#txtCompress').val(file[file.length - 1]+'.'+$('#selCompress').val());
                });
                $('#btnCompressAccept').click(function(){
                    file = contextPath.split('/');
                    compress(actualPath, file[file.length - 1], $('#txtCompress').val(), $('#selCompress').val());
                });
                $("#btnNewFile").click(function(){
                    $('#dialogInput').attr("data-action", "touch");
                    $('#dialogInputLabel').text("Create File");
                    $('#txtDialogInput').prop('placeholder', 'Insert File Name');
                    $('#txtDialogInput').val('');
                    $('#dialogInput').modal('show');
                });
                $("#btnNewFolder").click(function(){
                    $('#dialogInput').attr("data-action", "mkdir");
                    $('#dialogInputLabel').text("Create Folder");
                    $('#txtDialogInput').prop('placeholder', 'Insert Folder Name');
                    $('#txtDialogInput').val('');
                    $('#dialogInput').modal('show');
                });
            });
        </script>
    </body>
</html>
