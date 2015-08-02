<?php

$last=substr($argv[1], -1);

$testl="c";

if ($last==$testl){

	$argv[1] = substr($argv[1], 0, strrpos($argv[1], "-"));

	$argv[1] = substr($argv[1], strrpos($argv[1], "-"), 20);

	$pidc=str_replace("-","",$argv[1]);

	$collec="http://account.hotstar.com/AVS/besc?action=GetAggregatedContentDetails&channel=PCTV&contentId=$pidc";

	$options  = array('http' => array('user_agent' => 'custom user agent string'));

	$context  = stream_context_create($options);

	$showc = file_get_contents($collec, false, $context);

	$resultObj=json_decode($showc)->resultObj;

	$contentInfo=$resultObj->{'contentInfo'};

	$contentInfo2=($contentInfo[0]);

	$realpidc = $contentInfo2->categoryId;

	$collecs="http://account.hotstar.com/AVS/besc?action=GetArrayContentList&categoryId=$realpidc&channel=PCTV";

	$showcs = file_get_contents($collecs, false, $context);

	$resultObjs=json_decode($showcs)->resultObj;

	$contentList=$resultObjs->{'contentList'};

	if(isset($argv[2])){

		$title="http://account.hotstar.com/AVS/besc?action=GetAggregatedContentDetails&channel=PCTV&contentId=$argv[2]";

		$show = file_get_contents($title, false, $context);

		$resultObj=json_decode($show)->resultObj;

		$contentInfo=$resultObj->{'contentInfo'};

		$contentInfo2=($contentInfo[0]);

		$contentTitle = $contentInfo2->contentTitle; $contentTitle=str_replace(" ","-",$contentTitle);

		$episodeTitle = $contentInfo2->episodeTitle; $episodeTitle=str_replace(" ","-",$episodeTitle);

		$episodeNumber= $contentInfo2->episodeNumber; $episodeNumber="episode$episodeNumber";

		$filename= "'$contentTitle'_'$episodeNumber'_'$episodeTitle'";

		$filename=iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $filename);
		
		$filename = preg_replace('/[^A-Za-z0-9_\. -]/', '', $filename);

		$json="http://getcdn.hotstar.com/AVS/besc?action=GetCDN&asJson=Y&channel=IOS&id=$argv[2]&type=VOD";

		$test=file_get_contents($json, false, $context);

		$obj=json_decode($test)->resultObj;

		$hls=$obj->{'src'};

		$testhttps=strpos($hls, 'https');

		if($testhttps){  
			$m3u8=str_replace("https","hlsvariant://https",$hls);
			} 
			else{ 
			$m3u8=str_replace("http","hlsvariant://http",$hls);
			}

		$badformat = array("4500","3000","2000","1300","800","400","180","106","54","16");

		$googformat = array("1080p", "900p","720p","404p","360p","234p","","","","");

		$quality = str_replace($badformat, $googformat, $m3u8);
				
		$endq= strrpos($quality, ',');  $beginq= strpos($quality, ','); $endrq = $endq - $beginq ; 
		$bitrate= substr($quality, $beginq , $endrq); $bitrate=str_replace(","," ",$bitrate);

		if(isset($argv[3])){
			}
			else{
			echo "Available streams: $bitrate\n";
			}

	}
	else{
	foreach($contentList as $r){
	echo "Id:".$r->contentId." "."episodeTitle:".$r->episodeTitle." "."contentTitle:".$r->contentTitle."\n";
	}
	}
	if(isset($argv[3]) AND isset($argv[2])){

		echo "Starting livestreamer...\n\n";
		echo shell_exec("$argv[5]livestreamer  \"$m3u8\" \"$argv[3]\" -o \"$argv[4]$filename.ts\" &");
		echo "Done.\n";
		}
		else{ 
		}

}
else{ 

	$folder = ""; 

	$argv[1] = substr($argv[1], 0, strrpos($argv[1], "-")); $argv[1] = substr($argv[1], strrpos($argv[1], "-"), 20);

	$pid=str_replace("-","",$argv[1]);

	$title="http://account.hotstar.com/AVS/besc?action=GetAggregatedContentDetails&channel=PCTV&contentId=$pid";

	$json="http://getcdn.hotstar.com/AVS/besc?action=GetCDN&asJson=Y&channel=IOS&id=$pid&type=VOD";
                                 
	$options  = array('http' => array('user_agent' => 'custom user agent string'));

	$context  = stream_context_create($options);

	$show = file_get_contents($title, false, $context);

	$resultObj=json_decode($show)->resultObj;

	$contentInfo=$resultObj->{'contentInfo'};

	$contentInfo2=($contentInfo[0]);

	$contentTitle = $contentInfo2->contentTitle; $contentTitle=str_replace(" ","-",$contentTitle);

	$episodeTitle = $contentInfo2->episodeTitle; $episodeTitle=str_replace(" ","-",$episodeTitle);

	$episodeNumber= $contentInfo2->episodeNumber; $episodeNumber="episode$episodeNumber";

	$filename= "'$contentTitle'_'$episodeNumber'_'$episodeTitle'";

	$filename=iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $filename);
		
	$filename = preg_replace('/[^A-Za-z0-9_\. -]/', '', $filename);

	$test=file_get_contents($json, false, $context);

	$obj=json_decode($test)->resultObj;

	$hls=$obj->{'src'};

	$testhttps=strpos($hls, 'https');

	if($testhttps){  
		$m3u8=str_replace("https","hlsvariant://https",$hls);
		} 
		else{ 
		$m3u8=str_replace("http","hlsvariant://http",$hls);
		}

	$badformat = array("4500","3000","2000","1300","800","400","180","106","54","16");

	$googformat = array("1080p", "900p","720p","404p","360p","234p","","","","");

	$quality = str_replace($badformat, $googformat, $m3u8);
				
	$endq= strrpos($quality, ',');  $beginq= strpos($quality, ','); $endrq = $endq - $beginq ; 
	$bitrate= substr($quality, $beginq , $endrq);

	$bitrate=str_replace(","," ",$bitrate);


	if(isset($argv[2])){
		}
		else{ echo "Available streams: $bitrate\n";
		}


	if(isset($argv[2])){


		echo "Starting livestreamer...\n\n";
		echo shell_exec("$argv[4]livestreamer  \"$m3u8\" \"$argv[2]\" -o \"$argv[3]$filename.ts\" &");
		echo "Done.\n";
		}
		else{ 
		}
}

?>
