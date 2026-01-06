<?php
namespace Tests\Feature;
use Auth;
use Database\Seeders\DatabaseSeeder;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\Employee;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Filament\Auth\Pages\Login;
use Filament\Auth\Pages\PasswordReset\{RequestPasswordReset, ResetPassword};
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
class PortalAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }
    public function createEmployee()
    {
        $faker = Faker::create();
        $password = 'password123';
        $employee = Employee::create([
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Hash::make($password),
        ]);
        $employee->assignRole('employee');
        return $employee;
    }
    public function test_login_page_can_be_accessed()
    {
        $response = $this->get('/portal/login');

        $response->assertStatus(200);
    }
    public function test_reset_password_page_can_be_accessed()
    {
        $response = $this->get('/portal/password-reset/request');

        $response->assertStatus(200);
    }
    public function test_user_cannot_access_register_page()
    {
        $response = $this->get('/portal/register');

        $response->assertStatus(404);
    }
    public function test_user_can_login()
    {

        $password = 'password123';
        $employee = $this->createEmployee();
        Livewire::test(name: Login::class)
            ->fillForm([
                'email' => $employee->email,
                'password' => $password,
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors();



        $this->assertEquals(Auth::id(), $employee->id);
    }
    public function test_unregistered_user_cannot_login()
    {

        $faker = Faker::create();

        Livewire::test(name: Login::class)
            ->fillForm([
                'email' => $faker->safeEmail(),
                'password' => 'password123',
            ])
            ->call('authenticate')
            ->assertHasFormErrors()
            ->assertNoRedirect()
        ;
    }
    public function test_user_cannot_login_with_wrong_details()
    {

        $password = 'incorrectPassword';
        $employee = $this->createEmployee();
        Livewire::test(name: Login::class)
            ->fillForm([
                'email' => $employee->email,
                'password' => $password,
            ])
            ->call('authenticate')
            ->assertHasFormErrors()
            ->assertNoRedirect()
        ;
    }
    public function test_user_can_request_password_reset()
    {

        $employee = $this->createEmployee();
        Livewire::test(name: RequestPasswordReset::class)
            ->fillForm([
                'email' => $employee->email,

            ])
            ->call('request')
            ->assertHasNoFormErrors();
    }
    public function test_user_can_reset_password()
    {

        $employee = $this->createEmployee();
        $broker = app(PasswordBroker::class);
        $token = $broker->createToken($employee);
        Livewire::test(ResetPassword::class, ['token' => $token])

            ->fillForm([
                'email' => $employee->email,
                'password' => 'newpassword123',
                'passwordConfirmation' => 'newpassword123',
            ])->call('resetPassword')
            ->assertHasNoFormErrors();
        $this->assertTrue(Hash::check('newpassword123', $employee->fresh()->password));

    }
    public function test_user_can_logout()
    {
        Event::fake();
        $employee = $this->createEmployee();
        event(new Logout(guard: 'web', user: $employee));
        Event::assertDispatched(Logout::class, function ($event) use ($employee) {
            return $event->user->id === $employee->id && $event->guard === 'web';
        });


    }
}