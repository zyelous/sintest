@extends('layouts.app')
@section('title', 'Tambah Arsip')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('arsip.index') }}">Arsip</a> <span>/</span> <span>Tambah</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Tambah Arsip</h1></div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('arsip.store') }}" enctype="multipart/form-data">@csrf
        <h3 class="form-section-title">Informasi Berkas</h3>
        <div class="form-grid">
            <div class="form-group"><label>Kode Klasifikasi <span class="required">*</span></label><input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}" required class="form-input @error('kode_klasifikasi') input-error @enderror">@error('kode_klasifikasi')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>No. Berkas <span class="required">*</span></label><input type="text" name="no_berkas" value="{{ old('no_berkas') }}" required class="form-input @error('no_berkas') input-error @enderror">@error('no_berkas')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group full-width"><label>Uraian Berkas <span class="required">*</span></label><textarea name="uraian_berkas" rows="2" required class="form-input">{{ old('uraian_berkas') }}</textarea></div>
            <div class="form-group"><label>Kurun Waktu</label><input type="text" name="kurun_waktu" value="{{ old('kurun_waktu') }}" class="form-input" placeholder="cth: 2020-2024"></div>
            <div class="form-group"><label>Jumlah Berkas</label><input type="text" name="jumlah_berkas" value="{{ old('jumlah_berkas', '1') }}" class="form-input"></div>
        </div>

        <h3 class="form-section-title">Informasi Arsip</h3>
        <div class="form-grid">
            <div class="form-group"><label>No. Item Arsip</label><input type="text" name="no_item_arsip" value="{{ old('no_item_arsip') }}" class="form-input"></div>
            <div class="form-group"><label>Tanggal Diarsipkan <span class="required">*</span></label><input type="date" name="tanggal_diarsipkan" value="{{ old('tanggal_diarsipkan', date('Y-m-d')) }}" required class="form-input"></div>
            <div class="form-group full-width"><label>Uraian Arsip</label><textarea name="uraian_arsip" rows="2" class="form-input">{{ old('uraian_arsip') }}</textarea></div>
            <div class="form-group"><label>Jumlah Halaman/Bundle</label><input type="text" name="jumlah_halaman_bundle" value="{{ old('jumlah_halaman_bundle') }}" class="form-input"></div>
            <div class="form-group"><label>Tingkat Perkembangan</label><input type="text" name="tingkat_perkembangan" value="{{ old('tingkat_perkembangan') }}" class="form-input" placeholder="cth: Asli/Salinan"></div>
        </div>

        <h3 class="form-section-title">Lokasi Penyimpanan</h3>
        <div class="form-grid">
            <div class="form-group full-width"><label>Lokasi Simpan</label><input type="text" name="lokasi_simpan" value="{{ old('lokasi_simpan') }}" class="form-input"></div>
            <div class="form-group"><label>No. Rak</label><input type="text" name="no_rak" value="{{ old('no_rak') }}" class="form-input"></div>
            <div class="form-group"><label>No. Boks</label><input type="text" name="no_boks" value="{{ old('no_boks') }}" class="form-input"></div>
            <div class="form-group"><label>No. Folder</label><input type="text" name="no_folder" value="{{ old('no_folder') }}" class="form-input"></div>
        </div>

        <h3 class="form-section-title">Klasifikasi & Status</h3>
        <div class="form-grid">
            <div class="form-group"><label>Klasifikasi Keamanan <span class="required">*</span></label><select name="klasifikasi_keamanan" required class="form-select"><option value="biasa">Biasa</option><option value="terbatas">Terbatas</option><option value="rahasia">Rahasia</option><option value="sangat_rahasia">Sangat Rahasia</option></select></div>
            <div class="form-group"><label>Status Retensi <span class="required">*</span></label><select name="status_retensi" required class="form-select"><option value="aktif">Aktif</option><option value="inaktif">Inaktif</option></select></div>
            <div class="form-group"><label>Nasib Akhir</label><input type="text" name="nasib_akhir" value="{{ old('nasib_akhir') }}" class="form-input" placeholder="cth: Musnah/Permanen"></div>
            <div class="form-group"><label>Bidang <span class="required">*</span></label>@if(auth()->user()->isOperator())<input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}"><input type="text" value="{{ auth()->user()->bidang->nama_bidang }}" class="form-input" readonly>@else<select name="bidang_id" required class="form-select"><option value="">Pilih Bidang</option>@foreach($bidangList as $b)<option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>@endforeach</select>@endif</div>
            <div class="form-group"><label>File Arsip</label><input type="file" name="file_arsip" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" class="form-input"><small class="text-secondary">Maks 10MB</small></div>
        </div>

        <div class="form-actions"><a href="{{ route('arsip.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div>
@endsection
