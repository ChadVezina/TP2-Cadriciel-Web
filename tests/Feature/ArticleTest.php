<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that articles index page can be viewed.
     */
    public function test_articles_index_can_be_rendered(): void
    {
        $response = $this->get('/articles');

        $response->assertStatus(200);
        $response->assertViewIs('articles.index');
    }

    /**
     * Test that an authenticated user can create an article.
     */
    public function test_authenticated_user_can_create_article(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/articles', [
            'title_fr' => 'Titre en français',
            'content_fr' => 'Contenu en français avec au moins 10 caractères',
            'title_en' => 'English title',
            'content_en' => 'English content with at least 10 characters',
            'language' => 'fr',
        ]);

        $response->assertRedirect('/articles');
        $this->assertDatabaseHas('articles', [
            'title' => 'Titre en français',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that a guest cannot create an article.
     */
    public function test_guest_cannot_create_article(): void
    {
        $response = $this->post('/articles', [
            'title_fr' => 'Titre en français',
            'content_fr' => 'Contenu en français',
            'title_en' => 'English title',
            'content_en' => 'English content',
            'language' => 'fr',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('articles', 0);
    }

    /**
     * Test that only article owner can update it.
     */
    public function test_only_article_owner_can_update_it(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $article = Article::factory()->create([
            'user_id' => $owner->id,
        ]);

        $this->actingAs($otherUser);

        $response = $this->put("/articles/{$article->id}", [
            'title_fr' => 'Titre modifié',
            'content_fr' => 'Contenu modifié avec texte suffisant',
            'title_en' => 'Modified title',
            'content_en' => 'Modified content with enough text',
            'language' => 'fr',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that article owner can delete it.
     */
    public function test_article_owner_can_delete_it(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete("/articles/{$article->id}");

        $response->assertRedirect('/articles');
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }
}
