<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Visit;

class VisitorController extends Controller
{
    public function create()
{
    return view('partials.register-visitor-form');
}

public function store(Request $request)
{
    $data = $request->validate([
        'email' => 'required|email',
        'name' => 'required_if:is_new,true|string',
        'phone' => 'required_if:is_new,true|string',
        'organization' => 'required_if:is_new,true|string',
        'visit_date' => 'required|date',
        'time_of_visit' => 'required',
        'floor_of_visit' => 'required|string',
        'reason' => 'required|string',
    ]);

    // Check if visitor exists
    $visitor = Visitor::where('email', $data['email'])->first();

    // Create visitor if doesn't exist
    if (!$visitor) {
        $visitor = Visitor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'organization' => $data['organization'],
        ]);
    }

    // Create visit
    Visit::create([
        'visitor_id' => $visitor->id,
        'staff_id' => auth()->id(), // assuming staff is authenticated
        'visit_date' => $data['visit_date'],
        'time_of_visit' => $data['time_of_visit'],
        'floor_of_visit' => $data['floor_of_visit'],
        'reason' => $data['reason'],
        'status' => 'pending',
        'unique_code' => strtoupper(Str::random(10)),
        'verification_message' => 'Visitor has not arrived at the gate',
    ]);

    return redirect()->back()->with('success', __('Visit scheduled successfully.'));
}

public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('csv_file');

    $handle = fopen($file->getRealPath(), 'r');
    $header = fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {
        $data = array_combine($header, $row);

        // Create or update visitor
        $visitor = Visitor::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'organization' => $data['organization'],
            ]
        );

        // Create a visit
        Visit::create([
            'visitor_id' => $visitor->id,
            'staff_id' => auth()->id(),
            'visit_date' => $data['visit_date'],
            'time_of_visit' => $data['time_of_visit'],
            'floor_of_visit' => $data['floor'],
            'reason' => $data['reason'],
            'status' => 'pending',
            'unique_code' => strtoupper(Str::random(10)),
            'verification_message' => 'Visitor has not arrived at the gate',
        ]);
    }

    fclose($handle);

    return redirect()->back()->with('success', 'Visitors imported successfully.');
}

}
