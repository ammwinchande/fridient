<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Recipe;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Recipe::paginate(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_array($request->ingredients)) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid syntax'
            ]);
        }

        $recipe = Recipe::create($this->validateRecipe($request));

        return response()->json([
            'message' => 'success',
            'recipe' => $recipe
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        // return json_decode($recipe->ingredients[0])->amount;
        return response()->json($recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        if (!is_array($request->ingredients)) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid syntax'
            ]);
        }

        if (!$recipe->update($this->validateRecipe($request))) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid data provided'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'recipe' => $recipe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Validate recipe resource
     *
     * @return array $validatedAttributes
     */
    protected function validateRecipe(Request $request)
    {
        return $request->validate([
            'name' => 'required|min:3|max:120',
            'description' => 'required|min:3',
            'ingredients' => 'required|array'
        ]);
    }
}
