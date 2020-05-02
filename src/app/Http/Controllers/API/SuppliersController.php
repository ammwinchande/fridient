<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(Supplier::paginate(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Supplier::firstWhere('code', $request->code)) {
            return response()->json([
                'status' => 403,
                'message' => 'Supplier code already used'
            ]);
        }
        $supplier = Supplier::create($this->validateSupplier($request));
        return response()->json([
            'message' => 'success',
            'supplier' => $supplier
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        if (!$supplier->update($this->validateSupplier($request))) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid data provided'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'supplier' => $supplier
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Validate supplier resource
     *
     * @return array $validatedAttributes
     */
    protected function validateSupplier(Request $request)
    {
        return $request->validate([
            'code' => 'required|unique:suppliers|min:3|max:7',
            'name' => 'required|min:5|max:120'
        ]);
    }
}
