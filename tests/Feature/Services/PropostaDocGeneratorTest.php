<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\Proposta;
use App\Models\User;
use App\Services\PropostaDocGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropostaDocGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private PropostaDocGeneratorService $service;
    private User $user;
    private Empresa $empresa;
    private Proposta $proposta;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new PropostaDocGeneratorService();
        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);
        
        $this->proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $licitacao->id
        ]);
        
        $this->proposta->itens()->create([
            'descricao' => 'Item Teste',
            'quantidade' => 10,
            'valor_unitario' => 100,
            'valor_total' => 1000
        ]);

        $this->actingAs($this->user);
        Storage::fake('public');
    }

    public function test_gerar_docx_com_sucesso()
    {
        $path = $this->service->gerarDocx($this->proposta);

        $this->assertFileExists($path);
        $this->assertStringEndsWith('.docx', $path);
        
        // Verifica se o arquivo foi criado dentro do storage fake (ou real nesse caso, pois phpword salva em disco físico)
        // O service usa storage_path(), então vai salvar no disco real temporário do teste
        // Vamos apenas verificar se existe e se tem tamanho > 0
        
        $this->assertGreaterThan(0, filesize($path));
        
        // Limpeza
        @unlink($path);
    }
    
    public function test_rota_download_docx()
    {
        $response = $this->get(route('propostas.gerar-docx', $this->proposta->id));
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    }
}
