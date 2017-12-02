<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class MessageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var array
     */
    private $server;

    /**
     * @var User
     */
    private $user;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['password' => Hash::make('123')]);
        $response = $this->call('POST', 'api/login', ['email' => $this->user->email, 'password' => '123']);
        $this->server = [
            'HTTP_Authorization' => json_decode($response->getContent(), true)['api_key']
        ];
    }

    /**
     * @test
     */
    public function it_returns_status_401_when_unauthorised(): void
    {
        $response = $this->call('POST', 'api/messages');
        $this->assertEquals(401, $response->status());

        $response = $this->call('GET', 'api/users/1/messages');
        $this->assertEquals(401, $response->status());
    }

    /**
     * @test
     * @dataProvider missingOrWrongParametersDataProvider
     * @param array $parameters
     */
    public function it_returns_status_422_if_parameters_missing_when_storing_a_message(array $parameters): void
    {
        $response = $this->call('POST', 'api/messages', $parameters, [], [], $this->server);
        $this->assertEquals(422, $response->status());
    }

    /**
     * @test
     */
    public function it_stores_a_message(): void
    {
        $response = $this->call(
            'POST',
            'api/messages',
            ['message' => 'msg', 'receiver_id' => $this->user->id],
            [],
            [],
            $this->server
        );
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('messages', ['message' => 'msg', 'receiver_id' => $this->user->id]);
        $this->assertArrayHasKey('result', json_decode($response->getContent(), true));
    }

    /**
     * @test
     */
    public function it_retrieves_messages_by_sender(): void
    {
        $this->call(
            'POST',
            'api/messages',
            ['message' => 'msg', 'receiver_id' => $this->user->id],
            [],
            [],
            $this->server
        );
        $this->seeInDatabase('messages', ['message' => 'msg', 'receiver_id' => $this->user->id]);

        $response = $this->call(
            'GET',
            sprintf('api/users/%s/messages', (string)$this->user->id),
            [],
            [],
            [],
            $this->server
        );
        $this->assertEquals(200, $response->status());
        $result = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('result', $result);
        $this->assertNotEmpty($result['result']);
        $this->assertCount(1, $result['result']);
    }

    /**
     * @return array
     */
    public function missingOrWrongParametersDataProvider(): array
    {
        return [
            [[]],
            [['message' => 'message']],
            [['receiver_id' => '1']],
            [['message' => 'message', 'receiver_id' => '99999']],
            [['password' => 'pass']],
        ];
    }
}
