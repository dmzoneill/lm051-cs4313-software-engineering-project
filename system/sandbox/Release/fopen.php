<?php

if ($fp = fopen('http://www.google.com/', 'r')) {
   $content = '';
   // keep reading until there's nothing left 
   while ($line = fread($fp, 1024)) {
      $content .= $line;
   }

	echo $content;
   // do something with the content here
   // ... 
} else {
   // an error occured when trying to open the specified url 
}

?>
