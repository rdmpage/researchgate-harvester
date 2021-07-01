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



$filename = '1.html';
$filename = '2.html';
$filename = 'rbge.html';
$filename = 'Australian_Museum.html';


$basedir = dirname(__FILE__) . '/list-html';

$files = scandir($basedir);

// debugging
//$files=array('vinuskz-15-0.html');


foreach ($files as $filename)
{
	echo "filename=$filename\n";
	if (preg_match('/.html/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);

		$dom = str_get_html($html);

		$people = array();

		/*
				<div class="nova-l-flex__item">
				  <a href="profile/Giuseppe-Signorino" class="nova-e-avatar nova-e-avatar--size-m nova-e-avatar--radius-full nova-e-avatar--framed nova-v-person-list-item__image"><img src="https://i1.rgstatic.net/ii/profile.image/635872147939331-1528615142581_Q64/Giuseppe-Signorino.jpg" alt="Giuseppe Signorino" class="nova-e-avatar__img"></a>
				</div>
		*/

		foreach ($dom->find('div[class=nova-l-flex__item] a') as $a)
		{
			$url = '';
			$image = '';
	
			//echo $a->href;
	
			foreach ($a->find('img') as $img)
			{
				$image = $img->src;
			}
	
			if (preg_match('/profile\/(?<name>.*)/', $a->href, $m))
			{
				$url = 'https://www.researchgate.net/profile/' . $m['name'];
			}
	
			echo $url . "\n";
	
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
			$f = str_replace('https://www.researchgate.net/profile/', '', $f);
			$f = str_replace('https://www.researchgate.net/scientific-contributions/', '', $f);
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
	
		}
	}
}


?>
