<?php
function getTags(){
    include "controller/connection.php";
    $sql = "SELECT * FROM tags";
    $result = $conn->query($sql);
    $conn->close();
    $data = "";
    while($row = $result->fetch_assoc()) {
        $data .= '<a class="item" href="'.SITE_HOST.'/tag/' . $row["tag_url"] .  '">' . $row["tag_name"] . '</a>';
    }
    $result->free();
    return $data;
}

function getSiteInfo(){
    include "controller/connection.php";
    $sql = "SELECT * FROM site_info";
    $result = $conn->query($sql);
    $conn->close();

    // $row = $result->fetch_assoc();
    // $result->free();
    // $data = array("site_name"=>$row["site_name"], "site_name_en"=>$row["site_name_en"]);

    return $result->fetch_assoc();
}

function getTag($tag_id){
    include "controller/connection.php";
    $sql = "SELECT * FROM tags
            WHERE id='$tag_id'";

    
    $result = $conn->query($sql);
    $conn->close();
    
    $row = $result->fetch_assoc();
    
    $result->free();
    return $row;
}

function getContentFeatures($limit = 10){
    include "controller/connection.php";
    $sql = "SELECT * FROM contents
            ORDER BY creation ASC
            LIMIT $limit";

    $result = $conn->query($sql);
    $conn->close();
    $data = "";
    while($row = $result->fetch_assoc()) {
        // print_r($row);
        $comment_count = getCommentsCount($row["id"]);
        $tag = getTag($row["tag"]);
        $data .=
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
        $data .= '<p>'.mb_substr($row['body'], 0, 450, 'utf-8').'...</i></p>';
        $data .= '<a class="ui black right labeled icon button" href="'.SITE_HOST.'/content/'.$row['content_url'].'"><i class="right arrow icon"></i>อ่านต่อ</a>';
        $data .= '<div class="ui section divider"></div>';
    }
    $result->free();
    
    return $data;
}

function getContentFeaturesByTagId($tag_id){
    include "controller/connection.php";
    $sql = "SELECT *
            FROM contents
            WHERE tag='$tag_id'";

    $result = $conn->query($sql);
    $conn->close();
    $data = "";
    while($row = $result->fetch_assoc()) {
        // print_r($row);
        $comment_count = getCommentsCount($row["id"]);
        // $tag = getTag($row["tag_name"]);
        $data .=
        '<h3 class="ui header">'.$row['title'].
            '<div class="sub header">
                <div class="ui breadcrumb">
                    <div class="section">
                        <div class="section">'.$comment_count.' ความคิดเห็น</div>
                    </div>
                    <div class="divider"> | </div>'.
                    date("Y-m-d H:i", strtotime($row["creation"]))
                .'</div>
                
            </div>
        </h3>';
        $data .= '<p>'.mb_substr($row['body'], 0, 450, 'utf-8').'...</i></p>';
        $data .= '<a class="ui black right labeled icon button" href="'.SITE_HOST.'/content/'.$row['content_url'].'"><i class="right arrow icon"></i>อ่านต่อ</a>';
        $data .= '<div class="ui section divider"></div>';
    }
    $result->free();
    if ($data === ""){
        $data = '<h3 class="ui header">ไม่พบเนื้อหา</h3>';
    }
    return $data;
}

function getCommentsCount($content_id){
    include "controller/connection.php";
    $sql = "SELECT * FROM comments
            WHERE content_id='$content_id'";
            
    $result = $conn->query($sql);
    $conn->close();
    $count = $result->num_rows;
    $result->free();
    return $count;
}

function getComments($content_id, &$count = null){
    include "controller/connection.php";
    $sql = "SELECT * FROM comments
            WHERE content_id='$content_id'";
            
    $result = $conn->query($sql);
    $conn->close();
    $data = "";
    $count = $result->num_rows;
    if ($count === 0){
        $data = "ไม่มีความคิดเห็น";
    } else {
        while($row = $result->fetch_assoc()) {
            $data .= '<div class="item">';
    
            $data .= '<i class="comment outline icon"></i>';
            $data .= '<div class="content">';
            $data .= '<div class="header">'.$row["comment_body"].'</div>';
            $data .= '<div class="description">โดย<b> '.$row["comment_author"].'</b> เมื่อ '.date("Y-m-d H:i", strtotime($row["comment_date"])).'</div>';
            $data .= '</div></div>';
        }
    }
    $result->free();
    
    return $data;
}

?>