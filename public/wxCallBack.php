<?php
include_once "php/WXBizMsgCrypt.php";  
// 第三方发送消息给公众平台   
$encodingAesKey = "bHVHIlpi6Ouc1jiLfPrRJHNCraNsrxZuXAQdDNgg2ir";
$token = "Kh6E3rdp8mSZ14S7CfigL";
$corpId = "wx32726be4bb032ac5";  
//公众号服务器数据  
$sReqMsgSig = $sVerifyMsgSig = $_GET['msg_signature'];  
$sReqTimeStamp = $sVerifyTimeStamp = $_GET['timestamp'];  
$sReqNonce = $sVerifyNonce = $_GET['nonce'];  
$sReqData = file_get_contents("php://input");;  
$sVerifyEchoStr = $_GET['echostr'];   
$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);   
if($sVerifyEchoStr){  
$sEchoStr = "";  
$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);  
if ($errCode == 0) {  
print($sEchoStr);   
} else {  
print($errCode . "\n\n");  
}  
exit;  
}  
 //decrypt  
$sMsg = "";  //解析之后的明文  
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);  
if ($errCode == 0) {   
$xml = new DOMDocument();  
$xml->loadXML($sMsg);   
$reqToUserName = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;  
$reqFromUserName = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;  
$reqCreateTime = $xml->getElementsByTagName('CreateTime')->item(0)->nodeValue;  
$reqMsgType = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;  
$reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;  
$reqMsgId = $xml->getElementsByTagName('MsgId')->item(0)->nodeValue;  
$reqAgentID = $xml->getElementsByTagName('AgentID')->item(0)->nodeValue;   
if ($reqMsgType == 'event') {
		$MsgEvent = $xml->getElementsByTagName('Event')->item(0)->nodeValue;
		if ($MsgEvent == 'click') {
			$EventKey = $xml->getElementsByTagName('EventKey')->item(0)->nodeValue;
			require 'wxCallBackFunctions.php';
			switch ($EventKey) {
				case 'rec_info':
					//$mycontent = createSelfInfo($reqFromUserName);
					$mycontent = "收件地址";
					break;
				case 'rec_m_info':
					//$mycontent = createRelationsInfo();
					$mycontent = "收款人：北京华名中驰知识产权代理有限公司\n开户行：中国农业银行北京市西城区支行民航大厦分理处\n帐号： 11021001040007943";
					break;
				case 'tel_info':
					//$mycontent = createFinanceInfo($reqFromUserName);
					$mycontent = "电话传真信息";
					break;

				default:
					break;
			}
		}
	}elseif ($reqMsgType == 'text') {
		//$reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
		require 'wxCallBackFunctions.php';
		require 'conf/conf.php';

		
		if(adminInList($reqFromUserName)){
			if(nameInList($reqContent)){
			$mycontent = createSelfInfo(nameInList($reqContent));
			}else{
				$mycontent = "没有这个用户的统计信息";
			}
		}else{
			$mycontent = "您没有权限";
		}
		
		
		/*
		switch ($reqContent) {
			case 'Eric':
				$mycontent = "He is a good man!";
				break;
			case 'Louis':
				$mycontent = "He is SuperMan!";
				break;
			default:
				$mycontent = "Can't find this guy!";
				break;
		}
		*/
	}elseif ($reqMsgType == 'image') {
		$mycontent = "This is a picture !";
	}
$sRespData =   
"<xml>  
<ToUserName><![CDATA[".$reqFromUserName."]]></ToUserName>  
<FromUserName><![CDATA[".$corpId."]]></FromUserName>  
<CreateTime>".sReqTimeStamp."</CreateTime>  
<MsgType><![CDATA[text]]></MsgType>  
<Content><![CDATA[".$mycontent."]]></Content>  
</xml>";  
$sEncryptMsg = ""; //xml格式的密文  
$errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);  
if ($errCode == 0) {  
//file_put_contents('smg_response.txt', $sEncryptMsg); //debug:查看smg  
print($sEncryptMsg);  
} else {  
print($errCode . "\n\n");  
}  
} else {  
print($errCode . "\n\n");  
}  
?>  