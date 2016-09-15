<?php
require('vendor/autoload.php');

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use GitStat\GitHub;


function isGuest(){
    if (isset($_COOKIE['access_token'])) {
        return false;
    }else{
        if(isset($_GET['code'])){
            setAccess($_GET['code']);
        }
        return true;
    }
}
function getInfo(){
    if (isset($_COOKIE['access_token'])) {
        $access_token = $_COOKIE['access_token'];

        $filesystemAdapter = new Local(__DIR__.'/');
        $filesystem        = new Filesystem($filesystemAdapter);
        $pool = new FilesystemCachePool($filesystem);

        if($pool->hasItem($access_token)){
            return $pool->getItem($access_token)->get();
        }else{
            $owner = GitHub::getUserInfo($access_token);
            $owner = $owner->login;
            $repositories = GitHub::getOwnerRepositories($owner, $access_token);
            $response = [];
            foreach($repositories as $repo){
                $data = json_decode(json_encode(GitHub::getRepoTraffic($owner, $repo->name, $access_token)), true);
                $data['name']=$repo->name;
                $response[] = $data;
            }
            usort($response, function($a, $b){
                return $b['count'] - $a['count'];
            });
            $item = $pool->getItem($access_token);
            $item->set($response);
            $item->expiresAfter(60*60);
            $pool->save($item);
            return $response;
        }

    }else{
        header('Location: '.strtok($_SERVER['REQUEST_URI'], '?'));
    }
}

function setAccess($code){
    $data = GitHub::getAccessToken($code);
    if($data['error']){
        //...
    }
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
<?ob_start();?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GitStat</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
        $(function(){
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            })
        });
    </script>
    <style>
        #page{
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="page">
        <div class="container-fluid">
            <?if(isGuest()){?>
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4">
                        <a href="<?=GitHub::getAuthUrl()?>" class="btn btn-block btn-info">Авторизоваться</a>
                    </div>
                </div>
            <?}else{?>
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $repositories = getInfo();
                        if($repositories)
                        foreach($repositories as $repo){?>
                            <div class="panel panel-default">
                                <div class="panel-heading"><b><?=$repo['name']?></b> - <span data-toggle="tooltip" data-title="Views/Unique" data-placement="top"></span><?=$repo['count'].'/'.$repo['uniques']?></div>
                                <div class="panel-body">
                                    <?showChart($repo['name'], $repo['views']);?>
                                </div>
                            </div>
                        <?}?>
                    </div>
                </div>
            <?}?>
        </div>
    </div>
</body>
</html>
<?ob_end_flush();?>