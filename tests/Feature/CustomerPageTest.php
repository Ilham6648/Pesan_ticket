<?php

namespace Tests\Feature;

use App\Models\event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_page_renders_order_form_with_registered_store_route(): void
    {
        event::create([
            'nama_konser' => 'Konser Uji',
            'category' => 'musik',
            'harga_per_tiket' => 125000,
            'description' => 'Deskripsi event uji.',
        ]);

        $response = $this->get(route('customer.index'));

        $response
            ->assertOk()
            ->assertSee('action="' . route('customer.checkout') . '"', false)
            ->assertSee('Konser Uji')
            ->assertSee('Rp 125.000')
            ->assertSee('data-name="Konser Uji" data-price="125000"', false);
    }
}
