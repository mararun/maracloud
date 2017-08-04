<?php

namespace MaraOpen;

class MatchClient extends BaseClient
{
    /**
     * 获取Authorization Token, 此Token建议保留在本地
     * @param string $accessKey 分配的accessKey
     * @param string $env 环境变量 (develop/online),表示获取哪个环境的key
     */
    public static function getAccessToken($accessKey, $env = Env::DEVELOP)
    {
        $domain = $env == Env::DEVELOP ? static::$developDomain : static::$onlineDomain;
        $url    = $domain . '/v1/auth/getAccessToken';
        $params = ['accessKey' => $accessKey];

        return Request::get($url, $params);
    }

     /**
     * 获取赛事列表
     * @param MatchQParam $QParam 查询对象,表明支持的字段
     * @return mixed|null
     */
    public function listsMatch(MatchQParam $QParam)
    {
        $url   = $this->getDomain() . '/v1/match/lists';
        $param = $QParam->toArr();

        return $this->get($url, $param);
    }

   /**
     * 获取套餐列表
     */
    public function listsPackage($matchID,$matchType)
    {
        $url   = $this->getDomain() . '/v1/event/listPackageByType';
        $param = $QParam->toArr();

        return $this->get($url, $param);
    }

    /**
     * 获取赛事详情
     * @param $id 赛事ID
     */
    public function detailMatch($id)
    {
        $url   = $this->getDomain() . '/v1/match/detail';
        $param = ['id' => $id];

        return $this->get($url, $param);
    }

    /**
     * 获取比赛项目的字段配置(用来生产表单,和表单内容翻译用)
     * @param $matchEventID 比赛项目ID
     */
    public function getEventFields($matchEventID)
    {
        $url   = $this->getDomain() . '/v1/event/fields';
        $param = ['matchEventID' => $matchEventID];

        return $this->get($url, $param);
    }

    /**
     * 报名
     * @param $owner   这里表示拥有者的电话号码,表示唯一的账号
     * @param $matchID     比赛ID
     * @param $matchEventID     比赛项目ID
     * @param $ticketDefIDs (门票、套餐、商品)ID的集合
     * @param array $runnerInfo 报名时根据比赛项目的字段配置拼接成的RunnerInfo
     */
    public function apply($owner, $matchID, $matchEventID, $ticketDefIDs, $runnerInfo = [])
    {
        $url  = $this->getDomain() . '/v1/application/apply';
        $data = [
            'owner'        => $owner,
            'matchID'      => $matchID,
            'matchEventID' => $matchEventID,
            'ticketDefIDs' => $ticketDefIDs,
            'runnerInfo'   => $runnerInfo,
        ];

        return $this->post($url, $data);
    }

    /**
     * 修改报名
     * @param $owner   这里表示拥有者的电话号码,表示唯一的账号
     * @param $applicationID    报名ID
     * @param array $runnerInfo 报名信息
     */
    public function updateApplication($owner, $applicationID, $runnerInfo = [])
    {
        $url                 = $this->getDomain() . '/v1/application/update';
        $runnerInfo['owner'] = $owner;
        $runnerInfo['id']    = $applicationID;

        return $this->post($url, $runnerInfo);
    }

    /**
     * 我的报名
     * @param $owner   这里表示拥有者的电话号码,表示唯一的账号
     * @param $matchEventID 比赛项目ID,如果为0 ,表示查询所有已报名过的列表
     */
    public function myApplicationLists($owner, $matchEventID = 0)
    {
        $url   = $this->getDomain() . '/v1/application/lists';
        $param = [
            'owner' => $owner,
        ];

        if ($matchEventID !== 0) {
            $param['matchEventID'] = $matchEventID;
        }

        return $this->get($url, $param);
    }

    /**
     * 获取报名详情
     * @param $id 报名ID
     */
    public function detailApplication($id)
    {
        $url   = $this->getDomain() . '/v1/application/detail';
        $param = [
            'id' => $id,
        ];

        return $this->get($url, $param);
    }

    /**
     * 取消报名
     * @param $owner   这里表示拥有者的电话号码,表示唯一的账号
     * @param $id 报名ID
     */
    public function cancel($owner, $id)
    {
        $url   = $this->getDomain() . '/v1/application/cancel';
        $param = [
            'id'    => $id,
            'owner' => $owner,
        ];

        return $this->post($url, $param);
    }

    /**
     * 计算费用
     * @param $matchEventID
     * @param array $ticketDefIDs 表示套餐、门票的集合
     */
    public function calculateExpense($matchEventID, $ticketDefIDs = [])
    {
        $param = [
            'matchEventID' => $matchEventID,
            'ticketDefIDs' => $ticketDefIDs,
        ];
        $url   = $this->getDomain() . '/v1/application/calcExpense';

        return $this->get($url, $param);
    }

    /**
     * 上传图片接口
     * @param $filePath 本地图片路径
     */
    public function uploadImage($filePath)
    {
        $url      = $this->getDomain() . '/v1/uploader/upload';
        $realPath = realpath($filePath);
        $data     = [
            'file'     => new \CURLFile($realPath),
            'fileType' => pathinfo($realPath)['extension'],
        ];
        $headers  = [
            'Authorization:' . $this->authorization,
        ];
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $return_data = curl_exec($ch);
        curl_close($ch);

        return json_decode($return_data, true);
    }
}
