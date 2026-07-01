@extends('layouts.app')
@section('title', 'Edit Surat Masuk')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('surat-masuk.index') }}">Surat Masuk</a> <span>/</span> <span>Edit</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Edit Surat Masuk</h1></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('surat-masuk.update', $surat_masuk) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group"><label>Nomor Surat <span class="required">*</span></label><input type="text" name="nomor_surat" value="{{ old('nomor_surat', $surat_masuk->nomor_surat) }}" required class="form-input"></div>
            <div class="form-group"><label>Tanggal Surat <span class="required">*</span></label><input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $surat_masuk->tanggal_surat->format('Y-m-d')) }}" required class="form-input"></div>
            <div class="form-group"><label>Tanggal Diterima <span class="required">*</span></label><input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', $surat_masuk->tanggal_diterima->format('Y-m-d')) }}" required class="form-input"></div>
            <div class="form-group"><label>Pengirim <span class="required">*</span></label><input type="text" name="pengirim" value="{{ old('pengirim', $surat_masuk->pengirim) }}" required class="form-input"></div>
            <div class="form-group full-width"><label>Perihal <span class="required">*</span></label><textarea name="perihal" rows="3" required class="form-input">{{ old('perihal', $surat_masuk->perihal) }}</textarea></div>
            <div class="form-group"><label>Sifat Surat</label><select name="sifat_surat" required class="form-select">@foreach(['biasa','segera','sangat_segera','rahasia'] as $s)<option value="{{ $s }}" {{ old('sifat_surat', $surat_masuk->sifat_surat) === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
            <div class="form-group"><label>Status</label><select name="status" required class="form-select"><option value="diteruskan" {{ old('status', $surat_masuk->status) === 'diteruskan' ? 'selected' : '' }}>Diteruskan</option><option value="diarsipkan" {{ old('status', $surat_masuk->status) === 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option></select></div>
            <div class="form-group"><label>Bidang Tujuan</label>
                @if(auth()->user()->isOperator())
                <input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}"><input type="text" value="{{ auth()->user()->bidang->nama_bidang }}" class="form-input" readonly>
                @else
                <select name="bidang_id" required class="form-select">@foreach($bidangList as $b)<option value="{{ $b->id }}" {{ old('bidang_id', $surat_masuk->bidang_id) == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>@endforeach</select>
                @endif
            </div>
            <div class="form-group"><label>Lampiran</label>@if($surat_masuk->lampiran_nama)<p class="text-secondary" style="margin-bottom:4px;">File saat ini: {{ $surat_masuk->lampiran_nama }}</p>@endif<input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="form-input"><small class="text-secondary">Kosongkan jika tidak diubah</small></div>
            <div class="form-group full-width"><label>Catatan</label><textarea name="catatan" rows="2" class="form-input">{{ old('catatan', $surat_masuk->catatan) }}</textarea></div>
        </div>
        <div class="form-actions"><a href="{{ route('surat-masuk.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Perbarui</button></div>
    </form>
</div></div>
@endsection
