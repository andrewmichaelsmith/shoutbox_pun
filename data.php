<?php
ob_start("ob_gzhandler");

function xmlEntities($str) 
{ 
    $xml = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;'); 
    $html = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;'); 
    $str = str_replace($html,$xml,$str); 
    $str = str_ireplace($html,$xml,$str); 
    return $str; 
} 
header("Cache-Control: no-cache");
header("Content-type: text/xml");  
define('FORUM_ROOT', '../../');
require FORUM_ROOT.'include/common.php';


function getShouts($id,$forum_db) {
  
  
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	  echo "<response>\n";   

    $query = array(
		'SELECT'	=> 'id, userid, date, shout',
		'FROM'		=> 'pun_shout',
		'ORDER BY'	=> 'date DESC',
		'WHERE'   => 'id > '.round($id),
		'LIMIT'		=> '50'
		
	  );
	  
	  $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	  
	  
	  $new = $forum_db->num_rows($result);
	  
	  
	
	  
	  $shout = array();
	  $username_cache = array();
    $flip_me = array();
    
    
    while ($s = $forum_db->fetch_assoc($result))
    {
      $flip_me[] = $s;
    }
	
	$last_id = $flip_me[0]['id'];
	
	echo "\t<forumuser>".$forum_user['id']."</forumuser>\n";
	echo "\t<newones>".$new."</newones>\n";  
    echo "\t<lastid>".$last_id."</lastid>\n";  
    
    $flip_me = array_reverse($flip_me);
    
	  foreach($flip_me as $shout)
	  {
	     if(!isset($username_cache[$shout['userid']])) 
	     {
	         $query = array(
        		'SELECT'	=> 'username',
        		'FROM'		=> 'users',
        		'WHERE'	=> 'id = '.$shout['userid']
        	  );
        	  $username_res = $forum_db->query_build($query) or error(__FILE__, __LINE__);
        	  
        	  $tmp = $forum_db->fetch_assoc($username_res);
        	  $username_cache[$shout['userid']] = $tmp['username'];
        }
        
	      echo "\t<message>\n";  
	      
	      echo "\t\t<id>";
	      echo $shout['id'];
	      echo "</id>\n";
	      
	      echo "\t\t<userid>";
	      echo $forum_user['id'];
	      echo "</userid>\n";

	      echo "\t\t<username>";
	      echo $username_cache[$shout['userid']];
	      echo "</username>\n";
	      
	      echo "\t\t<date>";
	      echo $shout['date']+$timediff;
	      echo "</date>\n";
	      
	      echo "\t\t<text>";
	      echo $shout['shout'];
	      echo "</text>\n";

        echo "\t</message>\n";
	  }
	  
echo "</response>";

}

if($forum_user['id']==1) 
{
      echo "<?xml version=\"1.0\"?>\n";
	  echo "<response>\n"; 
	  echo "\t<error>\n";
	  echo "\t\tYou are not logged in\n";
	  echo "\t</error>\n";
	  echo "</response>";
	  exit();
}
  
//This is the script that adds entries to the shoutbox, deletes them, edits them and lists entries in the shoutbox



else if($_GET['add'])
{
	echo "<?xml version=\"1.0\"?>\n";
	echo "<response>\n";  
	
	
	$msg_to_add = make_clickable($_GET['add']);
	
	
  $msg_to_add = xmlentities(htmlspecialchars($msg_to_add,ENT_QUOTES,'UTF-8'));
  
  if( (strlen($msg_to_add) > 0) && (strlen($msg_to_add) <= 255) )
  {	
    $query = array(
			'INSERT'	=> 'userid, date, shout',
			'INTO'		=> 'pun_shout',
			'VALUES'	=> '\''.$forum_user['id'].'\', \''.time().'\' , \''.mysql_escape_string($msg_to_add).'\''
		);
		
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
		
		//getShouts($_GET['id'],$forum_db);
		 echo "\t<iserror>";  
	  echo "0";
	  echo "</iserror>\n";
	}
	else
	{
	  
	  echo "\t<iserror>";  
	  echo "1";
	  echo "</iserror>\n";
	  echo "\t<error>";
	  echo "Too long or Too Short";
	  echo "</error>";
	
	  
	}
	
	  echo "</response>";
}

else if($_GET['del'])
{
  $msg_to_delete = round($_GET['del']);
  
  if($msg_to_delete >= 0)
  {
       $query = array(
        		'DELETE'	=> 'pun_shout',
        		'WHERE'	=> 'id = '.$msg_to_delete.' AND userid = '.$forum_user['id']
        	  );  
         $forum_db->query_build($query) or error(__FILE__, __LINE__);
         
  }
  else
	{
	  echo "<?xml version=\"1.0\"?>\n";
	  echo "<response>\n";  
	  echo "\t<error>";  
	  echo "you suck";
	  echo "</error>\n";
	  echo "</response>";
	  
	}
  
}

else if($_GET['m']=="list")
{
  getShouts($_GET['id'],$forum_db);
}

function _make_url_clickable_cb($matches) {
	$ret = '';
	$url = $matches[2];
 
	if ( empty($url) )
		return $matches[0];
	// removed trailing [.,;:] from URL
	if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($url, -1);
		$url = substr($url, 0, strlen($url)-1);
	}
	return $matches[1] . "<a href=\"".htmlspecialchars($url)."\" rel=\"nofollow\">".htmlspecialchars($url)."</a>" . $ret;
}
 
function _make_web_ftp_clickable_cb($matches) {
	$ret = '';
	$dest = $matches[2];
	$dest = 'http://' . $dest;
 
	if ( empty($dest) )
		return $matches[0];
	// removed trailing [,;:] from URL
	if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($dest, -1);
		$dest = substr($dest, 0, strlen($dest)-1);
	}
	return $matches[1] . "<a href=\"".htmlspecialchars($dest)."\" rel=\"nofollow\">".htmlspecialchars($dest)."</a>" . $ret;
}
 
function _make_email_clickable_cb($matches) {
	$email = $matches[2] . '@' . $matches[3];
	return $matches[1] . "<a href=\"mailto:".htmlspecialchars($email)."\">".htmlspecialchars($email)."</a>";
}
 
function make_clickable($ret) {
	$ret = ' ' . $ret;
	// in testing, using arrays here was found to be faster
	$ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
	$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
	$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);
 
	// this one is not in an array because we need it to run last, for cleanup of accidental links within links
	$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
	$ret = trim($ret);
	return $ret;
}

?>
