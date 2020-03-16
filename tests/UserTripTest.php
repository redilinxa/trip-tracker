<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTripTest extends WebTestCase
{
    private $client;
    static $user;
    static $trip;
    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = self::createClient();
    }

    // Clean up the test case, called for every defined test
    public function tearDown() { }

    // Clean up the whole test class
    public static function tearDownAfterClass() { }


    public function testUserCreate(){

        $data = [
            'username'=>'someuser',
            'password'=>'somepassword',
            'email'=>'someemail@somedomain.com'
        ];

        $this->client->request(
            'POST',
            '/api/users/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        if ($this->client->getResponse()->getStatusCode() === 200){
            self::$user = json_decode($this->client->getResponse()->getContent())->data->user;
        }
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserUpdate(){

        $data = [
            'username'=>'someuser',
            'password'=>'somenewpassword',
            'email'=>'someemail@somedomain.com'
        ];
        if (self::$user) {
            $this->client->request(
                'PUT',
                '/api/users/'.self::$user->id.'/update',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testTriptCreate(){

        $data = [
            'country'=>'Monaco',
            'startDate'=>'2020-07-19T12:00:00',
            'endDate'=>'2020-08-01T10:45:00',
            'notes'=>'Some notes for the journey!'
        ];

        if (self::$user) {
            $this->client->request(
                'POST',
                '/api/trips/user/' . self::$user->id . '/create',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
            );
            if ($this->client->getResponse()->getStatusCode() === 200) {
                self::$trip = json_decode($this->client->getResponse()->getContent())->data->trip;
            }
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testTripUpdate(){

        $data = [
            'country'=>'AL',
            'startDate'=>'2020-07-19T12:00:00',
            'endDate'=>'2020-08-01T10:45:00',
            'notes'=>'my updated journey the journey!'
        ];

        if (self::$trip){
            $this->client->request(
                'PUT',
                '/api/trips/'. self::$trip->id.'/user/'.self::$user->id.'/update',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testTripDelete(){

        if (self::$trip) {
            $this->client->request(
                'DELETE',
                '/api/trips/'.self::$trip->id.'/user/'.self::$user->id.'/delete',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                ''
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testUserDelete(){

        if (self::$user) {
            $this->client->request(
                'DELETE',
                '/api/users/'.self::$user->id.'/delete',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                ''
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

}