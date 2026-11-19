<?php

namespace App\Http\Controllers;

use App\Events\LiveDraw;
use App\Models\Game;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{

    public function all()
    {
        $games = Game::where('status', 'live')->orderBy('id', 'DESC')->get();

        return view('results', compact('games'));
    }
    public function one($id)
    {
        $game = Game::where('id', $id)->where('status', 'live')->firstOrFail();

        return view('result', compact('game'));
    }

    public function download($id)
    {
        $game = Game::where('id', $id)->where('status', 'live')->firstOrFail();
        $pdf = Pdf::loadView('result', ['game' => $game]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('download.pdf');
    }

    public function map() {

        $games = Game::where('status', 'live')->orderBy('id', 'DESC')->get();
        return response()->view('sitemap', compact('games'))
                         ->header('Content-Type', 'application/xml');
    }

    public function index()
    {
        return view('board');
    }

    public function create(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'slot' => 'required',
            'series_letter' => 'required|string|min:1|max:1',
            'series_number' => 'required|numeric|max_digits:2|min_digits:2',
            'winner' => 'required|numeric|max_digits:5|min_digits:5',
            'secret' => 'required|string',
        ]);

        if ($request->secret !== "778800"){
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect serect key',
            ]);
        }
        $used_numbers = [];

        $date = $request->date;
        $slot = $request->slot;
        $first = $request->series_number . $request->series_letter . " " . $request->winner;
        $like_first = $request->winner;
        $used_numbers[] = (int) $like_first;

        $existingGame = Game::where('date', $date)
                        ->where('slot', $slot)
                        ->first();

        if ($existingGame) {
            return response()->json([
                'status' => 'error',
                'message' => 'The result with this date and slot already exists.',
            ]);
        }

        $second_winners = [];
        for ($i = 0; $i < 10; $i++) {
            do {
                $random_number = rand(10000, 99999);
            } while (in_array($random_number, $used_numbers));  // Ensure it's unique
            $second_winners[] = $random_number;
            $used_numbers[] = $random_number;  // Add to used numbers
        }

        $third_winners = [];
        for ($j = 0; $j < 10; $j++) {
            do {
                $random_number = rand(1000, 9999);
            } while (in_array($random_number, $used_numbers));  // Ensure it's unique
            $third_winners[] = $random_number;
            $used_numbers[] = $random_number;  // Add to used numbers
        }

        $fourth_winners = [];
        for ($k = 0; $k < 10; $k++) {
            do {
                $random_number = rand(1000, 9999);
            } while (in_array($random_number, $used_numbers));  // Ensure it's unique
            $fourth_winners[] = $random_number;
            $used_numbers[] = $random_number;  // Add to used numbers
        }

        $fifth_winners = [];
        for ($l = 0; $l < 100; $l++) {
            do {
                $random_number = rand(1000, 9999);
            } while (in_array($random_number, $used_numbers));  // Ensure it's unique
            $fifth_winners[] = $random_number;
            $used_numbers[] = $random_number;  // Add to used numbers
        }

        // Log::debug($used_numbers);

        $game = Game::create([
            'date' => $date,
            'slot' => $slot,
            'first' => $first,
            'second_winners' => implode(', ', $second_winners),
            'third_winners' => implode(', ', $third_winners),
            'fourth_winners' => implode(', ', $fourth_winners),
            'fifth_winners' => implode(', ', $fifth_winners),
            'status' => 'draft',
        ]);

        // Return the result as a JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Result created successfully.',
            'data' => [
                'id' => $game->id,
                'first' => $first,
                'second' => $second_winners,
                'third' => $third_winners,
                'fourth' => $fourth_winners,
                'fifth' => $fifth_winners,
            ]
        ]);

    }
    
    public function report()
    {
        // Fetch all games from the database
        $games = Game::all();

        // Return the report view with games data
        return view('report', compact('games'));
    }
    
    public function replay(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:games,id',
        ]);

        $game = Game::find($request->id);

        if ($game) {

            event(new LiveDraw($request->id));

            return response()->json([
                'status' => 'success',
                'message' => 'Replayed successfully.',
                'data' => $game,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Result not found.',
        ], 404);
    }
    
    public function unpublish(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:games,id',
        ]);

        $game = Game::find($request->id);

        if ($game) {

            $game->status = 'draft';

            $game->save();

            event(new LiveDraw($request->id));

            return response()->json([
                'status' => 'success',
                'message' => 'Result status updated to draft successfully.',
                'data' => $game,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Result not found.',
        ], 404);
    }

    public function publish(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:games,id',
        ]);

        $game = Game::find($request->id);

        if ($game) {

            $game->status = 'live';

            $game->save();

            event(new LiveDraw($request->id));

            return response()->json([
                'status' => 'success',
                'message' => 'Result status updated to live successfully.',
                'data' => $game,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Result not found.',
        ], 404);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:games,id',
        ]);

        $game = Game::find($request->id);

        if ($game) {
            $game->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Result deleted successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Result not found.',
        ], 404);
    }

    public function store(StoreGameRequest $request)
    {
        //
    }

    public function show(Game $game)
    {
        return response()->json($game);
    }

    public function edit(Game $game)
    {
        //
    }

    public function update(UpdateGameRequest $request, Game $game)
    {
        //
    }

    public function destroy(Game $game)
    {
        //
    }
}
