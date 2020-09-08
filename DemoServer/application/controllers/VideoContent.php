<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class VideoContent extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->model('Mysql_model');
        $this->load->model('File_model');
    }

    public function index()
    {
        redirect('users/index');
    }

    public function load_video($id = NULL)
    {
        if ($this->session->userdata('logged')) {
            $data['status'] = $this->Mysql_model->get_rating_status($id, $this->session->userdata('username'));
        }
        $data['video'] = $this->Mysql_model->get_video_by_id($id);
        $this->load->view('templates/navigator.php');
        $this->load->view('users/video_content.php', $data);
    }

    // The function to update the rating
    public function set_rating()
    {
        if ($this->session->userdata('logged')) {
            $videoId = intval($this->input->post('videoId'));
            $username = $this->input->post('username');
            $type = $this->input->post('type');
            echo json_encode($this->Mysql_model->update_rating($videoId, $username, $type));
        } else {
            $data['status'] = 0;
            echo json_encode($data);
        }
    }

    public function add_comment()
    {
        $videoId = $this->input->post('videoId');
        $username = $this->session->userdata('username');
        $date =  date("Y-m-d h:i:sa");
        $comment = $this->input->post('comment_body');
        $parentId = $this->input->post('parentId');
        $this->Mysql_model->add_comment_model($videoId, $username, $date, $comment, $parentId);
    }

    public function generate_comments()
    {
        $videoId = $this->input->post('videoId');
        $result = $this->Mysql_model->fecth_comments($videoId, 0);
        $html = "";
        foreach ($result as $row) {
            $html .=  ' <div class="row">
                <div class="col-sm-1">
                <div class="thumbnail">
                <img class="img-responsive user-photo" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">
                </div>
                </div>
                
                <div class="col-sm-11">
                <div class="panel panel-default">
                <div class="panel-heading">
                <strong>'.$row['username'].'</strong> on <span class="text-muted">'.$row["date"].'</span>
                </div>
                <div class="panel-body">
                '.$row['comment'].'
                </div>
                <div class="panel-footer reply-container" align="right">
                <button type="button" class="btn btn-default reply" id="'.$row['id'] .'">
                Reply</button></div>
                </div>
                </div>
                </div>';
                $replys = $this->Mysql_model->fecth_comments($videoId, $row['id']);
            foreach ($replys as $reply) {
                $html .= ' <div class="row col-lg-offset-1">
                    <div class="col-sm-1">
                    <div class="thumbnail">
                    <img class="img-responsive user-photo" src="https://ssl.gstatic.com/accounts/ui/avatar_2x.png">
                    </div>
                    </div>
                    
                    <div class="col-sm-11">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    <strong>'.$reply['username'].'</strong> on <span class="text-muted">'.$reply["date"].'</span>
                    </div>
                    <div class="panel-body">
                    '.$reply['comment'].'
                    </div>
                    </div>
                    </div>
                    </div>';
            }
        }
        echo $html;
    }

    public function download_video()
    {
        $url = $this->input->post('url');
        $filename = $this->input->post('filename').'.mp4';
        if (!empty($url)) {
            $this->load->helper('download');
            $path = file_get_contents($url);
            force_download($filename, $path);
        } else {
            redirect('videoContent/load_video');
        }
    }
}