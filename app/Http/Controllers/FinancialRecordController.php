<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use App\Http\Resources\FinancialRecordResource;
use App\Http\Requests\StoreFinancialRecordRequest;
use App\Http\Requests\UpdateFinancialRecordRequest;
use Illuminate\Http\Request;

class FinancialRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $records = $user->hasRole('viewer')
            ? FinancialRecord::where('user_id', $user->id)->with('user')->latest()->paginate(15)
            : FinancialRecord::with('user')->latest()->paginate(15);

        return $this->success(
            FinancialRecordResource::collection($records),
            'Records fetched successfully'
        );
    }

    public function store(StoreFinancialRecordRequest $request)
    {
        $record = FinancialRecord::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return $this->created(
            new FinancialRecordResource($record->load('user')),
            'Record created successfully'
        );
    }

    public function show(FinancialRecord $financialRecord)
    {
        return $this->success(
            new FinancialRecordResource($financialRecord->load('user')),
            'Record fetched successfully'
        );
    }

    public function update(UpdateFinancialRecordRequest $request, FinancialRecord $financialRecord)
    {
        $financialRecord->update($request->validated());

        return $this->success(
            new FinancialRecordResource($financialRecord->load('user')),
            'Record updated successfully'
        );
    }

    public function destroy(FinancialRecord $financialRecord)
    {
        $financialRecord->delete();

        return $this->noContent('Record deleted successfully');
    }
}
