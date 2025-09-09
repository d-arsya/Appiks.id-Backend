<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuoteController extends Controller
{

    use ApiResponder;

    /**
     * Get all quotes
     */
    #[Group('Quote')]
    public function index()
    {
        $quotes = Quote::where('school_id', Auth::user()->school_id)->get();
        return $this->success(QuoteResource::collection($quotes));
    }
    /**
     * Get all quotes by type
     */
    #[Group('Quote')]
    public function getByType(string $type)
    {
        $quotes = Quote::whereType($type)->where('school_id', Auth::user()->school_id);
        if ($type == 'daily') {
            $quotes = $quotes->inRandomOrder()->take(5)->get();
        } else {
            $quotes = $quotes->inRandomOrder()->take(1)->get();
        }
        return $this->success(QuoteResource::collection($quotes));
    }


    /**
     * Create new quote
     */
    #[Group('Quote')]
    public function store(CreateQuoteRequest $request)
    {
        $quote = Quote::create($request->all());
        return $this->created(new QuoteResource($quote));
    }


    /**
     * Show quote detail
     */
    #[Group('Quote')]
    public function show(Quote $quote)
    {
        Gate::allowIf(fn(User $user) => $user->role == 'admin' && $user->school_id == $quote->school_id);
        return $this->success(new QuoteResource($quote));
    }


    /**
     * Delete quote
     */
    #[Group('Quote')]
    public function destroy(Quote $quote)
    {
        Gate::allowIf(fn(User $user) => $user->role == 'admin' && $user->school_id == $quote->school_id);
        $quote->delete();
        return $this->success(null);
    }
}
