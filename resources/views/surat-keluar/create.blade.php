@extends('layouts.app')
@section('title', 'Tambah Surat Keluar')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('surat-keluar.index') }}">Surat Keluar</a> <span>/</span> <span>Tambah</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Tambah Surat Keluar</h1></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('surat-keluar.store') }}" enctype="multipart/form-data">@csrf
        <div class="form-grid">
            <div class="form-group"><label>Nomor Surat <span class="required">*</span></label><input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required class="form-input @error('nomor_surat') input-error @enderror">@error('nomor_surat')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Tanggal Surat <span class="required">*</span></label><input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required class="form-input"></div>
            <div class="form-group"><label>Tujuan <span class="required">*</span></label><input type="text" name="tujuan" value="{{ old('tujuan') }}" required class="form-input @error('tujuan') input-error @enderror">@error('tujuan')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Sifat Surat</label><select name="sifat_surat" class="form-select"><option value="biasa">Biasa</option><option value="segera">Segera</option><option value="sangat_segera">Sangat Segera</option><option value="rahasia">Rahasia</option></select></div>
            <div class="form-group full-width"><label>Perihal <span class="required">*</span></label><textarea name="perihal" rows="3" required class="form-input">{{ old('perihal') }}</textarea></div>
            <div class="form-group"><label>Bidang Pembuat</label>@if(auth()->user()->isOperator())<input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}"><input type="text" value="{{ auth()->user()->bidang->nama_bidang }}" class="form-input" readonly>@else<select name="bidang_id" required class="form-select"><option value="">Pilih Bidang</option>@foreach($bidangList as $b)<option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>@endforeach</select>@endif</div>
            <div class="form-group"><label>Lampiran</label><input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="form-input"><small class="text-secondary">Maks 10MB</small></div>
            <div class="form-group full-width"><label>Catatan</label><textarea name="catatan" rows="2" class="form-input">{{ old('catatan') }}</textarea></div>
        </div>
        <div class="form-actions"><a href="{{ route('surat-keluar.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div>
@endsection
