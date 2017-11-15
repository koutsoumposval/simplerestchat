<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class UserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @dataProvider missingOrWrongParametersDataProvider
     * @param array $parameters
     */
    public function it_returns_422_for_missing_or_wrong_parameters(array $parameters): void
    {
        $response = $this->call('GET', 'api/login', $parameters);
        $this->assertEquals(422, $response->status());
    }

    /**
     * @test
     */
    public function it_returns_401_on_wrong_credentials(): void
    {
        $response = $this->call('GET', 'api/login', ['email' => 'correct@email.com', 'password' => '123']);
        $this->assertEquals(401, $response->status());
    }

    /**
     * @test
     */
    public function it_returns_200_when_authorised(): void
    {
        $user = factory(User::class)->create(['password' => Hash::make('123')]);
        $this->seeInDatabase('users', ['email' => $user->email]);
        $response = $this->call('GET', 'api/login', ['email' => $user->email, 'password' => '123']);
        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('api_key', json_decode($response->getContent(), true));
    }

    /**
     * @return array
     */
    public function missingOrWrongParametersDataProvider(): array
    {
        return [
            [[]],
            [['email' => 'email']],
            [['email' => 'email@email.com']],
            [['email' => 'email', 'password' => 'pass']],
            [['password' => 'pass']],
        ];
    }
}
