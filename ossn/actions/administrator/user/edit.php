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
$entity = ossn_user_by_username(input('username'));
if(!$entity){
	redirect(REF);
}
$user['firstname'] = input('firstname');
$user['lastname'] = input('lastname');
$user['email'] = input('email');
$user['type'] = input('type');
$user['username'] = input('username');

$fields = ossn_user_fields_names();
foreach($fields['required'] as $field){
	$user[$field] = input($field);
}

if (!empty($user)) {
    foreach ($user as $field => $value) {
        if (empty($value)) {
            ossn_trigger_message(ossn_print('fields:require'), 'error');
            redirect(REF);
        }
    }
}
$password = input('password');

$types = array(
    'normal',
    'admin'
);
if (!in_array($user['type'], $types)) {
    ossn_trigger_message(ossn_print('account:create:error:admin'), 'error');
    redirect(REF);
}

$OssnUser = new OssnUser;
$OssnUser->password = $password;
$OssnUser->email = $user['email'];

$OssnDatabase = new OssnDatabase;


$params['table'] = 'ossn_users';
$params['wheres'] = array("guid='{$entity->guid}'");

$params['names'] = array(
    'first_name',
    'last_name',
    'email',
    'type'
);
$params['values'] = array(
    $user['firstname'],
    $user['lastname'],
    $user['email'],
    $user['type']
);
//check if email is not in user
if($entity->email !== input('email')){
  if($OssnUser->isOssnEmail()){
    ossn_trigger_message(ossn_print('email:inuse'), 'error');
    redirect(REF);
  }
}
//check if email is valid email 
if(!$OssnUser->isEmail()){
    ossn_trigger_message(ossn_print('email:invalid'), 'error');
    redirect(REF);	
}
//check if password then change password
if (!empty($password)) {
    if (!$OssnUser->isPassword()) {
        ossn_trigger_message(ossn_print('password:error'), 'error');
        redirect(REF);
    }
    $salt = $OssnUser->generateSalt();
    $password = $OssnUser->generate_password($password, $salt);
    $params['names'] = array(
        'first_name',
        'last_name',
        'email',
        'type',
        'password',
        'salt'
    );
    $params['values'] = array(
        $user['firstname'],
        $user['lastname'],
        $user['email'],
        $user['type'],
        $password,
        $salt
    );
}

//save
if ($OssnDatabase->update($params)) {
    //update entities
    $guid = $entity->guid;
    if (!empty($guid)) {
        $entity->owner_guid = $guid;
        $entity->type = 'user';
		
		$entity->data = new stdClass;
		foreach($fields as $items){
				foreach($items as $field){
						$entity->data->{$field} = $user[$field];
				}
		}		
        $entity->save();
    }
    ossn_trigger_message(ossn_print('user:updated'), 'success');
    redirect(REF);
} 
