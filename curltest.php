<pre><?php

$c = curl_init();
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_HTTPHEADER, array(
	'Accept: application/json',
	'Content-Type: application/json',
	'User-Agent: mruley525'
));
curl_setopt($c, CURLOPT_URL, 'https://api.github.com/search/repositories?q=is:public+language:php&sort=stars&order=desc&page=1&per_page=20');

$content = curl_exec($c);
curl_close($c);

print_r( json_decode($content,1) );
//$api = json_decode($content);
//print($api->open_issues_count);

?></pre>

