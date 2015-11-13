<?php
require_once(dirname(__FILE__) . '/wxmp/common/GlobalDefine.php');
require_once(dirname(__FILE__) . '/wxmp/common/GlobalFunctions.php');
require_once(dirname(__FILE__) . '/wxmp/common/Common.php');

//验证token
function checkSignature() {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];    
            
    $token = WEIXIN_TOKEN;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);
    
    if($tmpStr == $signature) {
        return true;
    } else {
        return false;
    }
}
//if(false == checkSignature()){
//    exit(0);
//}

//$echoStr = $_GET["echostr"];

//valid signature, option
if(checkSignature()){
    echo $_GET["echostr"];
    exit(0);
}

function getWeChatObj($toUserName) {
	if($toUserName == USERNAME_JSZL) {
		require_once ROOT_PATH . '/class/WeChatCallBackJSZL.php';
		return new WeChatCallBackJSZL();
	}

	require_once ROOT_PATH . '/class/WeChatCallBack.php';
    //interface_log(INFO, EC_OK, 'require成功:');
	return  new WeChatCallBack();
}

function exitErrorInput(){
	
	echo 'error input!';
	interface_log(INFO, EC_OK, "***** interface request end *****");
	interface_log(INFO, EC_OK, "*********************************");
	interface_log(INFO, EC_OK, "");
	exit ( 0 );
}

$postStr = file_get_contents("php://input");
if (empty($postStr)) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}

interface_log(INFO, EC_OK, "");
interface_log(INFO, EC_OK, "***********************************");
interface_log(INFO, EC_OK, "***** interface request start *****");
interface_log(INFO, EC_OK, 'request:' . $postStr);
interface_log(INFO, EC_OK, 'get:' . var_export($_GET, true));

// 获取参数
$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
if(NULL == $postObj) {
	interface_log(ERROR, 0, "can not decode xml");	
	exit(0);
}

$content=$postObj->Content;
interface_log(INFO, EC_OK, '内容:' . $content);
$toUserName = (string)trim($postObj->ToUserName);

if (!$toUserName) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
} else {
	$wechatObj = getWeChatObj($toUserName);
}

$ret = $wechatObj->init($postObj);
interface_log(INFO, EC_OK, 'init($postObj):' . $ret);
if (! $ret) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}

$retStr = $wechatObj->process ();
interface_log ( INFO, EC_OK, "response:" . $retStr );
echo $retStr;

interface_log(INFO, EC_OK, "***** interface request end *****");
interface_log(INFO, EC_OK, "*********************************");
interface_log(INFO, EC_OK, "");

$useTime = microtime(true) - $startTime;
interface_log ( INFO, EC_OK, "cost time:" . $useTime . " " . ($useTime > 4 ? "warning" : "") );

?>
