<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DAO;

class UserAuthControllerTests extends TestCase
{
    //Вход пользователя
    public function test_userLogin_WithUser()
    {   
        $user = DAO::createUserRandom();
        $response = $this->get('api/v1/auth/login?email='.$user['email'].'&password=secret');
        $response->assertStatus(200);
        DAO::deleteUser($user['id']);
    }

    public function test_userLogin_WithoutUser()
    {   
        $response = $this->get('api/v1/auth/login?email=123@mail.ru&password=secret');
        $response->assertStatus(404);
    }

    //Выход пользователя
    public function test_userLogout_WithUser()
    {   
        $user = DAO::createUserRandom();
        $response = $this->get('api/v1/auth/logout?api_token='.$user['api_token']);
        $response->assertStatus(200);
        DAO::deleteUser($user['id']);
    }

    public function test_userLogout_WithoutUser()
    {   
        $response = $this->get('api/v1/auth/logout?api_token=123');
        $response->assertStatus(404);
    }

    //Получить список пользователей
    public function test_getUsers_WithUserAdmin()
    {   
        $user = DAO::createUserRandom('Admin');
        $response = $this->get('api/v1/users?api_token='.$user['api_token'].'&page=1&email='.$user['email'].'&password=secret');
        $response->assertStatus(200);
        DAO::deleteUser($user['id']);
    }

    public function test_getUsers_WithUser()
    {   
        $user = DAO::createUserRandom();
        $response = $this->get('api/v1/users?api_token='.$user['api_token'].'&page=1&email='.$user['email'].'&password=secret');
        $response->assertStatus(403);
        DAO::deleteUser($user['id']);
    }

    public function test_getUsers_WithoutUser()
    {   
        $response = $this->get('api/v1/users?api_token=11&page=1&email=11&password=secret');
        $response->assertStatus(401);
    }

    //Обновление пользователя
    public function test_patchUser_WithUserAdmin()
    {   
        $user = DAO::createUserRandom('Admin');
        $response = $this->patch('api/v1/user/'.$user['id'].'?name=123&role=Admin&banned=false&email='.$user['email'].'&password=secret');
        $response->assertStatus(200);
        DAO::deleteUser($user['id']);
    }

    public function test_patchUser_WithUserAdmin_UserNotFound()
    {   
        $user = DAO::createUserRandom('Admin');
        $response = $this->patch('api/v1/user/2?name=123&role=Admin&banned=false&email='.$user['email'].'&password=secret');        
        $response->assertStatus(404);
        DAO::deleteUser($user['id']);
    }

    public function test_patchUser_WithUser()
    {   
        $user = DAO::createUserRandom();
        $response = $this->patch('api/v1/user/'.$user['id'].'?name=123&role=Admin&banned=false&email='.$user['email'].'&password=secret');
        $response->assertStatus(403);
        DAO::deleteUser($user['id']);
    }

    public function test_patchUser_WithoutUser()
    {   
        $user = DAO::createUserRandom();
        $response = $this->patch('api/v1/user/1?name=name&role=Admin&banned=true&email=ieaiaio@bk.ru&password=secret');
        $response->assertStatus(401);
        DAO::deleteUser($user['id']);
    }
   
}