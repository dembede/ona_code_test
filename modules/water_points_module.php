<?php 

$data_url = "https://raw.githubusercontent.com/onaio/ona-tech/master/data/water_points.json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $data_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$json = curl_exec($ch);
curl_close($ch);

$data = json_decode($json, true);

$arr_water_points = array();
$arr_village_rankings = array();
$var_number_functional;
$var_points_per_community;
$var_broken_points_rankings;

function functional_waterpoints($data){
    global $var_number_functional;
    $var_number_functional = array_count_values(array_map(function($value){ return $value['water_functioning'];}, $data));
    $var_number_functional = $var_number_functional['yes'];
}

function points_per_community($data){
    global $arr_water_points;
    global $var_points_per_community;
    $arr_water_points = array_count_values(array_map(function($value){ return $value['communities_villages'];}, $data));
    $var_points_per_community = json_encode($arr_water_points);
}

function broken_points_ranking($data) {
    global $arr_water_points;
    global $arr_village_rankings;
    global $var_broken_points_rankings;
    $arr_water_points_broken = array(); // store broken points per community
    $broken_points = 0;
    foreach ($data as $village) {
        if($village['water_functioning'] === 'no'){
            $village_name = $village["communities_villages"]; // get village name
            $village_water_points = $arr_water_points[$village_name]; // get total water points
            if(array_key_exists($village_name, $arr_village_rankings)){ // check if village is already being ranked
                $broken_points = $arr_water_points_broken[$village_name]+1; // update broken points num                
                $arr_water_points_broken[$village_name] = $broken_points; // update broken points array
                $arr_village_rankings[$village_name] = round(($broken_points/$village_water_points)*100, 2); // update ranking
            } 
            else {
                $arr_water_points_broken[$village_name] = 1; // add new broken point
                $arr_village_rankings[$village_name] = round((1/$village_water_points)*100, 2); // calculate new percentage
            }
        }
    }
    $var_broken_points_rankings = json_encode($arr_village_rankings);
}
// RUN FUNCTIONS
if (empty($data)){
    die("ERROR: Data source seems empty (".$data_url."). Please check before trying again");

} else {
    functional_waterpoints($data);
    points_per_community($data);
    broken_points_ranking($data);    
}

$arr_processed_data = array();

$arr_processed_data['number_functional'] = $var_number_functional;
$arr_processed_data['number_water_points'] = $arr_water_points;
$arr_processed_data['community_ranking'] = $arr_village_rankings;

$wp_json_file = fopen('./modules/files/processed_data.json', 'w');
fwrite($wp_json_file, json_encode($arr_processed_data));
fclose($wp_json_file);

?>
