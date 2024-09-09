<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BaptismCheckController extends Controller
{
    public function check(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return view('baptism.check', ['error' => 'No ID provided']);
        }

        $baptism = User::find($id);

        if (!$baptism) {
            return view('baptism.check', ['error' => 'Invalid ID']);
        }

        return view('baptism.check', [
            'baptism' => $baptism,
            'is_baptized' => $baptism->is_baptized,
            'is_certified' => $baptism->is_certified,
        ]);
    }
}