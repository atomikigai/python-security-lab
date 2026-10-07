<?php 
if (strpos($message, "/extra")===0 or strpos($message, "!extra")===0 or strpos($message, ".extra")===0){
deleteprm($userId);
is_bin_ban_userbot();
  if ($userId != $Mi_Id ){
    if($chatId != verifiCharAdmin($chatId)){
    if( $userId != verifiPremium($userId)){
        if($userId != verifiAdmin($userId)){
            if($userId != veritimepremium($userId)){
    
            bot('sendMessage', [
                'chat_id' =>$chatId,
    'reply_to_message_id'=>$message_id,
    'parse_mode'=>'HTML',
                'text' =>"<b>Hello this chat is not Authorized
❄️ Buy a membership to make use of this commands
═══════════════════════
↯ Contact A Owner or a Seller</b>",
                'reply_markup'=> json_encode(['inline_keyboard'=>[
                    [['text'=>"🝂 𝗥𝗶𝘁𝗮 𝗖𝗵𝗲𝗰𝗸「𝑹𝒚𝒌」 ",'url'=>"https://example.invalid/legacy"]]
                    ],'resize_keyboard'=>true])
                ]);       
    die();
}}}}}

$get_Card = substr($message, 1);
$input = preg_replace("/\W/", " ", $get_Card);
$input = preg_replace("/\r|\n/", ' ', $input);
$input = preg_replace("/[^0-9]/", ' ', $input);
$input = preg_replace('/\s+/', ' ', $input);
$input = trim($input, ' ');
$card = explode(" ", $input);

$Extra = substr($card[0], 0, 6);

if(empty ($Extra)){
  reply_to($chatId,$message_id,$keyboard,"Ingrese un bin Valido");
  exit();
}
$chem = substr($Extra, 0,1);
  $vaut = array(1,2,7,8,9,0);
if (in_array($chem, $vaut)) { 
      reply_to($chatId, $message_id,$keyboard,'<b>Este bot solo soporta Amex, Visa, MasterCard y Discover.</b>');
      exit();
    }

$bin = substr($Extra, 0,6);
$type = type($bin);
$bank = bank($bin); 
$brand = brannd($bin); 
$scheme = level($bin);
$country = country($bin);
$emoji = Emoji($bin);

$messageidtoedit1 = bot('sendMessage', [
  'chat_id' =>$chatId,
'reply_to_message_id'=>$message_id,
'parse_mode'=>'HTML',
  'text' =>"<b><b>Searching..... 
Bin Extra :<code> $Extra </code> </b></b>"
]);
$messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
sendaction($chatId, typing);
$extrass = Exyrad($Extra);
if(empty ($extrass)){
  bot('editMessageText',[
  'chat_id'=>$chatId,
  'message_id'=>$messageidtoedit,
  'text'=>"<b>No tenemos esa extra en nuestra Base de dato</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
  exit();
} 
$text3 = "- [ BIN INFORMATION ♻️ ]
-Country: $country $emoji
-Info: $type - $brand - $level 
-Bank: $bank
——————————————————
-Extra CCS 
$extrass ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://example.invalid/legacy');
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'authority: edit.telegra.ph';
$headers[] = 'accept: application/json, text/javascript, */*; q=0.01';
$headers[] = 'content-type: multipart/form-data; boundary=---------------------------TelegraPhBoundary21';
$headers[] = 'origin: https://example.invalid/legacy';
$headers[] = 'referer: https://example.invalid/legacy';
$headers[] = 'sec-ch-ua: ".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"';
$headers[] = 'sec-ch-ua-mobile: ?0';
$headers[] = 'sec-ch-ua-platform: "Windows"';
$headers[] = 'sec-fetch-dest: empty';
$headers[] = 'sec-fetch-mode: cors';
$headers[] = 'sec-fetch-site: same-site';
$headers[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, '-----------------------------TelegraPhBoundary21
Content-Disposition: form-data; name="Data";filename="content.html"
Content-type: plain/text

[{"tag":"p","attrs":{"dir":"auto"},"children":["'.$text3.'"]}]
-----------------------------TelegraPhBoundary21
Content-Disposition: form-data; name="title"

Extras Rita
-----------------------------TelegraPhBoundary21
Content-Disposition: form-data; name="author"

@MLIOCH_BOT
-----------------------------TelegraPhBoundary21
Content-Disposition: form-data; name="author_url"


-----------------------------TelegraPhBoundary21
Content-Disposition: form-data; name="save_hash"


-----------------------------TelegraPhBoundary21
Content-Disposition: form-data; name="page_id"

0
-----------------------------TelegraPhBoundary21--');
$curl = curl_exec($ch);
$binna = json_decode($curl,true);
$link5 = $binna['path'];
curl_close($ch);

bot('editMessageText',[
  'chat_id'=>$chatId,
  'disable_web_page_preview' => true,
  'message_id'=>$messageidtoedit,
  'text'=>"<b>- [ BIN INFORMATION ♻️ ]
-Country: <code>$country</code> $emoji
-Info: <code>$type</code> - <code>$brand</code> - <code>$scheme</code>
-Bank: <code>$bank</code>
——————————————————
<a href='https://example.invalid/legacy'>Click to see the Extras</a>
——————————————————</b>",
'reply_markup'=> json_encode(['inline_keyboard'=>[
    [['text'=>"Rita Info!",'url'=>"https://example.invalid/legacy"],
    ['text'=>"Rita Referencias!",'url'=>"https://example.invalid/legacy"]],
    ],'resize_keyboard'=>true]),
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
        }

    
      

      ?>