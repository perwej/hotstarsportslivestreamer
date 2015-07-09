<?php

$options  = array('http' => array('user_agent' => 'custom user agent string'));

$context  = stream_context_create($options);

$testlink=strpos($argv[1], '/vod/');
$testlink2=strpos($argv[1], '/live-video');
$testlink3=substr("$argv[1]", -1);
if($testlink){  
			goto yesvod;
			} 
			else{ 
			goto novod;
			}

yesvod:
$prepidb= strpos($argv[1], 'video='); $prepide=strrpos($argv[1], '/'); $prepidef = $prepide - $prepidb ;
$prepid= substr($argv[1], $prepidb , $prepidef); $pid=str_replace("video=","",$prepid);
$json="http://m.starsports.com/syndicationdata/newmobile/video/video_details/$pid.json";
$showc = file_get_contents($json, false, $context);
$Video=json_decode($showc)->Video;
$pid=$Video->{'VideoId'};
goto endtest;



novod:
if($testlink2){  
			goto yeslive;
			} 
			else{ 
			goto nolive;
			}
goto endtest;

yeslive:
$prepids=str_replace("/live-video","",$argv[1]); 
$pid=substr("$prepids", -6);
$json="http://matchcentre.starsports.com/syndicationdata/newmobile/sportswire/cricket/match/$pid/videolist.json";
$showc = file_get_contents($json, false, $context);
$prepide= strpos($showc, 'Replay'); $prepidb=$prepide - 150; $prepidef = $prepide - $prepidb ;
$prepid= substr($showc, $prepidb , $prepidef);
$prepidb= strpos($prepid, '"VideoId": "'); $prepide=$prepidb + 19; $prepidef = $prepide - $prepidb ;
$prepid= substr($prepid, $prepidb , $prepidef); $pid = str_replace('"VideoId": "',"",$prepid); 
goto endtest;


nolive:
if (eregi("^[0-9]",$testlink3)){
			goto yesnumber;
			}
			else{
			goto nonumber;
			}
goto endtest;


yesnumber:
$prepid = substr($argv[1], strrpos($argv[1], "-"));
$pid = str_replace("-","",$prepid);
goto endtest;

nonumber:
goto allno;

allno:
$prepidb= strpos($argv[1], 'video='); $prepide=strrpos($argv[1], '/'); $prepidef = $prepide - $prepidb ;
$prepid= substr($argv[1], $prepidb , $prepidef); $pid=str_replace("video=","",$prepid);
$json="http://m.starsports.com/syndicationdata/newmobile/video/video_details/$pid.json";
$showc = file_get_contents($json, false, $context);
$Video=json_decode($showc)->Video;
$pid=$Video->{'VideoId'};
goto endtest;

endtest:

$json="http://www.starsports.com/divadata/output/VideoData/HLS/json/$pid.json";
$showc = file_get_contents($json, false, $context);
$m3u8=json_decode($showc)->videosource;
$rhls="http://m.starsports.com/tokens/portal/test/iOSStream.aspx?url=$m3u8";
$rrhls = file_get_contents($rhls, false, $context);
$m3u8final=json_decode($rrhls)->url;
$filename=json_decode($showc)->SEO;
$m3u8final=str_replace("http","hlsvariant://http",$m3u8final);







if(isset($argv[3])){
		echo "Starting livestreamer...\n\n";
		echo shell_exec("$argv[4]livestreamer  \"$m3u8final\" \"$argv[2]\" -o \"$argv[3]$filename.ts\" &");
		echo "Done.\n";
		}
		else{ 
		echo "Starting livestreamer...\n\n";
			echo shell_exec("$argv[2]livestreamer  \"$m3u8final\"  &");
		}


?>
