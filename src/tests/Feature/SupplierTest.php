<?php

namespace Tests\Feature;

use App\Supplier;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_will_create_supplier()
    {
        $response = $this->post(route('suppliers.store'), [
            'code'       => 'mu23jr',
            'name' => 'MuJr Suppliers'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('suppliers', [
            'code' => 'mu23jr'
        ]);

        $response->assertJsonStructure([
            'message',
            'supplier' => [
                'id',
                'code',
                'name',
                'updated_at',
                'created_at'
            ]
        ]);
    }

    /** @test */
    public function it_will_show_a_supplier()
    {
        $this->post(route('suppliers.store'), [
            'code'       => 'mu23jr',
            'name' => 'MuJr Suppliers'
        ]);

        $supplier = supplier::all()->first();

        $response = $this->get(route('suppliers.show', $supplier->id));

        $response->assertStatus(200);

        $response->assertJson($supplier->toArray());
    }

    /** @test */
    public function it_will_update_a_supplier()
    {
        $this->post(route('suppliers.store'), [
            'code'       => 'mu23jr',
            'name' => 'Mu Jr Suppliers'
        ]);

        $supplier = supplier::all()->first();

        $response = $this->put(route('suppliers.update', $supplier->id), [
            'code' => 'mu23jr',
            'name' => 'MuJr Suppliers'
        ]);

        $response->assertStatus(302);

        $supplier = $supplier->fresh();

        $this->assertEquals($supplier->name, 'Mu Jr Suppliers');
    }

    /** @test */
    public function it_will_delete_a_supplier()
    {
        $this->post(route('suppliers.store'), [
            'code'       => 'mu23jr',
            'name' => 'MuJr Suppliers'
        ]);

        $supplier = supplier::all()->first();

        $response = $this->delete(route('suppliers.destroy', $supplier->id));

        $supplier = $supplier->fresh();

        $this->assertNull($supplier);

        $response->assertJsonStructure([
            'message'
        ]);
    }
}
