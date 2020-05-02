<?php

namespace App\Http\Controllers\API;

use App\Box;
use App\Http\Controllers\Controller;
use App\Ingredient;
use App\Recipe;
use App\Supplier;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('supplier_code')) {
            $supplier = Supplier::firstWhere('code', strtolower($request->supplier_code));
            if ($supplier) {
                return response()->json(Ingredient::where('supplier_id', $supplier->id)->paginate(5));
            }
        }
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
     * Display weekly ingredients based on recipes ingredients
     *
     * @return \Illuminate\Http\Response
     */
    public function weeklyOrder(Request $request)
    {
        $ingredient_orders = [];

        if ($request->has('order_date') && !empty($request->order_date)) {
            $end_order_date = new DateTime($request->order_date);
            $end_order_date->add(new DateInterval('P7D'));

            $boxes = Box::whereBetween('delivery_date', [$request->order_date, $end_order_date])->get();

            $ingredients_summary = $this->ingredientsOccurrences($this->recipesOccurrences($boxes));

            foreach ($ingredients_summary as $key => $value) {
                $tmp_ingredient = [
                    "ingredient" => Ingredient::firstWhere('id', $key),
                    "amount" => $value
                ];
                array_push($ingredient_orders, $tmp_ingredient);
            }

            return response()->json([
                'order_date' => $request->order_date,
                'ingredients' => $ingredient_orders
            ]);
        }

        $boxes = Box::all();

        $ingredients_summary = $this->ingredientsOccurrences($this->recipesOccurrences($boxes));

        foreach ($ingredients_summary as $key => $value) {
            $tmp_ingredient = [
                "ingredient" => Ingredient::firstWhere('id', $key),
                "amount" => $value
            ];
            array_push($ingredient_orders, $tmp_ingredient);
        }

        return response()->json([
            'order_date' => $request->order_date,
            'ingredients' => $ingredient_orders
        ]);
    }

    /**
     * Manipulate recipe_ids, and their occurrences
     *
     * @param \Illuminate\Database\Eloquent\Collection $boxes
     * @return Array $recipes
     */
    public function recipesOccurrences(Collection $boxes)
    {
        /**
         * Expected: $recipes = [recipe_id => occurrence]
         */
        $recipes = [];
        foreach ($boxes as $box) {
            foreach ($box->recipe_ids as $recipe_id) {
                if (array_key_exists(
                    $recipe_id,
                    $recipes
                )) {
                    $recipes[$recipe_id] += 1;
                } else {
                    $recipes[$recipe_id] = 1;
                }
            }
        }
        return $recipes;
    }

    /**
     * Manipulate recipes to get ingredients, and total amount for each
     *
     * @param Array $recipes_summary
     * @return Array $ingredients_summary
     */
    public function ingredientsOccurrences($recipes_summary)
    {
        /**
         * Expected: $ingredients_summary = [ingredient_id => total_amount]
         */
        $ingredients_summary = [];

        foreach ($recipes_summary as $key => $value) {
            // using $key fetch ingredients from recipes
            // using $value calculate total amount
            // return json_decode($recipe->ingredients[0])->amount;
            $ingredients = Recipe::firstWhere('id', $key)->ingredients;
            foreach ($ingredients as $ingredient) {
                $ingredient_id = json_decode($ingredient)->ingredient_id;
                $amount = json_decode($ingredient)->amount;
                if (array_key_exists(
                    $ingredient_id,
                    $ingredients_summary
                )) {
                    $ingredients_summary[$ingredient_id] += ($amount * $value);
                } else {
                    $ingredients_summary[$ingredient_id] = $amount * $value;
                }
            }
        }

        return $ingredients_summary;
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
