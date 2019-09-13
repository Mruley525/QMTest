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
		// Setup the curl resource
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_USERPWD, "mruley525:C0d3Sh4r3");
		curl_setopt($c, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',
			'User-Agent: mruley525'
		));
		
		// Create an empty array to hold the data
		$datalist = array();
		
		// Now we loop through 10 pages of 1000 results (ugh)
		for ($i=1; $i<=10; $i++) {
			// Get the data from the url and translate to something more workable
			curl_setopt($c, CURLOPT_URL, 'https://api.github.com/search/repositories?q=is:public+language:php&sort=stars+name&order=desc&per_page=100&page='.$i);
			$content = curl_exec($c);
			$curldata = json_decode($content,1);
			
			// If we got items then tack them onto our data list
			if (array_key_exists('items', $curldata)) {
				array_push($datalist, ...$curldata['items']);
				//$datalist = array_merge($datalist, $curldata['items']);
			}
		}
		// Curl is done so close it
		curl_close($c);

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