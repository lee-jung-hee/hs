<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{


    /**
     * @test
     */
    public function 글쓰기_화면을_볼_수_있다(): void
    {
        $response = $this->get(route("articles.create"));

        $response->assertStatus(200)->assertSee("글쓰기");
    }

    public function 글을_작성할_수_있다(): void
    {
        $testData = [
            "body"=> "test article",
        ];

        //아래처럼 임의의 로그인된 유저 데이터를 넣어줄 수 있는데 이미 UserFactory.php가 있어서 가능한것임
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route("articles.store"), $testData)
            ->assertRedirect(route("articles.index"));

        $this->assertDatabaseHas("articles", $testData);
    }

    public  function 글_목록을_확인할_수_있다():void
    {

    }
}
