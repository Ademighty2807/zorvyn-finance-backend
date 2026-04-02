<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = $user->hasRole('viewer')
            ? FinancialRecord::where('user_id', $user->id)
            : FinancialRecord::query();

        if ($request->filled('from')) $query->whereDate('date', '>=', $request->from);
        if ($request->filled('to'))   $query->whereDate('date', '<=', $request->to);
        if ($request->filled('type')) $query->where('type', $request->type);

        return $this->success([
            'overview'            => $this->getOverview(clone $query),
            'monthly_breakdown'   => $this->getMonthlyBreakdown(clone $query),
            'category_breakdown'  => $this->getCategoryBreakdown(clone $query),
            'status_summary'      => $this->getStatusSummary(clone $query),
            'recent_transactions' => $this->getRecentTransactions(clone $query),
        ], 'Analytics fetched successfully');
    }

    // Total income, expense and net balance
    private function getOverview($query): array
    {
        $totals = (clone $query)
            ->select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $income  = $totals['income'] ?? 0;
        $expense = $totals['expense'] ?? 0;

        return [
            'total_income'  => number_format($income, 2),
            'total_expense' => number_format($expense, 2),
            'net_balance'   => number_format($income - $expense, 2),
        ];
    }

    // Income vs Expense grouped by month
    private function getMonthlyBreakdown($query): array
    {
        return (clone $query)
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('MONTHNAME(date) as month_name'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month', 'month_name', 'type')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('year')
            ->map(fn($months) => $months->groupBy('month_name'))
            ->toArray();
    }

    // Totals grouped by category
    private function getCategoryBreakdown($query): array
    {
        return (clone $query)
            ->select(
                'category',
                'type',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('category', 'type')
            ->orderBy('total', 'desc')
            ->get()
            ->toArray();
    }

    // Count of records by status
    private function getStatusSummary($query): array
    {
        return (clone $query)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    // Last 5 transactions
    private function getRecentTransactions($query): array
    {
        return (clone $query)
            ->with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($record) => [
                'id'       => $record->id,
                'title'    => $record->title,
                'type'     => $record->type,
                'amount'   => $record->amount,
                'category' => $record->category,
                'date'     => $record->date->format('Y-m-d'),
                'status'   => $record->status,
            ])
            ->toArray();
    }
}
