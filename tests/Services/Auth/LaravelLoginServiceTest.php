<?php namespace Tests\Services\Auth;


use App\Services\Auth\Laravel\LoginService;
use Illuminate\Support\Facades\Auth;

class LaravelLoginServiceTest extends \TestCase {

    /**
     * @var LoginService
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->service = new LoginService();
    }

    /**
     * @dataProvider attemptProvider
     */
    public function test_attempt($result, $credentials)
    {
        Auth::shouldReceive('attempt')->andReturn($result);

        $this->assertEquals($result, $this->service->attempt($credentials));
    }

    public function attemptProvider()
    {
        return [
            [true, ['email' => 'foo', 'password' => 'bar']],
            [false, ['email' => 'foo', 'password' => 'bar']],
        ];
    }

}