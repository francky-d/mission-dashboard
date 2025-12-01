<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\View\View;

class MissionController extends Controller
{
    public function index(): View
    {
        return view('consultant.missions.index');
    }

    public function show(Mission $mission): View
    {
        return view('consultant.missions.show', [
            'mission' => $mission,
        ]);
    }
}
