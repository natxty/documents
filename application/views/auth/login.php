<?php echo form_open("auth/login");?>
	
  <table>        
  <tr>
  	<th><label for="email">Email:</label></th>
  	<td><?php echo form_input($email);?></td>
  </tr>
  
  <tr>
  	<th><label for="password">Password:</label></th>
  	<td><?php echo form_input($password);?></td>
  </tr>
  
  <!-- <tr>
   <th>&nbsp;</th>
      <td>
      //<?php echo form_checkbox('remember', '1', FALSE);?><label for="remember"> Remember Me</label>
   
      </td>
  </tr> -->
  
  
  <tr><th>&nbsp;</th><td><?php echo form_submit('submit', 'Login');?></td></tr>
</table>
  
<?php echo form_close();?>
