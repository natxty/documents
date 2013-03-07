<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! class_exists('Controller'))
{
	class Controller extends CI_Controller {}
}

class Auth extends Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
        $this->data = array();
        $this->data['sitetitle'] = $this->config->item('site_name');
        $this->data['base_url'] = $this->config->item('base_url');
        $this->data['content'] = "<p class=\"alert\">Page load error!</p>" ;
        $this->data['nav_menu'] = nav_menu(array('logged_in'=>$this->ion_auth->logged_in(),'is_admin'=>$this->ion_auth->is_admin()));
        $this->data['title'] = 'Admin';
	}

	//redirect if needed, otherwise display the user list
	function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin())
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect($this->config->item('base_url'), 'refresh');
		}
		else
		{

            $this->data['content'] = '';
            if ($this->session->flashdata('error')) { $this->data['error'] = $this->session->flashdata('error'); }
            elseif ($this->session->flashdata('message')) { $this->data['message'] = $this->session->flashdata('message'); }

            //menu
            $menu_items = array(
              site_url('auth/users/') =>'Users List'
              ,site_url('auth/create_user/') =>'Create New User'
              ,site_url('auth/change_password/') =>'Change Your Password'
            );
            $this->data['content'] .= full_menu(array('items'=>$menu_items));


            $this->load->view('admin_page',$this->data);

		}
	}

	//display users
	function users()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin())
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect(site_url('home'), 'refresh');
		}
		else
		{

			//list the users
			$this->data['users'] = $this->ion_auth->get_users_array();
            $this->data['message'] = null;
			$content = $this->load->view('auth/index', $this->data, true);
            $this->data['content'] = $content;
            $this->data['title'] = 'Users';
            $this->load->view('admin_page',$this->data);

		}
	}


	//log the user in
	function login()
	{
		$this->data['title'] = "Login";
		
		//validate form input
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{ //check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
			{ //if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect($this->config->item('base_url'), 'refresh');
			}
			else
			{ //if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{  //the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            if ($this->data['message']) {
                $this->data['error'] = strip_tags($this->data['message']);
                $this->data['message'] = false;
            } else { $this->data['message'] = "You need to be logged in to access this site.  Please login with your email address and password below."; }


			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);

			$content = $this->load->view('auth/login', $this->data, true);
            $this->data['content'] = $content;
            $this->load->view('admin_page',$this->data);
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them back to the page they came from
		redirect('auth', 'refresh');
	}

	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', 'Old Password', 'required');
		$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
		$user = $this->ion_auth->get_user($this->session->userdata('user_id'));

		if ($this->form_validation->run() == false)
		{ //display the form
			//set the flash data error message if there is one
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['old_password'] = array('name' => 'old',
				'id' => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array('name' => 'new',
				'id' => 'new',
				'type' => 'password',
			);
			$this->data['new_password_confirm'] = array('name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
			);
			$this->data['user_id'] = array('name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			);

			//render
			$content = $this->load->view('auth/change_password', $this->data, true);
            $this->data['content'] = $content;
            $this->load->view('admin_page',$this->data);

		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{ //if the password was successfully changed
                $this->data['content'] = "<p class=\"message\">".$this->ion_auth->messages()."</p>";
                $this->load->view('admin_page',$this->data);

                //$this->session->set_flashdata('message', $this->ion_auth->messages());
				//$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);
			//set any errors and display the form
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$content = $this->load->view('auth/forgot_password', $this->data, true);
            $this->data['content'] = $content;
            $this->load->view('admin_page',$this->data);
		}
		else
		{
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

			if ($forgotten)
			{ //if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code)
	{
		$reset = $this->ion_auth->forgotten_password_complete($code);

		if ($reset)
		{  //if the reset worked then send them to the login page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth/login", 'refresh');
		}
		else
		{ //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
			$activation = $this->ion_auth->activate($id, $code);
		else if ($this->ion_auth->is_admin())
			$activation = $this->ion_auth->activate($id);


		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		// no funny business, force to integer
		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->get_user_array($id);
			$content = $this->load->view('auth/deactivate_user', $this->data, true);
            $this->data['content'] = $content;
            $this->load->view('admin_page',$this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_404();
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

    function edit_user($user_id=null) {
        $tables = $this->config->item('tables','ion_auth');
        if (!$user_id) { $this->session->set_flashdata('error', "No user ID entered!"); redirect('auth','refresh'); }
        $query = $this->db->query("SELECT * FROM `".$tables['users']."` WHERE `id` = ".$this->db->escape($user_id)." LIMIT 1");
        $row = $query->row_array();
        if (!$row) { $this->session->set_flashdata('error', "Invaid user ID entered!"); redirect('auth','refresh'); }
        $query = $this->db->query("SELECT * FROM `".$tables['meta']."` WHERE `user_id` = ".$this->db->escape($user_id)." LIMIT 1");
        $row2 = $query->row_array();
        $row = array_merge($row2,$row);
        $this->data['function'] = 'edit';
        $this->data['user_id'] = $user_id;
        $this->data['user_data'] = $row;
        $this->create_edit_user();
    }

	function create_user()
	{
        $this->data['function'] = 'create';
        $this->data['user_id'] = null;
        $this->data['user_data'] = array();
        $this->create_edit_user();
    }

    //this function is for both creating and editing a user
	private function create_edit_user()
	{
		if ($this->data['function'] == 'edit') {
            $this->data['title'] = "Edit User";
            $this->data['form_action'] = "auth/edit_user/".$this->data['user_id'];
        } else {
            $this->data['title'] = "Create User";
            $this->data['form_action'] = "auth/create_user";
        }

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		//$this->form_validation->set_rules('phone1', 'First Part of Phone', 'optional|xss_clean|min_length[3]|max_length[3]');
		//$this->form_validation->set_rules('phone2', 'Second Part of Phone', 'optional|xss_clean|min_length[3]|max_length[3]');
		//$this->form_validation->set_rules('phone3', 'Third Part of Phone', 'optional|xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('company', 'Company Name', 'optional|xss_clean');
        if ($this->data['function'] == 'create') {
    		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
	    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        } else {
    		$this->form_validation->set_rules('password', 'Password', 'optional|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
	    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'optional');
        }
		$this->form_validation->set_rules('group_id', 'Group', 'required|is_natural');
        if ($this->data['function'] == 'edit') {

		    $this->form_validation->set_rules('user_id', 'User ID', 'required|is_natural_no_zero');
        }

		if ($this->form_validation->run() == true)
		{
			if ($this->data['function'] == 'edit') { $user_id = $this->input->post('user_id'); } else { $user_id = null; }
            $username = strtolower($this->input->post('first_name')) . '_' . strtolower($this->input->post('last_name'));
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
                'group_id' => $this->input->post('group_id'),
                'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->save_user($user_id, $username, $password, $email, $additional_data))
		{ //check to see if we are creating/saving the user
			//redirect them back to the admin page
			if ($this->data['function'] == 'edit') { $word = 'updated'; } else { $word = 'created'; }
            $this->session->set_flashdata('message', "User has successfully been $word.");
            redirect('auth', 'refresh');
		}
		else
		{ //display the create/edit user form
			//set the flash data error message if there is one
			$this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $values = array();
            $keys = array('first_name','last_name','email','company','group_id');
            foreach ($keys as $key) {
                if ($this->form_validation->run() == true) { $values[$key] = $this->form_validation->set_value($key); }
                elseif ($this->data['function'] == 'edit') { $values[$key] = $this->data['user_data'][$key]; }
                else { $values[$key] = null; }
            }

			$this->data['first_name'] = array('name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $values['first_name'],
			);
			$this->data['last_name'] = array('name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $values['last_name'],
			);
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $values['email'],
			);
			$this->data['company'] = array('name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $values['company'],
			);
			/*$this->data['phone1'] = array('name' => 'phone1',
				'id' => 'phone1',
				'type' => 'text',
                'size' => 3,
				'value' => $this->form_validation->set_value('phone1'),
			);
			$this->data['phone2'] = array('name' => 'phone2',
				'id' => 'phone2',
				'type' => 'text',
                'size' => 3,
				'value' => $this->form_validation->set_value('phone2'),
			);
			$this->data['phone3'] = array('name' => 'phone3',
				'id' => 'phone3',
				'type' => 'text',
                'size' => 4,
				'value' => $this->form_validation->set_value('phone3'),
			);*/
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array('name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
            //get member type options
            $tables = $this->config->item('tables','ion_auth');
            $options = array();
            $query = $this->db->Query("SELECT * FROM `".$tables['groups']."` ORDER BY `description`");
            foreach ($query->result() as $row) { $options[$row->id] = $row->description; }
            $this->data['group_id_dropdown'] = form_dropdown('group_id',$options,$values['group_id']);
            if ($this->data['function'] == 'edit') { $this->data['hidden'] = form_hidden('user_id', $this->data['user_id']); } else { $this->data['hidden'] = ''; }
            if ($this->data['function'] == 'edit') { $this->data['button_caption'] = 'Edit User'; } else { $this->data['button_caption'] = 'Create User'; }
            if ($this->data['function'] == 'edit') { $this->data['pw_note'] = '<p>Leave the Password field blank unless you want to update it.</p>'; } else { $this->data['pw_note'] = ''; }

			$content = $this->load->view('auth/create_user', $this->data, true);
            $this->data['content'] = $content;
            $this->load->view('admin_page',$this->data);
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
				$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
