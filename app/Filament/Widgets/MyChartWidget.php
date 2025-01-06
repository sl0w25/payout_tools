<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use App\Models\Assistance;
use App\Models\Attendance;
use App\Models\FamilyHead;
use App\Models\LocationInfo;
use Filament\Widgets\ChartWidget;

class MyChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    public function getData(): array
    {
        // Example: Sales data for the last 30 days
        $salesData = DB::table('Attendances')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total_sales'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->where('status', 'Paid')
            ->where('created_at', '>=', now()->subDays(30)) 
            ->get();

        $dates = [];
        $sales = [];

        foreach ($salesData as $data) {
            $dates[] = $data->date;
            $sales[] = (float) $data->total_sales;
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Disburse',
                    'data' => $sales,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
