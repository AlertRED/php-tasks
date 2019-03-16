<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DAO;

class UserGroupControllerTests extends TestCase
{
    // Получение групп пользователя
    public function test_getGroupsByUser_WithUser()
    {   
        $user = DAO::createUserRandom();
        $response = $this->get('api/v0/user/1/groups');
        $response->assertStatus(200);
        DAO::deleteUser($user['id']);
    }

    public function test_getGroupsByUser_WithoutUser()
    {   
        $response = $this->get('api/v0/user/1/groups');
        $response->assertStatus(404);
    }

    // Создание группы
    public function test_postGroup_WithoutGroup()
    {   
        $response = $this->post('api/v0/users/group?name=322');
        $response->assertStatus(200);
    }

    public function test_postGroup_WithGroup()
    {   
        $group = DAO::createGroup('322');
        $response = $this->post('api/v0/users/group?name=322');
        $response->assertStatus(302);
        DAO::deleteGroup($group['id']);
    }

    // Удаление группы
    public function test_deleteUsetByGroup_WithGroup()
    {   
        $group = DAO::createGroup('322');
        $response = $this->delete('api/v0/users/groups/1');
        $response->assertStatus(200);
    }

    public function test_deleteUsetByGroup_WithoutGroup()
    {   
        $response = $this->delete('api/v0/users/groups/1');
        $response->assertStatus(200);
    }

    //Добавление пользователя в группу
    public function test_addUserToGroup_WithGroup_WithUser()
    {   
        $group = DAO::createGroup('322');
        $user = DAO::createUserRandom();
        $response = $this->post('api/v0/user/1/group/1');
        $response->assertStatus(200);
        DAO::deleteGroup($group['id']);
        DAO::deleteUser($user['id']);
    }

    public function test_addUserToGroup_WithGroup_WithoutUser()
        {   
            $group = DAO::createGroup('322');
            $response = $this->post('api/v0/user/1/group/1');
            $response->assertStatus(404);
            DAO::deleteGroup($group['id']);
        }

    public function test_addUserToGroup_WithoutGroup_WithUser()
        {   
            $user = DAO::createUserRandom();
            $response = $this->post('api/v0/user/1/group/1');
            $response->assertStatus(404);
            DAO::deleteUser($user['id']);
        }

    public function test_addUserToGroup_WithoutGroup_WithoutUser()
        {   $response = $this->post('api/v0/user/1/group/1');
            $response->assertStatus(404);
        }

    //Добавление пользователя в группу
    public function test_deleteUserToGroup_WithGroup_WithUser()
    {   
        $group = DAO::createGroup('322');
        $user = DAO::createUserRandom();
        $response = $this->delete('api/v0/user/1/group/1');
        $response->assertStatus(200);
        DAO::deleteGroup($group['id']);
        DAO::deleteUser($user['id']);
    }

    public function test_deleteUserToGroup_WithGroup_WithoutUser()
        {   
            $group = DAO::createGroup('322');
            $response = $this->delete('api/v0/user/1/group/1');
            $response->assertStatus(404);
            DAO::deleteGroup($group['id']);
        }

    public function test_deleteUserToGroup_WithoutGroup_WithUser()
        {   
            $user = DAO::createUserRandom();
            $response = $this->delete('api/v0/user/1/group/1');
            $response->assertStatus(404);
            DAO::deleteUser($user['id']);
        }

    public function test_deleteUserToGroup_WithoutGroup_WithoutUser()
        {   $response = $this->delete('api/v0/user/1/group/1');
            $response->assertStatus(404);
        }

}
