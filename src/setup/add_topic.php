<?php
die();
   define('IN_PHPBB', true);
   $phpbb_root_path = "./forum/";
   $phpEx = "php";

   include($phpbb_root_path . 'common.' . $phpEx);
   include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
   include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);

   $user->session_begin();
$user->data['username']='ManagerTeams [Bot]';
$user->data['user_id']= 223;
$user->data['user_colour']= '000000';
$user->ip= '91.148.145.91';
$auth->acl($user->data);
   $auth->acl($user->data);

   // note that multibyte support is enabled here
   $my_subject	= utf8_normalize_nfc(request_var('my_subject', '', true));
   $my_text	= utf8_normalize_nfc(request_var('my_text', '', true));

   // variables to hold the parameters for submit_post
   $poll = $uid = $bitfield = $options = '';

   generate_text_for_storage($my_subject, $uid, $bitfield, $options, false, false, false);
   generate_text_for_storage($my_text, $uid, $bitfield, $options, true, true, true);

   $data = array(
   'forum_id'		=> $_REQUEST['fid'],
   'icon_id'		=> false,

   'enable_bbcode'		=> true,
   'enable_smilies'	=> true,
   'enable_urls'		=> true,
   'enable_sig'		=> true,

   'message'		=> $my_text,
   'message_md5'	=> md5($my_text),

   'bbcode_bitfield'	=> $bitfield,
   'bbcode_uid'		=> $uid,

   'post_edit_locked'	=> 0,
   'topic_title'		=> $my_subject,
   'notify_set'		=> false,
   'notify'			=> false,
   'post_time' 		=> 0,
   'forum_name'		=> '',
   'enable_indexing'	=> true,
   );

   submit_post('post', $my_subject, 'NRPG', POST_NORMAL, $poll, $data);

   print($data['topic_id']);
   //print_r($data);
?>