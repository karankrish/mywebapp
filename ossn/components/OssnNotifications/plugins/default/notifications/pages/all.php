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
?>
    <h2><?php echo ossn_print('notifications'); ?></h2>
<?php
$get = new  OssnNotifications;
$notifications = $get->get(ossn_loggedin_user()->guid);
echo '<div class="ossn-notifications-all ossn-notification-page">';
if ($notifications) {
    foreach ($notifications as $not) {
        echo "{$not}";
    }
}
echo '</div>';
?>