<?php
class Repositories_model extends CI_Model 
{
	function __construct()
	{
		$this->load->database();
		
		// If we don't have the table then build it
		$query="CREATE TABLE IF NOT EXISTS `GitHubRepositories` (
				`RepositoryID` int(11) NOT NULL,
				`Name` varchar(100),
				`OwnerUsername` varchar(100),
				`CreatedDate` datetime,
				`LastPushDate` datetime,
				`Description` text,
				`Stars` int(11),
				`Updated` boolean DEFAULT 1,
				PRIMARY KEY (`RepositoryID`))";
		$this->db->query($query);
	}
	
	function update_records() {
		$datalist = array(); // Holds all the records we find
		$curl_array = array(); // An array of the different curl objects
		$mh = curl_multi_init(); // The multi-handle curl object

		for ($i=0; $i<10; $i++) {
			// We need to initial every curl object
			$c = curl_init( 'https://api.github.com/search/repositories?q=is:public+language:php&count=100&sort=stars&order=desc&count=1000&per_page=100&page='.($i+1) );
			curl_setopt($c, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Content-Type: application/json',
				'User-Agent: mruley525'
			));
			curl_setopt($c, CURLOPT_HEADER, 1);
			curl_setopt($c, CURLOPT_VERBOSE, 1);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			
			// Finally put the new curl object on the array and in the multi-handle
			array_push($curl_array, $c);
			curl_multi_add_handle($mh, $curl_array[$i]);
		}

		// Here we actually run the multi-handle
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);

		// Time to process our results
		for ($i=0; $i<10; $i++) {
			curl_multi_remove_handle($mh, $curl_array[$i]);
			$response = curl_multi_getcontent($curl_array[$i]);
			// Trim off the header
			$header_size = curl_getinfo($curl_array[$i], CURLINFO_HEADER_SIZE);
			$header = substr($response, 0, $header_size);
			$body = substr($response, $header_size);
			// And store the data in a simple table
			$curldata = json_decode($body,1);
			// Once we're sure it contains goodies, we add it to our datalist
			if (array_key_exists('items', $curldata)) {
				array_push($datalist, ...$curldata['items']);
			}
			// Close the now completed curl
			curl_close($curl_array[$i]);
		}
		// And finally close the multi-handle once it's empty
		curl_multi_close($mh);

		// Make sure we have records to cycle through (in case curl failed)
		if (count($datalist) > 0) {
			echo count($datalist).' records retrieved from curl, some repeats possible<br>';
			// We have records so we first set all the records to not be updated
			$query = 'UPDATE GitHubRepositories SET Updated = FALSE';
			$this->db->query($query);
			
			// Now cycle through all the 
			foreach ($datalist as $repodata) {
				// See if the repo is there
				$data = array();
				$query = $this->db->get_where('GitHubRepositories', array('RepositoryID' => $repodata['id']));
				$data = $query->row_array();
				
				$query = '';
				if (empty($data)) {
					// If we find nothing then insert the record
					$query="INSERT INTO GitHubRepositories VALUES('".$repodata['id']."', '".$repodata['name']."', '".$repodata['owner']['login']."', '".$repodata['created_at']."', '".$repodata['pushed_at']."', '".addslashes($repodata['description'])."', '".$repodata['stargazers_count']."', TRUE)";
				} else {
					// Update the information
					$query="UPDATE GitHubRepositories SET Name='".$repodata['name']."', OwnerUsername='".$repodata['owner']['login']."', CreatedDate='".$repodata['created_at']."', LastPushDate='".$repodata['pushed_at']."', Description='".addslashes($repodata['description'])."', Stars='".$repodata['stargazers_count']."', Updated=TRUE WHERE RepositoryID='".$repodata['id']."'";
				}
				$this->db->query($query);
			}
			
			// And finally anyone not updated gets the axe
			$query = 'DELETE FROM GitHubRepositories WHERE Updated=FALSE';
			$this->db->query($query);
		}
	}
	
	function get_records($id = FALSE)
	{
		if ($id === FALSE)
		{
			$this->db->order_by('Name', 'ASC');
			$query = $this->db->get('GitHubRepositories');
			return $query->result_array();
		}

		$query = $this->db->get_where('GitHubRepositories', array('RepositoryID' => $id));
		return $query->row_array();
	}
	
}