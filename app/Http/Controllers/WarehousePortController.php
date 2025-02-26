<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;

class WarehousePortController extends Controller
{
    public function export()
    {
        return (new FastExcel($this->warehouseGenerator()))->download('warehouses.xlsx');
    }

    public function import()
    {
        return Inertia::render('Warehouse/Import');
    }

    public function save(Request $request)
    {
        $request->validate(['excel' => 'required|file|mimes:xls,xlsx']);

        $path = $request->file('excel')->store('imports');
        try {
            $warehouses = (new FastExcel())->import(Storage::path($path), function ($line) {
                if (! $line['name'] || ! $line['code']) {
                    throw new \Exception(__('name & code are required.'));
                }

                return Warehouse::updateOrCreate(['name' => $line['name']], [
                    'code'    => $line['code'],
                    'name'    => $line['name'],
                    'email'   => $line['email'] ?? '',
                    'phone'   => $line['phone'] ?? '',
                    'address' => $line['address'] ?? '',
                    'active'  => $line['active'] == 'yes',
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('warehouses.index')->with('message', __choice('imported_text', ['records' => 'Warehouse', 'count' => $warehouses->count()]));
    }

    private function warehouseGenerator()
    {
        foreach (Warehouse::cursor() as $warehouse) {
            yield [
                'code'    => $warehouse->code,
                'name'    => $warehouse->name,
                'email'   => $warehouse->email,
                'phone'   => $warehouse->phone,
                'address' => $warehouse->address,
                'active'  => $warehouse->active,
            ];
        }
    }
}
