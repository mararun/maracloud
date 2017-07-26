<?php

namespace MaraTest;

class SDKTest extends \PHPUnit_Framework_TestCase
{
    static $client;
    static $owner;
    static $applicationID;
    static $matchID      = 4188290240783556;
    static $matchEventID = 4188292723811529;
    static $ticketDefID  = 4188293579449549;

    public static function setUpBeforeClass()
    {

        static::$owner  = time() . '0';
        $accessKey      = 'ac83b7e73dab361ac9d62cf293110801';
        $env            = \MaraOpen\Env::DEVELOP;
        $token          = \MaraOpen\MatchClient::getAccessToken($accessKey)['token'];
        static::$client = new \MaraOpen\MatchClient($token, $env);
    }

    /**
     * @return \MaraOpen\MatchClient
     */
    public static function getIns()
    {
        return static::$client;
    }

    public function testListMatch()
    {
        $QParam        = new \MaraOpen\MatchQParam();
        $QParam->limit = '[0,1]';
        $result        = static::getIns()->listsMatch($QParam);

        $this->assertTrue(count($result['matchs']) == 1);
    }

    public function testDetailMatch()
    {
        $result = static::getIns()->detailMatch(static::$matchID);

        $this->assertArrayHasKey('match', $result);
    }

    public function testGetEventFields()
    {
        $result = static::getIns()->getEventFields(static::$matchEventID);

        $this->assertTrue(count($result['groups']) > 0);
    }

    public function testApply()
    {
        $owner        = static::$owner;
        $matchID      = static::$matchID;
        $matchEventID = static::$matchEventID;
        $ticketDefIDs = [static::$ticketDefID];
        $runnerInfo   = [
            'country'   => 344,
            'identType' => 2,
            'pName'     => 'name' . time(),
            'identCard' => time(),
        ];


        $result                = static::getIns()->apply($owner, $matchID, $matchEventID, $ticketDefIDs, $runnerInfo);
        static::$applicationID = $result['application']['id'];
        $this->assertTrue($result['application']['matchID'] == $matchID);
    }

    public function testUpdateApplication()
    {
        $owner         = static::$owner;
        $applicationID = static::$applicationID;
        $pName         = 'changed' . time();
        $runnerInfo    = [
            'pName' => $pName,
        ];
        $result        = static::getIns()->updateApplication($owner, $applicationID, $runnerInfo);

        $this->assertTrue($result['result'] == 'success');
    }

    public function testMyApplicationLists()
    {
        $owner  = static::$owner;
        $result = static::getIns()->myApplicationLists($owner);

        $this->assertTrue(count($result['data']) > 0);
    }

    public function testDetailApplication()
    {
        $applicationID = static::$applicationID;
        $result        = static::getIns()->detailApplication($applicationID);

        $this->assertArrayHasKey('application', $result);
    }

    public function testCancel()
    {
        $applicationID = static::$applicationID;
        $owner         = static::$owner;
        $result        = static::getIns()->cancel($owner, $applicationID);

        $this->assertTrue($result['application']['procStatus'] == 98);
    }

    public function testCalculateExpense()
    {
        $matchEventID = static::$matchEventID;
        $ticketDefIDs = static::$ticketDefID;
        $result       = static::getIns()->calculateExpense($matchEventID, $ticketDefIDs);

        $this->assertTrue($result['expense'] == 2);
    }

    public function testUploadImage()
    {
        $imgPath = dirname(__FILE__) . '/../img/test.jpg';
        $result  = static::getIns()->uploadImage($imgPath);

        $this->assertStringEndsWith('jpg', $result['url']);
    }
}
