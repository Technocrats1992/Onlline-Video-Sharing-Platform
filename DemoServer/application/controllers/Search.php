<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->model('File_model');
        $this->load->model('Mysql_model');
    }

    public function index()
    {
        redirect('users/index');
    }

    public function search_video()
    {
        $keyword = $this->input->post('search');
        $videos = $this->Mysql_model->get_search_video($keyword);
        if (empty($videos)) {
            $data['flag'] = 'No results';
        } else {
            $this->session->set_flashdata('message', count($videos).' results totally found !');
            $data['content'] = '';
            foreach($videos as $video) {
                $data['content'] .= '<a href="'.base_url().'videoContent/load_video/'.$video['id'].'">
<div class="col-sm6 col-md-3 video-box"><div class="index-videos"><img src="https://infs3202-b51d6e13.uqcloud.net/Demo/'.$video['thumbnail'];
                $data['content'] .= '" class="img-responsive img-rounded video-thumbnail" id="'.$video['filename'];
                $data['content'] .= '"></div><div class="text-muted video-title"><span class="glyphicon glyphicon-facetime-video"></span>'.$video['filename'];
                $data['content'] .= '</div><div><div class="text-muted video-user"><span class="glyphicon glyphicon-user"></span>'.$video['username'];
                $data['content'] .= '</div><div class="text-muted video-date"><span class="glyphicon glyphicon-calendar"></span>'.$video['date'];
                $data['content'] .= '</div></div></div></a>';
            }
        }
        $this->load->view('templates/navigator');
        $this->load->view('users/search_outcome', $data);
    }
}