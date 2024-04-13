<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use Illuminate\Support\Carbon;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글쓰기_화면을_볼_수_없다(): void
    {
        $this->get(route('articles.create'))
            ->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

     /**
     * @test
     */
    public function 글을_작성할_수_있다(): void
    {
        $testData = [
            'body'=> 'test article',
        ];

        //아래처럼 임의의 로그인된 유저 데이터를 넣어줄 수 있는데 이미 UserFactory.php가 있어서 가능한것임
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('articles.store'), $testData)
            ->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', $testData);
    }

    /**
     * @test
     */
    public function 로그인_하지_않은_사용자는_글을_작성할_수_없다(): void
    {
        $testData = [
            'body'=> 'test article',
        ];

        $this->post(route('articles.store'), $testData)
            ->assertRedirectToRoute('login');

        $this->assertDatabaseMissing('articles', $testData);
    }

     /**
     * @test
     */
    public  function 글_목록을_확인할_수_있다():void
    {
        $now=Carbon::now();
        $afterOneSecond = (clone $now)->addSecond();

        $article1 = Article::factory()->create(
            ['created_at' => $now]
        );
        $article2 = Article::factory()->create(
            ['created_at'=> $afterOneSecond]
        );

        $this->get(route('articles.index'))
        ->assertSee($article1->body)
        ->assertSee($article2->body)
        ->assertSeeInOrder([
            $article2->body,
            $article1->body
        ]);
    }

    /**
     * @test
     */

     public function 개별_글을_조회할_수_있다(): void
     {
        $article = Article::factory()->create();

        $this->get(route('articles.show', ['article' => $article->id]))
        ->assertSuccessful()
        ->assertSee($article->body);
     }

    /**
     * @test
     */
    public function 로그인한_사용자는_글수정_화면을_볼_수_있다(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('articles.edit', ['article'=>$article->id]))
            ->assertStatus(200)
            ->assertSee('글 수정하기');
    }

    /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글수정_화면을_볼_수_없다(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.edit', ['article'=>$article->id]))
            ->assertSee('login');
    }

    /**
     * @test
     */
    public function 로그인한_사옹자는_글을_수정할_수_있다(): void
    {
        $user = User::factory()->create();

        $payload = ['body' => '수정된 글'];

        $article = Article::factory()->create(['user_id' => $user->id]);
        // factory에 create메소드에 이렇게 배열로 넘겨주면, 
        // 팩토리로 Aricle모델을 만들어줄 때 배열의 값을 덮어써주게 된다, 
        // 기본적으로 정해진 database>factory>ArticleFactory.php를 보면 
        // 기본적으로 User::factory()로 값을 메겨준게 있는데, user_id를 밖에서 넣어주게되면 
        // User::factory()대신에 밖에서 넣어준 값이 쓰이게 된다
        $this->actingAs($user)
            ->patch(
            route('articles.update', ['article'=>$article->id]),
            $payload
        )->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', $payload);

        $this->assertEquals($payload['body'],$article->refresh()->body);
        //내가 입력한 페이로드와 아티클에 저장된 바디가 제대로 수정됬는지 보는것
    }

    /**
     * @test
     */
    public function 로그인하지_않은_사옹자는_글을_수정할_수_없다(): void
    {
        $payload = ['body' => '수정된 글'];

        $article = Article::factory()->create();

        $this->patch(
                route('articles.update', ['article'=>$article->id]),
                $payload
            )->assertRedirectToRoute('login');

        $this->assertDatabaseMissing('articles', $payload);

        $this->assertNotEquals($payload['body'],$article->refresh()->body);
    }
     /**
     * @test
     */
    public function 로그인한_사용자는_글을_삭제할_수_있다(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create();

        $this->actingAs($user)
            ->delete(route('articles.destroy', ['article' => $article->id]))
            ->assertRedirect(route('articles.index'));

        $this->assertDatabaseMissing('articles', ['id'=> $article->id]);
    }

     /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글을_삭제할_수_없다(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create(['user_id'=>$user->id]);

        $this->delete(route('articles.destroy', ['article' => $article->id]))
            ->assertRedirectToRoute('login');

        $this->assertDatabaseHas('articles', ['id'=> $article->id]);
    }
}
