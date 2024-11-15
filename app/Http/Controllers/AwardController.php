<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAwardRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Models\Award;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const AWARD_PAGINATE = 9;
    public function index()
    {
        $awards = Award::paginate(9);

        return view('awards.list', compact('awards'));
    }

    public function add(Award $award)
    {
        return view('awards.add',[
            'award' => $award
        ]);
    }

    public function store(StoreAwardRequest $request)
    {
        Award::create($request->all());
        return redirect()->route('dashboard.index')->with('info', 'Award Succesfully added!');
    }

    public function edit($id)
    {
        $award = Award::find($id);
        
        return view('awards.edit', compact('award'));

    }

    public function update($id, UpdateAwardRequest $request)
    {
        $award = Award::find($id);
        $award->name = $request->input('name');
        $award->save();

        return redirect()->route('awards.index')->with('succes', 'Award Updated Correctly');
    }

}
