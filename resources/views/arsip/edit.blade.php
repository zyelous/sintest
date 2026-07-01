@extends('layouts.app')
@section('title', 'Edit Arsip')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('arsip.index') }}">Arsip</a> <span>/</span> <span>Edit</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Edit Arsip: {{ $arsip->no_berkas }}</h1></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('arsip.update', $arsip) }}" enctype="multipart/form-data">@csrf @method('PUT')
        <h3 class="form-section-title">Informasi Berkas</h3>
        <div class="form-grid">
            <div class="form-group"><label>Kode Klasifikasi <span class="required">*</span></label><input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $arsip->kode_klasifikasi) }}" required class="form-input"></div>
            <div class="form-group"><label>No. Berkas <span class="required">*</span></label><input type="text" name="no_berkas" value="{{ old('no_berkas', $arsip->no_berkas) }}" required class="form-input"></div>
            <div class="form-group full-width"><label>Uraian Berkas <span class="required">*</span></label><textarea name="uraian_berkas" rows="2" required class="form-input">{{ old('uraian_berkas', $arsip->uraian_berkas) }}</textarea></div>
            <div class="form-group"><label>Kurun Waktu</label><input type="text" name="kurun_waktu" value="{{ old('kurun_waktu', $arsip->kurun_waktu) }}" class="form-input"></div>
            <div class="form-group"><label>Jumlah Berkas</label><input type="text" name="jumlah_berkas" value="{{ old('jumlah_berkas', $arsip->jumlah_berkas) }}" class="form-input"></div>
        </div>
        <h3 class="form-section-title">Informasi Arsip</h3>
        <div class="form-grid">
            <div class="form-group"><label>No. Item Arsip</label><input type="text" name="no_item_arsip" value="{{ old('no_item_arsip', $arsip->no_item_arsip) }}" class="form-input"></div>
            <div class="form-group"><label>Tanggal Diarsipkan <span class="required">*</span></label><input type="date" name="tanggal_diarsipkan" value="{{ old('tanggal_diarsipkan', $arsip->tanggal_diarsipkan?->format('Y-m-d')) }}" required class="form-input"></div>
            <div class="form-group full-width"><label>Uraian Arsip</label><textarea name="uraian_arsip" rows="2" class="form-input">{{ old('uraian_arsip', $arsip->uraian_arsip) }}</textarea></div>
            <div class="form-group"><label>Jumlah Halaman/Bundle</label><input type="text" name="jumlah_halaman_bundle" value="{{ old('jumlah_halaman_bundle', $arsip->jumlah_halaman_bundle) }}" class="form-input"></div>
            <div class="form-group"><label>Tingkat Perkembangan</label><input type="text" name="tingkat_perkembangan" value="{{ old('tingkat_perkembangan', $arsip->tingkat_perkembangan) }}" class="form-input"></div>
        </div>
        <h3 class="form-section-title">Lokasi Penyimpanan</h3>
        <div class="form-grid">
            <div class="form-group full-width"><label>Lokasi Simpan</label><input type="text" name="lokasi_simpan" value="{{ old('lokasi_simpan', $arsip->lokasi_simpan) }}" class="form-input"></div>
            <div class="form-group"><label>No. Rak</label><input type="text" name="no_rak" value="{{ old('no_rak', $arsip->no_rak) }}" class="form-input"></div>
            <div class="form-group"><label>No. Boks</label><input type="text" name="no_boks" value="{{ old('no_boks', $arsip->no_boks) }}" class="form-input"></div>
            <div class="form-group"><label>No. Folder</label><input type="text" name="no_folder" value="{{ old('no_folder', $arsip->no_folder) }}" class="form-input"></div>
        </div>
        <h3 class="form-section-title">Klasifikasi & Status</h3>
        <div class="form-grid">
            <div class="form-group"><label>Klasifikasi Keamanan</label><select name="klasifikasi_keamanan" class="form-select">@foreach(['biasa','terbatas','rahasia','sangat_rahasia'] as $k)<option value="{{ $k }}" {{ old('klasifikasi_keamanan', $arsip->klasifikasi_keamanan) === $k ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$k)) }}</option>@endforeach</select></div>
            <div class="form-group"><label>Status Retensi</label><select name="status_retensi" class="form-select"><option value="aktif" {{ old('status_retensi', $arsip->status_retensi) === 'aktif' ? 'selected' : '' }}>Aktif</option><option value="inaktif" {{ old('status_retensi', $arsip->status_retensi) === 'inaktif' ? 'selected' : '' }}>Inaktif</option></select></div>
            <div class="form-group"><label>Nasib Akhir</label><input type="text" name="nasib_akhir" value="{{ old('nasib_akhir', $arsip->nasib_akhir) }}" class="form-input"></div>
            <div class="form-group"><label>Bidang</label>@if(auth()->user()->isOperator())<input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}"><input type="text" value="{{ auth()->user()->bidang->nama_bidang }}" class="form-input" readonly>@else<select name="bidang_id" required class="form-select">@foreach($bidangList as $b)<option value="{{ $b->id }}" {{ old('bidang_id', $arsip->bidang_id) == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>@endforeach</select>@endif</div>
            <div class="form-group"><label>File Arsip</label>@if($arsip->file_arsip)<p class="text-secondary" style="margin-bottom:4px;">File saat ini tersedia. <a href="{{ route('arsip.download', $arsip) }}">Download</a></p>@endif<input type="file" name="file_arsip" class="form-input"><small class="text-secondary">Kosongkan jika tidak diubah</small></div>
        </div>
        <div class="form-actions"><a href="{{ route('arsip.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Perbarui</button></div>
    </form>
</div></div>
@endsection
