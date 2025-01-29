<?php
foreach (scandir('.') as $file) if (substr($file, 0, 1) !== '.' && substr($file, -4) === '.txt') {
	echo "<a href='$file'>$file</a> (" . date('Y-m-d H:i:s', filemtime($file)) . ")<br>";
}
