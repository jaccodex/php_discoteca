<?php


//$text='01_Myrrys';
//$regexp='/^\d{2}_/';

//$text='001_Myrrys';
//$regexp='/^\d{3}_/';

//$text='01 Myrrys';
//$regexp='/^\d{2}\s/';

//$text='001 Myrrys';
//$regexp='/^\d{3}\s/';

$text='Myr0rys';
$regexp='/^\w/';


$matches=preg_match($regexp, $text);

if ($matches)
{
    echo 'yes, ' . $regexp . ' matches ' . $text;
}
else
{
    echo 'no, ' . $regexp . ' doesn\'t matches ' . $text;
}


/*
$text='01_Myrrys.mp3';

echo preg_replace('/(\w+)((.mp3)|(.MP3))/','$1',$text);
*/
?>
