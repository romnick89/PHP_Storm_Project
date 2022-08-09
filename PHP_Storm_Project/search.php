<?php
header("Content-Type: application/json");
session_start();
require_once('Models/PostDataSet.php');
require_once ('Models/TopicDataSet.php');

$topicDataSet = new TopicDataSet();

$postDataSet = new PostDataSet();
//new search method
$query = $_REQUEST["q"];
$hint = "";
if ($query != "") {
    $query = strtolower($query);
    $len = strlen($query);

    $postTitles = $postDataSet->searchPosts($_SESSION['topicID'], $query);
    foreach ($postTitles as $post) {
        $title = $post['post_subject'];
        if (stristr($query, substr($title,0, $len))) {
            //$searchedPost = $postDataSet->searchPost($_SESSION['topicID'], $query);
            //echo json_encode($post);
            if ($hint === "") {
                $hint = "[" . json_encode($post);
            }
            else {
                $hint .= "," . json_encode($post);
            }
        }
    }
    if ($hint != "") $hint .= "]";
}
echo $hint =="" ? "no posts" : $hint;

