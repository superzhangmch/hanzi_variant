<?php
$type="0";
$handle = fopen("./mp3.txt","r");
$buffer=fread($handle,100000);
fclose ($handle);
$tmp=explode("\r",$buffer);
for($i=0;$i<count($tmp);$i++)
{
	$tmp1=trim($tmp[$i],"¡¡ \t\n\r");
	if($tmp1!="")
	{
		$song[]=trim($tmp1," ");
	}
}
$song=array_unique($song);

//echo "<pre><hr>";print_r($song);echo "<hr>";
//------------------------------------------------------//
//"http://mp3.baidu.com/m?f=ms&tn=baidump3&ct=134217728&lf=&rn=&word=".$name."&lm=".$type;
//	-1	all
//	0	mp3
//	5	mid
$urlH="/m?f=ms&tn=baidump3&ct=134217728&lf=&rn=&word=";
for($i=0;$i<count($song);$i++)
{
	$url[$i]=$urlH.urlencode($song[$i])."&lm=".$type;
}
//echo "<pre><hr>";print_r($url);echo "<hr>";
//-------------------------------------------------------//
function get_page($host,$url)
{
	$url=str_replace (" ","%20",$url);	
	$buf=	"GET ".$url." HTTP/1.1"."\015\012".
		"Accept: */*"."\015\012".
		"Referer: http://mp3.baidu.com"."\015\012".
		"Accept-Language: zh-cn"."\015\012".
		"User-Agent: Mozilla/4.0(compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)"."\015\012".
		"Host: ".$host."\015\012".
		"Connection: Keep-alive"."\015\012".
		"Cookie: BAIDUID=6F0B3E65FB413F988B4E04219B727550"."\015\012"."\015\012";
	$rs=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	socket_connect($rs,$host,80);
	socket_write($rs,$buf);
	$page="";
	for(;;)
	{
		$tmp=socket_read($rs,1000000);
		if($tmp!=false)
			$page.=$tmp;
		else
			break;
	}
	socket_close($rs);
	return $page;
}
//-------------------------------------------------------//
for($p=0;$p<count($url);$p++)
{
	$page=get_page("mp3.baidu.com",$url[$p]);
	$pat="/\"http:\/\/220.181.38.82(\/.*?)\".*?>(.*?)<\/a>/";
	preg_match_all($pat,$page,$mat);
	$result=array();
	$j=0;
	
	if(count($mat)!=0)
	{
		for($i=0;$i<count($mat[0]);$i++)
		{
			$mat[2][$i]=strip_tags($mat[2][$i]);
			$k=stripos($mat[2][$i],$song[$p]);
			if(is_int($k))
			{
				$result[$j][0]=$mat[2][$i];
				$result[$j][1]=$mat[1][$i];
				$j++;
			}
		}
	}
	if(count($result)!=0)
	{
		$r=rand(0,count($result)-1);
		$page=get_page("220.181.38.82",$result[$r][1]);
		$pat="/¸èÇúÃû£º<a href=\"(.*)\"/";
		preg_match($pat,$page,$mat);
		$result=array($result[$r][0],null);

		if(count($mat)!=0)
		{
			$result[1]=$mat[1];

			$tmp="<a href='".$result[1]."'>".trim($result[0]," ¡¡")."</a>: ".$result[1]."<br>";
			echo "$p: $result[0] $result[1]\n";

			$handle=fopen("./mp3.lst","a");
			$handle1=fopen("./mp3.htm","a");
			fwrite($handle,"$result[1]"."\n");
			fwrite($handle1,$tmp);
			fclose($handle);
			fclose($handle1);
		}
		else
		{
			echo "$p: $song[$p] found but bad_html_code,retry\n";
			$p--;
			continue;
		}
	}
	else
	{
		echo "$p: $song[$p] Not Found\n";
	}
}
//echo "<hr><hr><pre>".$page;
?>
