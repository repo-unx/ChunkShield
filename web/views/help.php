<div class="card">
    <div class="card-header bg-info text-white">
        <h2 class="card-title mb-0">
            <i class="fas fa-question-circle me-2"></i> Bantuan ChunkShield
        </h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-4">Pertanyaan yang Sering Diajukan</h3>
                
                <div class="accordion" id="faqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Apa itu ChunkShield?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>ChunkShield adalah sistem perlindungan kode PHP yang dirancang untuk melindungi kode sumber Anda dari pencurian, pembajakan, dan rekayasa balik. Sistem ini menggabungkan beberapa teknik perlindungan kode canggih seperti obfuscation, chunking, loader terenkripsi, anti-debug, anti-tampering, dan semi-compiler.</p>
                                <p>Dengan ChunkShield, Anda dapat mendistribusikan aplikasi PHP Anda dengan lebih aman karena kode sumber Anda terlindungi dari ancaman yang mencoba mengakses logika bisnis Anda.</p>
                                <p>Untuk informasi lebih lengkap, silakan kunjungi halaman <a href="index.php?tab=docs">Dokumentasi</a>.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Bagaimana cara menggunakan ChunkShield?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Menggunakan ChunkShield sangat mudah:</p>
                                <ol>
                                    <li>Upload file PHP yang ingin Anda lindungi atau tempel kode PHP langsung ke editor.</li>
                                    <li>Pilih metode perlindungan yang Anda inginkan (Obfuscation atau Chunking).</li>
                                    <li>Konfigurasi opsi keamanan sesuai kebutuhan.</li>
                                    <li>Klik tombol "Proses" untuk melindungi kode Anda.</li>
                                    <li>Unduh hasil kode yang telah dilindungi untuk digunakan di proyek Anda.</li>
                                </ol>
                                <p>Tutorial lengkap tersedia di bagian <a href="index.php?tab=docs#getting-started">Memulai</a> pada dokumentasi.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Apa perbedaan antara Obfuscation dan Chunking?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><strong>Obfuscation:</strong> Proses mengubah kode sumber menjadi versi yang setara secara fungsional tetapi sangat sulit dibaca dan dimengerti oleh manusia. Teknik ini mencakup penghapusan komentar dan whitespace, enkripsi string, pengacakan nama variabel, dll.</p>
                                <p><strong>Chunking:</strong> Teknik keamanan lanjutan yang memecah kode Anda menjadi beberapa potongan terenkripsi yang terpisah, membuat rekayasa balik hampir tidak mungkin. Setiap potongan (chunk) dienkripsi secara individual.</p>
                                <p>Untuk perlindungan maksimal, disarankan untuk menggunakan kedua metode tersebut secara bersamaan: obfuscate kode terlebih dahulu, kemudian chunk kode yang sudah diobfuscate.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Bagaimana cara kerja Semi-Compiler?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Semi-Compiler mengubah kode PHP Anda menjadi format "semi-compiled" yang jauh lebih sulit untuk didekompilasi dan direkayasa balik dibandingkan dengan kode PHP biasa.</p>
                                <p>Berbeda dengan compiled language seperti C++, PHP adalah bahasa yang diinterpretasi. Semi-compiling menggabungkan teknik-teknik canggih untuk menyembunyikan logika kode asli, meskipun tidak sepenuhnya mengubahnya menjadi bytecode.</p>
                                <p>ChunkShield menawarkan 5 level proteksi Semi-Compiler, dari Level 1 (Tokenization) hingga Level 5 (Polymorphic Protection) dengan tingkat keamanan yang semakin tinggi.</p>
                                <p>Untuk detail lebih lanjut, lihat bagian <a href="index.php?tab=docs#semi-compiler">Semi-Compiler</a> di dokumentasi.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Bagaimana cara mengatasi error saat menggunakan ChunkShield?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="faqFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Jika Anda mengalami error saat menggunakan ChunkShield, berikut beberapa langkah yang dapat Anda lakukan:</p>
                                <ol>
                                    <li>Periksa kode sumber PHP Anda untuk memastikan bahwa kode tersebut valid dan dapat dijalankan.</li>
                                    <li>Untuk file besar, coba kurangi ukuran file atau batasi kompleksitas kode.</li>
                                    <li>Jika terjadi error saat obfuscation, coba kurangi level obfuscation atau matikan beberapa opsi obfuscation.</li>
                                    <li>Untuk error saat chunking, pastikan direktori output memiliki izin tulis yang cukup.</li>
                                    <li>Gunakan fitur validasi otomatis untuk memastikan kode yang dihasilkan dapat berjalan dengan baik.</li>
                                </ol>
                                <p>Panduan troubleshooting lengkap tersedia di bagian <a href="index.php?tab=docs#troubleshooting">Troubleshooting</a> pada dokumentasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <h4>Butuh bantuan lebih lanjut?</h4>
                    <div class="mt-4">
                        <button id="showToastDemo" class="btn btn-info">
                            <i class="fas fa-bell me-2"></i> Demonstrasi Notifikasi Toast
                        </button>
                        <a href="index.php?tab=docs" class="btn btn-primary ms-2">
                            <i class="fas fa-book me-2"></i> Lihat Dokumentasi Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toast demo button
    const toastDemoButton = document.getElementById('showToastDemo');
    if (toastDemoButton) {
        toastDemoButton.addEventListener('click', function() {
            // Show multiple toasts with different types
            if (window.toast) {
                // Success toast
                window.toast.success('Ini adalah contoh notifikasi sukses', 'Sukses!');
                
                // After a short delay, show error toast
                setTimeout(() => {
                    window.toast.error('Ini adalah contoh notifikasi error', 'Error!');
                }, 1000);
                
                // After another delay, show warning toast
                setTimeout(() => {
                    window.toast.warning('Ini adalah contoh notifikasi peringatan', 'Peringatan!');
                }, 2000);
                
                // Finally, show info toast
                setTimeout(() => {
                    window.toast.info('Ini adalah contoh notifikasi informasi', 'Info!');
                }, 3000);
            }
        });
    }
});
</script>