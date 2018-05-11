<?php
function url_shorten( $url, $length = 35 ) {
    $stripped = str_replace( array( 'https://', 'http://', 'www.' ), '', $url );
    $short_url = rtrim( $stripped, '/\\' );

    if ( strlen( $short_url ) > $length ) {
        $short_url = substr( $short_url, 0, $length - 3 ) . '&hellip;';
    }
    return $short_url;
}

function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}

function flatten($elements, $depth) {
    $result = array();

    foreach ($elements as $element) {
        $element['depth'] = $depth;

        if (isset($element['child'])) {
            $children = $element['child'];
            unset($element['child']);
        } else {
            $children = null;
        }

        $result[] = $element;

        if (isset($children)) {
            $result = array_merge($result, flatten($children, $depth + 1));
        }
    }

    return $result;
}

function metersToMiles($i) {
     return number_format($i*0.000621371192, 0) . ' miles';
}
function milesToMeters($i) {
     return number_format($i*1609.344, 0) . ' km';
}

function format_money($price, $currency) {
	$placement = 'before';
	try {
		$currency = new \Gerardojbaez\Money\Currency($currency);
		$currency->setSymbolPlacement($placement);
		if($price > 1000) {
			$currency->setPrecision(0);
		}
		$money = new \Gerardojbaez\Money\Money($price, $currency);
		return $money->format();
	} catch(\Exception $e) {
		if($placement == 'before')
			return $currency . ' '. number_format($price, 2);
		else
			return number_format($price, 2) . ' ' . $currency ;
	}
}
function getDir($id, $levels_deep = 32) {
    $file_hash   = md5($id);
    $dirname     = implode("/", str_split(
        substr($file_hash, 0, $levels_deep)
    ));
    return $dirname;
}
function store($dirname, $filename) {
    return $dirname . "/" . $filename;
}


function jsdeliver_combine($theme = 'default', $type = 'js') {
    $jsdeliver_js = "";
    if(file_exists(themes_path($theme.'/jsdeliver.json'))) {
        $files = json_decode(file_get_contents(themes_path($theme.'/jsdeliver.json')), true);
        $jsdeliver_js = implode(",", $files[$type]);
    }
    return $jsdeliver_js;
}

function array_to_string($input, $level = 0) {
    $array = json_decode(json_encode($input), true);
    $text = "";
    foreach($array as $k => $v) {
        if(is_array($v)) {
            $level = $level+1;
            $text .= str_repeat("&#8212;", $level) . " " . $k."\n";
            $text .= array_to_string($v, $level);
        } else{
            if($v)
                $text .= str_repeat("&#8212;", $level+1) . " $k: $v\n";
        }
    }
    return $text;
}


function save_language_file($file, $strings) {
    $file = resource_path("views/langs/$file.php");
    $output = "<?php\n\n";
    foreach($strings as $string) {
        $output .= "__('".$string."');\n";
    }
    \File::put($file, $output);

}

function menu($location = null, $locale = null) {
    if(!$locale)
        $locale = LaravelLocalization::getCurrentLocale();
    if(!$location)
        $location = 'top';
    $menu = \App\Models\Menu::where('location', $location)->where('locale', $locale)->first();
    if($menu)
        return $menu->items;
    return [];
}

function _l($string) {
    return __($string);
}

function _p($string, $number = 2) {

    if((int) $number == 1) {
        return __($string);
    }

    if(!str_contains(__($string.'_plural'), "_plural")) {
        return __($string.'_plural');
    }

    try {
        return str_plural(__($string));
    } catch(\Exception $e) {

    }

    return __($string);
}

