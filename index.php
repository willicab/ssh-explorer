<!DOCTYPE html>
<html lang="esp" ng-app="app" itemscope itemtype="http://schema.org/Product">
    <head>
        <title>Explorer</title>
        <meta charset="UTF-8">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="row" id="login">
                <div class="col-md-4"></div>
                <div class="col-md-4">
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
                <div class="col-md-8" id="explorer">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="input-group">
                                <input type="text" class="form-control" id="titlePath" value="/">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btnCD"><img src="img/play.png" width="20"></button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="panel-body list-group">
                            <div class="btn-toolbar" role="toolbar" aria-label="...">
                                <div class="btn-group" role="group" aria-label="generalActions" id="toolBar">
                                    <button id="btnNewFolder" type="button" class="btn btn-default"><img src="img/folder-new.png"></button>
                                    <button id="btnNewFile" type="button" class="btn btn-default"><img src="img/file-new.png"></button>
                                    <button type="button" class="btn btn-default"><img src="img/upload.png"></button>
                                    <button type="button" class="btn btn-default"><img src="img/search.png"></button>
                                    <button type="button" class="btn btn-default"><img src="img/console.png"></button>
                                    <button type="button" class="btn btn-default"><img src="img/refresh.png"></button>
                                </div>
                                <div class="btn-group" role="group" aria-label="fileActions" id="toolBar">
                                    <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/copy.png"></button>
                                    <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/move.png"></button>
                                    <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/delete.png"></button>
                                    <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/rename.png"></button>
                                    <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/list-checks.png"></button>
                                    <button type="button" class="btn btn-default action action-zip action-folder action-file"><img src="img/download.png"></button>
                                    <button type="button" class="btn btn-default action action-folder action-file"><img src="img/compress.png"></button>
                                    <button type="button" class="btn btn-default action action-zip"><img src="img/uncompress.png"></button>
                                </div>
                            </div>
                        </div>
                        <div id="lstFiles"></div>
                    </div>
                </div>
                <div class="col-md-4" id="sideBar">
                    <div class="panel panel-default">
                        <div class="panel-heading">Details</div>
                        <div class="panel-body list-group" id="panelDetail"></div>
                    </div>
                </div>
            </div>
            <!-- Modal Editor-->
            <div class="modal fade" id="textEditor" tabindex="-1" role="dialog" aria-labelledby="textEditorTitle">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="textEditorTitle">Text Editor</h4>
                        </div>
                        <div class="modal-body">
                            <textarea id="editor" style="width:100%;height:300px;"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
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
        </div>
    </body>
    <script src="js/jquery-2.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        var host, port, username, password;
        var p
        oldHtml = '';
        actualPath = '/';
        $(document).ready(function () {
            $('#loginBtn').click(function(){
                console.log("Login")
                host = $("#loginHost").val();
                port = $("#loginPort").val();
                username = $("#loginUsername").val();
                password = $("#loginPassword").val();
                $("#loginBtn").prop("disabled", true);
                $("#loginBtn").html('<img src="img/loader.gif">');
                $.post("include/login.php", {host:host, port:port, username:username, password:password, path:actualPath}, function( data ) {
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
            $("#btnCD").click(function(){
                cd($("#btnCD").val());
            });
            $('#btnDialogInputAccept').click(function(){
                switch($('#dialogInput').attr("data-action")) {
                    case "mkdir":
                        mkdir(actualPath, $('#txtDialogInput').val());
                        break;
                    case "touch":
                        touch(actualPath, $('#txtDialogInput').val());
                        break;
                }
            });
            $("#btnNewFile").click(function(){
                $('#dialogInput').attr("data-action", "touch");
                $('#dialogInputLabel').text("Create File");
                $('#txtDialogInput').prop('placeholder', 'Insert File Name');
                $('#txtDialogInput').val('')
                $('#dialogInput').modal('show');
            });
            $("#btnNewFolder").click(function(){
                $('#dialogInput').attr("data-action", "mkdir");
                $('#dialogInputLabel').text("Create Folder");
                $('#txtDialogInput').prop('placeholder', 'Insert Folder Name');
                $('#txtDialogInput').val('')
                $('#dialogInput').modal('show');
            });
        });
    </script>
</html>
