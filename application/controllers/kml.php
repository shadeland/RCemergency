<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kml extends CI_Controller {

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
       $vhicleID=$this->input->get('vehicleID');
        $this->load->model('m_vehicles');
        $data=$this->m_vehicles->getLastPositions($vhicleID,5);
        // Creates the Document.
        $dom = new DOMDocument('1.0', 'UTF-8');

// Creates the root KML element and appends it to the root document.
        $node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
        $parNode = $dom->appendChild($node);

// Creates a KML Document element and append it to the KML element.
        $dnode = $dom->createElement('Document');
        $docNode = $parNode->appendChild($dnode);
        $node = $dom->createElement('Placemark');
        $placeNode = $docNode->appendChild($node);
        $lnode=$dom->createElement('LineString');
        $placeNode->appendChild($lnode);
        $coor="";

    foreach( $data as $d){
        $coor.=$d['lng']." , ".$d['lat']." , 0 \n";

    }
        $coorNode=$dom->createElement('coordinates',$coor);
        $lnode->appendChild($coorNode);
        $kmlOutput = $dom->saveXML();
        header('Content-type: application/vnd.google-earth.kml+xml');
        echo $kmlOutput;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */