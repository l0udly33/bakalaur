<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\BadWord;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $badWords = BadWord::pluck('word')->toArray();

        $reviews = Review::with('user')
            ->get()
            ->filter(function ($review) use ($badWords) {
                foreach ($badWords as $word) {
                    if (stripos($review->comment, $word) !== false) {
                        return true;
                    }
                }
                return false;
            });

        return view('admin.reviews.bad', [
            'reviews' => $reviews,
            'badWords' => $badWords
        ]);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Atsiliepimas pašalintas.');
    }

    public function storeBadWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255|unique:bad_words,word'
        ]);

        BadWord::create(['word' => $request->word]);

        return back()->with('success', 'Netinkamas žodis pridėtas.');
    }
}
