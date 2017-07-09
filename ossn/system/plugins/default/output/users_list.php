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
$users = $params['users'];
if (!isset($params['icon_size'])) {
    $avatar_size = 'large';
} else {
    $avatar_size = $params['icon_size'];
}
$sizes = array('large', 'larger', 'small', 'topbar');
foreach ($users as $user) {
	if(isset($avatar_size) && in_array($avatar_size, $sizes)){
		$icon = $user->iconURL()->$avatar_size;
	}	 else {
		$icon = $user->iconURL()->small;
	}
    ?>

    <div class="ossn-list-users">
        <img src="<?php echo $icon; ?>"/>

        <div class="uinfo">
            <a class="userlink"
               href="<?php echo ossn_site_url(); ?>u/<?php echo $user->username; ?>"><?php echo $user->fullname; ?></a>
        </div>
        <?php if (ossn_isLoggedIn()) { ?>
            <?php if (ossn_loggedin_user()->guid !== $user->guid) {
                if (!ossn_user_is_friend(ossn_loggedin_user()->guid, $user->guid)) {
                    if (ossn_user()->requestExists(ossn_loggedin_user()->guid, $user->guid)) {
                        ?>
          <a href="<?php echo ossn_site_url("action/friend/remove?cancel=true&user={$user->guid}", true); ?>"
                               class='button-grey friendlink'>
                                <?php echo ossn_print('cancel:request'); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo ossn_site_url("action/friend/add?user={$user->guid}", true); ?>"
                               class='button-grey friendlink'>
                                <?php echo ossn_print('add:friend'); ?>
                            </a>
                        <?php
                        }
                    } else {
                        ?>
                        <a href="<?php echo ossn_site_url("action/friend/remove?user={$user->guid}", true); ?>"
                           class='button-grey friendlink'>
                            <?php echo ossn_print('remove:friend'); ?>
                        </a>
                <?php
                }

            }
        }?>
    </div>


<?php } ?>