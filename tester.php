<?php
error_reporting(0);

$url = "https://raw.githubusercontent.com/maliaside/xymalfilepxy/refs/heads/main/ID.txt";
$data = file_get_contents($url);
$proxies = explode("\n", $data);
$proxies = array_filter(array_map('trim', $proxies));
$randomProxy = $proxies[array_rand($proxies)];

ulangRegist:
$getUsn = generateRandomString(12);
$fakeIp = fakeIP();
$devId = generateDid19();
$webId = web_id();

$proxy = "$randomProxy";

echo "[ * ] Auto With Proxy [ * ]\n";
echo ">>> Menggunakan Proxy: $randomProxy\n";
echo "==========================\n";

$data = '{"name":"'.$getUsn.'","domain":""}';
    $lenght = strlen($data);
    $headers = [
        "Host: api.internal.temp-mail.io",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: json",
        "X-Forwarded-For: $fakeIp",
        "X-Real-IP: $fakeIp",
        "Client-IP: $fakeIp"
        
    ];
    
    $getEmail = curl("https://api.internal.temp-mail.io/api/v3/email/new", $data, $headers, $proxy);
    $email = get_between($getEmail[1], '"email":"', '","');
    if ($email) {
    echo "[ + ] Success get email ( $email )\n";
    
    } else {
    echo "[ ! ] Gagal mendapatkan email..\n";
    goto ulangRegist;
    
    }
      
     ulangOtp:
    $data = '{"smsType":2,"mobilePhone":"'.$email.'"}';
    $lenght = strlen($data);
    $headers = [
        "Host: api.owlproxy.com",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: json"
        
    ];
    
    $sendOtp = curl("https://api.owlproxy.com/owlproxy/api/sms/smsSend", $data, $headers, $proxy);
    $sendMsg = get_between($sendOtp[1], '"msg":"', '","');
    if ($sendMsg == 'success') {
    echo "[ - ] Sent OTP success..\n";
    
    } else {
    echo "[ ! ] Gagal kirim OTP, kirim ulang!\n";
    goto ulangOtp;
    
    }
    
    for ($i = 1; $i <= 10; $i++) {
    $data = '';
    $lenght = strlen($data);
    $headers = [
        "Host: api.internal.temp-mail.io",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: json",
        "X-Forwarded-For: $fakeIp",
        "X-Real-IP: $fakeIp",
        "Client-IP: $fakeIp"
        
    ];
    
$getCode = curl("https://api.internal.temp-mail.io/api/v3/email/$email/messages", $data, $headers, $proxy);

// Decode JSON
$json = json_decode($getCode[1], true);

$otp = null;

// Loop semua pesan (jika lebih dari 1)
foreach ($json as $msg) {

    // Cek body_text
    if (!empty($msg['body_text'])) {
        $body = $msg['body_text'];

        // Cari OTP 5–6 digit setelah kata "Verification Code"
        if (preg_match('/Verification Code:\s*\n*(\d{5,6})/i', $body, $match)) {
            $otp = $match[1];
            break;
        }

        // Alternatif: cari kode 6 digit di mana saja
        if (preg_match('/\b\d{6}\b/', $body, $match)) {
            $otp = $match[0];
            break;
        }
    }
}

if ($otp) {
    echo "[ + ] OTP: $otp\n";
    goto skip;
    
} else {
    echo "[ ? ] OTP: ???\n";
}
  }
  echo "[ ! ] OTP tidak ada, regist ulang..\n\n";
  goto ulangRegist;
  
    skip:
    TryRegist:
    $data = '{"mobilePhone":"'.$email.'","loginType":0,"verifyCode":"'.$otp.'","channel":"web","password":"3d6dad44bfe36b932a3fd649480a9c99"}';
    $lenght = strlen($data);
    $headers = [
        "Host: api.owlproxy.com",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "appVersion: 2002703",
        "userId: 0",
        "clientType: web",
        "channel: web",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: json"
        
    ];
    
    $getLogin = curl("https://api.owlproxy.com/owlproxy/api/user/login", $data, $headers, $proxy);
    $loginMsg = get_between($getLogin[1], '"token":"', '","');
    $userId = get_between($getLogin[1], '"userId":', ',"');
    if ($loginMsg) {
    echo "[ - ] Register account success..\n";
    
    } else {
    echo "[ ! ] Gagal mendaftarkan akun, try again..\n";
    goto ulangRegist;
    
    }
    
    ulangConfig:
    $data = '';
    $lenght = strlen($data);
    $headers = [
    "Host: api.owlproxy.com",
    "Connection: keep-alive",
    "SupplierType: 0",
    "sec-ch-ua-platform: \"Android\"",
    "requestsource: wechat-miniapp",
    "Accept-Language: en",
    "sec-ch-ua: \"Not:A-Brand\";v=\"99\", \"Android WebView\";v=\"145\", \"Chromium\";v=\"145\"",
    "sec-ch-ua-mobile: ?1",
    "appVersion: 2002703",
    "User-Agent: Mozilla/5.0 (Linux; Android 12; M2010J19SG Build/SKQ1.211202.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.79 Mobile Safari/537.36",
    "Accept: application/json, text/plain, */*",
    "userId: $userId",
    "Content-Type: application/json",
    "clientType: web",
    "Token: $loginMsg",
    "Origin: https://proxy.owlproxy.com",
    "X-Requested-With: mark.via.gp",
    "Sec-Fetch-Site: same-site",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Dest: empty",
    "Referer: https://proxy.owlproxy.com/",
    "Accept-Encoding: json"
        
    ];
    
    $claim2 = curl("https://api.owlproxy.com/owlproxy/api/configure/getCommonConfig", $data, $headers, $proxy);
    $msgClaim2 = get_between($claim2[1], '"msg":"', '","');
    if ($msgClaim2 == 'success') {
    	echo "[ + ] Set config success..\n";
    
    } else {
    echo "[ ! ] Set config error, try again..\n";
    goto ulangConfig;
    
    }
    
   
   $data = '';
    $lenght = strlen($data);
    $headers = [
    "Host: api.owlproxy.com",
    "Connection: keep-alive",
    "SupplierType: 0",
    "sec-ch-ua-platform: \"Android\"",
    "requestsource: wechat-miniapp",
    "Accept-Language: en",
    "sec-ch-ua: \"Not:A-Brand\";v=\"99\", \"Android WebView\";v=\"145\", \"Chromium\";v=\"145\"",
    "sec-ch-ua-mobile: ?1",
    "appVersion: 2002703",
    "User-Agent: Mozilla/5.0 (Linux; Android 12; M2010J19SG Build/SKQ1.211202.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.79 Mobile Safari/537.36",
    "Accept: application/json, text/plain, */*",
    "userId: $userId",
    "Content-Type: application/json",
    "clientType: web",
    "Token: $loginMsg",
    "Origin: https://proxy.owlproxy.com",
    "X-Requested-With: mark.via.gp",
    "Sec-Fetch-Site: same-site",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Dest: empty",
    "Referer: https://proxy.owlproxy.com/",
    "Accept-Encoding: json"
        
    ];
    
    $claim2 = curl("https://api.owlproxy.com/owlproxy/api/newUserGuide/getNewUserGuideConfig_V2", $data, $headers, $proxy);
    $msgClaim2 = get_between($claim2[1], '"showNewUserPop":', ',"');
    if ($msgClaim2 == true) {
    echo "[ - ] Free Proxy Ready, otw claim..\n";
    
    } else {
    echo "[ ! ] Free Proxy Belum Tersedia..\n";
    goto ulangConfig;
    }
    
    $data = '';
    $lenght = strlen($data);
    $headers = [
    "Host: api.owlproxy.com",
    "Connection: keep-alive",
    "SupplierType: 0",
    "sec-ch-ua-platform: \"Android\"",
    "requestsource: wechat-miniapp",
    "Accept-Language: en",
    "sec-ch-ua: \"Not:A-Brand\";v=\"99\", \"Android WebView\";v=\"145\", \"Chromium\";v=\"145\"",
    "sec-ch-ua-mobile: ?1",
    "appVersion: 2002703",
    "User-Agent: Mozilla/5.0 (Linux; Android 12; M2010J19SG Build/SKQ1.211202.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.79 Mobile Safari/537.36",
    "Accept: application/json, text/plain, */*",
    "userId: $userId",
    "Content-Type: application/json",
    "clientType: web",
    "Token: $loginMsg",
    "Origin: https://proxy.owlproxy.com",
    "X-Requested-With: mark.via.gp",
    "Sec-Fetch-Site: same-site",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Dest: empty",
    "Referer: https://proxy.owlproxy.com/",
    "Accept-Encoding: json"
        
    ];
    $claim = curl("https://api.owlproxy.com/owlproxy/api/newUserGuide/getNewUserReceiveTraffic?guideId=10003", $data, $headers, $proxy);
    $msgClaim = get_between($claim[1], '"msg":"', '","');
    if ($msgClaim == 'success') {
    echo "[ + ] Claim proxy success..\n";
    
    } else {
    echo "[ ! ] Claim proxy gagal, try again..\n";
    goto ulangConfig;
    }
    
    createUlang:
    echo "[ + ] Create Proxy..\n";
    $data = '{"proxyType":"https","proxyHost":"change5.owlproxy.com:7778","countryCode":"ID","state":"","city":"","time":0,"goodNum":1,"format":"user:pass:ip:port"}';
    $lenght = strlen($data);
    $headers = [
    "Host: api.owlproxy.com",
    "Connection: keep-alive",
    "SupplierType: 0",
    "sec-ch-ua-platform: \"Android\"",
    "requestsource: wechat-miniapp",
    "Accept-Language: en",
    "sec-ch-ua: \"Not:A-Brand\";v=\"99\", \"Android WebView\";v=\"145\", \"Chromium\";v=\"145\"",
    "sec-ch-ua-mobile: ?1",
    "appVersion: 2002703",
    "User-Agent: Mozilla/5.0 (Linux; Android 12; M2010J19SG Build/SKQ1.211202.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.79 Mobile Safari/537.36",
    "Accept: application/json, text/plain, */*",
    "userId: $userId",
    "Content-Type: application/json",
    "clientType: web",
    "Token: $loginMsg",
    "Origin: https://proxy.owlproxy.com",
    "X-Requested-With: mark.via.gp",
    "Sec-Fetch-Site: same-site",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Dest: empty",
    "Referer: https://proxy.owlproxy.com/",
    "Accept-Encoding: json"
        
    ];
    
$getProxy = curl("https://api.owlproxy.com/owlproxy/api/vcDynamicGood/createProxy", $data, $headers, $proxy);
$uName = get_between($getProxy[1], '"userName":"', '","');
$pass  = get_between($getProxy[1], '"password":"', '","');
if ($uName && $pass) {
	
    echo "=========================\n";
    echo "[ + ] Create proxy success..\n";
    echo "[ + ] Kuota: 200Mb\n";
    echo "[ + ] Detail: $uName:$pass@change5.owlproxy.com:7778\n";
    echo "=========================\n";

    
} else {

    echo "[ ! ] Create Proxy Gagal..\n";
    goto createUlang;

}


$proxiy = "http://$uName:$pass@change5.owlproxy.com:7778";
$token = getenv("GITHUB_TOKEN");
$repo  = "maliaside/xymalfilepxy";
$file  = "TH.txt";
$url   = "https://api.github.com/repos/$repo/contents/$file";

/* ambil isi file */
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: token $token",
    "User-Agent: php-script",
    "Accept: application/vnd.github+json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
curl_close($ch);

$response = json_decode($res, true);

$sha = $response['sha'] ?? null;
$content = isset($response['content']) ? base64_decode($response['content']) : '';

/* buang enter kosong di akhir */
$content = rtrim($content, "\r\n");

/* append tanpa bikin baris kosong */
$newContent = $content === '' ? $proxiy : $content . "\n" . $proxiy;

/* update file */
$data = [
    "message" => "Add new proxy",
    "content" => base64_encode($newContent),
    "sha" => $sha
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: token $token",
    "User-Agent: php-script",
    "Content-Type: application/json",
    "Accept: application/vnd.github+json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

echo "[✓] Simpan ke TXT Github [✓]\n";
echo "$result\n\n";
goto ulangRegist;

 
 function generateDid19(): string {
    // digit pertama 1–9 (tidak boleh 0)
    $did = strval(random_int(1, 9));

    // 18 digit sisanya 0–9
    for ($i = 0; $i < 18; $i++) {
        $did .= strval(random_int(0, 9));
    }

    return $did;
}
    
    function fakeIP() {
    return rand(0,255) . "." . rand(0,255) . "." . rand(0,255) . "." . rand(0,255);
}  
    
    function input($text) {
    echo $text.": ";
    $a = trim(fgets(STDIN));
    return $a;
}

function getName() {
    $r = file_get_contents('https://www.random-name-generator.com/indonesia?gender=&n=1&s='.rand(111,999));
    $namenya = get_between($r,'<div class="col-sm-12 mb-3" id="','-');
    $nama_indo = preg_replace('/s+/', '', $namenya);
    return ucfirst($nama_indo);
}

function get_between($string, $start, $end) 
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }


function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function nama() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.namefake.com/indonesian-indonesia");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	return $ex;
}

function curl($url, $post = 0, $httpheader = 0, $proxy = 0){ // url, postdata, http headers, proxy, uagent
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if($post){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($httpheader){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        }
        if($proxy){        	
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        if(!$httpcode) return "Curl Error : ".curl_error($ch); else{
            $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            curl_close($ch);
            return array($header, $body);
        }
    }
    
 function decodeMixedEmail(string $hex, int $key = 0x05): string
{
    if (strlen($hex) % 2 !== 0 || !ctype_xdigit($hex)) {
        return '';
    }

    $bin = hex2bin($hex);
    if ($bin === false) return '';

    $out = '';
    for ($i = 0, $n = strlen($bin); $i < $n; $i++) {
        $out .= chr(ord($bin[$i]) ^ $key);
    }
    return $out;
}

function encodeEmail(string $enc): string
{
    $key = 0x05;
    $out = '';

    for ($i = 0, $n = strlen($enc); $i < $n; $i++) {
        $out .= chr(ord($enc[$i]) ^ $key);
    }

    return bin2hex($out);
}

/**
 * Generate web_id 19 digit (string), contoh: 7608543821757072914
 * PHP 7+ (random_int)
 */
function web_id(): string
{
    // digit pertama 1..9 supaya tidak diawali 0
    $first = (string) random_int(1, 9);

    // 18 digit sisanya, dipad kiri dengan nol jika perlu
    $rest  = str_pad((string) random_int(0, 999999999999999999), 18, '0', STR_PAD_LEFT);

    return $first . $rest; // total 19 digit
}
