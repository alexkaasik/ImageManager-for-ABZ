<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function getPositions()
    {
        $positions = [
            ['id' => 1, 'name' => 'Lawyer'],
            ['id' => 2, 'name' => 'Content manager'],
            ['id' => 3, 'name' => 'Security'],
            ['id' => 4, 'name' => 'Designer'],
        ];
        
        return response()->json(['success'=> true, 'positions' => $positions], 200);
    }
}
