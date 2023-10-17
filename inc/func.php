<?php

function myobflush()
{
    echo str_repeat(' ',1024*64);
    usleep(500);
}


function addline($file, $line)
{
    if(!file_exists($file))
    {
        file_put_contents($file,'');
    }

    $lines = file($file);
    if (!in_array($line, $lines)) {
        file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

function cookies2str($cookies_file)
{
    $lines = file($cookies_file);
    foreach ($lines as $l) {
        $segs = explode("	", $l);
        $cookies[] = "$segs[5]=$segs[6]";
    }
   // print_r($cookies);
    return implode("; ", $cookies);
}

/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the HTTP server response header fields and content.
 */
function get($url)
{
    //$cstring = cookies2str("cookies.txt");
    $options = [
        CURLOPT_VERBOSE => true,
        CURLOPT_COOKIESESSION => true,
        CURLOPT_COOKIEJAR => "cookiejar.txt",
        CURLOPT_COOKIEFILE => "cookiefile.txt",
        // CURLOPT_COOKIE => $cstring,
        CURLOPT_REFERER => "http://coomer.us/",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*\/*;q=0.8",
            "Accept-Language: en-US,en;q=0.5",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests: 1",
        ],
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => true, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_ENCODING => "", // handle all encodings
        CURLOPT_USERAGENT => $_SERVER["HTTP_USER_AGENT"], // who am i
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
        CURLOPT_TIMEOUT => 120, // timeout on response
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false, // Disabled SSL Cert checks
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);

    $header["errno"] = $err;
    $header["errmsg"] = $errmsg;
    $header["content"] = $content;
    return $header;
}
