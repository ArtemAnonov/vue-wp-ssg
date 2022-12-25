
<h1>Operation complated</h1>
<a href="javascript:history.go(-1)">Go to previous page</a>


<?php
// echo '<pre><b>',__FILE__,__LINE__,'</b><br>', print_r($globals['RADLStore'],1),'</pre>';
$output = null;
$retval = null;
exec(
    '
cd /var/www/html/wp-content/themes/logotype-ssr/vue-vite-ssr; 
npm run generate  2>&1
chmod 777 dist;
',
    $output,
    $retval
);
echo "Status $retval \n";
echo '<pre><b>', __FILE__, __LINE__, '</b><br>', print_r($output, 1), '</pre>';
?>

