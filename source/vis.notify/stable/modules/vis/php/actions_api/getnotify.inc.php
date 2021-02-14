<?php

$result=array();
$result['data']=array();
if (($data=osW_VIS_Notify::getInstance()->get(osW_VIS_User::getInstance()->getId()))!=array()) {
	foreach ($data as $notify) {
		$result['data'][]=array(
			'id'=>$notify['notify_id'],
			'message'=>$notify['notify_message'],
			'style'=>$notify['notify_style'],
			'time'=>$notify['notify_time'],
		);
	}
}

echo json_encode($result);

?>