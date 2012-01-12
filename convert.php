<PRE>
<?php

function xmlEntities($str) 
{ 
    $xml = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;'); 
    $html = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;'); 
    $str = str_replace($html,$xml,$str); 
    $str = str_ireplace($html,$xml,$str); 
    return $str; 
} 

$db_type = 'mysql';
$db_host = 'localhost';
$db_name = 'dh';
$db_username = 'root';
$db_password = 'pandogmillionarie12';
$db_prefix = '';
$p_connect = false;

mysql_connect($db_host,$db_username,$db_password);
mysql_select_db($db_name);
$result=mysql_query("SELECT * FROM shoutbox WHERE 1 ORDER BY date DESC");
echo mysql_error();

$shouts = array();

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  
  if( (round($row['date'])>10000) && ($row['message'] != "") && ($row['name'] != "") )
  {
    $u_result=mysql_query("SELECT id FROM durk_users WHERE username = '".mysql_escape_string($row['name'])."';");
    
    if(mysql_num_rows($u_result)==1)
    {
      $row['name'] = mysql_result($u_result,0,0);
      
      $shouts[]=$row;
    }
  }
}

//print_r($shouts);

$db_type = 'mysqli';
$db_host = 'forum-db.fack.org';
$db_name = 'newdhforum';
$db_username = 'newforumdb';
$db_password = '...h3yh0l3tsg0';
$db_prefix = '';
$p_connect = false;

mysql_connect($db_host,$db_username,$db_password);
mysql_select_db($db_name);

$result=mysql_query("TRUNCATE pun_shout;");

foreach($shouts as $shout)
{
  mysql_query($sql="INSERT INTO pun_shout (id,userid,date,shout) VALUES ('".$shout['id']."','".$shout['name']."','".$shout['date']."','".trim(xmlentities(htmlentities(mysql_escape_string($shout['message']))))."');");
  echo $sql;
  echo mysql_error();
}
