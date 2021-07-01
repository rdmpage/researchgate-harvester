<?php

error_reporting(E_ALL);

// Date timezone
date_default_timezone_set('UTC');


require_once(dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	
	
	curl_close($ch);
	
	
	
	return $data;
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



$filename = 'Keita-Matsumoto-3.html';
$filename = '1.html';
$filename = 'giribet.html';
$filename = 'Bong-Kyu-Byun.html';
$filename = 'Neal EVENHUIS.html';
$filename = 'Zhi-Qiang Zhang.html';
$filename = 'V. DEEPAK.html';
$filename = 'Sandra KNAPP.html';
$filename = 'Alfred NEWTON.html';
$filename = 'Iorgu PETRESCU.html';
$filename = 'Paul HEBERT.html';
$filename = 'Feng_Zhang38.html';
$filename = 'Daniel_Burckhardt2.html';



$basedir = dirname(__FILE__) . '/person-html';

$files = scandir($basedir);

// debugging
//$files=array('vinuskz-15-0.html');

//$files=array('r.html');


foreach ($files as $filename)
{
	echo "filename=$filename\n";
	if (preg_match('/.html/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);

		$dom = str_get_html($html);

		$people = array();

		foreach ($dom->find('li[class=nova-e-list__item] a') as $a)
		{
			echo "x\n";
		
			$url = '';
			$image = '';
	
			foreach ($a->find('meta[itemprop=image]') as $meta)
			{
				$image = $meta->content;
			}

			foreach ($a->find('meta[itemprop=url]') as $meta)
			{
				$url = $meta->content;
			}
			
			foreach ($a->find('img') as $img)
			{
				$image = $img->src;
			}

			if ($url == '')
			{
				$url = $a->href;
			}
			
	
			echo $url . "\n";
			echo $image . "\n";
	
			if ($url != '' && $image != '')
			{
				$people[$url] = $image;
			}
		}	

		echo '<table>';
		foreach ($people as $url => $image)
		{
			echo '<tr>';
			echo '<td>' . $url . '</td>';
			echo '<td><img src="' . $image . '"></td>';
			echo '</tr>';
			echo "\n";
		}
		echo '</table>';

		$count = 1;
		foreach ($people as $url => $image)
		{
			$f = $url;
			
			if ($f == 'https://www.researchgate.net/null')
			{
			}
			else
			{
			
				$f = str_replace('https://www.researchgate.net/profile/', '', $f);
				$f = str_replace('https://www.researchgate.net/scientific-contributions/', '', $f);
				$f = str_replace('https://www.researchgate.net/scientific-contributions/', '', $f);
				$f = str_replace('profile/', '', $f);
				$f = str_replace('scientific-contributions/', '', $f);
				
				$f = preg_replace('/\?.*$/', '', $f);
				
				
				$ok = false;
				
				$image_filename = get_image_filename('images', $f, 'jpg');
				
				if (file_exists($image_filename))
				{
					$ok = true;
				}
				
				if (!$ok)
				{
					$image_filename = create_image_filename('images', $f, 'jpg');
					$data = get($image);
	
					file_put_contents($image_filename, $data);
	
					// Give server a break every 10 items
					if (($count++ % 5) == 0)
					{
						$rand = rand(1000000, 3000000);
						echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
						usleep($rand);
					}

				}				
				
				/*
				
				$f .= '.jpg';
	
				$f = 'images/' . $f;
	
				if (!file_exists($f))
				{	
					$data = get($image);
	
					file_put_contents($f, $data);
	
					// Give server a break every 10 items
					if (($count++ % 5) == 0)
					{
						$rand = rand(1000000, 3000000);
						echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
						usleep($rand);
					}
				}
				*/
			}
	
		}
	}
}


?>
