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

        $query = $user->hasRole('viewer')
            ? FinancialRecord::where('user_id', $user->id)->with('user')
            : FinancialRecord::with('user');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('category', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest()->paginate(15);

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
