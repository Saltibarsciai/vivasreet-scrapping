<?php

namespace App\Http\Controllers;

use App\Car;
use App\Http\Requests\LinkRequest;
use App\Services\CurlExecService;
use App\Services\Scraper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('partials.master');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LinkRequest $request
     * @param Scraper $scraper
     * @return Response
     */
    public function store(LinkRequest $request, Scraper $scraper)
    {
        $scraper->scrapeVivaStreet($request->link);
        return redirect()->route('cars.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Car  $car
     * @return Response
     */
    public function show(Car $car)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Car  $car
     * @return Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Car  $car
     * @return Response
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car  $car
     * @return Response
     */
    public function destroy(Car $car)
    {
        //
    }
}
