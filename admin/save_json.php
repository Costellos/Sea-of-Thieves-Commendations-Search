<?php
// File path for JSON data
$jsonFile = '../assets/json/commendations.json';

// Load existing JSON data
$jsonData = [];

if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $jsonData = json_decode($jsonContent, true);
    
    // Check if jsonData is an array
    if (!is_array($jsonData)) {
        $jsonData = []; // Fallback to empty array if data is not an array
    }
}

// Update commendations
if (isset($_POST['commendations'])) {
    $jsonData['commendations'] = $_POST['commendations'];
}

// Update rewardTypes
if (isset($_POST['rewardTypes'])) {
    $pre_data = $_POST['rewardTypes'];
    $new_rewards_data = array();
    for ($i=0; $i < count($pre_data) ; $i++) { 
        $new_rewards_data[$pre_data[$i]['name']] = $pre_data[$i];
    }
    $jsonData['rewardTypes'] = $new_rewards_data;
}

// Encode the updated data as JSON
$newJsonContent = json_encode($jsonData, JSON_PRETTY_PRINT);

// echo('<pre>');
// echo($newJsonContent);
// echo('</pre>');

// Save the updated JSON data back to the file
file_put_contents($jsonFile, $newJsonContent);

// Redirect or output a success message
header('Location: edit_json.php?success=1');
exit;
?>
