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
$domain='coomer.su';


echo "<h1>$domain Scraper</h1>";

$users=file('coomer_users.txt');
foreach($users as $user)
{
    $user=trim($user);
    echo "<h2>$user</h2>";
    myobflush();
    $i = 0;
    while ($i <= 100) {
        $offset = $i * 50;
        $page_url = "https://$domain/onlyfans/user/$user?o=$offset";
        echo "<h3>$page_url</h3>";
        myobflush();
        $page_results = get($page_url);
        $page_content = $page_results["content"];
        if (!empty($page_content) && $page_content != null && $page_content != false) 
        {
            $page_html = str_get_html($page_content);

            try {
                foreach ($page_html->find("article a") as $page_el) {
                    if (!empty($page_el->href)) {
                        if (strpos(" ".$page_el->href." ", "/$user/post/")) {
                            $post_url = "https://$domain" . $page_el->href;
                            $post_results = get($post_url);
                            $post_content = $post_results["content"];
                            if (!empty($post_content) && $post_content != null && $post_content != false) {
                                $post_html = str_get_html($post_content);
                                $post_title = str_replace(' | Coomer','',trim($post_html->find("title", 0)->innertext));
                                foreach($post_html->find("a[download]") as $post_el)
                                {                            
                                    $media_link = $post_el->href;
                                    $media_file = $post_el->download;
                                    if (strpos(" $media_link ", ".jpg")) {
                                        //echo "<img style='max-width:360px;max-height:180px;' src='$img'>";
                                        //myobflush();
                                        $media_type = "image";
                                    } else {
                                        //echo "<video width='360' height='180' controls><source src='$link'></source></video>";
                                        //myobflush();
                                        $media_type = "video";
                                    }

                                    $line = "$user|$post_url|$post_title|$media_link|$media_file|$media_type";
                                    echo "   $line<br>";
                                    addline("coomer_media.txt", $line);
                                    myobflush();
                                }
                            }
                        }
                    }

                    sleep(5);
                }
            } catch (Exception $e) {
                echo "Caught exception: ", $e->getMessage(), "\n";
            }
        }
        $i++;
        sleep(5);
    }
}
