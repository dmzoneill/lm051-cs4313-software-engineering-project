<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="{lang}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>fail</title>
</head>
<body>

<?php
require_once ( "../../conf.php" );
require_once ( "../locale/en.php" );

$db = DatabaseManager :: getInstance( array ( __DB_HOST , __DB_USER , __DB_PASS , __DB_NAME ) );
$registry = Registry::getInstance();

$langs = $db->query( "select * from locale where available=1" , "array" );
$gt = new Gtranslate( );
$gt->setRequestType( 'curl' );

print "<select name='lang' onChange='changeLanguage(this.value)' size='50'>";

foreach ( $langs as $lang )
{
    try
    {
        $langVal = preg_replace( "^\W|_^" , " " , $lang[2] );
        
        $data = call_user_func_array( array ( $gt , 'en_to_' . $lang [ 3 ] ) , array ( ucfirst( strtolower ( $langVal ) ) ) );
        $db->query( "update locale set locale='$data' where id='$lang[0]'" , "one" );
        print "<option value='$lang[3]'>" . $data . "</option>\n";
    }
    catch ( GTranslateException $ge )
    {
        
    }
    
}

print "</select>";



print "<select name='lang' onChange='changeLanguage(this.value)' size='50'>";

foreach ( $langs as $lang )
{
    try
    {
        print "<option value='$lang[3]'>" . $lang[1] . "</option>\n";
    }
    catch ( GTranslateException $ge )
    {
        
    }
    
}

print "</select>";