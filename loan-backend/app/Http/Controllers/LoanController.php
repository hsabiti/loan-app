<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->loans()->latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'term' => 'required|integer|min:1',
        ]);

        $loan = $request->user()->loans()->create([
            'amount' => $validated['amount'],
            'term' => $validated['term'],
            'interest_rate' => 10.0, // Default or calculated
            'status' => 'pending',
        ]);

        return response()->json($loan, 201);
    }


    /**
     * Display the specified resource.
     */
   public function show(Loan $loan)
    {
        $this->authorize('view', $loan);
        return $loan;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
