<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\Proposta;
use App\Models\User;
use App\Services\PropostaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropostaServiceTest extends TestCase
{
    use RefreshDatabase;

    private PropostaService $service;
    private User $user;
    private Empresa $empresa;
    private Licitacao $licitacao;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new PropostaService();
        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        $this->licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);
        
        $this->actingAs($this->user);
    }

    public function test_criar_proposta_com_sucesso()
    {
        $dados = [
            'licitacao_id' => $this->licitacao->id,
            'status' => 'rascunho',
            'valor_total' => 1000.00,
            'itens' => [
                [
                    'descricao' => 'Item 1',
                    'quantidade' => 10,
                    'valor_unitario' => 100.00
                ]
            ]
        ];

        $proposta = $this->service->criarProposta($dados);

        $this->assertDatabaseHas('propostas', [
            'id' => $proposta->id,
            'licitacao_id' => $this->licitacao->id,
            'valor_total' => 1000.00
        ]);

        $this->assertDatabaseHas('proposta_itens', [
            'proposta_id' => $proposta->id,
            'descricao' => 'Item 1'
        ]);
    }

    public function test_atualizar_proposta()
    {
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $this->licitacao->id,
            'valor_total' => 500.00
        ]);

        $novosDados = [
            'valor_total' => 750.00,
            'status' => 'em_analise'
        ];

        $atualizada = $this->service->atualizarProposta($proposta, $novosDados);

        $this->assertEquals(750.00, $atualizada->valor_total);
        $this->assertEquals('em_analise', $atualizada->status);
    }

    public function test_excluir_proposta()
    {
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $this->licitacao->id
        ]);
        
        $proposta->itens()->create([
            'descricao' => 'Item Teste',
            'quantidade' => 1,
            'valor_unitario' => 100,
            'valor_total' => 100
        ]);

        $this->service->excluirProposta($proposta);

        $this->assertDatabaseMissing('propostas', ['id' => $proposta->id]);
        $this->assertDatabaseMissing('proposta_itens', ['proposta_id' => $proposta->id]);
    }

    public function test_enviar_proposta()
    {
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $this->licitacao->id,
            'status' => 'rascunho'
        ]);

        $this->service->enviarProposta($proposta);

        $this->assertEquals('enviada', $proposta->fresh()->status);
    }

    public function test_marcar_como_ganha()
    {
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $this->licitacao->id
        ]);

        $this->service->marcarComoGanha($proposta);

        $this->assertEquals('ganhou', $proposta->fresh()->status);
    }

    public function test_marcar_como_perdida()
    {
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $this->licitacao->id
        ]);

        $this->service->marcarComoPerdida($proposta);

        $this->assertEquals('perdeu', $proposta->fresh()->status);
    }
}
