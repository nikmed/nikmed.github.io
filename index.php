<?
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit;
}

header('Content-Type: application/json;charset=utf-8');

$json = $_POST;
echo $json;
$values = json_decode($json, true);


$header = array('Content-Type: application/json;charset=utf-8');
$types = explode(',',$values['source']);

$type = trim($types[0]);
$values['source'] = trim($types[1]);
$values['origin'] = 'https://nikmed.github.io/'
if(!isset($values['site'])){
  $values['site'] = 'https://alorbroker.ru/';	
}

$json = json_encode($values, JSON_FORCE_OBJECT);


$url = 'http://api.dev.alor.ru//client/v1.0/leads/actions/crm-test';

$error_msg = array('Operation'=>'leadToApi',
                              'Status' => 'success',
                               'URL' => $url,
                               'Body' => $json
                               );
    Config::stdLog($error_msg, 'clientApi', 'info'); 


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

/*echo $statusCode;
exit();*/

header(' ',true,$statusCode);

if($statusCode >=400)
{
    $error_msg = array('Operation'=>'lead add',
                              'Status' => 'error',
                               'URL' => $url,
                               'Method' => 'POST',
                               'StatusCode' => $statusCode,
                               'Body' => $json
                               );
    Config::stdLog($error_msg, 'clientApi', 'error'); 
}

echo $json;
?>
