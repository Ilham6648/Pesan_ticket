<?php

namespace Tests\Feature;

use App\Models\event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_uses_the_event_ticket_price_for_order_totals(): void
    {
        $event = event::create([
            'nama_konser' => 'Konser Hitung',
            'category' => 'musik',
            'harga_per_tiket' => 125000,
            'description' => 'Deskripsi event uji.',
        ]);

        $response = $this->post(route('customer.checkout'), [
            'customer_name' => 'Pelanggan Uji',
            'customer_email' => 'pelanggan@example.test',
            'items' => [$event->id => 2],
        ]);

        $response->assertRedirect(route('customer.index'));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Pelanggan Uji',
            'total_price' => 250000,
        ]);
        $this->assertDatabaseHas('orderdetails', [
            'event_id' => $event->id,
            'quantity' => 2,
            'subtotal' => 250000,
        ]);

        $this->actingAs(User::factory()->create());
        $dashboard = $this->get(route('dashboard'));
        $dashboard
            ->assertOk()
            ->assertSee('Konser Hitung')
            ->assertSee('Rp 250.000');
    }

    public function test_admin_can_create_and_update_event_name_and_price(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('events.store'), [
            'name' => 'Event Baru',
            'category' => 'festival',
            'price' => 30000,
            'description' => 'Deskripsi festival.',
        ]);

        $response->assertRedirect(route('events.index'));
        $event = event::firstOrFail();
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'nama_konser' => 'Event Baru',
            'category' => 'festival',
            'harga_per_tiket' => 30000,
        ]);

        $response = $this->get(route('events.edit', $event));
        $response
            ->assertOk()
            ->assertSee('name="name" value="Event Baru"', false)
            ->assertSee('name="price" value="30000"', false);

        $response = $this->put(route('events.update', $event), [
            'name' => 'Event Diperbarui',
            'category' => 'seminar',
            'price' => 45000,
            'description' => 'Deskripsi seminar.',
        ]);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'nama_konser' => 'Event Diperbarui',
            'category' => 'seminar',
            'harga_per_tiket' => 45000,
            'image' => null,
        ]);

        $response = $this->get(route('events.index'));
        $response
            ->assertOk()
            ->assertSee('Event Diperbarui')
            ->assertSee('Rp 45.000');
    }
}
