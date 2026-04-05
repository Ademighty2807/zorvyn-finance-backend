<?php

namespace Database\Seeders;

use App\Models\FinancialRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinancialRecordSeeder extends Seeder
{
    public function run(): void
    {
        $admin       = User::where('email', 'admin@zorvyn.com')->first();
        $accountant  = User::where('email', 'accountant@zorvyn.com')->first();
        $viewer      = User::where('email', 'viewer@zorvyn.com')->first();

        $records = [
            // Admin records
            [
                'user_id'     => $admin->id,
                'title'       => 'Q1 Product Sales',
                'description' => 'Revenue from product sales in Q1',
                'type'        => 'income',
                'amount'      => 1500000.00,
                'category'    => 'Sales',
                'date'        => '2026-01-15',
                'status'      => 'approved',
            ],
            [
                'user_id'     => $admin->id,
                'title'       => 'Office Rent - January',
                'description' => 'Monthly office space rent payment',
                'type'        => 'expense',
                'amount'      => 250000.00,
                'category'    => 'Utilities',
                'date'        => '2026-01-05',
                'status'      => 'approved',
            ],
            [
                'user_id'     => $admin->id,
                'title'       => 'Staff Salaries - February',
                'description' => 'Monthly payroll disbursement',
                'type'        => 'expense',
                'amount'      => 800000.00,
                'category'    => 'Salaries',
                'date'        => '2026-02-28',
                'status'      => 'approved',
            ],
            [
                'user_id'     => $admin->id,
                'title'       => 'Q1 Consulting Revenue',
                'description' => 'Consulting fees from external clients',
                'type'        => 'income',
                'amount'      => 600000.00,
                'category'    => 'Consulting',
                'date'        => '2026-03-10',
                'status'      => 'approved',
            ],

            // Accountant records
            [
                'user_id'     => $accountant->id,
                'title'       => 'Software Subscriptions',
                'description' => 'Annual SaaS tools renewal',
                'type'        => 'expense',
                'amount'      => 120000.00,
                'category'    => 'Technology',
                'date'        => '2026-02-01',
                'status'      => 'approved',
            ],
            [
                'user_id'     => $accountant->id,
                'title'       => 'Client Invoice - March',
                'description' => 'Payment received from client A',
                'type'        => 'income',
                'amount'      => 450000.00,
                'category'    => 'Invoice',
                'date'        => '2026-03-20',
                'status'      => 'approved',
            ],
            [
                'user_id'     => $accountant->id,
                'title'       => 'Marketing Campaign',
                'description' => 'Digital marketing spend for Q1',
                'type'        => 'expense',
                'amount'      => 180000.00,
                'category'    => 'Marketing',
                'date'        => '2026-03-01',
                'status'      => 'pending',
            ],
            [
                'user_id'     => $accountant->id,
                'title'       => 'Equipment Purchase',
                'description' => 'New laptops for engineering team',
                'type'        => 'expense',
                'amount'      => 350000.00,
                'category'    => 'Equipment',
                'date'        => '2026-01-20',
                'status'      => 'approved',
            ],

            // Viewer records
            [
                'user_id'     => $viewer->id,
                'title'       => 'Freelance Income - January',
                'description' => 'Payment from freelance project',
                'type'        => 'income',
                'amount'      => 85000.00,
                'category'    => 'Freelance',
                'date'        => '2026-01-30',
                'status'      => 'approved',
            ],
            [
                'user_id'     => $viewer->id,
                'title'       => 'Training & Development',
                'description' => 'Online course subscriptions',
                'type'        => 'expense',
                'amount'      => 25000.00,
                'category'    => 'Training',
                'date'        => '2026-02-10',
                'status'      => 'pending',
            ],
            [
                'user_id'     => $viewer->id,
                'title'       => 'April Consulting Fee',
                'description' => 'Consulting payment received',
                'type'        => 'income',
                'amount'      => 120000.00,
                'category'    => 'Consulting',
                'date'        => '2026-04-01',
                'status'      => 'pending',
            ],
        ];

        foreach ($records as $record) {
            FinancialRecord::updateOrCreate(
                [
                    'user_id' => $record['user_id'],
                    'title'   => $record['title'],
                ],
                $record
            );
        }
    }
}
