<?php

Route::get('/api/visitors/lookup', function (Request $request) {
    $visitor = \App\Models\Visitor::where('email', $request->query('email'))->first();

    if ($visitor) {
        return response()->json([
            'exists' => true,
            'visitor' => $visitor,
        ]);
    }

    return response()->json(['exists' => false]);
});
