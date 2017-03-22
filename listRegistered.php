<?php

require_once("header.php");
require_once("ESP.php");

echo "<pre>";
$App = new ESP();
$App->loadDB();
$App->expireDB();
$App->saveDB();
?>
<h3>Active modules | <a href="listIO.php">All GPIO</a></h3>
<table>
<tr><th>Module ID</th><th>IP Address</th></tr>
<?php
foreach ($App->db as $key => $val) {
    echo "<tr><td><a href=\"http://".$val["IP"]."/esp\">".$key."<a></td><td>".$val["IP"]."</td></tr>";
}
?>
</table>


</body>
</html>

