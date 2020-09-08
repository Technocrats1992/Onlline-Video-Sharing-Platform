<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Upload a single image in the profile page.
    public function upload_image($url, $username, $filename)
    {
        $data = array (
            'url' => $url,
            'username' => $username,
            'filename' => $filename,
        );
        $this->mongo_db->insert('images', $data);
    }

    public function user_image($username)
    {
        $array = $this->mongo_db->get_where('images', array('username' => $username));
        return $array;
    }
}