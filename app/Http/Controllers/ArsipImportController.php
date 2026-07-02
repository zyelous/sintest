<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsipImport;

class ArsipImportController extends Controller
{
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $data = Excel::toArray([], $request->file('file'));
            return response()->json([
                'status' => 'success',
                'data' => $data[0] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:20480'
        ]);

        try {
            $import = new ArsipImport();
            Excel::import($import, $request->file('file'));

            return redirect()->back()
                ->with('success', 'Berhasil mengimport ' . $import->getRowCount() . ' data arsip!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}