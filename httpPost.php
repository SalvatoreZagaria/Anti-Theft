<?php
$ch = curl_init("https://fcm.googleapis.com/fcm/send");

//The device token.
$token = $argv[1];

//Title of the Notification.
$title = "ALLARME";

//Body of the Notification.
$body = "E' possibile che qualcuno stia cercando di irrompere nella tua casa!";

//Setting sound
$sound = "default";

//Setting icon
$icon = "alarm_notif2";

//Setting icon color
$color = "#980404";

//Creating the notification array.
$notification = array('title' =>$title , 'body' => $body, 'icon' => $icon, 'sound' => $sound, 'color' => $color);

//Setting priority
$priority = "high";

//This array contains, the token and the notification. The 'to' attribute stores the token.
$arrayToSend = array('to' => $token, 'notification' => $notification);

//Generating JSON encoded string form the above array.
$json = json_encode($arrayToSend);

//Setup headers:
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key= AAAA97A-8u8:APA91bEXjyWO_lTOpvWiXjSoqv21HN__l1XTZA_HZuSbxpcU0LsB1wJJIOCQrS6kmsOWhv3ONTfdr7wPUWet9r20gIseAUi_B21QTE1UDZDvafiMXMwbtLbWKyTjFDwWcvOUOLvD6MiL';

//Setup curl, add headers and post parameters.
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

//Send the request
curl_exec($ch);

//Close request
curl_close($ch);

?>
