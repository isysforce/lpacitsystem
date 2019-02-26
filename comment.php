<?php

$author = $_POST['author'];
$comment = $_POST['comment'];
$content_id = $_POST['content_id'];
$redirect = $_POST['redirect'];

if (empty($author) || empty($comment) || empty($content_id) || empty($redirect)){
    header("Location: /");
    die();
}

$author_l = mb_strlen($author, 'utf-8');
$comment_l = mb_strlen($comment, 'utf-8');

if ($author_l < 2 || $author_l > 30 || $comment_l < 2 || $comment_l > 510){
    header("Location: ". $redirect);
    die();
}

include "config/database.php";
include "controller/connection.php";

$sql = "INSERT INTO comments (content_id, comment_author, comment_body, comment_date)
        VALUES ($content_id, '$author', '$comment', NOW())";

if ($conn->query($sql) === TRUE) {
    // echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("Location: ". $redirect);
die();
?>