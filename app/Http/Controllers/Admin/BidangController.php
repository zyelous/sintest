<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidangRequest;
use App\Http\Requests\UpdateBidangRequest;
use App\Models\Bidang;
use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index(Request $request)
    {
        $query = Bidang::with(['operator', 'users'])->withCount(['users', 'arsip']);

        if ($request->filled('search') && !$request->filled('search_user')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_bidang', 'like', "%{$s}%")
                  ->orWhere('kode_bidang', 'like', "%{$s}%");
            });
        }

        $bidangList = $query->orderBy('nama_bidang')->paginate(10, ['*'], 'bidang_page')->withQueryString();

        // --- User data ---
        $userQuery = User::with('bidang');
        if ($request->filled('search_user')) {
            $s = $request->search_user;
            $userQuery->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }
        if ($request->filled('role')) {
            $userQuery->where('role', $request->role);
        }
        $userList = $userQuery->latest()->paginate(10, ['*'], 'user_page')->withQueryString();

        // --- Pending reset requests ---
        $pendingResetRequests = PasswordResetRequest::with('user.bidang')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.bidang.index', compact('bidangList', 'userList', 'pendingResetRequests'));
    }

    public function create()
    {
        return view('admin.bidang.create');
    }

    public function store(StoreBidangRequest $request)
    {
        Bidang::create($request->validated());
        return redirect()->route('admin.bidang.index', ['tab' => 'bidang'])->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit(Bidang $bidang)
    {
        return view('admin.bidang.edit', compact('bidang'));
    }

    public function update(UpdateBidangRequest $request, Bidang $bidang)
    {
        $bidang->update($request->validated());
        return redirect()->route('admin.bidang.index', ['tab' => 'bidang'])->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(Bidang $bidang)
    {
        if ($bidang->users()->count() > 0 || $bidang->arsip()->count() > 0) {
            return back()->with('error', 'Bidang tidak dapat dihapus karena masih memiliki user atau arsip terkait.');
        }
        $bidang->delete();
        return redirect()->route('admin.bidang.index', ['tab' => 'bidang'])->with('success', 'Bidang berhasil dihapus.');
    }
}
