<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ingredient;
use App\Supplier;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Ingredient::paginate(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Supplier::firstWhere('id', $request->supplier_id)) {
            return response()->json([
                'status' => 404,
                'message' => 'No supplier found with that id'
            ]);
        }
        $ingredient = Ingredient::create($this->validateIngredient($request));
        return response()->json([
            'message' => 'success',
            'supplier' => $ingredient
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        return response()->json($ingredient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        if (!$ingredient->update($this->validateIngredient($request))) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid data provided'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'ingredient' => $ingredient
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Validate ingredient resource
     *
     * @return array $validatedAttributes
     */
    protected function validateIngredient(Request $request)
    {
        return $request->validate([
            'supplier_id' => 'required|exists:suppliers, id',
            'name' => 'required|min:5|max:120',
            'measure' => 'required|max:16'
        ]);
    }
}
