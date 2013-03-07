<?php echo form_open("auth/change_password");?>

  <table>
      <tr><th>Old Password</th><td>
      <?php echo form_input($old_password);?>
      </td></tr>
      
      <tr><th>New Password</th><td>
      <?php echo form_input($new_password);?>
      </td></tr>
      
      <tr><th>Confirm New Password</th><td>
      <?php echo form_input($new_password_confirm);?>
      </td></tr>
      
      <?php echo form_input($user_id);?>
      <tr><th>&nbsp;</th><td><?php echo form_submit('submit', 'Change');?></td></tr>
  </table>
<?php echo form_close();?>