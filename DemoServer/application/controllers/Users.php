<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->model('Mysql_model');
        $this->load->model('File_model');
        $this->load->helper('captcha');
    }

    public function index()
    {
        $data['videos'] = $this->Mysql_model->all_user_video();
        $this->load->view('templates/navigator');
        $this->load->view('users/index', $data);
    }

    public function login()
    {
        $this->load->view('templates/navigator');
        // The state is logged out.
        if (!$this->session->userdata('logged')) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $captcha = $this->input->post('captcha');
            // If the username and password is valid
            if (isset($username) && isset($password) && isset($captcha)) {
                if ($captcha != $this->session->userdata('captcha')) {
                    $data['flag'] = 'invalid captcha';
                    $data['captchaImg'] = $this->generate_captcha();
                    $this->load->view('users/login_form', $data);
                } else {
                    // If the username and password are correct.
                    $check_account = $this->Mysql_model->check_account($username, $password);
                    if ($check_account == 'valid account') {
                        // remember me
                        $remember = $this->input->post('remember');
                        $this->set_remember_me($remember, $username, $password);
                        $user_data = array(
                            'username' => $username,
                            'logged' => true
                        );
                        $this->session->set_userdata($user_data);
                        redirect('users/index');
                    } else {         // If the username and password are incorrect.
                        $data['flag'] = $check_account;
                        $data['captchaImg'] = $this->generate_captcha();
                        $this->session->set_userdata('register_fail', false);
                        $this->load->view('users/login_form', $data);
                    }
                }
            } else {
                $data['captchaImg'] = $this->generate_captcha();
                $this->load->view('users/login_form', $data);
            }
        } else {    // If the status is logged in.
            redirect('users/index');
        }
    }

    public function register()
    {
        $this->load->view('templates/navigator');
        // If the status is logged in
        if (!$this->session->userdata('logged')) {
            $register_username = $this->input->post('register_username');
            $register_password = $this->input->post('register_password');
            $rpassword = $this->input->post('rpassword');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');

            // If the register information is valid
            if (isset($register_username) && isset($register_password) && isset($rpassword)
                && isset($email) && isset($phone)) {
                $data['username'] = $register_username;
                $check = $this->Login_model->create_account($register_username, $register_password, $email, $phone);
                if ($check == 'Success') {
                    $verification_key = $this->config_email_verification($register_username, $email);
                    $this->Mysql_model->create_user($register_username, $register_password, $email, $phone, $verification_key);
                    $this->File_model->upload_image('data/image/profile/icon.jpg', $register_username, 'icon.jpg');
                    $this->File_model->upload_image('data/image/profile/icon-1.jpeg', $register_username, 'icon-1.jpeg');
                    redirect('users/index');
                } else {
                    $data['flag'] = $check;
                    $this->session->set_flashdata('register_fail', true);
                    $this->load->view('users/login_form', $data);
                }
            } else {    // If the register information is invalid
                $this->load->view('users/login_form');
            }
        } else {    //If the status is logged out
            redirect('users/index');
        }
    }

    public function logout()
    {
        $this->session->set_userdata('register_fail', false);
        $this->session->unset_userdata('logged');
        $this->session->unset_userdata('username');
        $this->session->sess_destroy();
        redirect('users/login');
    }

    public function flag()
    {
        if ($this->session->flashdata('register_fail')) {
            echo json_encode('register_fail');
        }
    }

    // Set the remember me information and cookie
    public function set_remember_me($remember,$username, $password)
    {
        if ($remember) {
            $this->input->set_cookie('username', $username, 24 * 60 * 60);
            $this->input->set_cookie('password', $password, 24 * 60 * 60);
        } else {
            $this->input->set_cookie('username', '');
            $this->input->set_cookie('password', '');
        }
    }

    // Controller function to set profile image by dragging the picture
    public function set_profile_image ()
    {
        $url = $this->input->post('src');
        $this->Login_model->set_image($url);
    }

    public function update_profile()
    {
        $contactname = $this->input->post('contactname');
        $birthday = $this->input->post('birthday');
        $gender = $this->input->post('gender');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $website = $this->input->post('website');
        $state = $this->input->post('state');
        $this->Login_model->edit_profile($contactname, $birthday, $phone, $gender, $website,
            $address, $state);
        redirect('profile/index');
    }

    // Controller function to get autocompletion outcome
    public function fetch_search_info() {
        $query = $this->input->post('search');
        $data = $this->Mysql_model->get_search_outcome($query);
        echo json_encode($data);
    }

    public function set_page_position()
    {
        $position = $this->input->post('position');
        $this->input->set_cookie('profile_position', $position, 2 * 60 * 60);
    }

    public function config_email_verification($username, $email)
    {
        $this->load->library('email');
        $verification_key = md5(rand());
        $subject = "Please verify email for login";
        $url = base_url()."users/verify_email/".$verification_key;
        $message = "
    <p>Hi $username</p>
    <p>This is email from INFS7203 project.</p>
    <p>The verification key is <strong>$verification_key</strong></p>
    <p>For complete registration process and login into system. First you want to verify you email by click this <a href='$url'>$url</a>.</p>
    <p>Once you click this link your email will be verified and you can login into system.</p>
    <p>Thanks,</p>
    <p>Chenxi Li</p>
    ";

        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'mailhub.eait.uq.edu.au',
            'smtp_port' => 25,
            'mailtype'  => 'html',
            'charset'    => 'iso-8859-1',
            'smtp_timeout' => '20', //in seconds
            'wordwrap' => TRUE
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('noreply@infs3202-b51d6e13.uqcloud.net');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            $this->session->set_flashdata('message', 'Check in your email for email verification mail');
        } else {
            $this->session->set_flashdata('message', 'Sending email is failed');
        }
        return $verification_key;
    }

    public function verify_email()
    {
        if($this->uri->segment(3))
        {
            $verification_key = $this->uri->segment(3);
            $username = $this->Mysql_model->verify_email_model($verification_key);
            if($username)
            {

                $this->session->set_flashdata('message', 'Your Email has been successfully verified');
                $user_data = array(
                    'username' => $username,
                    'logged' => true
                );
                $this->session->set_userdata($user_data);
                redirect('users/index');
            }
            else
            {
                echo '<h1 align="center">Invalid Link</h1>';
            }
        }
    }

    public function reset_password_request()
    {
        $this->load->library('email');
        $email = $this->input->post('email');
        $reset_key = md5(rand());
        $subject = "Please verify email for resetting password";
        $url = base_url()."users/reset_password_interface/".$reset_key;
        $message = "
    <p>Hi</p>
    <p>This is email from INFS7203 project.</p>
    <p>The reset key is <strong>$reset_key</strong></p>
    <p>For complete reset password process. First you want to verify you email by click this <a href='$url'>$url</a>.</p>
    <p>Once you click this link your email will be verified and you can reset your password.</p>
    <p>Thanks,</p>
    <p>Chenxi Li</p>
    ";

        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'mailhub.eait.uq.edu.au',
            'smtp_port' => 25,
            'mailtype'  => 'html',
            'charset'    => 'iso-8859-1',
            'smtp_timeout' => '20', //in seconds
            'wordwrap' => TRUE
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('noreply@infs3202-b51d6e13.uqcloud.net');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            $this->Mysql_model->update_reset_key($reset_key, $email);
            $this->session->set_flashdata('message', 'Check in your email for resetting password');
        } else {
            $this->session->set_flashdata('message', 'Sending email is failed');
        }
        redirect('users/index');
    }

    public function reset_password_interface()
    {
        if($this->uri->segment(3))
        {
            $reset_key = $this->uri->segment(3);
            $username = $this->Mysql_model->reset_password_model($reset_key);
            if($username)
            {
                $data['username'] = $username;
                $this->load->view('templates/navigator');
                $this->load->view('users/reset_password', $data);
            }
            else
            {
                echo '<h1 align="center">Invalid Link</h1>';
            }
        }
    }

    public function reset_password()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('register_password');
        if ($this->Mysql_model->update_password($username, $password)) {
            $this->Login_model->update_password($username, $password);
            $this->session->set_flashdata('message', 'Password is reset successfully!');
        } else {
            $this->session->set_flashdata('message', 'Reset password is failed!');
        }
        redirect('users/index');
    }

    public function generate_captcha()
    {
        $config = array(
            'img_path'      => 'captcha_images/',
            'img_url'       => base_url().'captcha_images/',
            'font_path'     => 'system/fonts/texb.ttf',
            'img_width'     => '130',
            'img_height'    => 40,
            'word_length'   => 4,
            'font_size'     => 18
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('captcha');
        $this->session->set_userdata('captcha', $captcha['word']);
        // Pass captcha image to view
        return $captcha['image'];
    }

    public function refresh_captcha()
    {
        $config = array(
            'img_path'      => 'captcha_images/',
            'img_url'       => base_url().'captcha_images/',
            'font_path'     => 'system/fonts/texb.ttf',
            'img_width'     => '130',
            'img_height'    => 40,
            'word_length'   => 4,
            'font_size'     => 18
        );
        $captcha = create_captcha($config);

        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captcha');
        $this->session->set_userdata('captcha',$captcha['word']);

        // Display captcha image
        echo $captcha['image'];
    }
}