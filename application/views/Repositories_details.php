
<h1><?php echo $title; ?></h1>

<p><b>Full Name:</b> <?php echo $repo_info['OwnerUsername'] .'/'. $repo_info['Name']; ?></p>
<p><b>Repository ID:</b> <?php echo $repo_info['RepositoryID']; ?></p>
<p><b>Owner Username:</b> <?php echo $repo_info['OwnerUsername']; ?></p>
<p><b>Create:</b> <?php echo date('M j Y g:i A', strtotime($repo_info['CreatedDate'])); ?></p>
<p><b>Last Push:</b> <?php echo date('M j Y g:i A', strtotime($repo_info['LastPushDate'])); ?></p>
<p><b>Stars:</b> <?php echo $repo_info['Stars']; ?></p>
<p><b>Description:</b> <?php echo $repo_info['Description']; ?></p>

<p><a href="<?php echo base_url(); ?>">&lt;- Return</a></p>

