<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegistrationUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_must_be_at_least_8_characters()
    {
        // Given that the password is less than 8 characters
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ];

        // When it attempts to validate
        $validator = validator($userData, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Then they will receive an error that their password is not long enough
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_password_validation_passes_with_8_or_more_characters()
    {
        // Given that the password is 8 or more characters
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

        // When it attempts to validate
        $validator = validator($userData, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Then the validation should succeed
        $this->assertFalse($validator->fails());
    }

    public function test_user_saved_to_database_with_hashed_password()
    {
        // Given that the user creates a plain text password
        $plainPassword = '123456789';
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => Hash::make($plainPassword),
        ];

        // When the user is created
        $user = User::create($userData);

        // Then the user should be saved and their password should be hashed
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@user.com',
        ]);

        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    public function test_email_uniqueness_validation_works()
    {
        // Given that there is an existing user
        User::factory()->create(['email' => 'test@user.com']);

        // When a user is created using the same email address
        $userData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

        $validator = validator($userData, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Then the user should receive an error that the email is already taken
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_name_is_required_for_registration()
    {
        // Given that the user leaves their name blank when registering
        $userData = [
            'name' => '',
            'email' => 'test@user.com',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

        // When it attempts to validate
        $validator = validator($userData, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Then the user should receive an error that the name field is required
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }
}
