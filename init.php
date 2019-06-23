<?php

	spl_autoload_register(function ($class_name) {

		$dir = __DIR__.'/FR/modele/';
		read($dir);
		$dir = __DIR__.'/FR/modele/classes/';
		read($dir);


		/*$dir = __DIR__.'/ENG/modele/';
		read($dir);
		$dir = __DIR__.'/ENG/modele/classes/';
		read($dir);*/

		
	});

	function read($dir){
		if ($handle = opendir($dir)) {

		    while (false !== ($entry = readdir($handle))) {
		    	$file = $dir.$entry;
		    	
		    	if( is_file($file)){
		    		require_once $file;
		    	}
		    	
		    }

		    closedir($handle);
		}
	}


?>