<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ArsipImportTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $operatorSek;
    private $bidangSek;
    private $bidangEko;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Bidang
        $this->bidangSek = Bidang::create(['nama_bidang' => 'SEKRETARIAT', 'kode_bidang' => 'SEK']);
        $this->bidangEko = Bidang::create(['nama_bidang' => 'EKONOMI', 'kode_bidang' => 'EKO']);

        // Setup Users
        $this->admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@sintara.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->operatorSek = User::create([
            'name' => 'Operator SEK',
            'username' => 'opsek',
            'email' => 'sek@sintara.test',
            'password' => bcrypt('password'),
            'role' => 'operator',
            'bidang_id' => $this->bidangSek->id,
            'is_active' => true,
        ]);
    }

    /**
     * Helper to generate a fake TSV file
     */
    private function createFakeTsvFile(string $filename = 'test.xlsv'): UploadedFile
    {
        $headers = [
            'Kode Klasifikasi', 'No. Berkas', 'Uraian Informasi Berkas', 'Kurun Waktu', 
            'Jumlah Berkas', 'No. Item Arsip', 'Uraian Informasi Arsip', 'Tanggal Diarsipkan', 
            'Jumlah Halaman/ Map/ Bundle', 'Tingkat Perkembangan', 'Keterangan Lokasi Simpan', 
            'No. Rak', 'No. Boks', 'No. Folder', 'Biasa', 'Terbatas', 'Rahasia', 'Sangat Rahasia', 
            'Aktif', 'Inaktif', 'Nasib Akhir'
        ];
        
        $row1 = [
            '000.3', '000/7/VI.01/2026', 'Uraian Surat Masuk Test', '2026',
            '1', '1', 'Uraian Detail Item', '2026-06-30',
            '5 hal', 'Asli', 'Gedung A',
            'Rak 1', 'BKS-2026-001', 'Folder 01', '1', '', '', '',
            '1', '', 'Musnah'
        ];

        $tsvContent = implode("\t", $headers) . "\n" . implode("\t", $row1) . "\n";
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test_import');
        file_put_contents($tempFile, $tsvContent);

        return new UploadedFile(
            $tempFile,
            $filename,
            'text/tab-separated-values',
            null,
            true // test mode
        );
    }

    public function test_guest_cannot_access_import_or_preview()
    {
        $file = $this->createFakeTsvFile();

        $this->post(route('arsip.preview'), ['file' => $file])
            ->assertRedirect(route('login'));

        $this->post(route('arsip.import'), ['file' => $file])
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_preview_tsv_file()
    {
        $this->actingAs($this->admin);
        $file = $this->createFakeTsvFile();

        $response = $this->post(route('arsip.preview'), ['file' => $file]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'Kode Klasifikasi',
                        'No. Berkas',
                        'Uraian Informasi Berkas'
                    ]
                ]
            ]);
            
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('000.3', $data[0]['Kode Klasifikasi']);
    }

    public function test_admin_can_import_tsv_file_with_bidang_selection()
    {
        $this->actingAs($this->admin);
        $file = $this->createFakeTsvFile();

        $response = $this->post(route('arsip.import'), [
            'file' => $file,
            'bidang_id' => $this->bidangEko->id
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('arsip', [
            'no_berkas' => '000/7/VI.01/2026',
            'kode_klasifikasi' => '000.3',
            'bidang_id' => $this->bidangEko->id,
            'klasifikasi_keamanan' => 'biasa',
            'status_retensi' => 'aktif',
        ]);
    }

    public function test_operator_import_is_force_constrained_to_their_bidang()
    {
        $this->actingAs($this->operatorSek);
        $file = $this->createFakeTsvFile();

        // Even if operator tries to specify another bidang_id, it should be ignored and set to their own
        $response = $this->post(route('arsip.import'), [
            'file' => $file,
            'bidang_id' => $this->bidangEko->id
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('arsip', [
            'no_berkas' => '000/7/VI.01/2026',
            'bidang_id' => $this->bidangSek->id, // Force set to SEKRETARIAT
        ]);
        
        $this->assertDatabaseMissing('arsip', [
            'no_berkas' => '000/7/VI.01/2026',
            'bidang_id' => $this->bidangEko->id,
        ]);
    }

    public function test_invalid_file_extension_fails_preview()
    {
        $this->actingAs($this->admin);
        
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post(route('arsip.preview'), ['file' => $file]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error'
            ]);
    }
}
