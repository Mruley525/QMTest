<pre><?php




$datalist = array();
$curl_array = array();
$mh = curl_multi_init();

for ($i=0; $i<10; $i++) {
	$c = curl_init( 'https://api.github.com/search/repositories?q=is:public+language:php&count=100&sort=stars&order=desc&count=1000&per_page=20&page='.($i+1) );
	curl_setopt($c, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Content-Type: application/json',
		'User-Agent: mruley525'
	));
	curl_setopt($c, CURLOPT_HEADER, 1);
	curl_setopt($c, CURLOPT_VERBOSE, 1);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	
	array_push($curl_array, $c);
	curl_multi_add_handle($mh, $curl_array[$i]);
}

$running = null;
do {
	curl_multi_exec($mh, $running);
} while ($running);

for ($i=0; $i<10; $i++) {
	curl_multi_remove_handle($mh, $curl_array[$i]);
	$response = curl_multi_getcontent($curl_array[$i]);
	$header_size = curl_getinfo($curl_array[$i], CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	print_r($header);
	print '<hr>';
	print_r($body);
	
	$curldata = json_decode($body,1);
	
	if (array_key_exists('items', $curldata)) {
		array_push($datalist, ...$curldata['items']);
	}
}
curl_multi_close($mh);
 
print_r($datalist);









/*

$c = curl_init();
$curl = 'https://api.github.com/search/repositories?q=is:public+language:php&count=100&sort=stars&order=desc&count=1000&per_page=20';
$datalist = array();

//for($i=1; $i<=10; $i++) {
	curl_setopt($c, CURLOPT_URL, $curl);
	curl_setopt($c, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Content-Type: application/json',
		'User-Agent: mruley525'
	));
	curl_setopt($c, CURLOPT_HEADER, 1);
	curl_setopt($c, CURLOPT_VERBOSE, 1);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	
	//echo $i;
	
	$response = curl_exec($c);
	$header_size = curl_getinfo($c, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	print_r($header);
	print '<hr>';
	print_r($body);
	
	$curldata = json_decode($body,1);
	
	if (array_key_exists('items', $curldata)) {
		array_push($datalist, ...$curldata['items']);
		//$datalist = array_merge($datalist, $curldata['items']);
	}
	
	print_r($curldata);
	
	//$curl = $curldata->pagination->next_url;
//}
curl_close($c);

print_r( $datalist );
//print_r( json_decode($content,1) );
//$api = json_decode($content);
//print($api->open_issues_count);

/*
public function fetchLikes() 
{
    $url = 'https://api.instagram.com/v1/users/self/media/liked?count=100&access_token=' . $access_token;

    while (isset($url) && $url != '') {  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $jsonData = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($jsonData);

        foreach ($response->data as $post) {

            echo $post->user->username . "<br>";
        }

        $url = $response->pagination->next_url;
    }
}
*/


?></pre>

