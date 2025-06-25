<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoanController extends Controller
{
    use AuthorizesRequests;
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
            'amount' => 'required|numeric|min:1',
            'annual_interest_rate' => 'required|numeric|min:0.0001',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'repayment_type' => 'required|in:repayment,interest-only,interest-retained',
            'events' => 'array',
            'events.*.type' => 'in:part_payment,rate_change,early_payoff',
            'events.*.event_date' => 'date',
            'events.*.amount' => 'nullable|numeric|min:0',
            'events.*.rate' => 'nullable|numeric|min:0',
        ]);

        $loan = $request->user()->loans()->create([
            'amount' => $validated['amount'],
            'annual_interest_rate' => $validated['annual_interest_rate'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'repayment_type' => $validated['repayment_type'],
        ]);

        // Optional: Save loan events
        if (!empty($validated['events'])) {
            $loan->events()->createMany($validated['events']);
        }

        return response()->json([
            'message' => 'Loan saved successfully.',
            'loan' => $loan->load('events')
        ]);
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
