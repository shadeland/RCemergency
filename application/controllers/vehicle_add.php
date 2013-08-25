<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle_add extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->library('form_validation');
        $this->load->helper('url');
       $this->load->view('vehicle_add');
	}
    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('SID', 'SID', 'required|callback_checkSID');
        $this->form_validation->set_rules('vehicle_type_ID', 'Vehicle Type', 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $this->load->model('m_vehicles');
           $data= $this->m_vehicles->addVehicle($this->input->post('name'),$this->input->post('vehicle_type_ID'),$this->input->post('SID'));
                print_r($data);
        }
    }
    public function checkSID($SID){
        $this->load->model('m_vehicles');
        $result=$this->m_vehicles->findBySID($SID);
        if($result!=0){
            $this->form_validation->set_message('checkSID',"This SID is already registered for another vehicle");
            return false;
        }else{
            return true;
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */