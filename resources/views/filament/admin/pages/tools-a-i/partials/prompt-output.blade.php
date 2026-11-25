<div class="w-full lg:w-1/2 p-4 flex flex-col bg-slate-100">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 mb-4">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#1e3a8a]"><i class="fas fa-magic mr-2"></i>Prompt Dihasilkan</h2>
                <p class="text-xs text-slate-500 mt-1">Prompt diperbarui otomatis. Salin lalu buka AI.</p>
            </div>
            <button @click="copyToClipboard()" class="bg-[#1e3a8a] hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition flex items-center shadow-md">
                <i class="fas fa-copy mr-2"></i> SALIN PROMPT
            </button>
        </div>
        
        <div class="mt-4 pt-3 border-t flex flex-wrap gap-2 justify-center sm:justify-start">
            <span class="text-xs font-semibold text-slate-500 w-full text-center sm:text-left mb-1">Buka AI untuk menempelkan Prompt:</span>
            <a href="https://chatgpt.com/" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded flex items-center shadow transition">
                <i class="fas fa-robot mr-1.5"></i> ChatGPT
            </a>
            <a href="https://gemini.google.com/" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded flex items-center shadow transition">
                <i class="fas fa-sparkles mr-1.5"></i> Gemini
            </a>
            <a href="https://claude.ai/" target="_blank" class="bg-orange-600 hover:bg-orange-700 text-white text-xs px-3 py-1.5 rounded flex items-center shadow transition">
                <i class="fas fa-brain mr-1.5"></i> Claude
            </a>
        </div>
    </div>
    
    <div class="relative flex-1 rounded-lg shadow-inner border border-slate-300 bg-white overflow-hidden flex flex-col">
        <textarea x-model="promptText" readonly class="flex-1 w-full p-5 bg-slate-50 text-slate-700 text-sm font-mono resize-none focus:outline-none"></textarea>
        
        <div x-show="showToast" x-transition class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex flex-col items-center z-50">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <span class="font-bold text-lg">Berhasil Disalin!</span>
            <span class="text-xs text-green-100 text-center mt-1">Silakan buka ChatGPT/Gemini/Claude lalu Paste.</span>
        </div>
    </div>
</div>