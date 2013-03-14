<?php

namespace Spassets;

class Spassets
{
	public $minify = false;  					// minify
	public $cacheTime = 600; 					// seconds to cache
	public $basePath = "/";						// path prepended
	public $assetPath = "app/assets"; 		// path to assets
	public $cachePath = "app/assets/cache";		// path to cache location
	public static $types = array('css','js');	// file types supported

	public function buildAssets($type, $assets)
	{
		if (in_array($type, self::$types))
		{
			$file = ($this->minify) ? md5(json_encode($assets)).".min.".$type : md5(json_encode($assets)).".".$type;
				
			if (!file_exists($this->cachePath."/".$file) || (filemtime($this->cachePath."/".$file)+$this->cacheTime) < time())
			{
				$content = "";
				foreach ($assets as $asset)
				{
					$content .= $this->getContents($asset);
				}
				
				file_put_contents($this->cachePath."/".$file, $content);
			}
			
			return $file;
		}
		else
		{
			throw new SlimAssertsException("Asset must be one of: ".implode(', ', self::$types));
		}
	}
	
	public function getContents($asset)
	{
		if (file_exists($this->assetPath.$asset))
		{
			$contents = ($this->minify) ? $this->minify(file_get_contents($this->assetPath.$asset)) : file_get_contents($this->assetPath.$asset);
			return $contents;
		}
	}

	public function minify($content)
	{
		// remove comments
		$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
		// remove tabs, consecutivee spaces, newlines, etc.
		$content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '	', '	'), '', $content);
		// remove single spaces
		$content = str_replace(array(" {", "{ ", "; ", ": ", " :", " ,", ", ", ";}"), array("{", "{", ";", ":", ":", ",", ",", "}"), $content);

		return $content;
	}

	public function printAssets($type, $assets)
	{
		$file = $this->buildAssets($type, $assets);

		switch ($type)
		{
			case 'css':
				echo "<link href='/".$this->cachePath."/".$file."' rel='stylesheet' type='text/css' />";
				break;
			case 'js':
				echo "<script src='/".$this->cachePath."/".$file."' type='text/javascript'></script>";
				break;
		}
	}
}

class SlimAssertsException extends \Exception {};
?>