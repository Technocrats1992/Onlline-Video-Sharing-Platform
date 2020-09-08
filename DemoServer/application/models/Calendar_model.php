<?php


class Calendar_Model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_events($start, $end, $username)
    {
        return $this->db->where("username", $username)->where("start >=", $start)->where("end <=", $end)->get("calendar_events");
    }

    public function add_event($data)
    {
        $this->db->insert("calendar_events", $data);
    }

    function get_events_pdf($username)
    {
        $this->db->where('username', $username);
        $data = $this->db->get('calendar_events');
        $output = '<table width="100%" cellspacing="5" cellpadding="5">';
        foreach($data->result() as $row)
        {
            $output .= '
   <tr>
    <td>
     <p><b>Event title : </b>'.$row->title.'</p>
     <p><b>Description : </b>'.$row->description.'</p>
     <p><b>Start date : </b>'.$row->start.'</p>
     <p><b>End date: </b>'.$row->end.'</p>
    </td>
   </tr>
   ';
        }
        $output .= '<tr>
   <td colspan="2" align="center"><a href="'.base_url().'profile" class="btn btn-primary">Back</a></td>
  </tr>';
        $output .= '</table>';
        return $output;
    }

}
