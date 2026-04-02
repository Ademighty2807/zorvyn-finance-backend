<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use App\Http\Resources\FinancialRecordResource;
use App\Http\Requests\StoreFinancialRecordRequest;
use App\Http\Requests\UpdateFinancialRecordRequest;
use Illuminate\Http\Request;

class FinancialRecordController extends Controller
{
    // GET /records — Admin & Accountant see all, Viewer sees own
    public function index(Request $request)
    {
        $user = $request->user();

        $records = $user->hasRole('viewer')
            ? FinancialRecord::where('user_id', $user->id)->with('user')->latest()->paginate(15)
            : FinancialRecord::with('user')->latest()->paginate(15);

        return FinancialRecordResource::collection($records);
    }

    // POST /records
    public function store(StoreFinancialRecordRequest $request)
    {
        $record = FinancialRecord::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return new FinancialRecordResource($record->load('user'));
    }

    // GET /records/{id}
    public function show(FinancialRecord $financialRecord)
    {
        return new FinancialRecordResource($financialRecord->load('user'));
    }

    // PUT /records/{id}
    public function update(UpdateFinancialRecordRequest $request, FinancialRecord $financialRecord)
    {
        $financialRecord->update($request->validated());

        return new FinancialRecordResource($financialRecord->load('user'));
    }

    // DELETE /records/{id}
    public function destroy(FinancialRecord $financialRecord)
    {
        $financialRecord->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
