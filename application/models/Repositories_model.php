<?php
class Repositories_model extends CI_Model 
{
	function function __construct()
	{	$this->load->database();
		
		// If we don't have the table then build it
		$query="CREATE TABLE IF NOT EXISTS `GitHubRepositories` (
				`RepositoryID` int(11) NOT NULL,
				`Name` varchar(100),
				`OwnerUsername` varchar(100),
				`CreatedDate` datetime,
				`LastPushDate` datetime,
				`Description` text,
				`Stars` int(11),
				`Updated` boolean,
				PRIMARY KEY (`RepositoryID`)";
		$this->db->query($query);
	}
	
	function updateRecords() {
		// First, we set all the records to not be updated
		$query = 'UPDATE GitHubRepositories SET Updated = FALSE';
		$this->db->query($query);
		// And setup the curl resource
		$ch = curl_init();
		
		// Now we loop through 10 pages of 1000 results (ugh)
		for ($i=1; $i<=10; $i++) {
			$c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Content-Type: application/json',
				'User-Agent: mruley525'
			));
			curl_setopt($c, CURLOPT_URL, 'https://api.github.com/search/repositories?q=is:public+language:php&sort=stars&order=desc&page='.$i.'&per_page=20');

			$content = curl_exec($c);
			curl_close($c);

			$curldata = json_decode($content,1);
			foreach ($curldata['items'] as $repodata) {
				// See if the repo is there
				$data = array();
				$query = $this->db->get_where('GitHubRepositories', array('RepositoryID' => $repodata['id']));
				$data = $query->row_array();
				
				if (empty($data)) {
					// If we find nothing then insert the record
					$query="INSERT INTO GitHubRepositories VALUES('".$repodata['id']."', '".$repodata['name']."', '".$repodata['owner']['login']."', '".$repodata['created_at']."', '".$repodata['pushed_at']."', '".$repodata['description']."', '".$repodata['stargazers_count']."', TRUE)";
					$this->db->query($query);
				} else {
					// Update the information
					$query="UPDATE GitHubRepositories SET Name='".$repodata['name']."', OwnerUsername='".$repodata['owner']['login']."', CreatedDate='".$repodata['created_at']."', LastPushDate='".$repodata['pushed_at']."', Description='".$repodata['description']."', Stars='".$repodata['stargazers_count']."', Updated=TRUE WHERE RepositoryID='".$repodata['id']."'";
				}
			}
		}
		
		// And finally anyone not updated gets the axe
		$query = 'DELETE FROM GitHubRepositories WHERE Updated = FALSE';
		$this->db->query($query);
	}
	
	
	/*Insert*/
	function saverecords($first_name,$last_name,$email)
	{

	$query="insert into crud values('','$first_name','$last_name','$email')";
	$this->db->query($query);
	}
	
}