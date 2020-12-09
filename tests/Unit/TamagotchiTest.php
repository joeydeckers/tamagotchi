<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class TamagotchiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function createTamagotchiAuthTest()
    {
        $userData = [
            "name" => "John",
            "age" => "10",
        ];

        $this->json('POST', 'api/tamagotchi/create', $userData, ['Accept' => 'application/json'])
            ->assertStatus(401);
    }

    /** @test
     */
    public function createTamagotchiTest()
    {
        $user = User::create(["name" => "Joey", "password"=> "test", "email"=> "test@gmail.com", "isAdmin" => 0]);

        $userData = [
            "name" => "John",
            "age" => "10",
        ];

        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/tamagotchi/create', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }


    /** @test
     */
    public function createRoomTest()
    {
        $user = User::create(["name" => "Joey", "password"=> "test", "email"=> "testadmin@gmail.com", "isAdmin" => 1]);

        $userData = [
            "size" => "10",
            "type" => "relax",
        ];

        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/hotelroom/create', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    /** @test
     */
    public function createRoomTestFails()
    {
        $user = User::create(["name" => "Joey", "password"=> "test", "email"=> "testadminfail@gmail.com", "isAdmin" => 0]);

        $userData = [
            "size" => "10",
            "type" => "relax",
        ];

        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/hotelroom/create', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400);
    }

    /** @test */
    public function testRegisterFails()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "name"=> [
                  "The name field is required."
                ],
                "email"=> [
                    "The email field is required."
                ],
                "password"=> [
                    "The password field is required."
                ]
            ]);
    }

    /** @test */
    public function testRegisterSucceeds()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",
            "isAdmin" => 0
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    /** @test */
    public function testCreateHotelRoomFails()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",
            "isAdmin" => 0
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
