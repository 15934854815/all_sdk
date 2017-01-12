<?php
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$api = new WxPayApi();					//Wxpay.Api.php
$notify = new WxPayResults();			//Wxpay.Data.php
$notify_reply = new WxPayNotifyReply();	//Wxpay.Data.php
$notify->FromXml($xml);
if($notify->CheckSign()){
	$total_fee = $notify->values["total_fee"];
	$trade_id = $notify->values["attach"];
	$amount = 99;//����$trade_id��ȡfarm_trade_log���е�trade_money
	if(intval($amount*100) == $total_fee){ //�жϽ��
		//����1������farm_trade_log���е���ˮ��¼Ϊ�ɹ�
		//����2������ǰ̨�û����������trade_money
		//����3�����ؽ����΢��
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