<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PartOfTwoPieceSet;

class PartOfSetController extends Controller
{
    public function tescoPartOfSet()
    {
        try {
            $records = PartOfTwoPieceSet::select('id', 'name', 'polish', 'czech', 'slovak', 'hungarian')
                ->orderBy('id', 'asc')
                ->get();
            return response()->json(['error' => 'false', 'message' => 'success', 'part_of_set' => $records]);
        } catch (\Exception $e) {
            return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
    }

    public function addTescoPartOfSet(Request $request)
    {
        try {
            if (PartOfTwoPieceSet::where('name', 'ilike', $request['name'])->exists()) {
                $result = response()->json(['error' => true, 'message' => 'Entry already exists'], 208);
            } else {
                $inserted = PartOfTwoPieceSet::create([
                    'name'      => $request['name'],
                    'polish'    => $request['polish'],
                    'czech'     => $request['czech'],
                    'slovak'    => $request['slovak'],
                    'hungarian' => $request['hungarian'],
                ]);
                $result = $inserted
                    ? response()->json(['error' => false, 'message' => 'success'], 200)
                    : response()->json(['error' => true, 'message' => 'Error'], 400);
            }
        } catch (\Exception $e) {
            $result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
        return $result;
    }

    public function getTescoPartOfSet(Request $request)
    {
        try {
            $record = PartOfTwoPieceSet::where('id', $request['id'])->get();
            return response()->json(['error' => 'false', 'message' => 'success', 'part_of_set' => $record]);
        } catch (\Exception $e) {
            return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
    }

    public function updateTescoPartOfSet(Request $request)
    {
        try {
            $updated = PartOfTwoPieceSet::where('id', $request['id'])->update([
                'name'      => $request['name'],
                'polish'    => $request['polish'],
                'czech'     => $request['czech'],
                'slovak'    => $request['slovak'],
                'hungarian' => $request['hungarian'],
            ]);
            $result = $updated !== false
                ? response()->json(['error' => false, 'message' => 'success'], 200)
                : response()->json(['error' => true, 'message' => 'Error'], 400);
        } catch (\Exception $e) {
            $result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
        return $result;
    }
}
