<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuoteController extends Controller
{
    use ApiResponder;

    /**
     * Get all quotes
     *
     * Mendapatkan semua jenis quotes yang ada di sekolah tersebut
     */
    #[Group('Quote')]
    public function index()
    {
        $quotes = Quote::where('school_id', Auth::user()->school_id)->get();

        return $this->success(QuoteResource::collection($quotes));
    }

    /**
     * Get random quotes by mood
     *
     * Mendapatkan quotes random berdasarkan mood terakhir. Hanya bisa diakses oleh user yang sudah merekam mood hari itu
     */
    #[Group('Quote')]
    public function getByType()
    {
        Gate::allowIf(fn (User $user) => $user->role == 'student' && $user->lastmood() !== null);
        $type = Auth::user()->lastmood();
        $type = in_array($type, ['happy', 'neutral']) ? 'secure' : 'insecure';
        $quotes = Quote::whereType($type)->where('school_id', Auth::user()->school_id)->inRandomOrder()->take(1)->first();

        return $this->success(new QuoteResource($quotes));
    }

    /**
     * Get random quotes daily
     *
     * Mendapatkan 5 quotes random harian
     */
    #[Group('Quote')]
    public function getDaily()
    {
        $quotes = Quote::whereType('daily')->where('school_id', Auth::user()->school_id)->inRandomOrder()->limit(5)->get();

        return $this->success(QuoteResource::collection($quotes));
    }

    /**
     * Create new quote
     *
     * Membuat quotes baru. Hanya bisa dilakukan oleh admin TU
     */
    #[Group('Quote')]
    public function store(CreateQuoteRequest $request)
    {
        $quote = Quote::create($request->all());

        return $this->created(new QuoteResource($quote));
    }

    /**
     * Show quote detail
     *
     * Mendapatkan detail quotes berdasarkan ID dan hanya bisa dilakukan oleh Admin TU di sekolah tersebut
     */
    #[Group('Quote')]
    public function show(Quote $quote)
    {
        Gate::allowIf(fn (User $user) => $user->role == 'admin' && $user->school_id == $quote->school_id);

        return $this->success(new QuoteResource($quote));
    }

    /**
     * Delete quote
     *
     * Menghapus quotes berdasarkan ID dan hanya bisa dilakukan oleh Admin TU di sekolah tersebut
     */
    #[Group('Quote')]
    public function destroy(Quote $quote)
    {
        Gate::allowIf(fn (User $user) => $user->role == 'admin' && $user->school_id == $quote->school_id);
        $data = $quote->toArray();
        $quote->delete();

        return $this->delete($data);
    }
}
