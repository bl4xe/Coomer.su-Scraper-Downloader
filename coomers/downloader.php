<?php
error_reporting(E_ALL);
ini_set("display_errors", "on");
ini_set("user_agent", $_SERVER["HTTP_USER_AGENT"]);
set_time_limit(0);
ob_implicit_flush(true);
require "../inc/simple_html_dom.php";
require "../inc/func.php";

/*
$u = "https://$domain/onlyfans/user/$user?o=50";
    $c = get($u);
    echo '<pre>';
    print_r($c);
    exit;
*/
$domain = "coomer.su";

echo "<h1>$domain Scraper</h1>";

$users = file("coomer_users.txt");
foreach ($users as $user) {
    $media = file("coomer_media.txt");
    echo "<h2>$user</h2>";
    myobflush();
    $i = 1;
    foreach ($media as $item) {
        $item = trim($item);
        $data = explode("|", $item);
        $post_user = $data[0];
        $post_url = $data[1];
        $post_url_parts = explode("/", $post_url);
        $post_id = end($post_url_parts);
        $post_tite = $data[2];
        $media_link = $data[3];
        $media_file = $data[4];
        $media_type = $data[5];
        $media_file_name = "$post_user-$post_id-$i-$media_file";
        $media_dir = "media/$post_user/";
        @mkdir($media_dir, 0777, true);
        $media_path = "$media_dir/$media_file_name";
        if (!file_exists($media_path) or filesize($media_path) < 1024 * 80) {
            $data = get($media_link);
            $fp = fopen($media_path, "w");
            fwrite($fp, $data["content"]);
            fclose($fp);
        }
        if ($post_id == $last_post_id) {
            $i++;
        } else {
            $i = 1;
        }
        $last_post_id = $post_id;
    }
}
