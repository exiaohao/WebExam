<?php
header("HTTP/1.0 404 Not Found");
?>
<h1>404 Not Found</h1>
<hr />
<p>There's no file '<?=$_SERVER['REQUEST_URI'];?>' from this server.</p>
<address>ETNWS WebSlim Server</address>
