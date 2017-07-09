/**
* Open Source Social Network
*
* @package   (softlab24.com).ossn
* @author    OSSN Core Team
<info@opensource-socialnetwork.org>
* @copyright 2014-2017 SOFTLAB24 LIMITED
* @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
* @link      https://www.opensource-socialnetwork.org/
*/
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
$new_all    = ossn_chat()->getNewAll(array(
		'message_from'
));
$allfriends = OssnChat::AllNew();

$active_sessions = OssnChat::GetActiveSessions();

$construct_active = NULL;
$new_messages     = NULL;

if($active_sessions) {
		foreach($active_sessions as $friend) {
				$messages = ossn_chat()->getNew($friend, ossn_loggedin_user()->guid);
				if($messages) {
						foreach($messages as $message) {
								if(ossn_loggedin_user()->guid == $message->message_from) {
										$vars['message'] = $message->message;
										$vars['time']    = $message->time;
										$vars['id']      = $message->id;
										$messageitem     = ossn_plugin_view('chat/message-item-send', $vars);
								} else {
										$vars['reciever'] = ossn_user_by_guid($message->message_from);
										$vars['message']  = $message->message;
										$vars['time']     = $message->time;
										$vars['id']       = $message->id;
										$messageitem      = ossn_plugin_view('chat/message-item-received', $vars);
								}
								$total          = get_object_vars($messages);
								$new_messages[] = array(
										'fid' => $friend,
										'id' => $message->id,
										'message' => $messageitem,
										'total' => count($total)
								);
						}
				}
				
				if(OssnChat::getChatUserStatus($friend, 10) == 'online') {
						$status = 'ossn-chat-icon-online';
				} else {
						$status = 'ossn-chat-icon-offline';
				}
				$construct_active[$friend] = array(
						'status' => $status
				);
		}
}
if($new_messages){
	foreach($new_messages as $item) {
		$messages_items[$item['fid']][] = array(
				'id' => $item['id'],
				'fid' => $item['fid'],
				'message' => $item['message'],
				'total' => $item['total']
		);
	}
}
if($messages_items){
	foreach($messages_items as $key => $mitem) {
		$messages_combined[] = array(
				'message' => $mitem,
				'total' => $mitem[0]['total'],
				'fid' => $key
		);
	}
}
$api = json_encode(array(
		'active_friends' => $construct_active,
		'allfriends' => $allfriends,
		'friends' => array(
				'online' => ossn_chat()->countOnlineFriends('', 10),
				'data' => ossn_plugin_view('chat/friendslist')
		),
		'newmessages' => $messages_combined,
		'all_new' => $new_all
		
));

echo 'var OssnChat = ';
echo preg_replace('/[ ]{2,}/', ' ', $api);
echo ';';
?>


/**
 * Count Online friends and put then in friends list
 *
 * @params OssnChat['friends'] Array
 */	
$friends_online = $('.ossn-chat-online-friends-count').find('span');
if(OssnChat['friends']['online'] > $friends_online.text() || OssnChat['friends']['online'] < $friends_online.text()){
   $('.friends-list').find('.data').html(OssnChat['friends']['data']);
}
$friends_online.html(OssnChat['friends']['online']);

/**
 * Reset the user status
 *
 * @params OssnChat['active_friends'] Array
 */	
if(OssnChat['active_friends']){
$.each(OssnChat['active_friends'], function(key, data){
               $('#ossnchat-ustatus-'+key).attr('class', data['status']);
});
}
/**
 * Add all friends in sidebar
 *
 * @params OssnChat['active_friends'] Array
 */	
if(OssnChat['allfriends']){
  $.each(OssnChat['allfriends'], function(key, data){
       var $item  = $(".ossn-chat-windows-long .inner").find('#friend-list-item-'+data['guid']);
       if($item.length){
          $item.find('.ustatus').removeClass('ossn-chat-icon-online');
          $item.find('.ustatus').addClass(data['status']);
        } 
        if($item.length == 0){
         var appendata = '<div class="friends-list-item" id="friend-list-item-'+data['guid']+'" onClick="Ossn.ChatnewTab('+data['guid']+');"><div class="friends-item-inner"><div class="icon"><img src="'+data['icon']+'" /></div> <div class="name">'+data['name']+'</div><div class="'+data['status']+' ustatus"></div> </div></div>';    
          $(".ossn-chat-windows-long .inner").find('.ossn-chat-none').hide();
          $(".ossn-chat-windows-long .inner").append(appendata);
        }
  });
}

/**
 * Check if there is new message then put them in tab
 *
 * @params OssnChat['newmessages'] Array
 */	
if(OssnChat['newmessages']){
$.each(OssnChat['newmessages'], function(key, data){
            if($('.ossn-chat-base').find('#ftab-i'+data['fid']).length){
                      $totalelement = $('#ftab-i'+data['fid']).find('.ossn-chat-new-message');
                      $texa = $('#ftab-i'+data['fid']).find('.ossn-chat-new-message').text();
                      if(data['total'] > 0){
                      	    $.each(data['message'], function(ikey, item){
                            	  if($('#ossn-message-item-'+item['id']).length == 0){
	 		                          $('#ftab-i'+data['fid']).find('.data').append(item['message']); 
                                  }
                            })
                           
                           if($('.ossn-chat-base').find('#ftab-i'+data['fid']).find('.tab-container').is(":not(:visible)")){
                               $('#ftab-i'+data['fid']).find('#ftab'+data['fid']).addClass('ossn-chat-tab-active');
                               $totalelement.html(data['total']);
                               $totalelement.show();
                           } else {
                           	   $totalelement.empty();
                               Ossn.ChatMarkViewed(data['fid']);
                           }
                           if($texa != data['total']){
	                           Ossn.playSound();
                           }
                           Ossn.ChatScrollMove(data['fid']);
                           
                           //chat linefeed problem #278.
                           // move scroll once again when div is loaded fully
                           $("#ossn-chat-messages-data-"+data['fid']).load(function() {
                           		Ossn.ChatScrollMove(data['fid']);
                           });

                       }
                 
            }
});
}
/**
 * Open new tab on new message
 *
 * @params OssnChat['all_new'] Array
 */	
if(OssnChat['all_new']){
$.each(OssnChat['all_new'], function(key, data){
     if($(".ossn-chat-containers").children(".friend-tab-item").size() < 4){   						   
         var $friend = data['message_from'];
         Ossn.ChatnewTab($friend);         
           if(!$('#ftab-i'+$user)){   						     
              Ossn.playSound();
              Ossn.ChatScrollMove(data['message_from']);
           }
     }
});
}
