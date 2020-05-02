<?php

namespace Tests\Feature;

use App\Ingredient;
use App\Supplier;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create a supplier resource
     *
     * @return \App\Supplier
     */
    public function createSupplier()
    {
        return factory(Supplier::class)->create();
    }


    /** @test */
    public function it_will_create_ingredient()
    {
        $supplier = $this->createSupplier();

        $ingredient = factory(Ingredient::class)->create(['supplier_id' => $supplier->id]);

        $this->assertInstanceOf(Supplier::class, $ingredient->supplier);
    }

    /** @test */
    public function it_will_show_ingredient()
    {
        $supplier = $this->createSupplier();

        $ingredient = factory(Ingredient::class)->create(['supplier_id' => $supplier->id]);

        $ingredient = ingredient::all()->first();

        $response = $this->get(route('ingredients.show', $ingredient->id));

        $response->assertStatus(200);

        $response->assertJson($ingredient->toArray());
    }

    /** @test */
    public function it_will_update_ingredient()
    {
        $supplier = $this->createSupplier();

        $ingredient = factory(Ingredient::class)->create(['supplier_id' => $supplier->id]);

        $ingredient = ingredient::all()->first();

        $response = $this->put(route('ingredients.update', $ingredient->id), [
            'name' => 'sugar',
            'measure' => 'gramms'
        ]);

        $response->assertStatus(302);

        $ingredient = $ingredient->fresh();

        $this->assertNotEquals($ingredient->name, 'sugar');
    }

    /** @test */
    public function it_will_delete_ingredient()
    {
        $supplier = $this->createSupplier();

        $ingredient = factory(Ingredient::class)->create(['supplier_id' => $supplier->id]);

        $ingredient = ingredient::all()->first();

        $response = $this->delete(route('ingredients.destroy', $ingredient->id));

        $ingredient = $ingredient->fresh();

        $this->assertNull($ingredient);

        $response->assertJsonStructure([
            'message'
        ]);
    }
}
