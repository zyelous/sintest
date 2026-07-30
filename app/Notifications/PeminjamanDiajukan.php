<?php

namespace App\Notifications;

use App\Models\PeminjamanArsip;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PeminjamanDiajukan extends Notification
{
    use Queueable;

    public function __construct(public PeminjamanArsip $peminjaman) {}

    /**
     * Kirim hanya via database (in-app notification).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan di kolom `data` pada tabel notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        $arsip = $this->peminjaman->arsip;
        return [
            'peminjaman_id'  => $this->peminjaman->id,
            'nama_peminjam'  => $this->peminjaman->nama_peminjam,
            'bidang_peminjam'=> $this->peminjaman->bidang_peminjam,
            'no_berkas'      => $arsip?->no_berkas ?? '-',
            'judul_arsip'    => $arsip?->judul_arsip ?? '-',
            'tanggal_pinjam' => $this->peminjaman->tanggal_pinjam?->format('d/m/Y'),
            'url'            => route('admin.peminjaman.show', $this->peminjaman->id),
        ];
    }
}
