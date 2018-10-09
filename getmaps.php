<?php
$mapImage = "https://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318&markers=color:red%7Clabel:C%7C40.718217,-73.998284&key=AIzaSyC4XdNQeAcl1DNZCyV3qQ7lXHrGTCR1yuQ";
//$mapImage='thismofo.png';
//$data = file_get_contents($mapImage);
$data =imagecreatefrompng($mapImage);
header("Content-type:image/png");       
//header("Content-type: " . strlen($data));
//readfile($mapImage);
//echo $data;
//$imagedata = imagecreatefromstring($data);
imagepng($data);
?>




