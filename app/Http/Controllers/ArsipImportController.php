<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsipImport;
use App\Models\Arsip;

class ArsipImportController extends Controller
{
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);

        $file = $request->file('file');
        $data = Excel::toArray(new ArsipImport, $file);

        $previewData = $data[0]; // sheet pertama

        return response()->json([
            'status' => 'success',
            'data' => $previewData,
            'count' => count($previewData)
        ]);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        try {
            $imported = Excel::import(new ArsipImport, $request->file('file'));
            return response()->json([
                'status' => 'success',
                'message' => 'Data arsip berhasil diimport!',
                'count' => Arsip::count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}