<?php
$c=count($dashboard_tpls);
$i=0;
foreach ($dashboard_tpls as $file) {
	$i++;
	include($file);
	if ($i<$c) {
		echo '<hr/>';
	}
}

?>