	<h1>Edit User</h1>
	<p>Please enter the user's information below.</p>

  <table>
    <?php echo form_open("admin/edit_user/".$this->uri->segment(3));?>
      <tr><th>First Name</th><td>
      <?php echo form_input($firstName);?>
      </td></tr>
      
      <tr><th>Last Name</th><td>
      <?php echo form_input($lastName);?>
      </td></tr>
      
      <tr><th>Company Name</th><td>
      <?php echo form_input($company);?>
      </td></tr>
      
      <tr><th>Email</th><td>
      <?php echo form_input($email);?>
      </td></tr>
      
      <tr><th>Phone</th><td>
      <?php echo form_input($phone1);?>-<?php echo form_input($phone2);?>-<?php echo form_input($phone3);?>
      </td></tr>
      
      <tr><th>
      	<input type=checkbox name="reset_password"> <label for="reset_password">Reset Password</label>
      </td></tr>
      
      <?php echo form_input($user_id);?>
      <tr><th><th>&nbsp;</th><td><?php echo form_submit('submit', 'Submit');?></td></tr>
  </table>
      
    <?php echo form_close();?>

