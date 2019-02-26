<?php

include "config/site.php";
include "config/database.php";
include "view/template.php";
include "view/getTags.php";


$path = ltrim($_SERVER['REQUEST_URI'], '/');
$elements = explode('/', $path);
// print_r($elements);
array_shift($elements);//Remove local hostname
if(empty($elements[0]))
{
    ShowHomepage();
}
else switch(array_shift($elements))
{
    case 'content':
        // print_r($elements[0]);
        $content_url = $elements[0];
        ShowContent($content_url);
        break;
    case 'tag':
        $tag_url = $elements[0];
        ShowTag($tag_url);
        break;
    case 'tags':
        ShowAllTag();
        break;
    default:
        // echo $elements;
        header('HTTP/1.1 404 Not Found');
        Show404Error();
}

function ShowAllTag(){
    $view = new Template();
    $view->title = "Content title";
    $view->properties['name'] = "Jude";
    $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
    $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";

    $view->properties["tag_name"] = "ทั้งหมด";

    $view->properties['tags'] = getTags();
    $view->properties['content_features'] = getContentFeatures(100);
    echo $view->render('tag_content.html');
    return;
    // header('HTTP/1.1 404 Not Found');
    // Show404Error(); 
}

function ShowTag($tag_url){
    include "controller/connection.php";

    // echo "TAG: ".$tag_url[0] . "<br>";

    

    $sql = "SELECT *
            FROM tags
            WHERE tag_url='$tag_url'";
    
    if ($result = $conn->query($sql)){
        $conn->close();
        $row = $result->fetch_assoc();
        $result->free();
        if ($row){
            // echo $row["title"];
            $view = new Template();
            $view->title = "Content title";
            // $view->properties['name'] = "Jude";
            $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
            $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";

            $view->properties["tag_name"] = $row["tag_name"];
        
            $view->properties['tags'] = getTags();
            $view->properties['content_features'] = getContentFeaturesByTagId($row['id']);
            echo $view->render('tag_content.html');
            return;
        }
        
    }
    // $conn->close();
    header('HTTP/1.1 404 Not Found');
    Show404Error(); 
}

function ShowContent($content_url)
{
    include "controller/connection.php";
    
    $sql = "SELECT contents.id, title, body, tags.tag_name, tags.tag_url
            FROM contents
            INNER JOIN tags
            ON tag=tags.id
            WHERE content_url='$content_url'";
    
    if ($result = $conn->query($sql)){
        $conn->close();
        $row = $result->fetch_assoc();
        $result->free();
        if ($row){
            $view = new Template();
            $view->title = $row["title"];
            $view->properties['body'] = $row["body"];
            $view->properties['content_id'] = $row["id"];
            $view->properties['tag'] = $row["tag_name"];
            $view->properties['tag_url'] = $row["tag_url"];
            $view->properties['tags'] = getTags();
            $view->properties['comments'] = getComments($row["id"]);
            $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
        
            echo $view->render('content.html');
            return;
        }
        
    }
    header('HTTP/1.1 404 Not Found');
    Show404Error(); 
}

function ShowHomepage()
{
    $view = new Template();
    $view->title = "Lampang's Arts & Culture IT System";
    $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
    $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";

    $view->properties['tags'] = getTags();
    $view->properties['content_features'] = getContentFeatures();
    echo $view->render('index.html');
}

function Show404Error()
{
    $view = new Template();
    $view->title = "ไม่พบหน้านี้";
    $view->properties['tags'] = getTags();
    $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
    $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";
    echo $view->render('404.html');
}

?>