<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->model('File_model');
        $this->load->model('Mysql_model');
        $this->load->model('Calendar_model');
        $this->load->library('pdf');
    }

    public function index()
    {
        if ($this->session->userdata('logged')) { // If the status is logged-in
            $this->load->view('templates/navigator');
            $username = $this->session->userdata('username');   // Get the username
            $data['user'] = $this->Login_model->get_current_user($username); // Get the information from this user
            $data['images'] = $this->File_model->user_image($username); // Get the stored image from model
            $data['videos'] = $this->Mysql_model->user_video($username);
            $this->load->view('users/profile', $data);
        } else {
            redirect('users/login');
        }
    }

    // Upload a single image or multiple images in the profile page.
    public function upload_image()
    {
        $count = count($_FILES['files']['name']);

        for($i=0;$i<$count;$i++){

            if(!empty($_FILES['files']['name'][$i])){

                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                $path = 'data/image/profile/';
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '5000';
                $this->load->library('upload',$config);

                if ($this->upload->do_upload('file')) {
                    $username = $this->session->userdata('username');
                    $url = $path.$this->upload->data('file_name');
                    $this->File_model->upload_image($url, $username, $this->upload->data('raw_name'));
                    $this->session->set_flashdata('image-info', 'Upload successfully');
                } else {
                    $this->session->set_flashdata('image-info', $this->upload->display_errors());
                }
            }
        }
        redirect('profile/index');
    }

    // Drag and drop images for uploading
    public function drag_image()
    {
        $username = $this->session->userdata('username');
        if(!empty($_FILES['file']['name'][0])) {
            foreach($_FILES['file']['name'] as $position => $name) {
                $id = uniqid();
                if (move_uploaded_file($_FILES['file']['tmp_name'][$position],
                    'data/image/profile/'.$id)) {
                    $url = 'data/image/profile/'.$id;
                    $this->File_model->upload_image($url, $username, $id);
                }
            }
        }
        redirect('profile/index');
    }

    public function upload_video()
    {
        $count = count($_FILES['videos']['name']);

        for($i=0;$i<$count;$i++){

            if(!empty($_FILES['videos']['name'][$i])){

                $_FILES['file']['name'] = $_FILES['videos']['name'][$i];
                $_FILES['file']['type'] = $_FILES['videos']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['videos']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['videos']['error'][$i];
                $_FILES['file']['size'] = $_FILES['videos']['size'][$i];

                // Configure the uploader before uploading\
                $path = 'data/video/';
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'mp4';
                $config['max_size'] = '102400';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file')) {
                    $username = $this->session->userdata('username');
                    $url = $path.$this->upload->data('file_name');
                    $date =  date("Y-m-d h:i:sa");
                    $this->Mysql_model->upload_video($url, $username,
                        $this->upload->data('raw_name'), $date);
                    $this->session->set_flashdata('video-info', 'Upload successfully');
                } else {
                    $this->session->set_flashdata('video-info', $this->upload->display_errors());
                }
            }
        }
        redirect('profile/index');
    }

    public function fetch_videos()
    {
        $data = $this->Mysql_model->load_videos($this->input->post('limit'),
            $this->input->post('start'))->result_array();
        echo json_encode($data);
    }

    public function resize_image()
    {
        $config = array();
        $src = $this->input->post('resize-src');
        $width = intval($this->input->post('width'));
        $height = intval($this->input->post('height'));
        $filename = $this->input->post('filename');
        $filepath = 'data/image/profile/'.$filename;
        $config['image_library'] = 'gd2';
        $config['source_image'] = $src;
        $config['new_image'] = $filepath;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $this->load->library('image_lib');
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            $this->session->set_flashdata('processing-info', $this->image_lib->display_errors());
        } else {
            $this->File_model->upload_image($filepath, $this->session->userdata('username'), $filename);
            $this->session->set_flashdata('processing-info', 'Upload successfully');
        }
        redirect('profile/index');
    }

    public function rotate_image()
    {
        $config = array();
        $src = $this->input->post('rotate-src');
        $degree = $this->input->post('rotate-degree');
        $filename = $this->input->post('filename');
        $filepath = 'data/image/profile/'.$filename;
        $config['image_library'] = 'gd2';
        $config['source_image'] = $src;
        $config['new_image'] = $filepath;
        $config['rotation_angle'] = $degree;
        $this->load->library('image_lib');
        $this->image_lib->initialize($config);
        if (!$this->image_lib->rotate()) {
            $this->session->set_flashdata('processing-info', $this->image_lib->display_errors());
        } else {
            $this->File_model->upload_image($filepath, $this->session->userdata('username'), $filename);
            $this->session->set_flashdata('processing-info', 'Upload successfully');
        }
        redirect('profile/index');
    }

    public function add_watermark()
    {
        $src = $this->input->post('watermark-src');
        $text = $this->input->post('text');
        $filename = $this->input->post('filename');
        $filepath = 'data/image/profile/'.$filename;
        $config['image_library'] = 'gd2';
        $config['source_image'] = $src;
        $config['new_image'] = $filepath;
        $config['wm_text'] = $text;
        $config['wm_font_color'] = '000000';
        $config['wm_type'] = 'text';
        $config['wm_font_size'] = '1700';
        $config['wm_vrt_alignment'] = 'middle';
        $config['wm_hor_alignment'] = 'center';;
        $this->load->library('image_lib');
        $this->image_lib->initialize($config);
        if (!$this->image_lib->watermark()) {
            $this->session->set_flashdata('processing-info', $this->image_lib->display_errors());
        } else {
            $this->File_model->upload_image($filepath, $this->session->userdata('username'), $filename);
            $this->session->set_flashdata('processing-info', 'Upload successfully');
        }
        redirect('profile/index');
    }

    public function get_events()
    {
        // Our Start and End Dates
        $start = $this->input->get("start");
        $end = $this->input->get("end");

        $startdt = new DateTime('now'); // setup a local datetime
        $startdt->setTimestamp($start); // Set the date based on timestamp
        $start_format = $startdt->format('Y-m-d H:i:s');

        $enddt = new DateTime('now'); // setup a local datetime
        $enddt->setTimestamp($end); // Set the date based on timestamp
        $end_format = $enddt->format('Y-m-d H:i:s');

        $events = $this->Calendar_model->get_events($start_format, $end_format, $this->session->userdata('username'));

        $data_events = array();

        foreach($events->result() as $r) {

            $data_events[] = array(
                "id" => $r->ID,
                "title" => $r->title,
                "description" => $r->description,
                "end" => $r->end,
                "start" => $r->start
            );
        }

        echo json_encode(array("events" => $data_events));
        exit();
    }

    public function add_event()
    {
        /* Our calendar data */
        $username = $this->session->userdata('username');
        $name = $this->input->post("name", TRUE);
        $desc = $this->input->post("description", TRUE);
        $start_date = $this->input->post("start_date", TRUE);
        $end_date = $this->input->post("end_date", TRUE);

        if(!empty($start_date)) {
            $sd = DateTime::createFromFormat("Y/m/d H:i", $start_date);
            $start_date = $sd->format('Y-m-d H:i:s');
            $start_date_timestamp = $sd->getTimestamp();
        } else {
            $start_date = date("Y-m-d H:i:s", time());
            $start_date_timestamp = time();
        }

        if(!empty($end_date)) {
            $ed = DateTime::createFromFormat("Y/m/d H:i", $end_date);
            $end_date = $ed->format('Y-m-d H:i:s');
            $end_date_timestamp = $ed->getTimestamp();
        } else {
            $end_date = date("Y-m-d H:i:s", time());
            $end_date_timestamp = time();
        }

        $this->Calendar_model->add_event(array(
                "username" => $username,
                "title" => $name,
                "description" => $desc,
                "start" => $start_date,
                "end" => $end_date,
            )
        );
        redirect('profile/index');
    }

    public function generate_pdf()
    {
        $html_content = '<h3 align="center">'.$this->session->userdata('username').'\'s Event Calendar</h3>';
        $html_content .= $this->Calendar_model->get_events_pdf($this->session->userdata('username'));
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("".$this->session->userdata('username').".pdf", array("Attachment"=>0));
    }
}