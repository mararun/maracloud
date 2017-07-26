#### 马拉赛事接入 PHP-SDK 使用说明

 ```php
$accessKey = '*****1asd324123asd12****';
$env       = \MaraOpen\Env::DEVELOP;
$token     = \MaraOpen\MatchClient::getAccessToken($accessKey)['token'];
$client    = new \MaraOpen\MatchClient($token, $env);

// 赛事列表
$QParam        = new \MaraOpen\MatchQParam();
$QParam->limit = '[0,1]';
$result        = $client->listsMatch($QParam);

// 赛事详情
$result = $client->detailMatch('4188290240783556');

// 获取报名表字段配置
result = $client->getEventFields(4188292723811529);

// 提交报名
$owner        = 18888888888;
$matchID      = 4188290240783556;
$matchEventID = 4188292723811529;
$ticketDefIDs = [4188293579449549];
$runnerInfo   = [
    'country'   => 344,
    'identType' => 2,
    'pName'     => 'name',
    'identCard' => '130827199209088873'),
];

$result = $client->apply($owner, $matchID, $matchEventID, $ticketDefIDs, $runnerInfo);

// 修改报名信息
$owner         = 18888888888;
$applicationID = 41999302133432;
$runnerInfo    = [
    'pName' => changedName,
];
$result = $client->updateApplication($owner, $applicationID, $runnerInfo);

// 报名列表
$result = $client->myApplicationLists(18888888888);

// 报名详情
$result = $client->detailApplication(41999302133432);

// 取消报名
$result = $client->cancel(18888888888, $applicationID);

// 价格计算 ticketDefIDs为套餐id
$matchEventID = 4188292723811529;
$ticketDefIDs = 4188293579449549;
$result       = $client->calculateExpense($matchEventID, $ticketDefIDs);

// 上传图片
$imgPath = 'path/to/file';
$result  = $client->uploadImage($imgPath);
```
