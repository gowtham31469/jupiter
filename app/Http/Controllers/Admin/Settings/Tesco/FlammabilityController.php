<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TescoFlammability;

class FlammabilityController extends Controller
{
    public function tescoFlammability()
    {
        try {
            $tesco_flammability = TescoFlammability::select('id', 'flammability_text', 'is_enabled')->orderBy('is_enabled', 'desc')->get();
            return response()->json(['error' => 'false', 'message' => 'success', 'tesco_flammability' => $tesco_flammability]);
        } catch (\Exception $e) {
            return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
    }

    /**
     * Add tesco flammability.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoFlammability(Request $request)
    {

        try {
            if (TescoFlammability::where('flammability_text', 'ilike', $request['flammability_text'])->exists()) {
                $result = response()->json(['error' => true, 'message' => 'Flammability text Already Exists'], 208);
            } else {
                $inserted = TescoFlammability::create([
                    'flammability_text'   => $request['flammability_text'],
                ]);
                $result = $inserted != '' ? response()->json(['error' => false, 'message' => 'success'], 200) : response()->json(['error' => true, 'message' => 'Error'], 400);
            }
        } catch (\Exception $e) {
            $result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Get tesco flammability details by ID for editing.
     * @param tesco flammability id
     * @return \Illuminate\Http\Response
     */
    public function getTescoFlammability(Request $request)
    {
        try {
            $id = $request['id'];
            $flammability = TescoFlammability::where('id', $id)->select('*')->get();
            return response()->json(['error' => 'false', 'message' => 'success', 'flammability' => $flammability]);
        } catch (\Exception $e) {
            return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
    }

    /**
     * Update tesco flammability by id.
     * @param tesco flammability id
     * @return \Illuminate\Http\Response
     */
    public function updateTescoFlammability(Request $request)
    {
        try {
            $id = $request['id'];
            $updated = TescoFlammability::where('id', $id)->update([
                'flammability_text'   => $request['flammability_text'],
            ]);
            $result = $updated != '' ? response()->json(['error' => false, 'message' => 'success'], 200) : response()->json(['error' => true, 'message' => 'Error'], 400);
        } catch (\Exception $e) {
            $result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Make flammability enabled
     * @param flammability id
     * @return boolean true or false
     */
    public function enableFlammability(Request $request)
    {
        try {
            $id = $request['id'];
            TescoFlammability::where('id', $id)->update([
                'is_enabled'  => 1
            ]);
            $result = response()->json(['error' => false, 'message' => 'Success'], 200);
        } catch (\Exception $e) {
            $result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Make flammability disabled
     * @return boolean true or false
     */
    public function disableFlammability(Request $request)
    {
        try {
            $id = $request['id'];
            TescoFlammability::where('id', $id)->update([
                'is_enabled'  => 0
            ]);
            $result = response()->json(['error' => false, 'message' => 'Success'], 200);
        } catch (\Exception $e) {
            $result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
        return $result;
    }
}
