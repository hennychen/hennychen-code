<?php

class JSON_API_bp_Activity {
  
  var $id;      // Integer
  var $user_id;    // String
  var $component;     // String
  var $date;    // String
  var $content; // String
  var $parent;  // Integer
  var $author;  // Object (only if the user was registered & logged in)
  var $postid;  //postid
  
  function JSON_API_bp_Activity($wp_bp_activity = null) {
    if ($wp_bp_activity) {
      $this->import_wp_object($wp_bp_activity);
    }
  }
  
  function import_wp_object($wp_bp_activity) {
    global $json_api;
    
    $date_format = $json_api->query->date_format;
    $content = apply_filters('comment_text', $wp_bp_activity->comment_content);
    
    $this->id = (int) $wp_bp_activity->comment_ID;
    $this->name = $wp_bp_activity->comment_author;
    $this->url = $wp_bp_activity->comment_author_url;
    $this->date = date($date_format, strtotime($wp_bp_activity->comment_date));
    $this->content = $content;
    $this->parent = (int) $wp_bp_activity->comment_parent;
    //$this->raw = $wp_bp_activity;
    $this->postid = $wp_bp_activity->comment_post_ID;
    
    if (!empty($wp_bp_activity->user_id)) {
      $this->author = new JSON_API_Author($wp_bp_activity->user_id);
    } else {
      unset($this->author);
    }
  }
  
  function handle_submission() {
    global $comment, $wpdb;
    add_action('comment_id_not_found', array(&$this, 'comment_id_not_found'));
    add_action('comment_closed', array(&$this, 'comment_closed'));
    add_action('comment_on_draft', array(&$this, 'comment_on_draft'));
    add_filter('comment_post_redirect', array(&$this, 'comment_post_redirect'));
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['comment_post_ID'] = $_REQUEST['post_id'];
    $_POST['author'] = $_REQUEST['name'];
    $_POST['email'] = $_REQUEST['email'];
    $_POST['url'] = empty($_REQUEST['url']) ? '' : $_REQUEST['url'];
    $_POST['comment'] = $_REQUEST['content'];
    $_POST['parent'] = $_REQUEST['parent'];
    include ABSPATH . 'wp-comments-post.php';
  }
  
  function comment_id_not_found() {
    global $json_api;
    $json_api->error("Post ID '{$_REQUEST['post_id']}' not found.");
  }
  
  function comment_closed() {
    global $json_api;
    $json_api->error("Post is closed for comments.");
  }
  
  function comment_on_draft() {
    global $json_api;
    $json_api->error("You cannot comment on unpublished posts.");
  }
  
  function comment_post_redirect() {
    global $comment, $json_api;
    $status = ($comment->comment_approved) ? 'ok' : 'pending';
    $new_comment = new JSON_API_Comment($comment);
    $json_api->response->respond($new_comment, $status);
  }
  
}

?>
