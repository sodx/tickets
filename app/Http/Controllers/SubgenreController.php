<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubgenreRequest;
use App\Http\Requests\UpdateSubgenreRequest;
use App\Models\Subgenre;

class SubgenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubgenreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubgenreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subgenre  $subgenre
     * @return \Illuminate\Http\Response
     */
    public function show(Subgenre $subgenre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subgenre  $subgenre
     * @return \Illuminate\Http\Response
     */
    public function edit(Subgenre $subgenre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubgenreRequest  $request
     * @param  \App\Models\Subgenre  $subgenre
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubgenreRequest $request, Subgenre $subgenre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subgenre  $subgenre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subgenre $subgenre)
    {
        //
    }
}
