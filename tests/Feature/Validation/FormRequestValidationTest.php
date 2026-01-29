<?php

namespace Tests\Feature\Validation;

use App\Models\Empresa;
use App\Models\Licitacao;
use App\Models\Proposta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FormRequestValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $empresa;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->empresa = Empresa::factory()->create();
        $this->user = User::factory()->create(['empresa_id' => $this->empresa->id]);
        
        $this->actingAs($this->user);
    }

    /** @test */
    public function store_proposta_requires_mandatory_fields()
    {
        $response = $this->post(route('propostas.store'), []);

        $response->assertSessionHasErrors(['licitacao_id', 'valor_total', 'status']);
    }

    /** @test */
    public function store_proposta_validates_numeric_fields()
    {
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);

        $data = [
            'licitacao_id' => $licitacao->id,
            'valor_total' => 'not-a-number',
            'status' => 'rascunho',
        ];

        $response = $this->post(route('propostas.store'), $data);

        $response->assertSessionHasErrors(['valor_total']);
    }

    /** @test */
    public function store_proposta_validates_status_enum()
    {
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);

        $data = [
            'licitacao_id' => $licitacao->id,
            'valor_total' => 1000,
            'status' => 'invalid_status',
        ];

        $response = $this->post(route('propostas.store'), $data);

        $response->assertSessionHasErrors(['status']);
    }

    /** @test */
    public function store_faturamento_requires_mandatory_fields()
    {
        // Need an existing proposal
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $licitacao->id
        ]);

        $response = $this->post(route('financeiro.faturar', $proposta->id), []);

        $response->assertSessionHasErrors(['numero_nf', 'valor', 'data_emissao', 'data_vencimento']);
    }

    /** @test */
    public function store_faturamento_validates_date_logic()
    {
        $licitacao = Licitacao::factory()->create(['empresa_id' => $this->empresa->id]);
        $proposta = Proposta::factory()->create([
            'empresa_id' => $this->empresa->id,
            'licitacao_id' => $licitacao->id
        ]);

        $data = [
            'numero_nf' => '123',
            'valor' => 1000,
            'data_emissao' => '2024-02-01',
            'data_vencimento' => '2024-01-01', // Before emission
        ];

        $response = $this->post(route('financeiro.faturar', $proposta->id), $data);

        $response->assertSessionHasErrors(['data_vencimento']);
    }
}
