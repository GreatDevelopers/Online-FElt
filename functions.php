<?php


	function downloadFile ($url, $path) {

		$newfname = basename($url);
		    $file = fopen ($url, "rb");
		    if ($file) {
			        $newf = fopen ($newfname, "wb");
				if ($newf)
				while(!feof($file)) {
				      fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
				}
		    }
		      if ($file) {
			          fclose($file);
		      }
		      if ($newf) {
			          fclose($newf);
		      }
	}
?>
