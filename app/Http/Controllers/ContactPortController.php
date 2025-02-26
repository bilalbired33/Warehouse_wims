<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;

class ContactPortController extends Controller
{
    public function export()
    {
        return (new FastExcel($this->contactGenerator()))->download('contacts.xlsx');
    }

    public function import()
    {
        return Inertia::render('Contact/Import');
    }

    public function save(Request $request)
    {
        $request->validate(['excel' => 'required|file|mimes:xls,xlsx']);

        $path = $request->file('excel')->store('imports');
        try {
            $contacts = (new FastExcel())->import(Storage::path($path), function ($line) {
                if (! $line['name'] || (! $line['email'] && ! $line['phone'])) {
                    throw new \Exception(__('name along email or phone are required.'));
                }

                return Contact::updateOrCreate(['name' => $line['name']], [
                    'email'   => $line['email'],
                    'phone'   => $line['phone'],
                    'details' => $line['details'] ?? '',
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('contacts.index')->with('message', __choice('imported_text', ['records' => 'Contact', 'count' => $contacts->count()]));
    }

    private function contactGenerator()
    {
        foreach (Contact::cursor() as $contact) {
            yield [
                'name'    => $contact->name,
                'email'   => $contact->email,
                'phone'   => $contact->phone,
                'details' => $contact->details,
            ];
        }
    }
}
