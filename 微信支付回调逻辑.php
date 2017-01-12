<?php
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$api = new WxPayApi();					//Wxpay.Api.php
$notify = new WxPayResults();			//Wxpay.Data.php
$notify_reply = new WxPayNotifyReply();	//Wxpay.Data.php
$notify->FromXml($xml);
if($notify->CheckSign()){
	$total_fee = $notify->values["total_fee"];
	$trade_id = $notify->values["attach"];
	$amount = 99;//根据$trade_id获取farm_trade_log表中的trade_money
	if(intval($amount*100) == $total_fee){ //判断金额
		//步骤1：处理farm_trade_log表中的流水记录为成功
		//步骤2：处理前台用户的余额增加trade_money
		//步骤3：返回结果给微信
		$notify_reply->SetReturn_code("SUCCESS");
		$notify_reply->SetReturn_msg("OK");
	}else{
		$notify_reply->SetReturn_code("FAIL");
		$notify_reply->SetReturn_msg("Total_fee error");
	}
}else{
	$notify_reply->SetReturn_code("FAIL");
	$notify_reply->SetReturn_msg("Sign error");
}
$api->replyNotify($notify_reply->ToXml());



//221.11.5.41 