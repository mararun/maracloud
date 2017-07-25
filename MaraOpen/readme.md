 ```php
$accessKey = '*****1asd324123asd12****';
$env       = \MaraOpen\Env::DEVELOP;
$token     = \MaraOpen\MatchClient::getAccessToken($accessKey)['token'];
$client    = new \MaraOpen\MatchClient($token, $env);

// 赛事列表
$QParam        = new \MaraOpen\MatchQParam();
$QParam->limit = '[0,1]';
$result        = $client->listsMatch($QParam);
var_dump($result); // array(2) { ["matchs"]=> array(0) { } ["total"]=> string(1) "0" }

// 赛事详情
$result = $client->detailMatch('3998711810526577');
// result :
/*
*/
