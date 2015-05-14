<?php
require_once "nusoap.php";

$serverName = "https://www.develost.com/apps/surfway/last";
$serverMessageCode = "serverMessageCode";
$serverMessageText = "serverMessageText";
$serverVersion = "serverVersion";
$clientUsernameMessage = "clientUsernameMessage";
$clientPasswordMessage = "clientPasswordMessage";
$clientVersionMessage = "clientVersionMessage";
$clientFileMessage = "clientFileMessage";
$clientGenTypeMessage = "clientGenTypeMessage";
$serverDetails = array();
$applicationFolder = "";


function time_elapsed_A($secs){
    $ret = "";
    $bit = array(
        'y' => $secs / 31556926 % 12,
        'w' => $secs / 604800 % 52,
        'd' => $secs / 86400 % 7,
        'h' => $secs / 3600 % 24,
        'm' => $secs / 60 % 60,
        's' => $secs % 60
    );
    foreach($bit as $k => $v) {
        if($v > 0) {
            //$ret[] = $v . $k;
            $ret .= $v.$k . ' ';
        }
    }
    if ($ret == "") {$ret = "0s";}
    return $ret;
}


function generate(){
    global $serverName;
    global $serverMessageCode;
    global $serverMessageText;
    global $serverVersion;
    global $clientUsernameMessage;
    global $clientPasswordMessage;
    global $clientVersionMessage;
    global $clientFileMessage;
    global $clientGenTypeMessage;
    global $serverDetails;    
    global $applicationFolder;
    

    if (isset($_POST["generate"])){
        $inputValid = true;
        
        if (isset($_POST["username"])){
            $clientUsernameMessage = $_POST["username"];
        }else{
            $clientUsernameMessage = "ERROR - username not set";
            $inputValid = false;
        }
        
        if (isset($_POST["password"])){
            $clientPasswordMessage = "&#42;&#42;&#42;&#42;&#42;";
        }else{
            $clientPasswordMessage = "ERROR - password not set";
            $inputValid = false;
        }        
        
        if (isset($_POST["version"])){
            $clientVersionMessage = $_POST["version"];        
        }else{
            $clientVersionMessage = "ERROR - version not set";
            $inputValid = false;
        }
        

        if (isset($_FILES["filename"])){
            if ($_FILES["filename"]["error"] > 0) {
                $clientFileMessage = "Error: " . $_FILES["filename"]["error"];
                $inputValid = false;
            } else {
                // TODO check XML file with content
                $xml = simplexml_load_file($_FILES["filename"]["tmp_name"]);
                if ($xml === false) {
                    $clientFileMessage = "Error parsing XML: " . $_FILES["filename"]["error"];
                    $inputValid = false;
                }else{
                    $applicationFolder = $xml->folder;
                    $clientFileMessage = "";
                    $clientFileMessage.= $_FILES["filename"]["name"];
                    $clientFileMessage.= " - ".intval($_FILES["filename"]["size"] / 1024) . " kB";
                    //$clientFileMessage.= "Stored in: " . $_FILES["filename"]["tmp_name"];
                    //echo "Type: " . $_FILES["filename"]["type"] . "<br>";
                    //echo "Size: " . ($_FILES["filename"]["size"] / 1024) . " kB<br>";
                    //echo "Stored in: " . $_FILES["filename"]["tmp_name"];
                }
            }
        }else{
            $clientFileMessage = "ERROR - application file not set";
            $inputValid = false;
        }

        if (isset($_POST["genType"])){
            $clientGenTypeMessage = $_POST["genType"];
        }else{
            $clientGenTypeMessage = "ERROR - genType not set";
            $inputValid = false;
        }        
        
        if ($inputValid){
            $start = time();
            
            ini_set( "soap.wsdl_cache_enabled", "0" );
            $client=new SoapClient('xml2app.wsdl');
            
            //$client = new nusoap_client($serverName);
            $applicationStringt = file_get_contents($_FILES["filename"]["tmp_name"]);
            $applicationString = base64_encode(gzcompress(serialize($applicationStringt))); 
            $retval = $client->generate($_POST["username"],$_POST["password"],$_POST["genType"],$_POST["version"],$applicationString);
            $result = unserialize(gzuncompress(base64_decode($retval)));

            // OK
            $pages = $result['pages'];
            $pagesLen = count($pages);
            $pageNames = array_keys($pages);
            
            if (!file_exists($applicationFolder)) {
                mkdir($applicationFolder, 0755, true);
            }
            
            $customCssContent ='/* Custom CSS goes here */'. PHP_EOL;
            $customCssContent.='/* This file will never be overwritten */'. PHP_EOL;
            
            if (!file_exists($applicationFolder.'/custom.css')) {
                file_put_contents($applicationFolder.'/custom.css',$customCssContent);
            }
            
            for ($i=0;$i<$pagesLen;$i++){
                file_put_contents($applicationFolder.'/'.$pageNames[$i],$pages[$pageNames[$i]]);
            }

            $stop = time();
            $now = date(DATE_RFC2822);
            array_push($serverDetails, "Generated in ". time_elapsed_A($stop-$start) . ' - ' .  $now);
            
            
            $generationLog = "";
            $generationLog.= "now:               ".$now.PHP_EOL;
            $generationLog.= "generated in:      ".time_elapsed_A($stop-$start).PHP_EOL;
            $generationLog.= "username:          ".$_POST["username"].PHP_EOL;
            $generationLog.= "password:          *****".PHP_EOL;
            $generationLog.= "genType:           ".$_POST["genType"].PHP_EOL;
            $generationLog.= "version:           ".$_POST["version"].PHP_EOL;
            $generationLog.= "application:       ".$applicationString.PHP_EOL;
            $generationLog.= "result:            ".$retval.PHP_EOL;
            file_put_contents($applicationFolder.'/generation.log',$generationLog);
            
            
            
            
            $serverMessageCode = $result['messageCode'];
            $serverMessageText = $result['messageText'];
            $serverVersion = $result['version'];
            array_push($serverDetails, "Application folder " . $applicationFolder);
            array_push($serverDetails, "" . $pagesLen . " files generated:");
            $pageNames = array_keys($pages);
            for ($i=0;$i<$pagesLen;$i++){
                array_push($serverDetails, " - " .$pageNames[$i]);                        
            }
            //OK
        }
    }else{
        $serverMessageCode     = "ERROR - this is not a generation";
    }
}
?>


<?php
generate();
?>




<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta http-equiv="cache-control" content="no-cache" />
        <title>It's a trap - Version 0.0.1</title>
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
            .status-name{float:left;width:20%;overflow:hidden;}
            .full-size{float:left;width:95%;}            
            .status-value{margin-left:5%;width:70%;float:left;}
        </style>
    </head>
    <body>
    <form action="index.php">
    <div class="unselectable header">
            <h1 class="unselectable" unselectable="on">It's a trap</h1>
            <h5 class="unselectable" unselectable="on">Version 0.0.0</h5>
        </div>
        <div class="container">
            <div class="content">
                <div class="section">
                    <h2>Result</h2>
                    <p class="row"><?php echo $serverMessageCode . " &#45; " . $serverMessageText;?></p>
                    <p class="row"><input class="full-size" type="submit" value="Go back"></p>
                    <p class="row"></p>
                </div>                
                <div class="section">
                    <h2>Request</h2>
                    <p class="row"><label class="status-name">Version</label><label class="status-value"><?php echo $clientVersionMessage . " &#45;&#45;&#62; " . $serverVersion; ?></label></p>
                    <p class="row"><label class="status-name">User</label><label class="status-value"><?php echo $clientUsernameMessage;?></label></p>
                    <p class="row"><label class="status-name">Password</label><label class="status-value"><?php echo $clientPasswordMessage;?></label></p>
                    <p class="row"><label class="status-name">File</label><label class="status-value"><?php echo $clientFileMessage;?></label></p>
                    <p class="row"><label class="status-name">GenType</label><label class="status-value"><?php echo $clientGenTypeMessage;?></label></p>
                    <p class="row"></p>
                </div>
                <div class="section">
                    <h2>Details</h2>
                    <?php
                        $detailsLen = count($serverDetails);
                        for ($i=0;$i<$detailsLen;$i++){
                            echo '<p class="row">'.$serverDetails[$i].'</p>'.PHP_EOL;
                        }
                    ?>
                    <p class="row"></p>
                </div>
                </form>
            </div>
        </div>
        <div class="unselectable footer">
            <h5 class="unselectable" unselectable="on">tante belle cose da mettere nel footer</h5>
        </div>
    </body>
    </form>
</html>