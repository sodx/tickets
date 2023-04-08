<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttractionRequest;
use App\Http\Requests\UpdateAttractionRequest;
use App\Models\Attraction;
use Artesaos\SEOTools\Facades\SEOMeta;

class AttractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get attractions which have more than 1 event, sort them by events click count and paginate them
        $attractions = Attraction::whereHas('events', function ($query) {
            $query->where('start_date', '>=', date('Y-m-d'));
        })->withCount('events')->orderBy('events_count', 'desc')->get()->paginate(20);

        SEOMeta::setTitle('All Artists and Attractions ' . ' | ' . setting('site.title'));
        SEOMeta::setDescription('List of All Artists and Attractions touring right now' . ' | ' . setting('site.description'));

        $attractionsArr = $attractions->toArray();
        $currentPage = $attractionsArr['current_page'];
        if($currentPage > 1) {
            // add canonical link
            SEOMeta::setPrev(url()->current() . '?page=' . ($currentPage - 1) . '&per_page=' . 20);
            SEOMeta::setNext(url()->current() . '?page=' . ($currentPage + 1) . '&per_page=' . 20);
            SEOMeta::setCanonical(url()->current());
            SEOMeta::addMeta('robots', 'noindex, follow');
        }

        return view('attractions', [
            'attractions' => $attractions,
            'links' => $attractions->links('vendor.pagination.default'),
        ]);
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
     * @param  \App\Http\Requests\StoreAttractionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttractionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function show(Attraction $attraction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function edit(Attraction $attraction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttractionRequest  $request
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateattractionRequest $request, Attraction $attraction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attraction  $attraction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attraction $attraction)
    {
        //
    }
}
