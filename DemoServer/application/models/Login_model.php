<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create_account($username, $password, $email, $phone)
	{
		$user = array (
			'username' => $username,
			'password' => $password,
			'email' => $email,
            'phone' => $phone,
            'profile_image' => 'https://infs3202-b51d6e13.uqcloud.net/Demo/data/image/profile/icon-default.png',
            'contactname' => '',
            'birthday' => '',
            'gender' => '',
            'address'=> '',
            'website' => '',
            'state' => '',
		);
		if ($array = $this->mongo_db->get_where('users', array ('username' => $username))) {
			return 'Duplicated username';
		} else if ($array = $this->mongo_db->get_where('users', array ('email' => $email))) {
		    return 'Duplicated email';
        } else {
			$this->mongo_db->insert('users', $user);
			return 'Success';
		}
	}

	public function get_current_user($username)
    {
        $user = $this->mongo_db->get_where('users', array('username'=> $username));
        return $user[0];
    }

    public function set_image($url)
    {
        if ($url != null) {
            $this->mongo_db->where('username', $this->session->userdata('username'));
            $this->mongo_db->set('profile_image', $url);
            $this->mongo_db->update('users');
            return true;
        } else {
            return false;
        }
    }

    public function edit_profile($contactname, $birthday, $phone, $gender, $website,
                           $address, $state)
    {
        $this->mongo_db->where('username', $this->session->userdata('username'));
        $this->mongo_db->set('contactname', $contactname);
        $this->mongo_db->set('birthday', $birthday);
        $this->mongo_db->set('phone', $phone);
        $this->mongo_db->set('gender', $gender);
        $this->mongo_db->set('website', $website);
        $this->mongo_db->set('address', $address);
        $this->mongo_db->set('state', $state);
        $this->mongo_db->update('users');
        redirect('profile/index');
    }

    public function update_password($username, $password)
    {
        $this->mongo_db->where('username', $username);
        $this->mongo_db->set('password', $password);
        $this->mongo_db->update('users');
    }
}
