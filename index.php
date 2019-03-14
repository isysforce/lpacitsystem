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
        $content_url = rawurldecode($elements[0]);
        // echo rawurldecode($content_url);
        ShowContent($content_url);
        break;
    case 'tag':
        $tag_url = $elements[0];
        ShowTag($tag_url);
        break;
    case 'tags':
        ShowAllTag();
        break;
    case 'search':
        $search_str = rawurldecode($elements[0]);
        Search($search_str);
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        Show404Error();
}

function ShowAllTag(){
    $view = new Template();
    $view->title = "หมวดหมู่ทั้งหมด";
    $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
    $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";

    $view->properties["tag_name"] = "หมวดหมู่ทั้งหมด";

    $view->properties['tags'] = getTags();
    $view->properties['content_features'] = getContentFeatures(100);
    echo $view->render('tag_content.html');
    return;
}

function ShowTag($tag_url){
    include "controller/connection.php";
    $sql = "SELECT *
            FROM tags
            WHERE tag_url='$tag_url'";
    
    if ($result = $conn->query($sql)){
        $conn->close();
        $row = $result->fetch_assoc();
        $result->free();
        if ($row){
            $view = new Template();
            $view->title = "หมวดหมู่" . $row["tag_name"];
            $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
            $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";

            $view->properties["tag_name"] = "หมวดหมู่ " . $row["tag_name"];
        
            $view->properties['tags'] = getTags();
            $view->properties['content_features'] = getContentFeaturesByTagId($row['id']);
            echo $view->render('tag_content.html');
            return;
        }
        
    }
    Show404Error(); 
}

function Search($search_str){
    include "controller/connection.php";
    $sql = "SELECT *
            FROM contents
            WHERE (
                title LIKE '%$search_str%'
                OR
                body LIKE '%$search_str%'
                )";
    $view = new Template();
    $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
    $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";
    $view->properties['tags'] = getTags();

    $content = "";
    if ($result = $conn->query($sql)){
        $conn->close();

        while($row = $result->fetch_assoc()) {
            $comment_count = getCommentsCount($row["id"]);
            $tag = getTag($row["tag"]);
            $content .=
            '<h3 class="ui header">'.$row['title'].
                '<div class="sub header">
                    <div class="ui breadcrumb">
                        <div class="section">หมวดหมู่ <a href="'.SITE_HOST.'/tag/'.$tag["tag_url"].'">'.$tag["tag_name"].'</a></div>
                        <div class="divider"> | </div>'.
                        date("Y-m-d H:i", strtotime($row['creation']))
                        .'<div class="divider"> | </div>
                        <div class="section">'.$comment_count.' ความคิดเห็น</div>
                    </div>
                </div>
            </h3>';
            $content .= '<p>'.mb_substr($row['body'], 0, 450, 'utf-8').'...</i></p>';
            $content .= '<a class="ui black right labeled icon button" href="'.SITE_HOST.'/content/'.$row['content_url'].'"><i class="right arrow icon"></i>อ่านต่อ</a>';
            $content .= '<div class="ui section divider"></div>';
        }
        
    }
    if ($content === ""){
        $content = '<div class="ui message">
        <div class="header">
          ไม่พบเนื้อหา
        </div>
      </div>';
    }
        
        

    $view->title = "ผลการค้นหา";
    $view->properties["tag_name"] = "ผลการค้นหา " . $search_str;

    $view->properties['content_features'] = $content;
    echo $view->render('tag_content.html');
}


function ShowContent($content_url)
{
    include "controller/connection.php";
    $exp = "/((?:<\\/?\\w+)(?:\\s+\\w+(?:\\s*=\\s*(?:\\\".*?\\\"|'.*?'|[^'\\\">\\s]+)?)+\\s*|\\s*)\\/?>)([^<]*)?/";
    $ex1 = "/^([^<>]*)(<?)/i";
    $ex2 = "/(>)([^<>]*)$/i";
    
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
            $content_body = $row["body"];
            // $content_body = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content_body);
            
            $content_body = nl2br($content_body);
            $content_body = preg_replace_callback($exp, function ($matches) {
                return $matches[1] . str_replace(" ", "&nbsp;", $matches[2]);
            }, $content_body);
            $content_body = preg_replace_callback($ex1, function ($matches) {
                return str_replace(" ", "&nbsp;", $matches[1]) . $matches[2];
            }, $content_body);
            $content_body = preg_replace_callback($ex2, function ($matches) {
                return $matches[1] . str_replace(" ", "&nbsp;", $matches[2]);
            }, $content_body);
            
            $view->properties['body'] = $content_body;
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
    header('HTTP/1.1 404 Not Found');
    $view = new Template();
    $view->title = "ไม่พบหน้านี้";
    $view->properties['tags'] = getTags();
    $view->properties['site_name'] = "Lampang's Arts & Culture IT System";
    $view->properties['site_name_th'] = "ระบบสารสนเทศความรู้ทางศิลปวัฒนธรรมท้องถิ่นจังหวัดลำปาง";
    echo $view->render('404.html');
}

?>