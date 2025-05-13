<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="card-title mb-0">
            <i class="fas fa-book me-2"></i> Dokumentasi ChunkShield
        </h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group mb-4" id="docs-nav" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="intro-tab" data-bs-toggle="list" href="#intro" role="tab" aria-controls="intro">
                        <i class="fas fa-info-circle me-2"></i> Pengenalan
                    </a>
                    <a class="list-group-item list-group-item-action" id="getting-started-tab" data-bs-toggle="list" href="#getting-started" role="tab" aria-controls="getting-started">
                        <i class="fas fa-play me-2"></i> Memulai
                    </a>
                    <a class="list-group-item list-group-item-action" id="obfuscation-tab" data-bs-toggle="list" href="#obfuscation" role="tab" aria-controls="obfuscation">
                        <i class="fas fa-mask me-2"></i> Obfuscation
                    </a>
                    <a class="list-group-item list-group-item-action" id="chunking-tab" data-bs-toggle="list" href="#chunking" role="tab" aria-controls="chunking">
                        <i class="fas fa-puzzle-piece me-2"></i> Chunking
                    </a>
                    <a class="list-group-item list-group-item-action" id="loading-tab" data-bs-toggle="list" href="#loading" role="tab" aria-controls="loading">
                        <i class="fas fa-file-code me-2"></i> Loader
                    </a>
                    <a class="list-group-item list-group-item-action" id="advanced-security-tab" data-bs-toggle="list" href="#advanced-security" role="tab" aria-controls="advanced-security">
                        <i class="fas fa-shield-alt me-2"></i> Keamanan Lanjutan
                    </a>
                    <a class="list-group-item list-group-item-action" id="semi-compiler-tab" data-bs-toggle="list" href="#semi-compiler" role="tab" aria-controls="semi-compiler">
                        <i class="fas fa-code me-2"></i> Semi-Compiler
                    </a>
                    <a class="list-group-item list-group-item-action" id="replit-guide-tab" data-bs-toggle="list" href="#replit-guide" role="tab" aria-controls="replit-guide">
                        <i class="fas fa-server me-2"></i> Panduan Replit
                    </a>
                    <a class="list-group-item list-group-item-action" id="troubleshooting-tab" data-bs-toggle="list" href="#troubleshooting" role="tab" aria-controls="troubleshooting">
                        <i class="fas fa-wrench me-2"></i> Troubleshooting
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Introduction -->
                    <div class="tab-pane fade show active" id="intro" role="tabpanel" aria-labelledby="intro-tab">
                        <h3 class="mb-4">Pengenalan ke ChunkShield</h3>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> ChunkShield adalah sistem perlindungan kode PHP yang dirancang untuk melindungi kode sumber Anda dari pencurian, pembajakan, dan rekayasa balik.
                        </div>
                        <p>ChunkShield menggabungkan beberapa teknik perlindungan kode canggih untuk memberikan lapisan keamanan yang kuat untuk aplikasi PHP Anda:</p>
                        <ul class="feature-list">
                            <li><strong>Obfuscation:</strong> Mengubah kode menjadi format yang sulit dibaca manusia</li>
                            <li><strong>Chunking:</strong> Memecah kode menjadi potongan terenkripsi yang terpisah</li>
                            <li><strong>Loader Terenkripsi:</strong> Mekanisme pemuatan kode yang aman dengan pemeriksaan integritas</li>
                            <li><strong>Anti-Debug:</strong> Mendeteksi dan mencegah upaya debug dan analisis</li>
                            <li><strong>Anti-Tampering:</strong> Mencegah modifikasi kode tidak sah</li>
                            <li><strong>Semi-Compiler:</strong> Konversi kode PHP ke format yang hampir seperti tercompile</li>
                        </ul>
                        <p>Dengan menggunakan ChunkShield, Anda dapat mendistribusikan aplikasi PHP Anda dengan percaya diri bahwa kode sumber Anda terlindungi dari ancaman yang mencoba mengakses logika bisnis Anda.</p>
                    </div>
                    
                    <!-- Getting Started -->
                    <div class="tab-pane fade" id="getting-started" role="tabpanel" aria-labelledby="getting-started-tab">
                        <h3 class="mb-4">Memulai dengan ChunkShield</h3>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> ChunkShield sudah dikonfigurasi dan berjalan di Replit Anda!
                        </div>
                        <h4 class="mt-4">Langkah-langkah Dasar</h4>
                        <p>Berikut adalah langkah-langkah dasar untuk menggunakan ChunkShield:</p>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ol class="step-list">
                                    <li>
                                        <strong>Upload File:</strong> Unggah file PHP yang ingin Anda lindungi.
                                        <div class="mt-2 mb-3">
                                            <span class="badge bg-primary">Atau</span>
                                        </div>
                                        <strong>Paste Kode:</strong> Tempel kode PHP langsung ke editor.
                                    </li>
                                    <li>
                                        <strong>Pilih Metode Perlindungan:</strong>
                                        <ul>
                                            <li><strong>Obfuscation:</strong> Untuk kode tunggal tanpa dependensi</li>
                                            <li><strong>Chunking:</strong> Untuk perlindungan maksimum dengan potongan terenkripsi</li>
                                        </ul>
                                    </li>
                                    <li><strong>Konfigurasi Opsi:</strong> Sesuaikan pengaturan keamanan sesuai kebutuhan.</li>
                                    <li><strong>Proses Kode:</strong> Klik tombol "Proses" untuk melindungi kode Anda.</li>
                                    <li><strong>Unduh Hasil:</strong> Unduh kode yang telah dilindungi untuk digunakan di proyek Anda.</li>
                                </ol>
                            </div>
                        </div>
                        <h4>Format File yang Didukung</h4>
                        <p>ChunkShield mendukung format file berikut:</p>
                        <ul>
                            <li>File PHP individual (.php)</li>
                            <li>Script PHP tertentu dalam proyek</li>
                            <li>Kode PHP yang ditempel langsung</li>
                        </ul>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Catatan:</strong> Framework PHP yang kompleks mungkin memerlukan pendekatan perlindungan khusus. Hubungi pengembang untuk panduan lebih lanjut.
                        </div>
                    </div>
                    
                    <!-- Obfuscation -->
                    <div class="tab-pane fade" id="obfuscation" role="tabpanel" aria-labelledby="obfuscation-tab">
                        <h3 class="mb-4">Obfuscation Kode</h3>
                        <p>Obfuscation adalah proses mengubah kode sumber menjadi versi yang setara secara fungsional tetapi sangat sulit dibaca dan dimengerti oleh manusia.</p>
                        <h4 class="mt-4">Fitur Obfuscation</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ul class="feature-list">
                                    <li>
                                        <strong>Penghapusan Komentar & Whitespace</strong>
                                        <p class="text-muted small">Menghapus semua komentar dan whitespace yang tidak diperlukan untuk meminimalkan ukuran dan kejelasan.</p>
                                    </li>
                                    <li>
                                        <strong>Enkripsi String</strong>
                                        <p class="text-muted small">Mengkonversi string menjadi notasi terenkripsi untuk menyembunyikan teks dan pesan sensitif.</p>
                                    </li>
                                    <li>
                                        <strong>Pengacakan Nama Variabel</strong>
                                        <p class="text-muted small">Mengganti nama variabel, fungsi, dan kelas dengan nama acak yang tidak memiliki arti semantik.</p>
                                    </li>
                                    <li>
                                        <strong>Pengacakan Struktur</strong>
                                        <p class="text-muted small">Mengacak struktur kode untuk mempersulit analisis aliran dan tujuan kode.</p>
                                    </li>
                                    <li>
                                        <strong>Penambahan Kode Junk</strong>
                                        <p class="text-muted small">Menyisipkan kode "junk" yang tidak fungsional untuk mengaburkan logika sebenarnya dan membingungkan analisis kode.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <h4>Tingkat Obfuscation</h4>
                        <p>ChunkShield menawarkan beberapa tingkat obfuscation:</p>
                        <div class="row mt-3">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0">Ringan</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Obfuscation dasar dengan penghapusan komentar dan kompresi kode.</p>
                                        <p class="text-muted small">Ideal untuk: aplikasi sederhana dengan prioritas kinerja.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">Standar</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Obfuscation menengah dengan pengacakan nama dan beberapa enkripsi string.</p>
                                        <p class="text-muted small">Ideal untuk: mayoritas aplikasi komersial.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="card-title mb-0">Maksimal</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Obfuscation ekstensif dengan semua teknik dan keamanan tambahan.</p>
                                        <p class="text-muted small">Ideal untuk: aplikasi premium dan kode sensitif.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-lightbulb me-2"></i> <strong>Tip:</strong> Untuk keamanan maksimal, kombinasikan obfuscation dengan chunking dan lisensi.
                        </div>
                    </div>
                    
                    <!-- Chunking -->
                    <div class="tab-pane fade" id="chunking" role="tabpanel" aria-labelledby="chunking-tab">
                        <h3 class="mb-4">Chunking Kode</h3>
                        <p>Chunking adalah teknik keamanan lanjutan yang memecah kode Anda menjadi beberapa potongan terenkripsi yang terpisah, membuat rekayasa balik hampir tidak mungkin.</p>
                        
                        <h4 class="mt-4">Cara Kerja Chunking</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ol class="step-list">
                                    <li>
                                        <strong>Analisis Kode</strong>
                                        <p class="text-muted small">Kode PHP Anda dianalisis dan persiapan awal dilakukan.</p>
                                    </li>
                                    <li>
                                        <strong>Fragmentasi Kode</strong>
                                        <p class="text-muted small">Kode dibagi menjadi beberapa bagian (chunk) independen.</p>
                                    </li>
                                    <li>
                                        <strong>Enkripsi Chunk</strong>
                                        <p class="text-muted small">Setiap chunk dienkripsi secara individual menggunakan AES-256-CBC.</p>
                                    </li>
                                    <li>
                                        <strong>Kompresi</strong>
                                        <p class="text-muted small">Chunk dikompresi menggunakan gzip untuk mengurangi ukuran.</p>
                                    </li>
                                    <li>
                                        <strong>Penyebaran Chunk</strong>
                                        <p class="text-muted small">Chunk disimpan secara terpisah dengan nama file acak.</p>
                                    </li>
                                    <li>
                                        <strong>Pembuatan Loader</strong>
                                        <p class="text-muted small">File loader dibuat untuk menggabungkan dan menjalankan chunk.</p>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        
                        <h4>Keuntungan Chunking</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-shield-alt me-2"></i>Keamanan Ekstrem</h5>
                                        <p>Metode ini menawarkan tingkat keamanan tertinggi. Hanya dengan memiliki semua chunk dan loader, kode dapat direkonstruksi.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-random me-2"></i>Validasi Dinamis</h5>
                                        <p>Loader dapat melakukan validasi ekstra terhadap lingkungan eksekusi.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-key me-2"></i>Kunci Per-Chunk</h5>
                                        <p>Setiap chunk dienkripsi dengan kunci unik, menambah lapisan keamanan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-puzzle-piece me-2"></i>Interdependensi</h5>
                                        <p>Chunk didesain dengan ketergantungan satu sama lain, mencegah eksekusi parsial.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Penting:</strong> Setelah chunking, pastikan untuk menyimpan file output dengan struktur yang utuh. Penghapusan satu chunk akan membuat seluruh kode tidak berfungsi.
                        </div>
                    </div>
                    
                    <!-- Loading -->
                    <div class="tab-pane fade" id="loading" role="tabpanel" aria-labelledby="loading-tab">
                        <h3 class="mb-4">Sistem Loader ChunkShield</h3>
                        <p>Loader adalah komponen kunci dari sistem ChunkShield yang bertanggung jawab untuk memuat, mendekripsi, dan mengeksekusi kode yang dilindungi secara aman.</p>
                        
                        <h4 class="mt-4">Fungsi Loader</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ul class="feature-list">
                                    <li>
                                        <strong>Pemuatan Chunk</strong>
                                        <p class="text-muted small">Menemukan dan memuat semua chunk terenkripsi yang diperlukan.</p>
                                    </li>
                                    <li>
                                        <strong>Dekripsi</strong>
                                        <p class="text-muted small">Mendekripsi chunk menggunakan algoritma dan kunci yang benar.</p>
                                    </li>
                                    <li>
                                        <strong>Validasi Integritas</strong>
                                        <p class="text-muted small">Memverifikasi bahwa chunk tidak dirusak atau dimodifikasi.</p>
                                    </li>
                                    <li>
                                        <strong>Pemeriksaan Lingkungan</strong>
                                        <p class="text-muted small">Memastikan kode berjalan di lingkungan yang valid dan diizinkan.</p>
                                    </li>
                                    <li>
                                        <strong>Anti-Debug</strong>
                                        <p class="text-muted small">Mendeteksi dan mencegah upaya debugging atau analisis.</p>
                                    </li>
                                    <li>
                                        <strong>Eksekusi Aman</strong>
                                        <p class="text-muted small">Menjalankan kode yang dilindungi dalam konteks yang aman.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h4>Jenis Loader</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">Loader Standar</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Loader dasar yang memuat dan menjalankan kode yang dilindungi.</p>
                                        <p class="text-muted small mb-0">Fitur: Dekripsi, validasi checksum, eksekusi kode.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="card-title mb-0">Loader Terenkripsi</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Loader dengan lapisan keamanan tambahan, mempersulit ekstraksi kunci dekripsi.</p>
                                        <p class="text-muted small mb-0">Fitur: Self-encrypting, anti-tampering, obfuscation lanjutan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="mt-4">Mengamankan Loader</h4>
                        <p>Loader adalah titik fokus bagi penyerang, jadi ChunkShield menerapkan beberapa teknik untuk mengamankannya:</p>
                        <ul>
                            <li><strong>Kunci Terselubung:</strong> Kunci dekripsi tersembunyi dalam loader dengan berbagai metode</li>
                            <li><strong>Junk Eval:</strong> Blok eval palsu untuk mengaburkan logika sebenarnya</li>
                            <li><strong>Self-Modifying Code:</strong> Kode yang memodifikasi dirinya sendiri saat runtime</li>
                            <li><strong>Pengacakan Eksekusi:</strong> Urutan eksekusi yang tidak dapat diprediksi</li>
                            <li><strong>Deteksi Tampering:</strong> Mekanisme untuk mendeteksi jika loader dimodifikasi</li>
                        </ul>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-lightbulb me-2"></i> <strong>Tip:</strong> Untuk keamanan maksimal, selalu gunakan Loader Terenkripsi dan kombinasikan dengan validasi lisensi.
                        </div>
                    </div>
                    
                    <!-- Advanced Security -->
                    <div class="tab-pane fade" id="advanced-security" role="tabpanel" aria-labelledby="advanced-security-tab">
                        <h3 class="mb-4">Fitur Keamanan Lanjutan</h3>
                        <p>ChunkShield menyediakan beberapa fitur keamanan lanjutan untuk melindungi kode Anda dari berbagai serangan dan teknik pembajakan.</p>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">
                                <h4 class="card-title mb-0"><i class="fas fa-bug-slash me-2"></i>Anti-Debug Protection</h4>
                            </div>
                            <div class="card-body">
                                <p>Mencegah penyerang menggunakan debugger untuk menganalisis kode Anda selama eksekusi.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mt-3">Teknik yang Digunakan</h5>
                                        <ul>
                                            <li>Deteksi XDebug dan PHP debugger lainnya</li>
                                            <li>Time-check debugging detection</li>
                                            <li>Hook interception</li>
                                            <li>Memory analysis</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mt-3">Respon Anti-Debug</h5>
                                        <ul>
                                            <li>Pemutusan eksekusi</li>
                                            <li>Kode self-corruption</li>
                                            <li>Pengalihan ke kode honeypot</li>
                                            <li>Logging upaya serangan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">
                                <h4 class="card-title mb-0"><i class="fas fa-radiation me-2"></i>Mekanisme Self-Destruct</h4>
                            </div>
                            <div class="card-body">
                                <p>Implementasi mekanisme yang akan merusak kode secara otomatis jika terdeteksi upaya pembajakan.</p>
                                <h5 class="mt-3">Trigger Mekanisme</h5>
                                <ul>
                                    <li>Deteksi manipulasi file</li>
                                    <li>Kegagalan validasi lingkungan</li>
                                    <li>Perubahan file Loader</li>
                                    <li>Perubahan struktur chunk</li>
                                    <li>Upaya dump konten memori</li>
                                </ul>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Penting:</strong> Fitur self-destruct sangat kuat. Pastikan Anda memiliki backup kode asli sebelum implementasi.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">
                                <h4 class="card-title mb-0"><i class="fas fa-fingerprint me-2"></i>Fingerprinting & Licensi</h4>
                            </div>
                            <div class="card-body">
                                <p>Sistem fingerprinting menghasilkan identifikasi unik untuk setiap instalasi dan memvalidasi lisensi.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mt-3">Parameter Fingerprinting</h5>
                                        <ul>
                                            <li>Server hardware ID</li>
                                            <li>Nama domain dan path instalasi</li>
                                            <li>Alamat IP server</li>
                                            <li>Konfigurasi PHP</li>
                                            <li>Database identifiers</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mt-3">Jenis Validasi</h5>
                                        <ul>
                                            <li>Validasi lokal (file)</li>
                                            <li>Validasi remote (API)</li>
                                            <li>Validasi hybrid</li>
                                            <li>Time-based validation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">
                                <h4 class="card-title mb-0"><i class="fas fa-user-secret me-2"></i>Honeypot Traps</h4>
                            </div>
                            <div class="card-body">
                                <p>Honeypot traps adalah teknik untuk mendeteksi dan mengalihkan penyerang dengan memberikan informasi palsu.</p>
                                <h5 class="mt-3">Implementasi Honeypot</h5>
                                <ul>
                                    <li>Variabel & fungsi palsu dengan nama menarik</li>
                                    <li>Komentar palsu yang terlihat seperti informasi penting</li>
                                    <li>String "password" dan "key" palsu</li>
                                    <li>Path file palsu untuk menipu analisis statis</li>
                                    <li>Kode cacat yang terlihat seperti bagian asli</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-shield-alt me-2"></i> <strong>Rekomendasi:</strong> Untuk proyek komersial, sebaiknya terapkan minimal 3 lapisan keamanan lanjutan untuk perlindungan maksimal.
                        </div>
                    </div>
                    
                    <!-- Semi-Compiler -->
                    <div class="tab-pane fade" id="semi-compiler" role="tabpanel" aria-labelledby="semi-compiler-tab">
                        <h3 class="mb-4">Semi-Compiler ChunkShield</h3>
                        <p>Semi-Compiler mengubah kode PHP Anda menjadi format "semi-compiled" yang jauh lebih sulit untuk didekompilasi dan direkayasa balik dibandingkan dengan kode PHP biasa.</p>
                        
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i> <strong>Apa itu Semi-Compiling?</strong> Berbeda dengan compiled language seperti C++, PHP adalah bahasa yang diinterpretasi. Semi-compiling menggabungkan teknik-teknik canggih untuk menyembunyikan logika kode asli, meskipun tidak sepenuhnya mengubahnya menjadi bytecode.
                        </div>
                        
                        <h4>5 Level Proteksi Semi-Compiler</h4>
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0">Level 1 - Tokenization</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Kode PHP diubah menjadi representasi token dan direkonstruksi dengan cara yang berbeda.</p>
                                        <p class="text-muted small">Keamanan: <span class="text-success">★★☆☆☆</span></p>
                                        <p class="text-muted small">Kinerja: <span class="text-success">★★★★★</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">Level 2 - Dynamic Evaluation</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Kode dienkripsi dan dievaluasi secara dinamis menggunakan kombinasi teknik eval yang kompleks.</p>
                                        <p class="text-muted small">Keamanan: <span class="text-success">★★★☆☆</span></p>
                                        <p class="text-muted small">Kinerja: <span class="text-success">★★★★☆</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">Level 3 - Control Flow Obfuscation</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Struktur kontrol dimodifikasi secara radikal untuk mencegah analisis statis dan pemahaman aliran program.</p>
                                        <p class="text-muted small">Keamanan: <span class="text-success">★★★★☆</span></p>
                                        <p class="text-muted small">Kinerja: <span class="text-success">★★★☆☆</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="card-title mb-0">Level 4 - Virtual Machine</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Kode diubah menjadi bytecode kustom yang dijalankan oleh VM kecil yang tertanam dalam kode akhir.</p>
                                        <p class="text-muted small">Keamanan: <span class="text-success">★★★★★</span></p>
                                        <p class="text-muted small">Kinerja: <span class="text-success">★★☆☆☆</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="card-title mb-0">Level 5 - Polymorphic Protection</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Kombinasi dari semua level sebelumnya plus kode polimorfik yang berubah setiap kali dieksekusi, setiap instance loader menjadi unik.</p>
                                        <p>Ini adalah level perlindungan tertinggi yang menggunakan multiple teknik termasuk:</p>
                                        <ul>
                                            <li>Transformasi kode pada runtime</li>
                                            <li>Metamorfik execution paths</li>
                                            <li>Anti-decompilation multi-layer</li>
                                            <li>Enkripsi lanjutan dengan kunci dinamis</li>
                                            <li>Eksekusi hook yang terus berubah</li>
                                        </ul>
                                        <p class="text-muted small">Keamanan: <span class="text-success">★★★★★</span></p>
                                        <p class="text-muted small">Kinerja: <span class="text-success">★☆☆☆☆</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Perhatian Kinerja:</strong> Level proteksi yang lebih tinggi umumnya menurunkan kinerja. Untuk kode yang memerlukan performa maksimal, gunakan Level 1-2. Untuk kode yang sangat sensitif, gunakan Level 4-5.
                        </div>
                    </div>
                    
                    <!-- Replit Guide -->
                    <div class="tab-pane fade" id="replit-guide" role="tabpanel" aria-labelledby="replit-guide-tab">
                        <h3 class="mb-4">Panduan Menjalankan dan Memelihara ChunkShield di Replit</h3>
                        
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i> ChunkShield telah dioptimalkan khusus untuk berjalan di lingkungan Replit dengan performa dan keamanan maksimal.
                        </div>
                        
                        <h4 class="mt-4"><i class="fas fa-play-circle me-2"></i>Menjalankan ChunkShield</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ol class="step-list">
                                    <li>
                                        <strong>Menjalankan Server</strong>
                                        <p>ChunkShield sudah dikonfigurasi dengan dua workflow otomatis:</p>
                                        <ul>
                                            <li><code>ChunkShield</code> - Menjalankan setup awal dan server PHP</li>
                                            <li><code>run_chunkshield</code> - Menjalankan server PHP saja (lebih cepat)</li>
                                        </ul>
                                        <p>Klik tombol Run untuk memulai aplikasi. Server akan mulai pada port 5000.</p>
                                    </li>
                                    <li>
                                        <strong>Mengakses Antarmuka Web</strong>
                                        <p>Setelah server berjalan, klik tab "Webview" atau gunakan tombol "Open in new tab" untuk membuka ChunkShield di browser.</p>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        
                        <h4><i class="fas fa-folder-open me-2"></i>Struktur Direktori</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p>Mengenal struktur direktori ChunkShield di Replit:</p>
                                <ul>
                                    <li>
                                        <strong>ChunkShield/</strong> - Direktori utama
                                        <ul>
                                            <li><strong>chunks/</strong> - Direktori penyimpanan chunk sementara</li>
                                            <li><strong>logs/</strong> - Log aplikasi dan error</li>
                                            <li>
                                                <strong>output/</strong> - File output hasil pengolahan
                                                <ul>
                                                    <li><strong>chunks/</strong> - Kode yang telah di-chunk</li>
                                                    <li><strong>obfuscated/</strong> - Kode yang telah diobfuscate</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong>tools/</strong> - Komponen inti aplikasi
                                                <ul>
                                                    <li><strong>anti_debug.php</strong> - Proteksi anti-debugging</li>
                                                    <li><strong>anti_crack.php</strong> - Proteksi anti-cracking</li>
                                                    <li><strong>chunker.php</strong> - Engine chunking</li>
                                                    <li><strong>obfuscator.php</strong> - Engine obfuscation</li>
                                                    <li><strong>semi_compiler.php</strong> - Engine semi-compiler</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong>web/</strong> - Antarmuka web
                                                <ul>
                                                    <li><strong>assets/</strong> - File CSS, JS, dan gambar</li>
                                                    <li><strong>views/</strong> - Template tampilan PHP</li>
                                                    <li><strong>index.php</strong> - Entry point aplikasi web</li>
                                                </ul>
                                            </li>
                                            <li><strong>config.php</strong> - File konfigurasi utama</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h4><i class="fas fa-wrench me-2"></i>Konfigurasi dan Penyesuaian</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p>Anda dapat menyesuaikan ChunkShield dengan mengedit beberapa file konfigurasi:</p>
                                
                                <h5 class="mt-3">1. Konfigurasi Utama (config.php)</h5>
                                <p>File ini berisi pengaturan utama untuk semua fitur ChunkShield:</p>
                                <ul>
                                    <li><strong>DEBUG_MODE</strong> - Mode debugging (true/false)</li>
                                    <li><strong>OBFUSCATE_*</strong> - Pengaturan obfuscation</li>
                                    <li><strong>CHUNK_*</strong> - Pengaturan chunking</li>
                                    <li><strong>ENCRYPTION_*</strong> - Pengaturan enkripsi</li>
                                    <li><strong>SECURITY_*</strong> - Pengaturan keamanan</li>
                                </ul>
                                
                                <h5 class="mt-3">2. Optimasi Kinerja (performance.php)</h5>
                                <p>File ini berisi pengaturan untuk mengoptimalkan kinerja di Replit:</p>
                                <ul>
                                    <li><strong>CACHE_ENABLED</strong> - Mengaktifkan/menonaktifkan sistem cache</li>
                                    <li><strong>FILE_CACHE_TTL</strong> - Waktu cache file dalam detik</li>
                                    <li><strong>CHUNK_PROCESSING_MEMORY_LIMIT</strong> - Batas memori per operasi chunk</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h4><i class="fas fa-cloud-upload-alt me-2"></i>Penyimpanan dan Cadangan</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p>ChunkShield menyimpan semua file output di direktori <code>output/</code>. File ini tersimpan di Replit dan dapat diakses kapan saja.</p>
                                
                                <h5 class="mt-3">Praktik Terbaik untuk Penyimpanan:</h5>
                                <ul>
                                    <li>Secara berkala unduh file output penting sebagai cadangan</li>
                                    <li>Jangan menyimpan terlalu banyak file output yang tidak digunakan</li>
                                    <li>Hapus file log lama secara berkala untuk menghemat ruang</li>
                                </ul>
                                
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Penting:</strong> Replit memiliki batas penyimpanan. Jika Anda menghasilkan banyak file besar, pertimbangkan untuk menghapus file lama atau mengunduh dan menghapusnya dari Replit.
                                </div>
                            </div>
                        </div>
                        
                        <h4><i class="fas fa-tachometer-alt me-2"></i>Pemantauan Kinerja</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p>ChunkShield menyertakan utilitas pemantauan kinerja bawaan:</p>
                                <ul>
                                    <li>Statistik cache hit/miss tersedia di log server</li>
                                    <li>Informasi penggunaan memori ditampilkan (jika DEBUG_MODE aktif)</li>
                                    <li>Waktu pemrosesan terdokumentasi untuk operasi berat</li>
                                </ul>
                                
                                <p>Anda dapat melihat log sistem di direktori <code>logs/</code> untuk informasi lebih lanjut tentang kinerja.</p>
                            </div>
                        </div>
                        
                        <h4><i class="fas fa-sync me-2"></i>Memperbarui ChunkShield</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p>Untuk memperbarui ChunkShield ke versi terbaru:</p>
                                <ol>
                                    <li>Clone repositori terbaru ke tempat terpisah</li>
                                    <li>Salin file konfigurasi Anda (config.php) untuk digunakan kembali</li>
                                    <li>Pindahkan output yang ingin Anda pertahankan ke instalasi baru</li>
                                    <li>Jalankan versi baru dan verifikasi bahwa semuanya berfungsi</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Troubleshooting -->
                    <div class="tab-pane fade" id="troubleshooting" role="tabpanel" aria-labelledby="troubleshooting-tab">
                        <h3 class="mb-4">Troubleshooting dan FAQ</h3>
                        
                        <div class="accordion" id="troubleshootingAccordion">
                            <!-- Server Issues -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingServer">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServer" aria-expanded="true" aria-controls="collapseServer">
                                        <i class="fas fa-server me-2"></i> Masalah Server di Replit
                                    </button>
                                </h2>
                                <div id="collapseServer" class="accordion-collapse collapse show" aria-labelledby="headingServer" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <div class="troubleshooting-item">
                                            <h5>Server Tidak Mau Mulai</h5>
                                            <p><strong>Penyebab:</strong> Port yang sudah digunakan, kesalahan PHP, atau masalah izin file.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Restart Replit dengan menghentikan semua proses</li>
                                                <li>Periksa log untuk error spesifik di tab Console</li>
                                                <li>Pastikan tidak ada workflow lain yang berjalan di port yang sama</li>
                                                <li>Verifikasi bahwa setup.sh memiliki izin eksekusi</li>
                                            </ol>
                                        </div>
                                        
                                        <div class="troubleshooting-item mt-4">
                                            <h5>Server Berjalan Lambat</h5>
                                            <p><strong>Penyebab:</strong> Penggunaan memori yang tinggi, cache tidak efisien, atau terlalu banyak file di direktori output.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Aktifkan optimasi cache di performance.php</li>
                                                <li>Hapus file output lama yang tidak digunakan</li>
                                                <li>Kurangi CHUNK_PROCESSING_MEMORY_LIMIT jika memungkinkan</li>
                                                <li>Matikan DEBUG_MODE di config.php</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Processing Issues -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingProcessing">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProcessing" aria-expanded="false" aria-controls="collapseProcessing">
                                        <i class="fas fa-cogs me-2"></i> Masalah Pengolahan Kode
                                    </button>
                                </h2>
                                <div id="collapseProcessing" class="accordion-collapse collapse" aria-labelledby="headingProcessing" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <div class="troubleshooting-item">
                                            <h5>Error Saat Obfuscation</h5>
                                            <p><strong>Penyebab:</strong> Syntax PHP yang tidak valid, penggunaan konstruksi yang tidak didukung, atau batas memori terlampaui.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Periksa kode sumber untuk syntax error</li>
                                                <li>Kurangi ukuran file yang diproses</li>
                                                <li>Matikan beberapa opsi obfuscation yang lebih intensif di config.php</li>
                                                <li>Periksa log untuk error spesifik</li>
                                            </ol>
                                        </div>
                                        
                                        <div class="troubleshooting-item mt-4">
                                            <h5>Chunking Gagal</h5>
                                            <p><strong>Penyebab:</strong> Masalah izin direktori, penggunaan memori tinggi, atau kode sumber yang terlalu besar.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Pastikan direktori chunks/ dan output/ memiliki izin tulis</li>
                                                <li>Tingkatkan CHUNK_PROCESSING_MEMORY_LIMIT di config.php</li>
                                                <li>Pecah kode sumber besar menjadi file yang lebih kecil</li>
                                                <li>Verifikasi bahwa kode sumber valid dan dapat dieksekusi</li>
                                            </ol>
                                        </div>
                                        
                                        <div class="troubleshooting-item mt-4">
                                            <h5>Kode Terproteksi Tidak Berjalan</h5>
                                            <p><strong>Penyebab:</strong> Kesalahan dekripsi, chunk yang hilang, atau konflik dengan environment.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Pastikan semua chunk termasuk dalam deployment</li>
                                                <li>Verifikasi bahwa struktur direktori sama seperti saat pembuatan</li>
                                                <li>Periksa apakah versi PHP target kompatibel dengan kode yang dilindungi</li>
                                                <li>Gunakan opsi validasi otomatis saat membuat kode terproteksi</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Output Issues -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOutput">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOutput" aria-expanded="false" aria-controls="collapseOutput">
                                        <i class="fas fa-file-export me-2"></i> Masalah Output dan File
                                    </button>
                                </h2>
                                <div id="collapseOutput" class="accordion-collapse collapse" aria-labelledby="headingOutput" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <div class="troubleshooting-item">
                                            <h5>Tidak Dapat Mengunduh Output</h5>
                                            <p><strong>Penyebab:</strong> Masalah izin file, kesalahan PHP, atau batasan browser.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Gunakan tombol "Download" bawaan dari interface</li>
                                                <li>Periksa apakah file output ada di direktori yang benar</li>
                                                <li>Hapus cache browser dan coba lagi</li>
                                                <li>Periksa log untuk error spesifik</li>
                                            </ol>
                                        </div>
                                        
                                        <div class="troubleshooting-item mt-4">
                                            <h5>File Output Rusak</h5>
                                            <p><strong>Penyebab:</strong> Penghentian proses yang tidak tepat, masalah disk, atau bug dalam proses enkripsi.</p>
                                            <p><strong>Solusi:</strong></p>
                                            <ol>
                                                <li>Proses ulang kode sumber dengan opsi yang sama</li>
                                                <li>Pastikan Replit memiliki ruang disk yang cukup</li>
                                                <li>Periksa log untuk error selama pemrosesan</li>
                                                <li>Gunakan fitur validasi otomatis untuk memastikan output berfungsi</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- FAQ -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFAQ">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ" aria-expanded="false" aria-controls="collapseFAQ">
                                        <i class="fas fa-question-circle me-2"></i> Pertanyaan yang Sering Diajukan (FAQ)
                                    </button>
                                </h2>
                                <div id="collapseFAQ" class="accordion-collapse collapse" aria-labelledby="headingFAQ" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <div class="faq-item">
                                            <h5>Apakah ChunkShield mendukung semua versi PHP?</h5>
                                            <p>ChunkShield dikembangkan dan diuji pada PHP 7.4 dan lebih tinggi. Dukungan untuk PHP versi lebih rendah tidak dijamin dan mungkin memerlukan penyesuaian. Untuk hasil terbaik, gunakan PHP 8.0+.</p>
                                        </div>
                                        
                                        <div class="faq-item mt-4">
                                            <h5>Apakah kode yang dilindungi ChunkShield 100% aman dari pembajakan?</h5>
                                            <p>Meskipun ChunkShield menerapkan berbagai teknik keamanan canggih, tidak ada sistem perlindungan kode yang 100% tidak dapat ditembus. ChunkShield dirancang untuk membuat rekayasa balik menjadi sangat sulit, memakan waktu, dan mahal, sehingga penyerang potensial memilih target yang lebih mudah.</p>
                                        </div>
                                        
                                        <div class="faq-item mt-4">
                                            <h5>Bagaimana pengaruh ChunkShield terhadap kinerja aplikasi?</h5>
                                            <p>Dampak kinerja tergantung pada metode perlindungan yang dipilih:</p>
                                            <ul>
                                                <li>Obfuscation dasar: Hampir tidak ada dampak kinerja</li>
                                                <li>Chunking standar: Overhead 1-3% pada sebagian besar aplikasi</li>
                                                <li>Perlindungan lanjutan: Overhead 5-15% tergantung pada kompleksitas</li>
                                                <li>Semi-compiler level 4-5: Overhead 15-30% untuk keamanan maksimal</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="faq-item mt-4">
                                            <h5>Dapatkah ChunkShield melindungi framework PHP populer?</h5>
                                            <p>ChunkShield dapat melindungi kode khusus dalam framework, tetapi tidak seluruh framework. Pendekatan terbaik adalah melindungi kode bisnis kustom, plugin, atau modul yang Anda kembangkan di dalam framework, bukan seluruh codebase framework.</p>
                                        </div>
                                        
                                        <div class="faq-item mt-4">
                                            <h5>Bagaimana cara menghubungi dukungan untuk ChunkShield?</h5>
                                            <p>Untuk dukungan dan bantuan dengan ChunkShield, silakan kunjungi repositori GitHub atau hubungi pengembang melalui email support. Tim dukungan akan membantu Anda menyelesaikan masalah dan mengoptimalkan perlindungan kode PHP Anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show the active tab based on URL hash
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`a[href="${hash}"]`);
        if (tab) {
            const bsTab = new bootstrap.Tab(tab);
            bsTab.show();
        }
    }
    
    // Add hash to URL when tab is changed
    const tabLinks = document.querySelectorAll('#docs-nav a');
    tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', function(e) {
            window.location.hash = e.target.getAttribute('href');
        });
    });
    
    // Display welcome toast
    if (window.toast) {
        window.toast.info('Selamat datang di dokumentasi ChunkShield! Silakan pilih topik untuk mempelajari lebih lanjut.', 'Dokumentasi', {
            duration: 8000
        });
    }
});
</script>