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

$photoid = input('id');

$delete = ossn_photos();
$delete->photoid = $photoid;

$photo = $delete->GetPhoto($delete->photoid);

$owner = ossn_albums();
$owner = $owner->GetAlbum($photo->owner_guid);

if (($owner->album->owner_guid == ossn_loggedin_user()->guid) || ossn_isAdminLoggedin()) {
    if ($delete->deleteAlbumPhoto()) {
        ossn_trigger_message(ossn_print('photo:deleted:success'), 'success');
        redirect("album/view/{$owner->album->guid}");
    } else {
        ossn_trigger_message(ossn_print('photo:delete:error'), 'error');
        redirect(REF);
    }
} else {
    ossn_trigger_message(ossn_print('photo:delete:error'), 'error');
    redirect(REF);
}
