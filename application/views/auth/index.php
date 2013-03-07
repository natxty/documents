	<p>Below is a list of the users:</p>
	
	<table cellpadding=0 cellspacing=10>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Group</th>
			<th>Status</th>
            <th>Actions</th>
		</tr>
		<?php foreach ($users as $user):?>
			<tr>
				<td><?php echo $user['first_name']?></td>
				<td><?php echo $user['last_name']?></td>
				<td><?php echo $user['email'];?></td>
				<td><?php echo $user['group_description'];?></td>
				<td><?php echo ($user['active']) ? 'Active' : 'Inactive' ?></td>
                <td><?php
                    if ($user['active']) {
                        echo anchor("auth/edit_user/".$user['id'], 'Edit User').'<br />';
                        echo anchor("auth/deactivate/".$user['id'], 'Deactivate');
                    } else {
                        echo anchor("auth/activate/". $user['id'], 'Activate');
                    } ?></td>
			</tr>
		<?php endforeach;?>
	</table>
