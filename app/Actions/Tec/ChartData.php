<?php

namespace App\Actions\Tec;

use Carbon\Carbon;
use App\Models\Checkin;
use App\Models\Checkout;
use App\Models\Transfer;
use App\Models\Adjustment;
use App\Models\StockTrail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ChartData
{
    public $month;

    public $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?: date('n');
        $this->year = $year ?: date('Y');
    }

    public function month()
    {
        $this->month = $this->month > 9 ? $this->month : '0' . +$this->month;
        $end_date = Carbon::parse($this->year . '-' . $this->month . '-01')->endOfMonth();
        $start_date = Carbon::parse($this->year . '-' . $this->month . '-01')->startOfMonth();

        if (env('DB_CONNECTION') == 'sqlite') {
            $monthlyCheckins = Checkin::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();

            $monthlyCheckouts = Checkout::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();

            $monthlyAdjustments = Adjustment::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();

            $monthlyTransfers = Transfer::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();
        } else {
            $monthlyCheckins = Checkin::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();

            $monthlyCheckouts = Checkout::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();

            $monthlyAdjustments = Adjustment::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();

            $monthlyTransfers = Transfer::active()->selectRaw(
                DB::Raw('date, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date')->orderBy('date')->get();
        }

        return [
            'adjustment' => $this->prepareMonthArray($monthlyAdjustments, $start_date),
            'transfer'   => $this->prepareMonthArray($monthlyTransfers, $start_date),
            'checkin'    => $this->prepareMonthArray($monthlyCheckins, $start_date),
            'checkout'   => $this->prepareMonthArray($monthlyCheckouts, $start_date),
        ];
    }

    public function topProducts()
    {
        $this->month = $this->month > 9 ? $this->month : '0' . +$this->month;
        $end_date = Carbon::parse($this->year . '-' . $this->month . '-01')->endOfMonth();
        $start_date = Carbon::parse($this->year . '-' . $this->month . '-01')->startOfMonth();

        return StockTrail::select(['item_id'])->selectRaw('SUM(quantity) as y')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->with('item:id,code')->orderByRaw('SUM(quantity) desc')->groupBy('item_id')
            ->take(10)->get()->transform(fn ($i) => ['name' => $i->item ? $i->item->code : '', 'y' => +$i->y]);
    }

    public function year()
    {
        $end_date = Carbon::parse($this->year . '-01-01')->endOfYear();
        $start_date = Carbon::parse($this->year . '-01-01')->startOfYear();

        if (env('DB_CONNECTION') == 'sqlite') {
            $monthlyCheckins = Checkin::active()->selectRaw(
                DB::Raw("strftime('%m', date) as month, strftime('%Y', date) as year, COUNT(*) as total")
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw("strftime('%Y', date), strftime('%m', date)"))
                ->orderBy(DB::raw("strftime('%Y-%m', date)"))->get();

            $monthlyCheckouts = Checkout::active()->selectRaw(
                DB::Raw("strftime('%m', date) as month, strftime('%Y', date) as year, COUNT(*) as total")
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw("strftime('%Y', date), strftime('%m', date)"))
                ->orderBy(DB::raw("strftime('%Y-%m', date)"))->get();

            $monthlyAdjustments = Adjustment::active()->selectRaw(
                DB::Raw("strftime('%m', date) as month, strftime('%Y', date) as year, COUNT(*) as total")
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw("strftime('%Y', date), strftime('%m', date)"))
                ->orderBy(DB::raw("strftime('%Y-%m', date)"))->get();

            $monthlyTransfers = Transfer::active()->selectRaw(
                DB::Raw("strftime('%m', date) as month, strftime('%Y', date) as year, COUNT(*) as total")
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw("strftime('%Y', date), strftime('%m', date)"))
                ->orderBy(DB::raw("strftime('%Y-%m', date)"))->get();
        } else {
            $monthlyCheckins = Checkin::active()->selectRaw(
                DB::Raw('MONTH(date) as month, YEAR(date) as year, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
                ->orderBy('year')->orderBy('month')->get();

            $monthlyCheckouts = Checkout::active()->selectRaw(
                DB::Raw('MONTH(date) as month, YEAR(date) as year, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
                ->orderBy('year')->orderBy('month')->get();

            $monthlyAdjustments = Adjustment::active()->selectRaw(
                DB::Raw('MONTH(date) as month, YEAR(date) as year, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
                ->orderBy('year')->orderBy('month')->get();

            $monthlyTransfers = Transfer::active()->selectRaw(
                DB::Raw('MONTH(date) as month, YEAR(date) as year, COUNT(*) as total')
            )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
                ->orderBy('year')->orderBy('month')->get();
        }

        return [
            'adjustment' => $this->prepareYearArray($monthlyAdjustments, $start_date),
            'transfer'   => $this->prepareYearArray($monthlyTransfers, $start_date),
            'checkin'    => $this->prepareYearArray($monthlyCheckins, $start_date),
            'checkout'   => $this->prepareYearArray($monthlyCheckouts, $start_date),
        ];
    }

    private function prepareMonthArray($array, $date)
    {
        $days = $date->daysInMonth;
        $data = $array->mapWithKeys(function ($item) {
            return [$item['date'] => $item['total']];
        })->toArray();
        for ($i = 1; $i <= $days; $i++) {
            $key = Carbon::parse($date->format('Y-m-') . ($i < 10 ? '0' . ((int) $i) : $i))->format('Y-m-d');
            $data[$key] = Arr::has($data, $key) ? (0 + $data[$key]) : 0;
        }

        return $data;
    }

    private function prepareWeekArray($array)
    {
        $data = $array->mapWithKeys(function ($item) {
            return [$item['date'] => $item['total']];
        })->toArray();
        for ($i = 6; $i >= 0; $i--) {
            if ($i == 0) {
                $date = now();
            } elseif ($i == 1) {
                $date = now()->subDay();
            } else {
                $date = now()->subDays($i);
            }
            $key = $date->format('Y-m-d');
            $data[$key] = Arr::has($data, $key) ? (0 + $data[$key]) : 0;
        }

        return $data;
    }

    private function prepareYearArray($array, $date)
    {
        $data = $array->mapWithKeys(function ($item) {
            return [$item['year'] . ($item['month'] < 10 ? '-0' : '-') . ((int) $item['month']) => $item['total']];
        })->toArray();
        for ($i = 1; $i <= 12; $i++) {
            $key = Carbon::parse($date->format('Y-') . ($i < 10 ? '0' . $i : $i))->format('Y-m');
            $data[$key] = Arr::has($data, $key) ? (0 + $data[$key]) : 0;
        }

        return $data;
    }
}
