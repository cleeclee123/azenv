<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>AZ Environment variables 1.04</title>
</head>

<body>
<pre>
<?php
##########################################################################
#	
#	AZ Environment variables 1.04 © 2004 AZ
#	Civil Liberties Advocacy Network
#	http://clan.cyaccess.com   http://clanforum.cyaccess.com
#	
#	AZenv is written in PHP & Perl. It is coded to be simple,
#	fast and have negligible load on the server.
#	AZenv is primarily aimed for programs using external scripts to
#	verify the passed Environment variables.
#	Only the absolutely necessary parameters are included.
#	AZenv is free software; you can use and redistribute it freely.
#	Please do not remove the copyright information.
#
##########################################################################

// original azenv.php logic
// foreach ($_SERVER as $header => $value) {
// 	if (
// 		strpos($header, 'REMOTE') !== false || strpos($header, 'HTTP') !== false ||
// 		strpos($header, 'REQUEST') !== false
// 	) {
// 		echo $header . ' = ' . $value . "\n";
// 	}
// }

// Additions/Modifications Below

/**
 * Get all HTTP header key/values as an associative array for the current request.
 * Reference: https://github.com/ralouphie/getallheaders
 * 
 * @return string[string] The HTTP header key/value pairs.
 */
function getRequestHeaders()
{
	$headers = array();

	$copy_server = array(
		'CONTENT_TYPE'   => 'Content-Type',
		'CONTENT_LENGTH' => 'Content-Length',
		'CONTENT_MD5'    => 'Content-Md5',
	);

	foreach ($_SERVER as $key => $value) {
		if (strpos($key, 'REMOTE') !== false || strpos($key, 'HTTP') !== false || strpos($key, 'REQUEST') !== false) {
			$key = str_replace(['REMOTE_', 'HTTP_', 'REQUEST_'], "", $key);
			if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
				$headers[$key] = $value;
			}
		}		
		elseif (isset($copy_server[$key])) {
			$headers[$copy_server[$key]] = $value;
		}
	}

	if (!isset($headers['Authorization'])) {
		if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
			$headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
		} elseif (isset($_SERVER['PHP_AUTH_USER'])) {
			$basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
			$headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
		} elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
			$headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
		}
	}

	return $headers;
}

// to json
$jsonRes = json_encode(getRequestHeaders());
echo ($jsonRes);
?>

</pre>
</body>
</html>