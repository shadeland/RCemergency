<?php

class xmltool {

    public function parseXml($data) {
        $dom = new DOMDocument('1.0');
        $node = $dom->createElement('markers'); // create root node
        $parnode = $dom->appendChild($node);
        foreach ($data as $row) {
            $node = $dom->createElement('marker');
            $newnode = $parnode->appendChild($node);
            foreach ($row as $key => $value) {
                $newnode->setAttribute($key, $value);
            }
            $newnode->setAttribute('address', $this->getAddress($row['lat'], $row['long']));
        }
        return $dom->saveXML();
    }

    public function getAddress($lat, $lng) {

        $endpoint = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . trim(floatval($lat)) . "," . trim(floatval($lng)) . "&sensor=false";

        $raw = file_get_contents($endpoint);

        $json_data = json_decode($raw,true);

        if ($json_data['status'] == "OK") {
            $fAddress = $json_data['results'][0]['formatted_address'];
//            var_dump($json_data->results);
            //dumping result
           
        } else {
            //if no result found, status would be ZERO_RESULTS
            $fAddress = "";
            
        }
        return $fAddress;
    }

}

?>
