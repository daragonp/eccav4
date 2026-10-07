<?php

namespace Tests\Feature;

use App\Models\LibraryCategory;
use App\Models\LibraryResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = new User();
        $user->name = 'Admin de prueba';
        $user->email = 'admin_' . uniqid() . '@test.local';
        $user->password = bcrypt('secret');
        $user->image = '';
        $user->save();

        $role = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'administrador',
            'guard_name' => 'web',
        ]);
        $user->assignRole($role);

        return $user->fresh();
    }

    private function recurso(array $attrs = []): LibraryResource
    {
        return LibraryResource::create(array_merge([
            'title' => 'Recurso de prueba',
            'slug' => 'recurso-de-prueba-' . uniqid(),
            'type' => 'libro',
            'file' => 'libro.pdf',
            'published' => true,
            'published_at' => now(),
        ], $attrs));
    }

    // ==================== PÚBLICO ====================

    public function test_galeria_lista_solo_publicados_y_no_eliminados(): void
    {
        $this->recurso(['title' => 'Visible', 'slug' => 'visible']);
        $this->recurso(['title' => 'Despublicado', 'slug' => 'despub', 'published' => false]);
        $eliminado = $this->recurso(['title' => 'Eliminado', 'slug' => 'elim']);
        $eliminado->delete();

        $res = $this->get('/library');
        $res->assertStatus(200);
        $res->assertSee('Visible');
        $res->assertDontSee('Despublicado');
        $res->assertDontSee('Eliminado');
    }

    public function test_galeria_filtra_por_tipo(): void
    {
        $this->recurso(['title' => 'Un libro', 'slug' => 'un-libro', 'type' => 'libro']);
        $this->recurso(['title' => 'Un video', 'slug' => 'un-video', 'type' => 'video', 'file' => null, 'external_url' => 'https://youtu.be/abc']);

        $res = $this->get('/library?type=video');
        $res->assertStatus(200);
        $res->assertSee('Un video');
        $res->assertDontSee('Un libro');
    }

    public function test_galeria_filtra_por_categoria(): void
    {
        $cat = LibraryCategory::create(['name' => 'Teología', 'slug' => 'teologia']);
        $this->recurso(['title' => 'Con categoria', 'slug' => 'con-cat', 'category_id' => $cat->id]);
        $this->recurso(['title' => 'Sin categoria', 'slug' => 'sin-cat']);

        $res = $this->get('/library?category=teologia');
        $res->assertStatus(200);
        $res->assertSee('Con categoria');
        $res->assertDontSee('Sin categoria');
    }

    public function test_detalle_200_con_embed_de_youtube(): void
    {
        $this->recurso([
            'title' => 'Video YT', 'slug' => 'video-yt', 'type' => 'video',
            'file' => null, 'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $res = $this->get('/library/video-yt');
        $res->assertStatus(200);
        $res->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ', false);
        $res->assertSee('<iframe', false);
    }

    public function test_detalle_404_para_slug_inexistente(): void
    {
        $this->get('/library/no-existe')->assertStatus(404);
    }

    public function test_detalle_404_para_recurso_despublicado(): void
    {
        $this->recurso(['slug' => 'oculto', 'published' => false]);
        $this->get('/library/oculto')->assertStatus(404);
    }

    public function test_descarga_fuerza_descarga_del_archivo(): void
    {
        $dir = public_path('library/files');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $nombre = 'test_' . uniqid() . '.pdf';
        file_put_contents($dir . '/' . $nombre, '%PDF-1.1');
        $r = $this->recurso(['slug' => 'descargable', 'file' => $nombre]);

        $res = $this->get(route('library.download', $r->id));
        $res->assertStatus(200);
        $res->assertHeader('content-disposition');

        @unlink($dir . '/' . $nombre);
    }

    // ==================== ADMIN ====================

    public function test_rutas_admin_redirigen_sin_auth(): void
    {
        $this->get('/show-library')->assertRedirect();
    }

    public function test_store_crea_con_archivo(): void
    {
        $res = $this->actingAs($this->admin())->post('/addlibrary', [
            'title' => 'Nuevo libro',
            'type' => 'libro',
            'media_mode' => 'archivo',
            'file' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
            'published' => '1',
        ]);

        $res->assertRedirect('show-library');
        $this->assertDatabaseHas('library_resources', ['title' => 'Nuevo libro', 'type' => 'libro']);
        $r = LibraryResource::where('title', 'Nuevo libro')->first();
        $this->assertNotNull($r->file);
        $this->assertNull($r->external_url);
    }

    public function test_store_crea_con_enlace(): void
    {
        $res = $this->actingAs($this->admin())->post('/addlibrary', [
            'title' => 'Video enlazado',
            'type' => 'video',
            'media_mode' => 'enlace',
            'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'published' => '1',
        ]);

        $res->assertRedirect('show-library');
        $r = LibraryResource::where('title', 'Video enlazado')->first();
        $this->assertNull($r->file);
        $this->assertSame('https://www.youtube.com/watch?v=dQw4w9WgXcQ', $r->external_url);
    }

    public function test_store_rechaza_sin_archivo_ni_enlace(): void
    {
        $res = $this->actingAs($this->admin())->post('/addlibrary', [
            'title' => 'Incompleto',
            'type' => 'libro',
            'media_mode' => 'archivo',
            'published' => '1',
        ]);

        $res->assertSessionHasErrors();
        $this->assertDatabaseMissing('library_resources', ['title' => 'Incompleto']);
    }

    public function test_edit_persiste_los_cambios(): void
    {
        $r = $this->recurso(['title' => 'Original', 'slug' => 'original']);

        $res = $this->actingAs($this->admin())->post('/update-library/' . $r->id, [
            'title' => 'Editado',
            'type' => 'audio',
            'media_mode' => 'enlace',
            'external_url' => 'https://example.com/audio.mp3',
            'published' => '1',
            '_library_edit_id' => $r->id,
        ]);

        $res->assertRedirect();
        $r->refresh();
        $this->assertSame('Editado', $r->title);
        $this->assertSame('audio', $r->type);
        $this->assertSame('https://example.com/audio.mp3', $r->external_url);
        $this->assertNull($r->file); // la resolución limpió la columna opuesta
    }

    public function test_edit_sin_publicado_despublica(): void
    {
        $r = $this->recurso(['slug' => 'pub', 'published' => true]);

        $this->actingAs($this->admin())->post('/update-library/' . $r->id, [
            'title' => $r->title,
            'type' => 'libro',
            'media_mode' => 'archivo', // sin archivo nuevo, conserva el actual
            // 'published' ausente => boolean(false)
            '_library_edit_id' => $r->id,
        ]);

        $r->refresh();
        $this->assertFalse($r->published);
    }

    public function test_edit_con_publicado_marcado_queda_true(): void
    {
        $r = $this->recurso(['slug' => 'pub2', 'published' => false]);

        $this->actingAs($this->admin())->post('/update-library/' . $r->id, [
            'title' => $r->title,
            'type' => 'libro',
            'media_mode' => 'archivo',
            'published' => '1',
            '_library_edit_id' => $r->id,
        ]);

        $r->refresh();
        $this->assertTrue($r->published);
    }

    public function test_toggle_alterna_publicado_sin_tocar_deleted_at(): void
    {
        $r = $this->recurso(['slug' => 'toggle', 'published' => true]);

        $this->actingAs($this->admin())->post('/toggle-library/' . $r->id);
        $r->refresh();
        $this->assertFalse($r->published);
        $this->assertNull($r->deleted_at);
    }

    public function test_destroy_soft_delete(): void
    {
        $r = $this->recurso(['slug' => 'soft']);
        $this->actingAs($this->admin())->post('/delete-library/' . $r->id);
        $this->assertSoftDeleted('library_resources', ['id' => $r->id]);
    }

    public function test_activate_restaura(): void
    {
        $r = $this->recurso(['slug' => 'restore']);
        $r->delete();

        $this->actingAs($this->admin())->post('/activate-library/' . $r->id);
        $this->assertDatabaseHas('library_resources', ['id' => $r->id, 'deleted_at' => null]);
    }

    public function test_delete_hard_elimina(): void
    {
        $r = $this->recurso(['slug' => 'hard']);
        $this->actingAs($this->admin())->delete('/realdelete-library/' . $r->id);
        $this->assertDatabaseMissing('library_resources', ['id' => $r->id]);
    }

    public function test_get_edit_modal_responde_con_valores(): void
    {
        $r = $this->recurso(['title' => 'Para modal', 'slug' => 'modal']);
        $res = $this->actingAs($this->admin())->get('/admin/edit-modal/library/' . $r->id);
        $res->assertStatus(200);
        $res->assertSee('Para modal');
        $res->assertSee('library_title', false);
    }
}
