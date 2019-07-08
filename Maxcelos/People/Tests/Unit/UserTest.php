<?php

namespace Maxcelos\People\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maxcelos\Foundation\Entities\LegalDocument;
use Maxcelos\People\Entities\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    // use DatabaseTransactions;

    public function testCreateUser()
    {
        $authUser = factory(User::class)->create();

        $data = factory(User::class)->make()->toArray();

        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';

        $data['roles'] = [1, 2];

        unset($data['email_verified_at']);

        $response = $this->actingAs($authUser, 'api')->json('post', 'v1/users', $data);

        $response->assertStatus(201);
    }

    public function testListUsers()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->json('get', 'v1/users')->assertStatus(200);
    }

    public function testShowUser()
    {
        $authUser = factory(User::class)->create();

        $this->actingAs($authUser, 'api')->json('get', 'v1/users/' . $authUser->uuid)->assertStatus(200);
    }

    public function testUpdateUser()
    {
        $user = factory(User::class)->create();
        $newData = factory(User::class)->create()->toArray();

        unset($newData['email_verified_at']);

        $newData['uuid'] = $user->uuid;
        $newData['email'] = $newData['email'] . 'sd';

        unset($newData['created_at']);

        $response = $this->actingAs($user, 'api')->json('put', 'v1/users/' . $user->uuid, $newData);

        $response->assertJsonFragment($newData)->assertStatus(200);
    }

    public function testDeleteUser()
    {
        $authUser = factory(User::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($authUser, 'api')->json('delete', 'v1/users/' . $user->uuid)->assertStatus(204);
    }
}
