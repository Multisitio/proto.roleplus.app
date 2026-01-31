<?php
/**
 */
class _curl
{
    # 1.1
	static public function put_file_contents($url, $dest, $test=0)
	{
		$dir = dirname($dest) . '/';
        if ( ! realpath($dir)) {
            mkdir($dir, 0755, 1);
		}

		$name = basename($dest);
        
        if (realpath($dest)) {
            return;
		}

		$content = self::get_file_contents($url, $test);

        if ( ! $content) {
            return;
        }
		file_put_contents($dest, $content);

        return $content;
	}

    # 1.1
    static public function get_file_contents($url, $test=0)
    {
        $url = mb_strtolower($url);
        $ip = '137.101.' . rand(0, 255) . '.' . rand(0, 255);
        #$ip = $_SERVER['REMOTE_ADDR'];
		# El dominio servirá de referer
		$referer = _str::cut('http://', $url, '/');
		# Y el navegador del usuario para hacer pasar esto por un humano
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

        // Configura los encabezados requeridos
        $headers = [
            "User-Agent: MtgExampleApp/1.1", // Ajusta según tu aplicación
            "Accept: application/json;q=0.9,*/*;q=0.8" // Scryfall devuelve JSON, por lo que este encabezado es apropiado
        ];

        $options = [
            CURLOPT_AUTOREFERER		=> true,		// set referer on redirect
            CURLOPT_CONNECTTIMEOUT	=> 120,			// timeout on connect
            CURLOPT_ENCODING		=> "",			// handle all encodings
            CURLOPT_HEADER			=> false,		// don't return headers
            #CURLOPT_HTTPHEADER		=> ["REMOTE_ADDR: $ip", "X-Forwarded-For: $ip"],
            CURLOPT_HTTPHEADER		=> $headers,
            #CURLOPT_FILE			=> $file,		// using put_get_contents instead
            CURLOPT_FOLLOWLOCATION	=> true,		// follow redirects
            CURLOPT_MAXREDIRS		=> 10,			// stop after 10 redirects
            #CURLOPT_POST			=> 1,			// i am sending post data
            #CURLOPT_POSTFIELDS		=> $curl_data,	// this are my post vars
            CURLOPT_REFERER			=> $referer,	// the same site
            CURLOPT_RETURNTRANSFER	=> true,		// return web page
            #CURLOPT_SSL_VERIFYHOST	=> 0,			// don't verify ssl
            CURLOPT_SSL_VERIFYPEER	=> false,		// don't verify this too
            CURLOPT_TIMEOUT			=> 120,			// timeout on response
            CURLOPT_URL				=> $url,		// let's go
            CURLOPT_USERAGENT		=> $user_agent,	// the best simulation
            #CURLOPT_VERBOSE		=> 1			// for debug

		];
		$ch			= curl_init($url);
		curl_setopt_array($ch, $options);
		$content	= curl_exec($ch);
		$err		= curl_errno($ch);
		$errmsg		= curl_error($ch);
		$header		= curl_getinfo($ch);
		$http_code	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		# Si obtenemos un código http diferente a 200 salimos
		if ($http_code > 399) {
			return '';
		}
		curl_close($ch);

		if ($test) {
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            _var::die($header);
        }

        return $content;
    }

    # 2
    static function multiRequest($data, $options=[])
    {
        $curly = $result = [];
        $mh = curl_multi_init();
        foreach ($data as $url) {
            $curly[$url] = curl_init();

            /*if ( ! preg_match('/^http/i', $url)) {
                continue;
            }*/
            curl_setopt($curly[$url], CURLOPT_URL,            $url);
            curl_setopt($curly[$url], CURLOPT_HEADER,         0);
            curl_setopt($curly[$url], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curly[$url], CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

            if ( ! empty($options)) {
                curl_setopt_array($curly[$url], $options);
            }
            curl_multi_add_handle($mh, $curly[$url]);
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while($running > 0);

        foreach($curly as $url=>$c) {
            $result[$url] = curl_multi_getcontent($c);
            curl_multi_remove_handle($mh, $c);
        }
        curl_multi_close($mh);
        return $result;
    }
}