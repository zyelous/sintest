@extends('layouts.app')
@section('title', 'Pinjam Arsip')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('peminjaman.index') }}">Peminjaman</a> <span>/</span> <span>Pinjam</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Pinjam Arsip</h1></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('peminjaman.store') }}">@csrf
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Pilih Arsip <span class="required">*</span></label>
                <select name="arsip_id" required class="form-select @error('arsip_id') input-error @enderror">
                    <option value="">-- Pilih Arsip yang Tersedia --</option>
                    @foreach($arsipTersedia as $a)
                    <option value="{{ $a->id }}" {{ old('arsip_id') == $a->id ? 'selected' : '' }}>[{{ $a->kode_klasifikasi }}] {{ $a->no_berkas }} - {{ Str::limit($a->uraian_berkas, 60) }}</option>
                    @endforeach
                </select>
                @error('arsip_id')<span class="form-error">{{ $message }}</span>@enderror
                @if($arsipTersedia->isEmpty())<small class="text-secondary">Tidak ada arsip yang tersedia untuk dipinjam.</small>@endif
            </div>
            <div class="form-group"><label>Nama Peminjam <span class="required">*</span></label><input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required class="form-input @error('nama_peminjam') input-error @enderror">@error('nama_peminjam')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Bidang Peminjam <span class="required">*</span></label><input type="text" name="bidang_peminjam" value="{{ old('bidang_peminjam') }}" required class="form-input @error('bidang_peminjam') input-error @enderror">@error('bidang_peminjam')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Tanggal Pinjam <span class="required">*</span></label><input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="form-input"></div>
            <div class="form-group full-width"><label>Keterangan</label><textarea name="keterangan" rows="2" class="form-input">{{ old('keterangan') }}</textarea></div>
        </div>
        <div class="form-actions"><a href="{{ route('peminjaman.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan Peminjaman</button></div>
    </form>
</div></div>
@endsection
