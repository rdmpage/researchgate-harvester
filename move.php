<?php

//----------------------------------------------------------------------------------------
function get_image_filename($base_dir, $name, $extension = 'jpg')
{
	$image_filename = '';

	$prefix = substr($name, 0, 1);	
	$destination_dir = $base_dir . '/' . $prefix;
	$filename = $destination_dir . '/' . $name . '.' . $extension; 
	
	if (file_exists($filename))
	{
		$image_filename = $filename;
	}
	
	return $image_filename;

}

//----------------------------------------------------------------------------------------
function create_image_filename($base_dir, $name, $extension = 'jpg')
{
	$image_filename = '';

	$prefix = substr($name, 0, 1);
	
	$destination_dir = $base_dir . '/' . $prefix;
	
	if (!file_exists($destination_dir))
	{
		$oldumask = umask(0); 
		mkdir($destination_dir, 0777);
		umask($oldumask);
	}
	
	$image_filename = $destination_dir . '/' . $name  . '.' . $extension; 
	
	return $image_filename;

}


// move files

$basedir = 'images';

$files = scandir($basedir);

foreach ($files as $filename)
{
	if (preg_match('/\.jpg$/', $filename))
	{	
		$f = str_replace('.jpg', '', $filename);
		
		$image_filename = create_image_filename($basedir, $f, 'jpg');
		
		$from = $basedir . '/' . $filename;
		$to = $image_filename;
		
		echo "$from $to\n";
		
		rename($from, $to);
	}
}

?>

