<?php
require_once 'vendor/autoload.php';

function main(){
    $url = 'https://api.github.com';
    
    $curl = new Curl\Curl();
    #$owner = 'bookin';
    #$repo = 'yii2-wallet-one';
    if (isset($_COOKIE['access_token'])) {
        $access_token = $_COOKIE['access_token'];
        
        $curl->get($url."/user?access_token=".$access_token);
        $owner = json_decode($curl->response)->login;
        
        $curl->get($url."/users/$owner/repos?type=owner&sort=updatedaccess_token=".$access_token);
        $repositories = json_decode($curl->response);
        $curl->setHeader('Accept', 'application/vnd.github.spiderman-preview');
        foreach($repositories as $repo){
            $curl->get($url."/repos/$owner/$repo->name/traffic/views?access_token=".$access_token);
            $response = json_decode($curl->response, true);
            
            echo $repo->name.' ('.$response['count'].'/'.$response['uniques'].')';
            showChart($repo->name, $response['views']);
            #echo'<pre>';print_r($curl->response);echo'</pre>';
        }
        
        
       
        
    }else{
        if(isset($_GET['code'])){
            getAccess($_GET['code']);
        }else{
            auth();
        }
    }
}
#$authorizations = $github->api('authorizations')->all();


function auth(){
    $client_id = '2f7ddd9f5d31224c77ce';
    $scope = 'repo';
    $url = 'https://github.com/login/oauth/authorize?client_id='.$client_id.'&scope='.$scope;
    echo '<a href="'.$url.'">'.$url.'</a>';
}

function getAccess($code){
    $client_id = '2f7ddd9f5d31224c77ce';
    $client_secret = '01ece974753b7233a319bd88320724de5666e971';
    $curl = new Curl\Curl();
    $curl->post('https://github.com/login/oauth/access_token', [
        'client_id'=>$client_id,
        'client_secret'=>$client_secret,
        'code'=>$code
    ]);
    $response = $curl->response;
    $data = [];
    parse_str($response, $data);
    //var_dump($response, $data);
    if($data['access_token']){
        setcookie('access_token', $data['access_token'], time()+3600);
    }
    header('Location: '.strtok($_SERVER['REQUEST_URI'], '?'));
}

function showChart($id, $views){
    $data = [];
    foreach ($views as $view) {
        $data[] = ['x'=>$view['timestamp'], 'count'=>$view['count'], 'uniques'=>$view['uniques']];
    }
    if($data){
        $options = [
            'element'=> $id,
            'data'=>$data,
            'xkey'=>'x',
            'ykeys'=>['count', 'uniques'],
            'labels'=>['Visits', 'Uniques']
        ];
        
        echo '<div id="'.$id.'" style="height: 250px;"></div>';
        echo '<script>new Morris.Line('.json_encode($options).');</script>';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GitStat</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>
<body>
    <?php main();?>    
</body>
</html>