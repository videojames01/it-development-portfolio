<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_credentials()
    {
        // Given that a visitor enters valid data into the registration form
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];

        // When the user submits the form
        $response = $this->post('/register', $userData);

        // Then their account is created and they are redirected to the home page
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@user.com'
        ]);
        $this->assertAuthenticated();
    }

    public function test_user_cannot_register_with_existing_email()
    {
        // Given that an email is already linked to an existing account
        User::factory()->create(['email' => 'test@user.com']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];

        // When a visitor tries to register using that email
        $response = $this->post('/register', $userData);

        // Then they will receive an error explaining that the email has been used already
        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHasErrorsIn('default', [
            'email' => 'The email has already been taken.'
        ]);
        $this->assertGuest();
        $this->assertEquals(1, User::where('email', 'test@user.com')->count());
    }

    public function test_registration_requires_valid_name()
    {
        // Given that a visitor leaves the name field blank when registering
        $userData = [
            'name' => '',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];

        // When they submit the form
        $response = $this->post('/register', $userData);

        // Then they will receive an error explaining that the name field is required
        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@user.com']);
    }

    public function test_registration_requires_valid_email_format()
    {
        // Given that a visitor provides an invalid email when registering
        $userData = [
            'name' => 'Test User',
            'email' => 'test at user dot com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ];

        // When they submit the form
        $response = $this->post('/register', $userData);

        // Then they will receive an error explaining that their email address is in an invalid format
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['name' => 'Test User']);
    }

    public function test_registration_requires_password_confirmation()
    {
        // Given that the user mistypes their password confirmation
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '987654321',
        ];

        // When they submit the form
        $response = $this->post('/register', $userData);

        // Then they will receive an error explaining that their passwords do not match
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@user.com']);
    }

    public function test_registration_requires_minimum_password_length()
    {
        // Given that the user enters a password less than 8 characters
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123',
            'password_confirmation' => '123',
        ];

        // When they submit the form
        $response = $this->post('/register', $userData);

        // Then they will receive an error that their password is too short
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@user.com']);
    }
}
