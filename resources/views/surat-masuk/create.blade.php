@extends('layouts.app')
@section('title', 'Registrasi Surat Masuk')
@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary">Beranda</a> <span>/</span> <a href="{{ route('surat-masuk.index') }}" class="hover:text-primary">Arsip Surat</a> <span>/</span> <span class="text-slate-700 font-medium">Registrasi</span>
@endsection
@section('content')

<p class="text-xs font-semibold text-primary uppercase tracking-wide mb-1">Arsip Surat</p>
<h1 class="text-2xl font-bold text-slate-800 mb-1">Registrasi Surat Masuk</h1>
<p class="text-sm text-slate-500 mb-6">Silakan lengkapi detail surat yang diterima atau impor dari Excel.</p>

{{-- Import Excel Section --}}
<div class="mb-6 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
    <div class="flex items-center gap-2 mb-4">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        <h3 class="font-semibold text-slate-800">Import dari Excel (Surat Masuk)</h3>
    </div>
    <p class="text-xs text-slate-500 mb-4">Pilih file Excel untuk mengimpor satu atau beberapa surat masuk sekaligus.</p>
    <div class="grid grid-cols-1 gap-3">
        <input type="file" id="excelFile" accept=".xlsx,.xls,.xlsv,.csv" class="rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        @if(auth()->user()->isOperator())
            <input type="hidden" id="importBidangId" value="{{ auth()->user()->bidang_id }}">
        @else
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bidang Tujuan untuk Import</label>
                <select id="importBidangId" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    <option value="">Pilih Bidang (jika diimport sebagai arsip)</option>
                    @foreach($bidangList as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <button type="button" onclick="uploadExcel()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800 transition">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            Import
        </button>
    </div>
    <div id="importResult" class="mt-3 text-sm"></div>
</div>

<form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    <h3 class="font-semibold text-slate-800">Identitas Surat</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Surat <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required placeholder="Contoh: 065/UND/VI/2026" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nomor_surat') border-red-400 @enderror">
                        @error('nomor_surat')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pengirim <span class="text-red-500">*</span></label>
                        <input type="text" name="pengirim" value="{{ old('pengirim') }}" required placeholder="Contoh: Sekretariat Daerah" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('pengirim') border-red-400 @enderror">
                        @error('pengirim')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_surat') border-red-400 @enderror">
                        @error('tanggal_surat')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Diterima <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_diterima') border-red-400 @enderror">
                        @error('tanggal_diterima')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <h3 class="font-semibold text-slate-800">Isi Informasi</h3>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Perihal <span class="text-red-500">*</span></label>
                    <textarea name="perihal" rows="3" required placeholder="Tuliskan perihal surat..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('perihal') border-red-400 @enderror">{{ old('perihal') }}</textarea>
                    @error('perihal')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan (opsional)" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">{{ old('catatan') }}</textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lampiran</label>
                    <input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h3 class="font-semibold text-slate-800">Klasifikasi & Status</h3>
                </div>
                <div class="mb-4">
                    <p class="text-xs font-semibold text-slate-600 mb-2">Sifat Surat</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['biasa' => 'Biasa', 'segera' => 'Segera', 'sangat_segera' => 'S. Segera', 'rahasia' => 'Rahasia'] as $val => $label)
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="radio" name="sifat_surat" value="{{ $val }}" {{ old('sifat_surat', 'biasa') === $val ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-xs font-semibold text-slate-600 mb-2">Status Tindak Lanjut</p>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="radio" name="status" value="diteruskan" {{ old('status', 'diteruskan') === 'diteruskan' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            Diteruskan
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="radio" name="status" value="diarsipkan" {{ old('status') === 'diarsipkan' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            Diarsipkan
                        </label>
                    </div>
                </div>

                @if(auth()->user()->isOperator())
                    <input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bidang</label>
                        <input type="text" value="{{ auth()->user()->bidang->nama_bidang ?? '-' }}" readonly class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-500">
                    </div>
                @else
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bidang Tujuan <span class="text-red-500">*</span></label>
                        <select name="bidang_id" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                            <option value="">Pilih Bidang</option>
                            @foreach($bidangList as $b)
                            <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Surat Masuk
            </button>
        </div>
    </div>
</form>
<script>
function uploadExcel() {
    const fileInput = document.getElementById('excelFile');
    const file = fileInput.files[0];
    const resultBox = document.getElementById('importResult');
    resultBox.textContent = '';
    if (!file) {
        resultBox.textContent = 'Pilih file Excel terlebih dahulu.';
        return;
    }

    const previewData = new FormData();
    previewData.append('file', file);

    fetch('{{ route('surat-masuk.preview') }}', {
        method: 'POST',
        body: previewData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'success') {
            resultBox.textContent = 'Pratinjau gagal: ' + (data.message || 'Format file tidak dikenali.');
            return;
        }

        if (!data.preview || data.preview.length === 0) {
            resultBox.textContent = 'Tidak ada data di file Excel.';
            return;
        }

        // show header diagnostics
        const headers = data.headers_found || [];
        const diag = document.createElement('div');
        diag.className = 'mb-2';
        diag.innerHTML = `<strong>Header terdeteksi:</strong> ${headers.join(', ')}`;
        resultBox.appendChild(diag);

        if (!confirm('Data Excel sudah dibaca. Apakah Anda ingin mengimpor semua baris ke database?')) {
            resultBox.textContent = 'Impor dibatalkan. Periksa ulang file atau isi formulir secara manual.';
            return;
        }

        const importData = new FormData();
        importData.append('file', file);
        const bidangInput = document.getElementById('importBidangId');
        if (bidangInput && bidangInput.value) {
            importData.append('bidang_id', bidangInput.value);
        }

        fetch('{{ route('surat-masuk.import') }}', {
            method: 'POST',
            body: importData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(resp => {
            if (resp.status === 'success') {
                const messageType = resp.type === 'arsip' ? 'arsip' : 'surat masuk';
                resultBox.textContent = `Impor selesai: ${resp.created} ${messageType} berhasil, ${resp.skipped} dilewati.`;
                if (resp.created > 0) {
                    setTimeout(() => window.location.href = '{{ route('surat-masuk.index') }}', 1500);
                }
            } else {
                resultBox.textContent = 'Impor gagal: ' + (resp.message || JSON.stringify(resp));
            }
        })
        .catch(error => {
            resultBox.textContent = 'Gagal mengimpor: ' + error;
        });
    })
    .catch(error => {
        resultBox.textContent = 'Gagal membaca file: ' + error;
    });
}
</script>
@endsection
