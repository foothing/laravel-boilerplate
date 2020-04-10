<?php namespace Tests\Services\Auth;

use App\Reminder;
use App\Services\Auth\Laravel\LoginService;
use App\Services\Auth\Laravel\PasswordService;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LaravelPasswordServiceTest extends \TestCase {

    /**
     * @var LoginService
     */
    protected $service;

    protected $users;

    protected $reminders;

    public function setUp()
    {
        parent::setUp();

        $this->users = \Mockery::mock('App\Repositories\User\UserRepositoryInterface');
        $this->reminders = \Mockery::mock('App\Repositories\User\ReminderRepositoryInterface');

        $this->service = new PasswordService(
            $this->users,
            $this->reminders
        );
    }

    /**
     * @dataProvider beginResetProvider
     */
    public function test_BeginReset($email, $user, $reminder)
    {
        $service = \Mockery::mock(
            'App\Services\Auth\Laravel\PasswordService[emailBeginReset,createReminder]',
            [
                $this->users,
                $this->reminders
            ]
        );

        $this
            ->users
            ->shouldReceive('findByEmail')
            ->with($email)
            ->andReturn($user);

        if (! $user) {
            $this->expectException(\App\Services\Auth\PasswordResetException::class);
            return $service->beginReset($email);
        }

        $service->shouldReceive('createReminder')->andReturn($reminder);

        if (! $reminder) {
            $this->expectException(\App\Services\Auth\PasswordResetException::class);
        } else {
            $service
                ->shouldReceive('emailBeginReset')
                ->withArgs([$email, \Mockery::any()]);
        }

        $service->beginReset($email);
    }

    public function beginResetProvider()
    {
        return [
            ["foo@bar.baz", null, null],
            ["foo@bar.baz", null, true],
            ["foo@bar.baz", true, new Reminder()],
            ["foo@bar.baz", true, null],
        ];
    }

    public function test_emailBeginReset_fails_if_user_not_found()
    {
        $email = 'foo';

        $this
            ->users
            ->shouldReceive('findByEmail')
            ->with($email)
            ->andReturnNull();

        $this->expectException(\App\Services\Auth\PasswordResetException::class);

        $this->service->emailBeginReset($email, 'bar');
    }

    public function test_emailBeginReset()
    {
        $email = 'foo@bar.baz';

        $this
            ->users
            ->shouldReceive('findByEmail')
            ->andReturn((object)['foo' => 'bar']);

        Mail
            ::shouldReceive('to')
            ->with($email)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('send');

        $this->service->emailBeginReset($email, 'bar');
    }

    public function test_finalizeReset_fails_if_reminder_is_not_found()
    {
        $this
            ->reminders
            ->shouldReceive('findByToken')
            ->andReturnNull();

        $this->expectException(\App\Services\Auth\PasswordResetException::class);

        $this->service->finalizeReset('foo', 'bar');
    }

    public function test_finalizeReset_fails_if_user_is_not_found()
    {
        $this
            ->reminders
            ->shouldReceive('findByToken')
            ->andReturn(new Reminder());

        $this
            ->users
            ->shouldReceive('findByEmail')
            ->andReturnNull();

        $this->expectException(\App\Services\Auth\PasswordResetException::class);

        $this->service->finalizeReset('foo', 'bar');
    }

    /**
     * @dataProvider finalizeResetProvider
     *
     * @param $token
     * @param $password
     * @param $hashedPassword
     */
    public function test_finalizeReset($token, $password, $hashedPassword, $user)
    {
        $this->reminders->shouldReceive('findByToken')->andReturn(new Reminder());

        $this->users->shouldReceive('findByEmail')->andReturn($user);

        DB::shouldReceive('beginTransaction');
        DB::shouldReceive('commit');

        Hash::shouldReceive('make')->andReturn($hashedPassword);

        $this->users->shouldReceive('update')->andReturn($user);
        $this->reminders->shouldReceive('delete');

        $user = $this->service->finalizeReset($token, $password);

        $this->assertNotEquals($user->password, $password);
        $this->assertEquals($hashedPassword, $user->password);
    }

    public function finalizeResetProvider()
    {
        return [
            ['token', '123456', 'hash', new User(['name' => 'foo', 'email' => 'bar'])],
            ['token', '', 'hash', new User(['name' => 'foo', 'email' => 'bar'])],
        ];
    }
}
