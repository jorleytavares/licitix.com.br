<?php

namespace Tests\Feature\Services;

use App\Models\Empresa;
use App\Models\Faturamento;
use App\Models\Licitacao;
use App\Models\Proposta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceiroIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Empresa $empresa;
    private Proposta $proposta;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);
        
        $this->proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $licitacao->id,
            'status' => 'ganhou',
            'valor_total' => 5000.00
        ]);

        $this->actingAs($this->user);
    }

    public function test_pode_gerar_fatura_para_proposta_ganha()
    {
        $dadosFatura = [
            'numero_nf' => 'NF-12345',
            'valor' => 5000.00,
            'data_emissao' => now()->format('Y-m-d'),
            'data_vencimento' => now()->addDays(30)->format('Y-m-d'),
        ];

        $response = $this->post(route('financeiro.faturar', $this->proposta->id), $dadosFatura);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('faturamentos', [
            'proposta_id' => $this->proposta->id,
            'numero_nf' => 'NF-12345',
            'valor' => 5000.00,
            'status' => 'pendente'
        ]);

        $this->proposta->refresh();
        $this->assertEquals('faturado', $this->proposta->status);
    }

    public function test_validacao_fatura()
    {
        $response = $this->post(route('financeiro.faturar', $this->proposta->id), [
            'numero_nf' => '', // Inválido
            'valor' => -100, // Inválido
        ]);

        $response->assertSessionHasErrors(['numero_nf', 'valor', 'data_emissao', 'data_vencimento']);
    }

    public function test_view_proposta_exibe_secao_faturamento()
    {
        // Cria uma fatura para exibir
        Faturamento::create([
            'proposta_id' => $this->proposta->id,
            'empresa_id' => $this->empresa->id,
            'numero_nf' => 'NF-TEST-VIEW',
            'valor' => 1000.00,
            'data_emissao' => now(),
            'data_vencimento' => now()->addDays(15),
            'status' => 'pendente'
        ]);

        $response = $this->get(route('propostas.detalhes', $this->proposta->id));
        
        $response->assertStatus(200);
        $response->assertSee('Faturamento / Notas Fiscais');
        $response->assertSee('NF-TEST-VIEW');
        $response->assertSee('Gerar Nova Fatura');
    }
}
