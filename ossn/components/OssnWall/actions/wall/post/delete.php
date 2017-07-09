<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright 2014-2017 SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$ossnwall = new OssnWall;
$id = input('post');
$post = $ossnwall->GetPost($id);
if ($post->type == 'user' && !ossn_isAdminLoggedin()) {
    if ($post->poster_guid !== ossn_loggedin_user()->guid && $post->owner_guid !== ossn_loggedin_user()->guid) {
        if (!ossn_is_xhr()) {
            ossn_trigger_message(ossn_print('post:delete:fail'), 'error');
            redirect(REF);
        } else {
            echo 0;
            exit;
        }
    }
}
if ($post->type == 'group' && !ossn_isAdminLoggedin()) {
    $group = new OssnGroup;
    $group = $group->getGroup($post->owner_guid);
	//lastchange group admins are unable to delete member posting on group wall #171
	// change or operator to and
    if (($post->poster_guid !== ossn_loggedin_user()->guid) && (ossn_loggedin_user()->guid !== $group->owner_guid)) {
        if (!ossn_is_xhr()) {
            ossn_trigger_message(ossn_print('post:delete:fail'), 'error');
            redirect(REF);
        } else {
            echo 0;
            exit;
        }
    }
}
if ($ossnwall->deletePost($id)) {
    if (ossn_is_xhr()) {
        echo 1;
    } else {
        ossn_trigger_message(ossn_print('post:delete:success'), 'success');
        redirect(REF);
    }
} else {
    if (ossn_is_xhr()) {
        echo 0;
    } else {
        ossn_trigger_message(ossn_print('post:delete:fail'), 'error');
        redirect(REF);
    }
}
