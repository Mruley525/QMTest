<a href="<?php echo site_url('repositories/update'); ?>"><button class="form-control">Signup</button></a>

<h1><?php echo $title; ?></h1>

<table border="0" cellspacing="0" cellpadding="0" class="repo_list">
	<tr>
		<th>Name</th>
		<th>Owner</th>
		<th>Created</th>
		<th>Stars</th>
	</tr>
<?php foreach ($repolist as $repo_item): ?>

	<tr>
		<td><a href="<?php echo site_url('details/'.$repo_item['RepositoryID']); ?>"><?php echo $repo_item['Name']; ?></a></td>
		<td><?php echo $repo_item['OwnerUsername']; ?></td>
		<td><?php echo $repo_item['CreatedDate']; ?></td>
		<td><?php echo $repo_item['Stars']; ?></td>
	</tr>

<?php endforeach; ?>
</table>


