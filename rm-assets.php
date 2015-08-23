<?php
header('Content-type: text/plain');
set_time_limit(0);

recursive_rmdir(__DIR__ . DIRECTORY_SEPARATOR . 'assets', true);

function recursive_rmdir($directory, $empty = false){
	$directory = preg_replace('@[\\/]$@', '', $directory);

	if(!file_exists($directory) || !is_dir($directory))
		return false;
	elseif(!is_readable($directory))
		return false;
	else{

		$handle = opendir($directory);

		while (false !== ($item = readdir($handle))){
			if($item != '.' && $item != '..'){
				$path = $directory . DIRECTORY_SEPARATOR . $item;

				if(is_dir($path)) 
					recursive_rmdir($path);
				elseif(unlink($path))
					echo $path . "\n";
			}
		}
		closedir($handle);

		if($empty == false){
			if(rmdir($directory))
				echo $directory . "\n";
			else
				return false;
		}
		return true;
	}
}
