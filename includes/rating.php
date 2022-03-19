<?php
// Load and initialize post class
require_once ("Post.class.php");
$post = new Post();

  if(!empty($_POST['id'])){
    
    // Get post data
    $conditions['where'] = array(
        'review_id' => $_POST['id']
    );
    $conditions['return_type'] = 'single';
    $postData = $post->getRows($conditions);
    
    // Post total likes
    $postLike = $postData['like_num'];
    
    // Post total dislikes
    $postDislike = $postData['dislike_num'];
    
    // Calculates the numbers of like or dislike
    if($_POST['type'] == 1){
        $like_num = ($postLike + 1);
        $upData = array(
            'like_num' => $like_num
        );
        $return_count = $like_num;
    }else{
        $dislike_num = ($postDislike + 1);
        $upData = array(
            'dislike_num' => $dislike_num
        );
        $return_count = $dislike_num;
    }
    
    // Update post like or dislike
    $condition = array('review_id' => $_POST['id']);
    $update = $post->update($upData, $condition);
    
    // Return like or dislike number if update is successful, otherwise return error
    echo $update?$return_count:'err';
}
?>