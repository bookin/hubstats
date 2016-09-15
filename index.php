<?php
require('vendor/autoload.php');

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use GitStat\GitHub;

if($_SERVER['REQUEST_URI'] == '/logout'){
    setcookie("access_token", "", time()-3600);
    header('Location: /');
}

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
                $data['owner']=$owner;
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
        setcookie('access_token', $data['access_token'], time()+(3600*24));
    }
    header('Location: '.strtok($_SERVER['REQUEST_URI'], '?'));
}

/**
 * @param DateTime $date_start
 * @param DateTime $date_end
 * @param string $format
 * @param bool|string|integer|false $value
 * @param string $interval
 * @return array
 */
function ArrayDateRange(DateTime $date_start, DateTime $date_end, $format='Y-m-d', $value=false, $interval = 'P1D'){
    $return_array = [];
    $period = new DatePeriod(
        $date_start,
        new DateInterval($interval),
        $date_end
    );
    foreach($period as $date){
        if($value !== false){
            $return_array[$date->format($format)] = $value;
        }else{
            $return_array[]=$date->format($format);
        }
    }
    return $return_array;
}

function showChart($id, $views){
    if(!count($views)){
        return;
    }

    $start = new DateTime(date('c', ($views[0]['timestamp']/1000)-(60*60*24)));
    $end = new DateTime(date('c',($views[count($views)-1]['timestamp']/1000)+(60*60*24)));
    $data = ArrayDateRange($start, $end, 'U', ['count'=>0,'uniques'=>0]);
    foreach($data as $t=>&$d){
        $key = array_search($t*1000, array_column($views, 'timestamp'));
        $view = $views[$key];
        if($key !== false){
            $d = ['x'=>$view['timestamp'], 'count'=>$view['count'], 'uniques'=>$view['uniques']];
        }else{
            $d['x']=$t*1000;
        }
    }
    $data = array_values($data);
    if($data){
        $options = [
            'element'=> $id,
            'data'=>$data,
            'xkey'=>'x',
            'ykeys'=>['count', 'uniques'],
            'labels'=>['Visits', 'Uniques'],
            'xLabels'=>'day',
            'behaveLikeLine'=>true,
            'dateFormat'=>"js:function(x){ var date = new Date(x); return ('0' + (date.getMonth() + 1)).slice(-2) + '/' +  ('0' + date.getDate()).slice(-2) + '/' + date.getFullYear().toString().replace(new RegExp('^.{2}'), '');}",
            'lineColors'=>['#87D37C', '#4183D7'],
            'lineWidth'=>2,
            'hideHover'=>true,
//            'grid'=>false,
            'fillOpacity'=>0.5,
            'resize'=>true
        ];
        $options = json_encode($options);
        $options = preg_replace('/"js:(.*?)"/', '$1', $options);
        echo '<div id="'.$id.'" style="height: 250px;"></div>';
        echo '<script>new Morris.Area('.$options.');</script>';
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
    <?if(isGuest()){?>
        <link rel="stylesheet" href="http://getbootstrap.com/examples/cover/cover.css" crossorigin="anonymous">
    <?}?>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

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
        body{
            font-family: 'Roboto', sans-serif;
        }
        #page{
            margin-top: 20px;
        }
        .poser{
            display: inline-block;
            vertical-align: middle;
            max-width:100%;
        }
        .panel-body .head h2 a{
            color: #333;
        }
        .panel-body .head h2 a:hover,
        .panel-body .head h2 a:visited,
        .panel-body .head h2 a:active{
            text-decoration: none;
        }
        .panel-body .head h2{
            margin-top: 0;
            font-style: italic;
            font-weight: 300;
            display: inline-block;
        }
        .poser-list{
            margin-top: 10px;
        }
        .panel-default{
            box-shadow: 2px 2px 2px #ddd;
        }
        .cover-img{
            position: fixed;
            min-width: 100%;
            min-height: 100%;
            left: 0;
            top: 0;
            opacity: 0.1;
        }
        .site-wrapper{
            overflow: hidden;
        }
        .site-wrapper-inner{
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <?if(isGuest()){?>

        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div class="cover-container">

                    <div class="inner cover">
                        <h1 class="cover-heading">Stats of your repositories</h1>
                        <p class="lead">You can see stats of all your repositories on GitHub. Also if you have projects in the packagist.org, you can see information about downloads your packages.</p>
                        <p class="lead">
                            <a href="<?=GitHub::getAuthUrl()?>" class="btn btn-default btn-lg">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAABDlBMVEUAAAAiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiL+ublTAAAAWXRSTlMAAQIDBAUGBwoMDQ8QERITFRcYGhwdHh8lJicoKiwxMjQ1Nzk7QU9QUVRWV1xeX2Fkb3F4eXt8f4OFj5qdnqOlpqqrra+wsry+w8XHyMrT1d7g4uvt9/n7/fQTEl0AAAD2SURBVBgZhcHZQkFRAIbRfx9HRamkkeZRk4pGhTRPRFK+93+R9iY5rlpLv0Z2irXmx91BXH1iJbqeptWTbhGQVVeOfldGbdtQqdJVf4RTOWPAuBIPN9nMUeF5XmEgKasMDCgAqEqKYY2qx8eak3aBF6OAS+BcKgGLCooDNakORBXkYRl9AREFeVi+6sCUgoawjO6BfQWtAg0pAzR89ZgKkJcmsCoRdYULWMuSXknNtsiv+ZK8lbNvrIaRNEMzMgnvcm5pW5eT49qLpsJy9nCK6ihyOCgjZxPrLaQOcwEcy9kCyiH9WahxIifN54b6JBNyhpeM/vEDAodL3dSRubEAAAAASUVORK5CYII=" alt="">
                                Sign in
                            </a>
                        </p>
                    </div>

                    <div class="mastfoot">
                        <div class="inner">
                        </div>
                    </div>

                </div>
            </div>
            <img src="/img/stats.JPG" class="cover-img">
        </div>

    <?}else{?>
        <div id="page" class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <a href="/logout" class="btn pull-right">Выйти</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $repositories = getInfo();
                    if($repositories)
                    foreach($repositories as $repo){?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="head">
                                    <h2><a href="https://github.com/<?=$repo['owner']?>/<?=$repo['name']?>" target="_blank"><?=$repo['name']?></a></h2>
                                    <span data-toggle="tooltip" data-title="Views/Unique" data-placement="top"></span><?=$repo['count'].'/'.$repo['uniques']?>
                                    <ul class="poser-list list-unstyled list-inline pull-right">
                                        <li><img src="https://poser.pugx.org/<?=$repo['owner']?>/<?=$repo['name']?>/downloads?format=flat" class="poser"></li>
                                        <li><img src="https://poser.pugx.org/<?=$repo['owner']?>/<?=$repo['name']?>/d/monthly?format=flat" class="poser"></li>
                                        <li><img src="https://poser.pugx.org/<?=$repo['owner']?>/<?=$repo['name']?>/d/daily?format=flat" class="poser"></li>
                                    </ul>
                                </div>

                                <?showChart($repo['name'], $repo['views']);?>
                            </div>
                        </div>
                    <?}?>
                </div>
            </div>
        </div>
    <?}?>
</body>
</html>
<?ob_end_flush();?>