<?php

use \RADL as RADL;
// use \Awps\VueWordpress\VueWordpress;
// (new VueWordpress)->register();
$store = RADL::render_store();
echo '<script>console.log(' . json_encode($store) . ')</script>';

// for replace host


$json = json_encode($store);

file_put_contents(get_template_directory() . '/vue-vite-ssr/src/json/vuewp.json', $json);

// echo '<pre><b>',__FILE__,__LINE__,'</b><br>', print_r($store,1),'</pre>';

?>
<h1>SSGGenerator</h1>
<h4>Store rendered and writed!</h4>
<!-- <form action="<?php
// echo plugins_url('/exec.php', __FILE__)
?>/admin/scripts/hosting-exec.php">
    <h2>Form for Hosting</h2>
    <button type="submit">Render, write Store</button>
</form> -->

<form action="<?php echo plugins_url('/exec.php', __FILE__) ?>/admin/scripts/vps-exec.php">
    <h2>Form for VPS</h2>
    <button type="submit">Generate static files</button>
</form>