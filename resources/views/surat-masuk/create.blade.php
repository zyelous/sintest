@extends('layouts.app')
@section('title', 'Tambah Surat Masuk')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('surat-masuk.index') }}">Surat Masuk</a> <span>/</span> <span>Tambah</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Tambah Surat Masuk</h1></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div class="form-group"><label>Nomor Surat <span class="required">*</span></label><input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required class="form-input @error('nomor_surat') input-error @enderror">@error('nomor_surat')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Tanggal Surat <span class="required">*</span></label><input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required class="form-input @error('tanggal_surat') input-error @enderror">@error('tanggal_surat')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Tanggal Diterima <span class="required">*</span></label><input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}" required class="form-input @error('tanggal_diterima') input-error @enderror">@error('tanggal_diterima')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Pengirim <span class="required">*</span></label><input type="text" name="pengirim" value="{{ old('pengirim') }}" required class="form-input @error('pengirim') input-error @enderror">@error('pengirim')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group full-width"><label>Perihal <span class="required">*</span></label><textarea name="perihal" rows="3" required class="form-input @error('perihal') input-error @enderror">{{ old('perihal') }}</textarea>@error('perihal')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Sifat Surat <span class="required">*</span></label><select name="sifat_surat" required class="form-select"><option value="biasa" {{ old('sifat_surat') === 'biasa' ? 'selected' : '' }}>Biasa</option><option value="segera" {{ old('sifat_surat') === 'segera' ? 'selected' : '' }}>Segera</option><option value="sangat_segera" {{ old('sifat_surat') === 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option><option value="rahasia" {{ old('sifat_surat') === 'rahasia' ? 'selected' : '' }}>Rahasia</option></select></div>
            <div class="form-group"><label>Status <span class="required">*</span></label><select name="status" required class="form-select"><option value="diteruskan" {{ old('status') === 'diteruskan' ? 'selected' : '' }}>Diteruskan</option><option value="diarsipkan" {{ old('status') === 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option></select></div>
            <div class="form-group"><label>Bidang Tujuan <span class="required">*</span></label>
                @if(auth()->user()->isOperator())
                <input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}">
                <input type="text" value="{{ auth()->user()->bidang->nama_bidang }}" class="form-input" readonly>
                @else
                <select name="bidang_id" required class="form-select">
                    <option value="">Pilih Bidang</option>
                    @foreach($bidangList as $b)<option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>@endforeach
                </select>
                @endif
            </div>
            <div class="form-group"><label>Lampiran</label><input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="form-input"><small class="text-secondary">Maks 10MB. Format: PDF, JPG, PNG</small></div>
            <div class="form-group full-width"><label>Catatan</label><textarea name="catatan" rows="2" class="form-input">{{ old('catatan') }}</textarea></div>
        </div>
        <div class="form-actions"><a href="{{ route('surat-masuk.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div>
@endsection
