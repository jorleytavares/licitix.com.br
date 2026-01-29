<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\Proposta;
use App\Models\User;
use App\Services\PropostaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropostaDocumentoTest extends TestCase
{
    use RefreshDatabase;

    private PropostaService $service;
    private User $user;
    private Empresa $empresa;
    private Proposta $proposta;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new PropostaService();
        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);
        
        $this->proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $licitacao->id
        ]);
        
        $this->actingAs($this->user);
        Storage::fake('public');
    }

    public function test_adicionar_documento_com_sucesso()
    {
        $arquivo = UploadedFile::fake()->create('documento.pdf', 100);
        $dados = ['tipo' => 'Habilitação'];

        $documento = $this->service->adicionarDocumento($this->proposta, $dados, $arquivo);

        $this->assertDatabaseHas('proposta_documentos', [
            'id' => $documento->id,
            'proposta_id' => $this->proposta->id,
            'tipo' => 'Habilitação',
            'nome_original' => 'documento.pdf'
        ]);

        Storage::disk('public')->assertExists($documento->caminho_arquivo);
    }

    public function test_remover_documento_com_sucesso()
    {
        $arquivo = UploadedFile::fake()->create('documento_remove.pdf', 100);
        $dados = ['tipo' => 'Proposta Técnica'];
        
        $documento = $this->service->adicionarDocumento($this->proposta, $dados, $arquivo);
        
        // Verifica se existe antes de remover
        Storage::disk('public')->assertExists($documento->caminho_arquivo);

        $this->service->removerDocumento($documento);

        $this->assertDatabaseMissing('proposta_documentos', ['id' => $documento->id]);
        Storage::disk('public')->assertMissing($documento->caminho_arquivo);
    }
}
