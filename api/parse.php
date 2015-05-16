<?php
#Load Config and needed classes
include('../init.php');

#initialize connection
$dbClassName = 'db_'.DB_TYPE;
$db = new $dbClassName;


#Retrieve the data
//TODO:This should be received from POST to avoid writting on the disc

$trackerData = file('data/tracker.log');
$patient_id = 1;


//TODO: Process each tracker data
$trackerType = 'tet_et1000';
$trackerClassName = 'Tracker_'.$trackerType;
if(!class_exists($trackerClassName)) throw new Exception ("Tracker not supported");

$tracker = new $trackerClassName();
$tracker->setType($trackerType);




foreach($trackerData as $data){
   $rawDataId = $tracker->saveTrackerRawData($data,$patient_id); 
   $extraData = array(
       'raw_data_id'    => $rawDataId,
       'patient_id'     => $patient_id
   );
   $parsedTrackerData = $tracker->parseTrackerData($data,$extraData);
 
   if($parsedTrackerData!=false) $tracker->saveParsedTrackerData($parsedTrackerData);
   
   
   
}