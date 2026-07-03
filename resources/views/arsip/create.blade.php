@extends('layouts.app')
@section('title', 'Registrasi Arsip Baru')
@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary">Beranda</a> <span>/</span> <a href="{{ route('arsip.index') }}" class="hover:text-primary">Manajemen Arsip</a> <span>/</span> <span class="text-slate-700 font-medium">Registrasi</span>
@endsection
@section('content')

<p class="text-xs font-semibold text-primary uppercase tracking-wide mb-1">Arsip Surat</p>
<h1 class="text-2xl font-bold text-slate-800 mb-1">Registrasi Arsip Baru</h1>
<p class="text-sm text-slate-500 mb-6">Silakan lengkapi detail arsip dan tentukan lokasi penyimpanan fisik.</p>

<form method="POST" action="{{ route('arsip.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <h3 class="font-semibold text-slate-800">Identitas Berkas & Surat</h3>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Import dari Excel (Opsional)</label>
                    <input type="file" id="excelFile" accept=".xlsx,.xls,.xlsv,.csv" 
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Upload file Excel atau .xlsv (TSV), data akan otomatis terisi ke form</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode Klasifikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}" required placeholder="Contoh: 000.3" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('kode_klasifikasi') border-red-400 @enderror">
                        @error('kode_klasifikasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Berkas <span class="text-red-500">*</span></label>
                        <input type="text" name="no_berkas" value="{{ old('no_berkas') }}" required placeholder="Contoh: 000/7/VI.01/2026" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('no_berkas') border-red-400 @enderror">
                        @error('no_berkas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah Hal/Map/Bundle</label>
                        <input type="text" name="jumlah_halaman_bundle" value="{{ old('jumlah_halaman_bundle') }}" placeholder="e.g. 5 hal" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah Berkas</label>
                        <input type="number" min="1" name="jumlah_berkas" value="{{ old('jumlah_berkas', 1) }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Item Arsip</label>
                        <input type="text" name="no_item_arsip" value="{{ old('no_item_arsip') }}" placeholder="Masukkan nomor item" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <h3 class="font-semibold text-slate-800">Isi Informasi</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Uraian Informasi Berkas <span class="text-red-500">*</span></label>
                        <textarea name="uraian_berkas" rows="3" required placeholder="Tuliskan uraian informasi berkas..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('uraian_berkas') border-red-400 @enderror">{{ old('uraian_berkas') }}</textarea>
                        @error('uraian_berkas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Uraian Informasi Arsip</label>
                        <textarea name="uraian_arsip" rows="2" placeholder="e.g. Umum" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">{{ old('uraian_arsip') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Diarsipkan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_diarsipkan" value="{{ old('tanggal_diarsipkan', date('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_diarsipkan') border-red-400 @enderror">
                            @error('tanggal_diarsipkan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">File Arsip</label>
                            <input type="file" name="file_arsip" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" class="w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D99A1F" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <h3 class="font-semibold text-slate-800">Lokasi Simpan Fisik</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tingkat Perkembangan</label>
                        <input type="text" name="tingkat_perkembangan" value="{{ old('tingkat_perkembangan') }}" placeholder="cth: Asli/Salinan" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Rak <span class="text-red-500">*</span></label>
                        <input type="text" name="no_rak" value="{{ old('no_rak') }}" placeholder="e.g. 1" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Boks <span class="text-red-500">*</span></label>
                        <input type="text" name="no_boks" value="{{ old('no_boks') }}" placeholder="BKS-2026-001" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Folder</label>
                        <input type="text" name="no_folder" value="{{ old('no_folder') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lokasi Simpan</label>
                        <input type="text" name="lokasi_simpan" value="{{ old('lokasi_simpan') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h3 class="font-semibold text-slate-800">Keamanan & Retensi</h3>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-600 mb-2">Klasifikasi Keamanan</p>
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        @foreach(['biasa' => 'Biasa', 'terbatas' => 'Terbatas', 'rahasia' => 'Rahasia', 'sangat_rahasia' => 'S. Rahasia'] as $val => $label)
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="radio" name="klasifikasi_keamanan" value="{{ $val }}" {{ old('klasifikasi_keamanan', 'biasa') === $val ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs font-semibold text-slate-600 mb-2">Status Retensi</p>
                    <div class="flex gap-4 mb-4">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="radio" name="status_retensi" value="aktif" {{ old('status_retensi', 'aktif') === 'aktif' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            Aktif
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="radio" name="status_retensi" value="inaktif" {{ old('status_retensi') === 'inaktif' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            Inaktif
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nasib Akhir (opsional)</label>
                        <input type="text" name="nasib_akhir" value="{{ old('nasib_akhir') }}" placeholder="cth: Musnah/Permanen" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    </div>

                    @if(auth()->user()->isOperator())
                        <input type="hidden" name="bidang_id" value="{{ auth()->user()->bidang_id }}">
                    @else
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bidang <span class="text-red-500">*</span></label>
                            <select name="bidang_id" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                                <option value="">Pilih Bidang</option>
                                @foreach($bidangList as $b)
                                <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Arsip
            </button>
        </div>
    </div>
</form>
<script>
document.getElementById('excelFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);

    // First get a preview to map columns
    fetch('{{ route('arsip.preview') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success' && result.data.length > 0) {
            const row = result.data[0];

            const getValueFromRow = (keys) => {
                for (let k of keys) {
                    if (row[k] !== undefined && row[k] !== null) return row[k];
                    let tk = k.trim();
                    if (row[tk] !== undefined && row[tk] !== null) return row[tk];
                }
                return '';
            };

            // Fill form with first row for visual confirmation
            setValue('kode_klasifikasi', getValueFromRow(['Kode Klasifikasi ', 'Kode Klasifikasi', 'kode_klasifikasi']));
            setValue('no_berkas', getValueFromRow(['No. Berkas ', 'No. Berkas', 'no_berkas']));
            setValue('uraian_berkas', getValueFromRow(['Uraian Informasi Berkas ', 'Uraian Informasi Berkas', 'uraian_berkas', 'uraian_informasi_berkas']));
            setValue('uraian_arsip', getValueFromRow(['Uraian Informasi Arsip', 'Uraian Informasi Arsip ', 'uraian_arsip', 'uraian_informasi_arsip']));
            
            const rawDate = getValueFromRow(['Tanggal Diarsipkan', 'Tanggal Diarsipkan ', 'tanggal_diarsipkan', 'tanggal_arsip']);
            setValue('tanggal_diarsipkan', rawDate ? rawDate.split('T')[0] : '');

            setValue('jumlah_halaman_bundle', getValueFromRow(['Jumlah Halaman/ Map/ Bundle', 'Jumlah Halaman/Map/Bundle', 'jumlah_halaman_bundle', 'jumlah_halaman']));
            setValue('jumlah_berkas', getValueFromRow(['Jumlah Berkas', 'Jumlah Berkas ', 'jumlah_berkas']) || '1');
            setValue('no_item_arsip', getValueFromRow(['No. Item Arsip', 'No. Item Arsip ', 'no_item_arsip']));
            setValue('lokasi_simpan', getValueFromRow(['Keterangan Lokasi Simpan', 'Keterangan Lokasi Simpan ', 'lokasi_simpan', 'keterangan_lokasi_simpan']));
            setValue('no_rak', getValueFromRow(['No. Rak', 'No. Rak ', 'no_rak']));
            setValue('no_boks', getValueFromRow(['No. Boks', 'No. Boks ', 'no_boks']));
            setValue('no_folder', getValueFromRow(['No. Folder', 'No. Folder ', 'no_folder']));

            // Ask user if they want to import all rows directly
            if (confirm('Data dari Excel dimuat. Apakah Anda ingin langsung mengimpor semua baris ke database?')) {
                // Rebuild formdata to send file to import route
                const importData = new FormData();
                importData.append('file', file);

                fetch('{{ route('arsip.import') }}', {
                    method: 'POST',
                    body: importData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.status === 'success') {
                        alert('✅ Import berhasil. Total arsip sekarang: ' + resp.count);
                        // Optional: redirect to index
                        window.location.href = '{{ route('arsip.index') }}';
                    } else {
                        alert('Import gagal: ' + (resp.message || JSON.stringify(resp)));
                    }
                })
                .catch(err => alert('Gagal mengimpor: ' + err));
            } else {
                alert('Anda dapat meninjau formulir sebelum menyimpan manual.');
            }
        } else {
            alert('Tidak ada data yang ditemukan di file Excel.');
        }
    })
    .catch(err => alert('Gagal membaca file: ' + err));
});

function setValue(name, value) {
    const el = document.querySelector(`[name="${name}"]`);
    if (el) el.value = value;
}
</script>
@endsection
