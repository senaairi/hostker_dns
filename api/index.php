<?php
//$source_ip = ''; // 后端地址
//$source_port = '443';
$source_host = 'i.hostker.com'; // 后端请求主机名
$source_https = true; // 是否使用https

$curl = curl_init();

// 后端请求地址
$request_url = $source_https ? 'https://' : 'http://';
$request_url .= $source_host.$_SERVER['REQUEST_URI'];

curl_setopt($curl, CURLOPT_URL, $request_url);
// 启用时会将头文件的信息作为数据流输出。
curl_setopt($curl, CURLOPT_HEADER, true);
// true 将curl_exec()获取的信息以字符串返回，而不是直接输出。
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// true 时将会根据服务器返回 HTTP 头中的 "Location: " 重定向。（注意：这是递归的，"Location: " 发送几次就重定向几次，除非设置了 CURLOPT_MAXREDIRS，限制最大重定向次数。）。
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
// false 禁止 cURL 验证对等证书（peer's certificate）。要验证的交换证书可以在 CURLOPT_CAINFO 选项中设置，或在 CURLOPT_CAPATH中设置证书目录。
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// 设置为 1 是检查服务器SSL证书中是否存在一个公用名(common name)。译者注：公用名(Common Name)一般来讲就是填写你将要申请SSL证书的域名 (domain)或子域名(sub domain)。
// 设置成 2，会检查公用名是否存在，并且是否与提供的主机名匹配。
// 0 为不检查名称。 在生产环境中，这个值应该是 2（默认值）。
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// 允许 cURL 函数执行的最长秒数。
curl_setopt($curl, CURLOPT_TIMEOUT, 60);
//curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0); // true 会通过指定的 HTTP 代理来传输。
//curl_setopt($curl,CURLOPT_PROXY,$source_ip.':'.$source_port); // 设置后端的ip和端口。
if(!empty($_SERVER['HTTP_REFERER'])) curl_setopt($curl,CURLOPT_REFERER, $_SERVER['HTTP_REFERER']) ;

// 插入客户端传递的header
$headers = get_client_header();
curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);

// 处理post参数
if(!empty($_POST)) {
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($_POST));
}

// 处理cookie
$ClientCookie="";
foreach ($_COOKIE as $i => $value)
    $ClientCookie .= urlencode($i).' ='.urlencode($_COOKIE[$i]).';';
curl_setopt($curl, CURLOPT_COOKIE, $ClientCookie);

// 执行请求
$HttpResponse = curl_exec($curl);
$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE); // 取得头大小
$Header = substr($HttpResponse, 0, $headerSize);
$Response = substr($HttpResponse, $headerSize);

$Header = explode("\r\n", $Header);

// 输出header
foreach($Header as $h) {
    if(!empty($h)) {
        $h = trim($h);
        if(strpos($h,'Content-Length')!==false) continue;
        if(strpos($h,'Transfer-Encoding')!==false) continue;
        if(strpos($h,'Connection')!==false) continue;
        if(strpos($h,'HTTP/1.1 100 Continue')!==false) continue;
        if(strpos($h,'Set-Cookie')!==false) { // cookie要特殊处理
            header($h, false);
            continue;
        }
        header($h);
    }
}

// 跨域问题
$host_port = parse_url ($_SERVER['HTTP_REFERER'], PHP_URL_PORT);
$acao = 'Access-Control-Allow-Origin: '.parse_url($_SERVER['HTTP_REFERER'], PHP_URL_SCHEME).'://'
    .parse_url ($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
if(!empty($host_port) && $host_port != 80 && $host_port != 443)
    $acao .= ':'.$host_port;
header($acao);

curl_close($curl);
echo $Response;

function get_client_header(){
    $headers=array();
    foreach($_SERVER as $k=>$v){
        if(strpos($k,'HTTP_')===0){
            $k=strtolower(preg_replace('/^HTTP/', '', $k));
            $k=preg_replace_callback('/_\w/','header_callback', $k);
            $k=preg_replace('/^_/','',$k);
            $k=str_replace('_','-',$k);
            if($k=='Host') continue;
            $headers[]="$k:$v";
        }
    }
    return $headers;
}
function header_callback($str){
    return strtoupper($str[0]);
}
?>