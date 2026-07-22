<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidangRequest;
use App\Http\Requests\UpdateBidangRequest;
use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index(Request $request)
    {
        $query = Bidang::with(['users'])->withCount(['users', 'arsip']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_bidang', 'like', "%{$s}%")
                  ->orWhere('kode_bidang', 'like', "%{$s}%");
            });
        }

        $bidangList = $query->orderBy('nama_bidang')->paginate(10)->withQueryString();
        return view('admin.bidang.index', compact('bidangList'));
    }

    public function create()
    {
        return view('admin.bidang.create');
    }

    public function store(StoreBidangRequest $request)
    {
        Bidang::create($request->validated());
        return redirect()->route('admin.bidang.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit(Bidang $bidang)
    {
        return view('admin.bidang.edit', compact('bidang'));
    }

    public function update(UpdateBidangRequest $request, Bidang $bidang)
    {
        $bidang->update($request->validated());
        return redirect()->route('admin.bidang.index')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(Bidang $bidang)
    {
        if ($bidang->users()->count() > 0 || $bidang->arsip()->count() > 0) {
            return back()->with('error', 'Bidang tidak dapat dihapus karena masih memiliki user atau arsip terkait.');
        }
        $bidang->delete();
        return redirect()->route('admin.bidang.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
