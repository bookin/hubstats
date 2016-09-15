<?php
namespace GitStat;


class GitHub
{
    protected static $apiUrl = 'https://api.github.com';
    protected static $authUrl = 'https://github.com/login/oauth/authorize';

    public static $client_id = '';
    public static $client_secret = '';
    public static $scope = 'repo';

    private static $curl;
    const TYPE_GET = 'get';


    /**
     * @param array $options
     * @return string
     */
    public static function getAuthUrl($options=[]){
        if(!isset($options['client_id'])){
            $options['client_id'] = self::$client_id;
        }

        if(!isset($options['scope'])){
            $options['scope'] = self::$scope;
        }

//        if(!isset($options['redirect_uri'])){
//            $options['redirect_uri'] = $_SERVER['REQUEST_SCHEME'].'//'.$_SERVER['SERVER_NAME'];
//        }

        return self::$authUrl.'?'.urldecode(http_build_query($options));
    }

    /**
     * @param $code
     * @return array
     */
    public static function getAccessToken($code){
        $curl = new \Curl\Curl();
        $curl->post('https://github.com/login/oauth/access_token', [
            'client_id'=>self::$client_id,
            'client_secret'=>self::$client_secret,
            'code'=>$code
        ]);
        $response = $curl->response;
        $data = [];
        parse_str($response, $data);
        return $data;
    }

    /**
     * @param $access_token
     * @return mixed
     */
    public static function getUserInfo($access_token){
        $data = self::SendRequest('/user', ['access_token'=>$access_token]);
        if(isset($data->error)){
            //...
        }
        return $data;
    }

    public static function getOwnerRepositories($owner, $access_token, $options=[]){
        $opt = [
            'access_token' => $access_token,
            'type'=>'owner',
            'sort'=>'updated'
        ];
        $options = array_merge($opt, $options);
        $data = self::SendRequest('/users/'.$owner.'/repos', $options);
        if(isset($data->error)){
            //...
        }
        return $data;
    }

    public static function getRepoTraffic($owner, $repo, $access_token){
        $data = self::SendRequest('/repos/'.$owner.'/'.$repo.'/traffic/views', ['access_token'=>$access_token], ['Accept'=>'application/vnd.github.spiderman-preview']);
        if(isset($data->error)){
            //...
        }
        return $data;
    }

    /**
     * @param $method
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public static function SendRequest($method, $data, $headers=[]){
        if(empty(self::$curl) || !(self::$curl instanceof \Curl\Curl)){
            self::$curl = new \Curl\Curl();
        }
        $curl = self::$curl;
        if($headers){
            foreach($headers as $key=>$value){
                $curl->setHeader($key, $value);
            }
        }
        $url = self::$apiUrl.$method."?".http_build_query($data);
        $curl->get(self::$apiUrl.$method."?".http_build_query($data));
        return json_decode($curl->response);
    }
}