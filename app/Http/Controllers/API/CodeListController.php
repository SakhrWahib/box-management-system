<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CodeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CodeListController extends Controller
{
    public function index()
    {
        return CodeList::with(['user', 'device'])->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codes' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'device_id' => 'required|integer|exists:devices,id|unique:code_lists',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $codeList = CodeList::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $codeList
        ], 201);
    }

    public function show($id)
    {
        $codeList = CodeList::with(['user', 'device'])->findOrFail($id);
        return response()->json($codeList);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'codes' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|integer|exists:users,id',
            'device_id' => 'sometimes|required|integer|exists:devices,id|unique:code_lists,device_id,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $codeList = CodeList::findOrFail($id);
        $codeList->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $codeList
        ]);
    }

    public function destroy($id)
    {
        CodeList::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
