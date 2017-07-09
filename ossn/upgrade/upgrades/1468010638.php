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

ossn_generate_server_config('apache');
ossn_version_upgrade($upgrade, '4.2');

$database = new OssnDatabase;
$database->statement("ALTER TABLE `ossn_annotations`
	ADD KEY `owner_guid` (`owner_guid`),
	ADD KEY `subject_guid` (`subject_guid`),
	ADD KEY `time_created` (`time_created`);
  
ALTER TABLE `ossn_annotations` ADD FULLTEXT KEY `type` (`type`);

ALTER TABLE `ossn_entities`
	ADD KEY `owner_guid` (`owner_guid`),
	ADD KEY `time_created` (`time_created`),
	ADD KEY `time_updated` (`time_updated`),
	ADD KEY `active` (`active`),
	ADD KEY `permission` (`permission`);
  
ALTER TABLE `ossn_entities` 
	ADD FULLTEXT KEY `type` (`type`),
	ADD FULLTEXT KEY `subtype` (`subtype`);

ALTER TABLE `ossn_entities_metadata`
	ADD KEY `guid` (`guid`);
  
ALTER TABLE `ossn_entities_metadata` ADD FULLTEXT KEY `value` (`value`);

ALTER TABLE `ossn_notifications`
	ADD KEY `poster_guid` (`poster_guid`),
	ADD KEY `owner_guid` (`owner_guid`),
	ADD KEY `subject_guid` (`subject_guid`), 
	ADD KEY `time_created` (`time_created`),
	ADD KEY `item_guid` (`item_guid`);
ALTER TABLE `ossn_notifications` ADD FULLTEXT KEY `type` (`type`);

ALTER TABLE `ossn_object`
	ADD KEY `owner_guid` (`owner_guid`),
	ADD KEY `time_created` (`time_created`);
  
ALTER TABLE `ossn_object` 
	ADD FULLTEXT KEY `type` (`type`),
	ADD FULLTEXT KEY `subtype` (`subtype`);

ALTER TABLE `ossn_relationships`
	ADD KEY `relation_to` (`relation_to`),
	ADD KEY `relation_from` (`relation_from`),
	ADD KEY `time` (`time`);
ALTER TABLE `ossn_relationships` ADD FULLTEXT KEY `type` (`type`);

ALTER TABLE `ossn_users`
	ADD KEY `last_login` (`last_login`),
	ADD KEY `last_activity` (`last_activity`),
	ADD KEY `time_created` (`time_created`);
  
ALTER TABLE `ossn_users`
	ADD FULLTEXT KEY `type` (`type`),
	ADD FULLTEXT KEY `email` (`email`),
	ADD FULLTEXT KEY `first_name` (`first_name`),
	ADD FULLTEXT KEY `last_name` (`last_name`);");
$database->execute();

$factory = new OssnFactory(array(
		'callback' => 'installation',
		'website' => ossn_site_url(),
		'email' => ossn_site_settings('owner_email'),
		'version' => '4.2'
));
$factory->connect;
