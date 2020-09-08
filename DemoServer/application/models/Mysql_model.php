<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mysql_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function check_account ($username, $password)
    {
        $user = array (
            'username' => $username,
            'password' => $password
        );
        $name_check = $this->db->get_where('users', array('username' => $username))->row_array();
        $account_check = $this->db->get_where('users', $user)->row_array();
        if (!empty($name_check)) {      // Check whether the name is existed.
            if (!empty($account_check)) {        // Check whethe the password is correct.
                if ($account_check['verify_status']) {
                    return 'valid account';
                } else {
                    return 'Email remains to be verified';
                }
            } else {
                return 'invalid password';
            }
        } else {
            return 'invalid username';
        }
    }

    public function create_user($username, $password, $email, $phone, $verification_key)
    {
        $data = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'phone' => $phone,
            'verification_key' => $verification_key,
            'verify_status' => 0
        );
        $this->db->insert('users', $data);
    }

    function get_search_outcome($post) {
        $response = array();
        if(isset($post)) {
            // Select record
            $this->db->select('*');
            $this->db->where("filename like '%".$post."%' ");
            $video_records = $this->db->get('videos')->result();
            foreach($video_records as $row ) {
                $response[] = array("label"=>$row->filename);
            }
            $this->db->select('*');
            $this->db->where("username like '%".$post."%' ");
            $username_records = $this->db->get('users')->result();
            foreach($username_records as $row ) {
                $response[] = array("label"=>$row->username);
            }
        }
        return $response;
    }

    public function upload_video($url, $username, $filename, $date)
    {
        $data = array (
            'url' => $url,
            'username' => $username,
            'filename' => $filename,
            'date' => $date,
            'thumbnail' => 'data/image/thumbnail/video-thumbnail.jpg',
            'views' => 0,
            'likes' => 0,
            'dislikes' => 0,
        );
        $this->db->insert('videos', $data);
    }

    public function user_video($username)
    {
        $array = $this->db->get_where('videos', array('username' => $username))->result_array();
        return $array;
    }

    public function all_user_video()
    {
        $array = $this->db->query("SELECT * FROM videos")->result_array();
        return $array;
    }

    public function load_videos($limit, $start)
    {
        $this->db->select("*");
        $this->db->from("videos");
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query;
    }

    public function get_video_by_id($id)
    {
        $array = $this->db->get_where('videos', array('id' => $id))->row_array();
        return $array;
    }

    // The SQL operations for updating ratings
    public function update_rating($videoId, $username, $type)
    {
        $data = array (
            'videoId' => $videoId,
            'username' => $username,
            'type' => $type,
        );
        if ($type) {
            $array = $this->db->get_where('ratings', $data)->result_array();
            $arraydislike = $this->db->query("SELECT * FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type=0")->row_array();
            if (!empty($arraydislike)) {
                $this->db->query("DELETE FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type=0");
                $this->db->query("UPDATE videos SET dislikes=dislikes-1 WHERE id='$videoId'");
            }
            if (empty($array)) {
                $this->db->insert('ratings', $data);
                $this->db->query("UPDATE videos SET likes=likes+1 WHERE id='$videoId'");
            } else {
                $this->db->query("DELETE FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type='$type'");
                $this->db->query("UPDATE videos SET likes=likes-1 WHERE id='$videoId'");
            }
        } else {
            $array = $this->db->get_where('ratings', $data)->result_array();
            $arraylike = $this->db->query("SELECT * FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type=1")->row_array();
            if (!empty($arraylike)) {
                $this->db->query("DELETE FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type=1");
                $this->db->query("UPDATE videos SET likes=likes-1 WHERE id='$videoId'");
            }
            if (empty($array)) {
                $this->db->insert('ratings', $data);
                $this->db->query("UPDATE videos SET dislikes=dislikes+1 WHERE id='$videoId'");
            } else {
                $this->db->query("DELETE FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type='$type'");
                $this->db->query("UPDATE videos SET dislikes=dislikes-1 WHERE id='$videoId'");
            }
        }
        $data['dislikes'] = $this->db->query("SELECT dislikes FROM videos WHERE id='$videoId'")->row_array()['dislikes'];
        $data['likes'] = $this->db->query("SELECT likes FROM videos WHERE id='$videoId'")->row_array()['likes'];
        $data['status'] = $this->get_rating_status($videoId, $username);
        return $data;
    }

    // SQL operations to fetch all ratings for each video
    public function get_rating_status($videoId, $username)
    {
        $arraydislike = $this->db->query("SELECT * FROM ratings  WHERE videoId='$videoId' AND username='$username' AND type=0")->row_array();
        $arraylike = $this->db->query("SELECT * FROM ratings  WHERE videoId=$videoId AND username='$username' AND type=1")->row_array();
        if (!empty($arraydislike)) {
            return 1;
        } else if (!empty($arraylike)){
            return 2;
        } else {
            return 3;
        }
    }

    public function add_comment_model($videoId, $username, $date, $comment, $parentId)
    {
        $data = array (
            'videoId' => $videoId,
            'username' => $username,
            'date' => $date,
            'comment' => $comment,
            'parentId' => $parentId
        );
        $this->db->insert('comments', $data);
    }

    public function fecth_comments($videoId, $parentId)
    {
        $array = $this->db->get_where('comments', array('videoId'=>$videoId, 'parentId'=>$parentId))->result_array();
        return $array;
    }

    public function verify_email_model($key)
    {
        $this->db->where('verification_key', $key);
        $this->db->where('verify_status', 0);
        $query = $this->db->get('users');
        if($query->num_rows() > 0)
        {
            $username = $query->row_array()['username'];
            $data = array(
                'verify_status'  => 1
            );
            $this->db->where('verification_key', $key);
            $this->db->update('users', $data);
            return $username;
        }
        else
        {
            return false;
        }
    }

    public function update_reset_key($reset_key, $email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        if($query->num_rows() > 0)
        {
            $data = array(
                'reset_key'  => $reset_key,
                'reset_status'  => 1
            );
            $this->db->where('email', $email);
            $this->db->update('users', $data);
            return true;
        }
        else
        {
            return false;
        }
    }

    public function reset_password_model($reset_key)
    {
        $this->db->where('reset_key', $reset_key);
        $this->db->where('reset_status', 1);
        $query = $this->db->get('users');
        if($query->num_rows() > 0)
        {
            $username = $query->row_array()['username'];
            return $username;
        }
        else
        {
            return false;
        }
    }

    public function update_password($username, $password)
    {

        $this->db->where('username', $username);
        if($this->db->update('users', array('password' => $password, 'reset_status'=>0))) {
            return true;
        } else {
            return false;
        }
    }

    public function get_search_video($keyword)
    {
        $query = $this->db->query("SELECT * FROM videos WHERE filename LIKE '%$keyword%' OR username LIKE '%$keyword%' ");
        return $query->result_array();
    }
}