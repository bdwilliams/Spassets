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
					$content .= $this->getContents($type, $asset);
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
	
	public function getContents($type, $asset)
	{
		if (file_exists($this->assetPath.$asset))
		{
			if ($type == 'css')
			{
				return ($this->minify) ? $this->minify(file_get_contents($this->assetPath.$asset)) : file_get_contents($this->assetPath.$asset);
			}
			else
			{
				return file_get_contents($this->assetPath.$asset);	
			}			
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

	public function define($array)
	{
		$assetArray = array();
		
		if (is_array($array) && count($array) > 0)
		{
			foreach ($array as $type => $assets)
			{
				foreach ($assets as $name => $asset)
				{
					switch ($type)
					{
						case 'css':
							$assetArray[$type][$name] = "<link href='/".$this->cachePath."/".$this->buildAssets($type, $asset)."' rel='stylesheet' type='text/css' />";
							break;
						case 'js':
							$assetArray[$type][$name] = "<script src='/".$this->cachePath."/".$this->buildAssets($type, $asset)."' type='text/javascript'></script>";
							break;
					}
				}
			}
			
			return $assetArray;
		}
	}
	
	public function printAssets($type, $assets)
	{
		$file = $this->buildAssets($type, $assets);

		switch ($type)
		{
			case 'css':
				return "<link href='/".$this->cachePath."/".$file."' rel='stylesheet' type='text/css' />";
				break;
			case 'js':
				return "<script src='/".$this->cachePath."/".$file."' type='text/javascript'></script>";
				break;
		}
	}
}

class SlimAssertsException extends \Exception {};
?>
