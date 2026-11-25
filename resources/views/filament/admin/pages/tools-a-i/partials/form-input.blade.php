<div class="w-full lg:w-1/2 p-6 overflow-y-auto border-r border-slate-200 bg-white shadow-[10px_0_15px_-3px_rgba(0,0,0,0.05)] z-0">
    <div class="flex items-center justify-between mb-4 border-b pb-2">
        <h2 class="text-lg font-bold text-[#1e3a8a]"><i class="fas fa-edit mr-2"></i>Form Input Data Sekolah</h2>
        <span class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-500 border">Isi kolom yang tersedia</span>
    </div>
    
    <form class="space-y-6" @input="generatePrompt()" @change="generatePrompt()">
        
        <!-- Data Dasar Sekolah -->
        <div class="bg-slate-50 p-5 rounded-lg border border-slate-200">
            <h3 class="font-semibold text-slate-700 mb-4"><i class="fas fa-info-circle mr-2 text-[#3b82f6]"></i>Data Dasar Sekolah</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Nama Sekolah</label>
                    <input type="text" x-model="namaSekolah" class="w-full border-slate-300 rounded-md shadow-sm p-2 border" placeholder="Contoh: SD Negeri 1 Manislor">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">NPSN</label>
                    <input type="text" x-model="npsn" class="w-full border-slate-300 rounded-md shadow-sm p-2 border" placeholder="Contoh: 12345678">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Status Sekolah</label>
                    <select x-model="status" class="w-full border-slate-300 rounded-md shadow-sm p-2 border bg-white">
                        <option value="">Pilih Status</option>
                        <option value="Negeri">Negeri</option>
                        <option value="Swasta">Swasta</option>
                    </select>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Alamat Lengkap</label>
                    <textarea x-model="alamat" rows="2" class="w-full border-slate-300 rounded-md shadow-sm p-2 border" placeholder="Alamat lengkap..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Periode RKJM</label>
                    <input type="text" x-model="periode" class="w-full border-slate-300 rounded-md shadow-sm p-2 border">
                </div>
            </div>
        </div>

        <!-- Siswa & Guru -->
        <div class="bg-indigo-50/50 p-5 rounded-lg border border-indigo-100">
            <h3 class="font-semibold text-slate-700 mb-4"><i class="fas fa-users mr-2 text-indigo-500"></i>Rincian Siswa & Guru</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-indigo-700 mb-2">Pilih Jenjang</label>
                <select x-model="jenjang" @change="updateJenjang()" class="w-full border-slate-300 rounded-md shadow-sm p-2 border bg-white font-semibold">
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="SD">SD (Sekolah Dasar)</option>
                    <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                    <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                    <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                </select>
            </div>

            <!-- Dynamic Siswa Inputs -->
            <div class="mb-5 p-3 bg-white border rounded-md" x-show="jenjang !== ''">
                <p class="text-sm font-semibold mb-2 text-slate-600">Rincian Siswa per Kelas:</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <template x-for="(val, cls) in siswaKelas" :key="cls">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600" x-text="'Kelas ' + cls"></label>
                            <input type="number" x-model="siswaKelas[cls]" @input="calcSiswa()" class="w-full border border-slate-300 rounded p-1 text-sm" placeholder="Jml...">
                        </div>
                    </template>
                </div>
                <div class="mt-3 pt-2 border-t flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-600">Total Siswa:</span>
                    <span class="font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded" x-text="totalSiswa"></span>
                </div>
            </div>

            <!-- Guru Inputs -->
            <div class="p-3 bg-white border rounded-md">
                <p class="text-sm font-semibold mb-2 text-slate-600">Rincian Guru & Tendik:</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-slate-500">Guru Kelas/Umum</label>
                        <input type="number" x-model="guruKelas" class="w-full border border-slate-300 rounded p-1.5 text-sm" placeholder="Jml...">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500">Guru PAI/Agama</label>
                        <input type="number" x-model="guruAgama" class="w-full border border-slate-300 rounded p-1.5 text-sm" placeholder="Jml...">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500">Guru PJOK</label>
                        <input type="number" x-model="guruPJOK" class="w-full border border-slate-300 rounded p-1.5 text-sm" placeholder="Jml...">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500">Guru Lainnya</label>
                        <input type="number" x-model="guruLain" class="w-full border border-slate-300 rounded p-1.5 text-sm" placeholder="Jml...">
                    </div>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-3 pt-2 border-t items-center">
                    <div>
                        <label class="block text-sm font-bold text-slate-600">Total Guru:</label>
                        <span class="font-bold text-indigo-600" x-text="getTotalGuru()"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-600">Total Tendik:</label>
                        <input type="number" x-model="jmlTendik" class="w-full border border-slate-300 rounded p-1.5 text-sm" placeholder="Jml Tendik">
                    </div>
                </div>
            </div>
        </div>

        <!-- Arah Kebijakan -->
        <div class="bg-emerald-50/50 p-5 rounded-lg border border-emerald-100">
            <h3 class="font-semibold text-slate-700 mb-4"><i class="fas fa-bullseye mr-2 text-emerald-500"></i>Arah Kebijakan Sekolah</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Visi Sekolah</label>
                    <textarea x-model="visi" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Misi Sekolah</label>
                    <textarea x-model="misi" rows="3" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Tujuan Jangka Menengah</label>
                        <textarea x-model="tujuan" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Sasaran Strategis</label>
                        <textarea x-model="sasaran" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapor & Perencanaan -->
        <div class="bg-blue-50/50 p-5 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-slate-700 mb-4"><i class="fas fa-chart-line mr-2 text-blue-500"></i>Data Rapor & Perencanaan</h3>
            <div class="space-y-4">
                <div class="bg-white p-4 border border-blue-200 rounded-lg shadow-sm space-y-3">
                    <label class="block text-sm font-bold text-slate-700">Upload File Rapor (Excel/PDF)</label>
                    <input type="file" @change="handleFile($event)" class="block w-full text-sm text-slate-500 border border-slate-300 rounded cursor-pointer" accept=".xls,.xlsx,.csv,.pdf">
                    <div class="text-xs text-slate-400 font-bold uppercase text-center">- ATAU -</div>
                    <label class="block text-sm font-semibold text-slate-700">Ringkasan Rapor Manual</label>
                    <textarea x-model="rapor" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Hasil Evaluasi Diri (EDSP)</label>
                    <textarea x-model="edsp" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Capaian Akademik</label>
                        <textarea x-model="akademik" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Capaian Nonakademik</label>
                        <textarea x-model="nonakademik" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Permasalahan Prioritas</label>
                        <textarea x-model="masalah" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Potensi & Keunggulan</label>
                        <textarea x-model="potensi" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Kondisi Sarpras</label>
                        <textarea x-model="sarpras" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Kebutuhan SDM</label>
                        <textarea x-model="sdm" rows="2" class="w-full border-slate-300 rounded-md p-2 border text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>