<?php
/**
 * Created by PhpStorm.
 * User: edizduman
 * Date: 19.6.2015
 * Time: 16:25
 */


/* return result number */

function get_google_results($domain)
{
// get the result content
   $content = file_get_contents('http://www.google.com/search?q=site:'.$domain);

// parse to get results
    preg_match('/<div class="sd" id="resultStats">(.*?)<\/div>/s',$content,$result);

    $int = preg_replace('/[^0-9]+/', '', $result[1]);
    return $int;
}

function get_google_links($domain)
{
// get the result content
    $content = file_get_contents('http://www.google.com/search?q=link:'.$domain);

// parse to get results
    preg_match('/<div class="sd" id="resultStats">(.*?)<\/div>/s',$content,$result);
    $int = intval(preg_replace('/[^0-9]+/', '', $result[1]), 10);
    return $int;
}


echo "Google Index : ".get_google_results($_GET['q'])."</br>";
require("prClass.php"); // Class başka dosyada tutulacaksa.

$pr = new PR();
echo 'Google Pagerank : '.$pr->get_google_pagerank($_GET['q'])."</br>";

echo 'Google Backlink : '.get_google_links($_GET['q'])."</br>";

