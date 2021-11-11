<?php

$router = $di->getRouter();

/* ADMIN PANEL */
$router->add('/admin', 'adminArea::index');
// $router->add( '/admin/', 'AdminArea::index' );
// $router->add('/area', 'AdminArea::index');
// $router->add('/area/', 'AdminArea::index');
$router->add('/area/updateoptions', 'AdminArea::UpdateOptions'); // update options
$router->add('/area/addnewfriend', 'AdminArea::AddNewFriend'); // add new friend
$router->add('/area/renewpassword', 'AdminArea::RenewPassword'); // update password
$router->add('/area/updatestatus', 'AdminArea::UpdateStatus'); // update status
$router->add('/area/updateadmin', 'AdminArea::UpdateAdmin'); // update is_admin
$router->add('/area/addhashtag', 'AdminArea::AddNewHashtag'); // add hashtag
$router->add('/area/updatehashtag', 'AdminArea::UpdateHashtagActive'); // update hashtag
$router->add('/area/addnewreward', 'AdminArea::AddNewReward'); // add reward
$router->add('/area/updaterewardstatus', 'AdminArea::UpdateRewardStatus'); // update reward
$router->add('/area/updaterewardprice', 'AdminArea::UpdateRewardPrice'); // update reward
$router->add('/area/approveorder', 'AdminArea::ApproveOrder'); // update reward
$router->add('/area/rejectorder', 'AdminArea::RejectOrder'); // update reward
$router->add('/area/discord_id', 'AdminArea::updateDiscordId'); // set discord id


/* USER DASHBOARD */
$router->add('/auth', 'UserAuth::login');
$router->add('/login', 'UserAuth::login');
$router->add('/logout', 'UserAuth::logout');
$router->add('/area', 'Index::index');

$router->add("/gettimeline", "Index::getTimeLine"); // load more timeline

$router->add('/bonuz/addnew', 'BonuzActions::AddBonuz'); // add new bonuz (AJAX)
$router->add('/bonuz/comment', 'BonuzActions::AddComment'); // comment on bonuz (AJAX)
$router->add('/bonuz/{bonuzId:[0-9]+}', 'BonuzActions::ShowBonuz'); // show bonuz

$router->add('/hashtag/{hashtag:[A-Za-zı0-9\-]+}', 'Hashtag::Show'); // show hashtag
$router->add('/hashtag/loadmore', 'Hashtag::ShowMore'); // show more hashtag

/* Profile routes */
$router->add('/profile/settings', 'Profile::index'); // update settings
$router->add('/profile/update', 'Profile::updateProfile'); // update settings
$router->add("/profile/{profileId:[0-9]+}", "Profile::show"); // show user profile
$router->add('/getUserReceivedBonuzs', 'Profile::getReceivedBonuzs'); // load more received bonuzs
$router->add('/getUserGivenBonuzs', 'Profile::getGivenBonuzs'); // load more given bonuzs

/* Reward routes */
$router->add('/rewards', 'Reward::index');
$router->add('/rewards/buyitem', 'Reward::BuyItem');

$router->add('/slack', 'Slack::index');
$router->add('/giphy', 'Giphy::index');
/**Bots */
$router->add('/bots/discord', 'DiscordBot::giveBonuz');
$router->add('/bots/discord/link', 'DiscordBot::linkAccount');


$router->handle();
