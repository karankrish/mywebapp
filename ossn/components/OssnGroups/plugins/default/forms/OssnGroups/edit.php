<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright 2014-2017 SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
$group = $params['group'];
?>
<label><?php echo ossn_print('group:name'); ?></label>
<input type="text" name="groupname" value="<?php echo $group->title; ?>"/>
<label><?php echo ossn_print('group:desc'); ?></label>

<textarea name="groupdesc"><?php echo trim($group->description); ?></textarea>
<br/>

<label><?php echo ossn_print('privacy'); ?></label>
<select name="membership">
    <?php
    if ($group->membership == OSSN_PUBLIC) {
        $open = 'selected';
        $close = '';
    } elseif ($group->membership == OSSN_PRIVATE) {
        $close = 'selected';
        $open = '';
    }
    ?>
    <option value='2' <?php echo $open; ?>> <?php echo ossn_print('public'); ?> </option>
    <option value='1' <?php echo $close; ?>> <?php echo ossn_print('close'); ?> </option>
</select>
<input type="hidden" name="group" value="<?php echo $group->guid; ?>"/>
<input type="submit" value="<?php echo ossn_print('save'); ?>" class="btn btn-success"/>
<?php
	echo ossn_plugin_view('output/url', array(
			'text' => ossn_print('delete'),
			'href' => ossn_site_url("action/group/delete?guid=$group->guid"),
			'class' => 'btn btn-danger delete-group',
			'action' => true,
	));