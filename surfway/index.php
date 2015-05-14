<?php
    function checkPhpRunning(){
        return 'valid';
    }
    
    function checkWritePermission(){
        $dirname = __DIR__ ;
        if (is_writable($dirname)) {
            return 'valid';
        } else {
            return '';
        }        
    }

    function checkMysqlDriver(){
        if (function_exists('mysqli_get_client_version')){
            return 'valid';
        }else{
            return '';
        }
    } 
    
    function checkOracleDriver(){
        if (function_exists('oci_client_version')){
            return 'valid';
        }else{
            return '';
        }        
    }         

    function checkPostgresDriver(){
        if (function_exists('pg_query')){
            return 'valid';
        }else{
            return '';
        }
    }        
    
    function checkSqliteDriver(){
        if (function_exists('sqlite_exec')){
            return 'valid';
        }else{
            return '';
        }
    }        
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta http-equiv="cache-control" content="no-cache" />
        <title>It's a trap - Version 0.0.0</title>
        <style>
            *{margin:0;padding:0;}
            body{overflow:scroll-x;margin:0;padding:0;border:0;height:100%;width:100%;font-family:Verdana;background-color:#BBBBBB/*#FF9900*/;}
            .header{width:100%;text-align:center;}
            .header > h1{padding-top:10px;}
            .container{width:96%;background-color:#FFFFFF;margin-left:2%;margin-right:2%;margin-top:2%;margin-bottom:2%;border:1px solid;border-radius: 25px;}
            .content{margin:12px;}
            .footer{width:100%;text-align:center;}
            .footer > h5{padding-bottom:10px;}
            .section{clear:both;}
            .row{padding-left:5%;padding-top:7px;clear:both;}
            .unselectable{-moz-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;}
            .status-name{float:left;width:80%;overflow:hidden;}
            .full-size{float:left;width:95%;}
            .status-value{margin-left:5%;width:10%;float:left;background:red;}
            input:invalid+label{background:red;}
            input:valid+label{background:green;}
            .valid{background:green;}
        </style>
    </head>
    <body>
    <div class="unselectable header">
            <h1 class="unselectable" unselectable="on">It's a trap</h1>
            <h5 class="unselectable" unselectable="on">Version 0.0.1</h5>
        </div>
        <div class="unselectable container">
            <div class="content">
                <form action="generate.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="version" id="version" value="0.0.1">
                    <div class="section">
                        <h2 class="unselectable" unselectable="on">User</h2>
                        <p class="row"><input class="status-name" type="text" name="username" id="username" placeholder="type username" required=""/> <label class="status-value" unselectable="on">&nbsp;</label></p>
                        <p class="row"><input class="status-name" type="password" name="password" id="password" placeholder="type password" required=""/> <label class="status-value" unselectable="on">&nbsp;</label></p>
                        <p class="row"></p>
                    </div>                
                    <div class="section">
                        <h2 class="unselectable" unselectable="on">Application</h2>
                        <p class="row"><input class="status-name" type="file" name="filename" id="filename" placeholder="type filename" required=""/> <label class="status-value" unselectable="on">&nbsp;</label></p>
                        <p class="row"><select class="full-size" name="genType" required=""><option value="dev">Development (no support - free - with disclaimer)</option>  <option value="prod">Production (support - has a cost - no disclaimer)</option></select></p>
                        <p class="row"><input class="full-size" name="generate" id="generate" type="submit" value="Generate"/></p>
                        <p class="row"></p>
                    </div>
                    <div class="section">
                        <h2 class="unselectable" unselectable="on">Server status</h2>        
                        <p class="row"><label unselectable="on" class="status-name unselectable">PHP Running</label><label class="status-value unselectable <?php echo checkPhpRunning();?>" unselectable="on">&nbsp;</label></p>
                        <p class="row"><label unselectable="on" class="status-name unselectable">Write permission</label><label class="status-value unselectable <?php echo checkWritePermission();?>" unselectable="on">&nbsp;</label></p>
                        <p class="row"><label unselectable="on" class="status-name unselectable">Mysql driver</label><label class="status-value unselectable <?php echo checkMysqlDriver();?>" unselectable="on">&nbsp;</label></p>
                        <p class="row"><label unselectable="on" class="status-name unselectable">Oracle driver</label><label class="status-value unselectable <?php echo checkOracleDriver();?>" unselectable="on">&nbsp;</label></p>
                        <p class="row"><label unselectable="on" class="status-name unselectable">Postgres driver</label><label class="status-value unselectable <?php echo checkPostgresDriver();?>" unselectable="on">&nbsp;</label></p>
                        <p class="row"><label unselectable="on" class="status-name unselectable">Sqlite driver</label><label class="status-value unselectable <?php echo checkSqliteDriver();?>" unselectable="on">&nbsp;</label></p>
                        <p class="row"></p>
                    </div>
                </form>
            </div>
        </div>
        <div class="unselectable footer">
            <h5 class="unselectable" unselectable="on">tante belle cose da mettere nel footer</h5>
        </div>
    </body>
</html>