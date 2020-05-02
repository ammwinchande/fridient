<?php

namespace App\Http\Controllers\API;

use App\Box;
use App\Recipe;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;

class BoxesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Box::paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $today = new DateTime();
        $delivery_date = new DateTime($request->delivery_date);

        if ($today > $delivery_date) {
            return response()->json(['status' => 400, 'message' => 'Invalid delivery date']);
        } else {
            $diff = $delivery_date->diff($today);
            $hours = $diff->h + ($diff->days * 24);

            if ($hours < 48) {
                return response()->json(['status' => 400, 'message' => 'Invalid delivery date']);
            }
        }

        if (!is_array($request->recipe_ids)) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid data'
            ]);
        }

        if (count($request->recipe_ids) > 4) {
            return response()->json([
                'status' => 400,
                'message' => 'Maximum of 4 Recipe ids is required'
            ]);
        }

        foreach ($request->recipe_ids as $id) {
            if (!Recipe::firstWhere('id', $id)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Incorrect recipe id'
                ]);
            }
        }

        $box = Box::create($this->validateBox($request));

        return response()->json([
            'message' => 'success',
            'box' => $box
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function show(Box $box)
    {
        return response()->json($box);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Box $box)
    {

        $today = new DateTime();
        $delivery_date = new DateTime($request->delivery_date);

        if ($today > $delivery_date) {
            return response()->json(['status' => 400, 'message' => 'Invalid delivery date']);
        } else {
            $diff = $delivery_date->diff($today);
            $hours = $diff->h + ($diff->days * 24);

            if ($hours < 48) {
                return response()->json(['status' => 400, 'message' => 'Invalid delivery date']);
            }
        }

        if (!is_array($request->recipe_ids)) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid data'
            ]);
        }

        if (count($request->recipe_ids) > 4) {
            return response()->json([
                'status' => 400,
                'message' => 'Maximum of 4 Recipe ids is required'
            ]);
        }

        foreach ($request->recipe_ids as $id) {
            if (!Recipe::firstWhere('id', $id)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Incorrect recipe id'
                ]);
            }
        }

        if (!$box->update($this->validateBox($request))) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid data provided'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'recipe' => $box
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Box  $box
     * @return \Illuminate\Http\Response
     */
    public function destroy(Box $box)
    {
        $box->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Validate box resource
     *
     * @return array $validatedAttributes
     */
    protected function validateBox(Request $request)
    {
        return $request->validate([
            'delivery_date' => 'required|date|after:tomorrow',
            'recipe_ids' => 'required|array'
        ]);
    }
}
