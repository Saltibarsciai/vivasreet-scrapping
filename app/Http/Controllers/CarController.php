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
        $data = Car::where('active', true)->get();
        return view('partials.master', compact('data'));
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
}
