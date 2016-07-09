<?php
$title = "csgojackpotting.com"; // This will be the title of your site, also shown in the top left corner

$bot1id='76561198302742792'; // SteamID64 Which can be found at steamid.io
$bot1url='https://steamcommunity.com/tradeoffer/new/?partner=342477064&token=1lha0QKf'; // Trade URL of your first bot

$bot2id='76561198018643631'; // SteamID64 Which can be found at steamid.io
$bot2url='https://steamcommunity.com/tradeoffer/new/?partner=58377903&token=XJOaZ7A5'; // Trade URL of your second bot

$steamgroup=''; // Link to your Steam Group - The Icon will show on top if you set this to anything
$twitter='https://twitter.com/CSGOdotNetwork'; // Link to Twitter Account - The Icon will show on top if you set this to anything
$facebook='https://www.facebook.com/CSGO.Network/'; // Link to your Facebook Page - The Icon will show on top if you set this to anything
$reddit=''; // Link to your Reddit Page - The Icon will show on top if you set this to anything

$sgt=''; // Don't change these
if($steamgroup)
{
	$sgt='<li class="hidden-xs"><a href="'.$steamgroup.'" target="_BLANK"><i class="fa fa-steam"></i></a></li>';
}
$tt=''; // Don't change these
if($twitter)
{
	$tt='<li class="hidden-xs"><a href="'.$twitter.'" target="_BLANK"><i class="fa fa-twitter"></i></a></li>';
}
$fbt=''; // Don't change these
if($facebook)
{
	$fbt='<li class="hidden-xs"><a href="'.$facebook.'" target="_BLANK"><i class="fa fa-facebook"></i></a></li>';
}
$rdt=''; // Don't change these
if($reddit)
{
	$rdt='<li class="hidden-xs"><a href="'.$reddit.'" target="_BLANK"><i class="fa fa-reddit"></i></a></li>';
}



/*


Temp. removed, slow response from is.steam.rip and inaccurate response from the other one


function get_steam_status($steamID64, $timeout = 5) 
  {
    $context = stream_context_create(array('http' => array('timeout' => $timeout)));
    $file = @file_get_contents('http://steamcommunity.com/profiles/' . $steamID64 . '/?xml=1', false, $context);
    $xml = simplexml_load_string($file);
    if (isset($xml->onlineState)) {
      $online_state = (string)$xml->onlineState;
      $state_message = ($online_state == 'offline' ? 'Offline' : (string)$xml->stateMessage);
    } else {
      // Error loading profile
      $online_state = 'offline';
      $state_message = 'Offline';
    }
    $state_css = array('online' => 'on', 'in-game' => 'ing', 'offline' => 'off');
    return '<span class="steam_' . $state_css[$online_state] . '"><a href="http://steamcommunity.com/profiles/' . $steamID64 . '/" target="_blank">' . $state_message . '</a></span>';
  }

$string = file_get_contents("http://is.steam.rip/api/v1/?request=IsSteamRip");
$obj = json_decode($string);
if($obj->{'isSteamRip'} == false)
{
	$steamd='<i title="Steam Services are Online" class="fa fa-steam-square text-green"></i>';
	$steamstatus='<font color="green">Online</font>';
}
else
{
	$steamd='<i title="Steam Services are Offline" class="fa fa-steam-square text-red"></i>';
	$steamstatus='<font color="green">Offline</font>';
}
  
$bot1=get_steam_status($bot1id);
if (strpos($bot1, "Online") === FALSE)
{
	$bot1='<font color="red">Offline</font>';
	$bot1d='<li class="hidden-xs"><a><i title="Bot 1 Offline" class="fa fa-circle text-red"></i></a></li>';
}
else
{
	$bot1= '<font color="green">Online</font>';
	$bot1d='<li class="hidden-xs"><a href="'.$bot1url.'" target="_BLANK"><i title="Bot 1 Online" class="fa fa-circle text-green"></i></a></li>';
}

$bot2=get_steam_status($bot2id);
echo $bot2;
if (strpos($bot2, "Online") === FALSE)
{
	$bot2='<font color="red">Offline</font>';
	$bot2d='<li class="hidden-xs"><a><i title="Bot 2 Offline" class="fa fa-circle text-red"></i></a></li>';
}
else
{
	$bot2= '<font color="green">Online</font>';
	$bot2d='<li class="hidden-xs"><a href="'.$bot2url.'" target="_BLANK"><i title="Bot 2 Online" class="fa fa-circle text-green"></i></a></li>';
}


*/

?>