@extends('layouts.main')

@section('title', 'Jersey Customizer')

@php $hideNavFooter = true; @endphp

@section('content')
<div class="h-screen overflow-hidden flex flex-col bg-slate-50" x-data="customizer()" x-cloak>
    <style>
        [x-cloak] { display: none !important; }
        .canvas-container { margin: 0 auto !important; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        /* Disesuaikan ke Vibrant Orange */
        ::-webkit-scrollbar-thumb { background: #f97316; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #ea580c; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #f97316; border-radius: 10px; }
        
        @media (max-width: 768px) {
            #jersey-canvas { max-width: 100% !important; height: auto !important; }
            .canvas-container { width: 100% !important; height: auto !important; }
        }

        .floating-toolbar-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6), 0 8px 10px -6px rgba(0, 0, 0, 0.6);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @font-face { font-family: 'AC Milan 4th'; src: url('/assets/fonts/AC Milan 4th 23.woff2') format('woff2'); }
        @font-face { font-family: 'Brøndby IF'; src: url('/assets/fonts/Brøndby IF 25-26.woff2') format('woff2'); }
        @font-face { font-family: 'Girondins Bordeaux'; src: url('/assets/fonts/Girondins Bordeaux 25-26_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'Iraq 2025'; src: url('/assets/fonts/Iraq 2025_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'Osasuna 25-26'; src: url('/assets/fonts/Osasuna 25-26_NeroDesign.woff2') format('woff2'); }
        @font-face { font-family: 'PSG Fourth'; src: url('/assets/fonts/PSG Fourth 25-26_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'Palermo FC'; src: url('/assets/fonts/Palermo FC 125 years 25-26_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'Portugal WC 2026'; src: url('/assets/fonts/Portugal WC 2026_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'SC Freiburg'; src: url('/assets/fonts/SC Freiburg 25-26_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'South Africa'; src: url('/assets/fonts/South Africa 25-26_Nero Design.woff2') format('woff2'); }
        @font-face { font-family: 'Spain WC 2026'; src: url('/assets/fonts/Spain WC 2026_Nero Design.woff2') format('woff2'); }
        
        /* Cropper CSS disesuaikan dengan tema gelap */
        .cropper-container { background-color: #121212 !important; }
        .cropper-modal { background-color: #000 !important; opacity: 0.8 !important; }
        .cropper-view-box { outline: 2px solid #f97316 !important; }
        .cropper-face { background-color: transparent !important; }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <div x-show="isLoading" 
         class="fixed inset-0 z-[100] bg-brand-950/80 backdrop-blur-md flex flex-col items-center justify-center transition-all duration-500">
        <div class="relative w-24 h-24">
            <div class="absolute inset-0 border-4 border-brand-500/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-t-brand-500 rounded-full animate-spin"></div>
        </div>
        <p class="mt-6 text-brand-400 font-bold tracking-widest text-xs uppercase animate-pulse text-center">Menyiapkan Desain...</p>
    </div>

    <div id="app-container" class="flex flex-col md:flex-row h-full w-full overflow-hidden relative">
        
        <nav class="fixed bottom-0 md:relative w-full md:w-20 bg-brand-950/80 backdrop-blur-xl border-t md:border-t-0 md:border-r border-brand-800/30 flex flex-row md:flex-col items-center py-2 md:py-6 px-4 md:px-0 gap-2 md:gap-8 z-50 order-3 md:order-1 shrink-0 h-16 md:h-full">
            <div class="hidden md:flex p-3 rounded-xl bg-brand-900 shadow-lg mb-4 cursor-pointer hover:scale-110 transition-transform" @click="location.reload()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
            </div>
            
            <div class="flex flex-row md:flex-col flex-1 justify-around md:justify-start gap-2 md:gap-6 w-full overflow-x-auto no-scrollbar">
                <template x-for="menu in menus" :key="menu.id">
                    <button @click="setActiveMenu(menu.id)" :class="activeMenu === menu.id ? 'bg-brand-900/20 text-brand-400 border-brand-900/50' : 'text-slate-400 border-transparent'" class="group flex flex-col items-center gap-1 p-2 rounded-lg border transition-all min-w-[60px]">
                        <div class="p-2 rounded-lg group-hover:bg-brand-900/10"><span x-html="menu.icon"></span></div>
                        <span class="text-[9px] md:text-[10px] font-medium uppercase tracking-tighter" x-text="menu.label"></span>
                    </button>
                </template>
            </div>
        </nav>

        <aside 
            :class="isPanelOpen ? 'md:w-80 h-[45%] md:h-full opacity-100' : 'md:w-0 h-0 md:h-full opacity-0'"
            class="relative w-full bg-[#121212] border-t md:border-t-0 md:border-r border-brand-800/20 flex flex-col z-20 shadow-2xl order-2 md:order-2 transition-all duration-300 overflow-hidden shrink-0 mb-16 md:mb-0"
        >
            <header class="p-4 md:p-6 border-b border-brand-800/20 bg-brand-950/40 flex items-center justify-between">
                <div>
                    <h2 class="text-base md:text-lg font-bold capitalize text-brand-50" x-text="activeMenuLabel"></h2>
                    <p class="text-[10px] md:text-xs text-brand-400/60 mt-0.5 tracking-wider uppercase">Konfigurasi Elemen</p>
                </div>
            </header>
            
            <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-8 pb-32 custom-scrollbar">
                
                <div x-show="activeMenu === 'mockup'" x-transition class="space-y-6">
                    <label class="block text-[10px] font-bold text-brand-400/80 mb-4 uppercase tracking-[0.2em]">Pilih Model Mockup</label>
                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="mockup in availableMockups" :key="mockup.id">
                            <button 
                                @click="setModel(mockup.id)"
                                :class="currentModel === mockup.id ? 'border-brand-500 bg-brand-900/30 ring-1 ring-brand-500' : 'border-brand-800/30 hover:border-brand-700 bg-brand-950/20'"
                                class="w-full flex items-center gap-4 p-4 rounded-2xl border transition-all text-left group"
                            >
                                <div class="w-12 h-12 rounded-xl bg-brand-900/20 flex items-center justify-center group-hover:bg-brand-900/40 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-brand-50 uppercase tracking-wide" x-text="mockup.label"></h3>
                                    <p class="text-[10px] text-brand-400/60 mt-1 uppercase font-mono" x-text="mockup.id"></p>
                                </div>
                                <div x-show="currentModel === mockup.id" class="ml-auto">
                                    <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <div x-show="activeMenu === 'color'" x-transition class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-brand-400/80 mb-3 uppercase tracking-[0.2em]">Pilih Bagian Jersey</label>
                        <div class="space-y-1 bg-brand-950/40 rounded-xl border border-brand-800/20 overflow-hidden">
                            <template x-for="part in parts" :key="'part-'+part.id">
                                <button 
                                    @click="activePart = part.id"
                                    class="w-full flex items-center justify-between p-3 hover:bg-brand-900/20 transition-colors border-b border-brand-800/10 last:border-0"
                                    :class="activePart === part.id ? 'bg-brand-900/10 border-l-2 border-l-brand-500' : ''"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border border-white/10 relative overflow-hidden flex-shrink-0">
                                            <template x-if="partColorMode[part.id] === 'gradient' && activeState.partGradientEnabled[part.id]">
                                                <div class="absolute inset-0 rounded-full" :style="`background: linear-gradient(135deg, ${activeState.partGradientColor1[part.id]}, ${activeState.partGradientColor2[part.id]})`"></div>
                                            </template>
                                            <template x-if="partColorMode[part.id] !== 'gradient' || !activeState.partGradientEnabled[part.id]">
                                                <div class="absolute inset-0 rounded-full" :style="`background-color: ${partColors[part.id]}`"></div>
                                            </template>
                                        </div>
                                        <span class="text-xs font-medium" :class="activePart === part.id ? 'text-brand-300' : 'text-brand-100/70'" x-text="part.label"></span>
                                    </div>
                                    <span x-show="partColorMode[part.id] === 'gradient'" class="text-[8px] bg-brand-500/20 text-brand-400 px-1.5 py-0.5 rounded uppercase font-bold tracking-wider">Gradasi</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-brand-200 uppercase tracking-widest">Warna solid</label>
                            </div>
                            <button @click="showAllSolidColors = !showAllSolidColors" class="text-[10px] text-brand-400 hover:text-brand-300 transition-colors font-bold uppercase" x-text="showAllSolidColors ? 'Tutup' : 'Semua'"></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1.5">
                            <template x-for="(color, index) in solidColors" :key="'solid-'+color">
                                <button 
                                    x-show="showAllSolidColors || index < 14"
                                    @click="updatePartColor(activePart, color)" 
                                    :style="`background-color: ${color}`" 
                                    :class="partColors[activePart] === color && partColorMode[activePart] === 'solid' ? 'ring-2 ring-brand-400 ring-offset-2 ring-offset-[#121212] scale-110' : 'opacity-90 hover:opacity-100 hover:scale-105'" 
                                    class="w-7 h-7 rounded-full border border-white/5 transition-all shadow-lg"
                                ></button>
                            </template>
                            <button class="w-7 h-7 rounded-full border-2 border-dashed border-brand-800 hover:border-brand-500 bg-brand-950/40 flex items-center justify-center relative transition-all hover:scale-105">
                                <span class="text-brand-400 text-sm leading-none pointer-events-none">+</span>
                                <input type="color" x-ref="partPicker" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-full" @input="updatePartColor(activePart, $event.target.value)">
                            </button>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-brand-200 uppercase tracking-widest">Warna gradasi</label>
                            </div>
                            <button @click="showAllGradients = !showAllGradients" class="text-[10px] text-brand-400 hover:text-brand-300 transition-colors font-bold uppercase" x-text="showAllGradients ? 'Tutup' : 'Semua'"></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1.5">
                            <template x-for="(gp, index) in gradientPresets" :key="gp.id">
                                <button 
                                    x-show="showAllGradients || index < 14"
                                    @click="applyGradientPreset(activePart, gp)"
                                    :class="partActiveGradientPreset[activePart] === gp.id ? 'ring-2 ring-brand-400 ring-offset-2 ring-offset-[#121212] scale-110' : 'opacity-90 hover:opacity-100 hover:scale-105'"
                                    class="w-7 h-7 rounded-full border border-white/5 transition-all shadow-lg"
                                    :style="`background: ${gp.type === 'radial' ? 'radial-gradient(circle, ' + gp.color1 + ', ' + gp.color2 + ')' : 'linear-gradient(' + gp.angle + 'deg, ' + gp.color1 + ', ' + gp.color2 + ')'}`"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <div x-show="partColorMode[activePart] === 'gradient'" x-transition class="pt-4 border-t border-brand-800/20 space-y-5">
                        <label class="block text-[10px] font-bold text-brand-400 uppercase tracking-[0.2em]">Atur Gradasi</label>

                        <div class="flex gap-2 p-1 bg-brand-950/60 rounded-lg border border-brand-800/20">
                            <button @click="updatePartGradient(activePart, 'partGradientType', 'linear')" :class="activeState.partGradientType[activePart] === 'linear' ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-400'" class="flex-1 py-1.5 text-[10px] font-bold rounded-md transition-all uppercase">Linear</button>
                            <button @click="updatePartGradient(activePart, 'partGradientType', 'radial')" :class="activeState.partGradientType[activePart] === 'radial' ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-400'" class="flex-1 py-1.5 text-[10px] font-bold rounded-md transition-all uppercase">Radial</button>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] text-brand-500/80 mb-2 uppercase font-bold tracking-widest">Warna 1</label>
                                <label class="flex items-center gap-2 cursor-pointer group relative">
                                    <div class="w-9 h-9 rounded-lg border-2 border-white/5 group-hover:border-brand-500 transition-colors flex-shrink-0" :style="`background-color: ${activeState.partGradientColor1[activePart]}`"></div>
                                    <span class="text-[9px] text-brand-400 font-mono" x-text="activeState.partGradientColor1[activePart]"></span>
                                    <input type="color" :value="activeState.partGradientColor1[activePart]" @input="updatePartGradient(activePart, 'partGradientColor1', $event.target.value)" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer">
                                </label>
                            </div>
                            <div>
                                <label class="block text-[9px] text-brand-500/80 mb-2 uppercase font-bold tracking-widest">Warna 2</label>
                                <label class="flex items-center gap-2 cursor-pointer group relative">
                                    <div class="w-9 h-9 rounded-lg border-2 border-white/5 group-hover:border-brand-500 transition-colors flex-shrink-0" :style="`background-color: ${activeState.partGradientColor2[activePart]}`"></div>
                                    <span class="text-[9px] text-brand-400 font-mono" x-text="activeState.partGradientColor2[activePart]"></span>
                                    <input type="color" :value="activeState.partGradientColor2[activePart]" @input="updatePartGradient(activePart, 'partGradientColor2', $event.target.value)" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer">
                                </label>
                            </div>
                        </div>

                        <div x-show="activeState.partGradientType[activePart] === 'linear'" x-transition>
                            <label class="flex justify-between text-[10px] font-bold text-brand-400/80 mb-2 uppercase tracking-widest">
                                <span>Sudut Gradasi</span>
                                <span class="text-brand-400" x-text="activeState.partGradientAngle[activePart] + '°'"></span>
                            </label>
                            <input type="range" min="0" max="360" :value="activeState.partGradientAngle[activePart]" @input="updatePartGradient(activePart, 'partGradientAngle', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                        </div>
                    </div>
                </div>

                <div x-show="activeMenu === 'pattern'" x-transition class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-brand-400/80 mb-4 uppercase tracking-[0.2em]">Atur Motif Per Bagian</label>
                        <div class="space-y-1 bg-brand-950/40 rounded-xl border border-brand-800/20 overflow-hidden mb-6">
                            <template x-for="part in parts.filter(p => !['belt'].includes(p.id))" :key="'pnode-'+part.id">
                                <div 
                                    class="w-full flex items-center justify-between p-3 hover:bg-brand-900/10 transition-colors border-b border-brand-800/10 last:border-0 cursor-pointer"
                                    :class="activePatternPart === part.id ? 'bg-brand-900/10 border-l-2 border-l-brand-500' : ''"
                                    @click="activePatternPart = part.id"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full" :class="partPatterns[part.id] ? 'bg-brand-500' : 'bg-brand-800'"></div>
                                        <span class="text-xs font-medium" :class="activePatternPart === part.id ? 'text-brand-300' : 'text-brand-100/70'" x-text="part.label"></span>
                                    </div>
                                    <button 
                                        @click.stop="togglePartPattern(part.id)"
                                        class="relative inline-flex h-4 w-7 items-center rounded-full transition-colors focus:outline-none"
                                        :class="partPatterns[part.id] ? 'bg-brand-600' : 'bg-brand-800/50'"
                                    >
                                        <span class="inline-block h-2.5 w-2.5 transform rounded-full bg-white transition-transform" :class="partPatterns[part.id] ? 'translate-x-4' : 'translate-x-0.5'"></span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-brand-400/80 mb-3 uppercase tracking-[0.2em]">Pilih Motif <span class="text-brand-300" x-text="parts.find(p => p.id === activePatternPart)?.label"></span></label>
                        <div class="grid grid-cols-2 gap-3 mb-6 max-h-52 md:max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                            <template x-for="p in patterns" :key="p.id">
                                <button 
                                    @click="partActivePatterns[activePatternPart] = p.id; updatePattern()" 
                                    class="group relative aspect-[4/3] bg-brand-950/20 rounded-xl overflow-hidden border-2 transition-all" 
                                    :class="partActivePatterns[activePatternPart] === p.id ? 'border-brand-500 ring-2 ring-brand-500/20' : 'border-brand-800/30'"
                                >
                                    <img :src="p.url" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute inset-0 bg-gradient-to-t from-brand-950/80 to-transparent"></div>
                                    <div class="absolute bottom-2 left-2 text-[9px] font-bold text-brand-100 uppercase truncate pr-2" x-text="p.name"></div>
                                </button>
                            </template>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Warna Motif</label>
                                <button @click="showAllPatternColors = !showAllPatternColors" class="text-[10px] text-brand-400 hover:text-brand-300 font-bold uppercase transition-colors" x-text="showAllPatternColors ? 'Tutup' : 'Semua'"></button>
                            </div>
                            <div class="grid grid-cols-7 gap-1.5">
                                <template x-for="(color, index) in solidColors" :key="'pat-'+color">
                                    <button x-show="showAllPatternColors || index < 14" @click="updatePatternColor(color)" :style="`background-color: ${color}`" :class="patternColor === color ? 'ring-2 ring-brand-400 ring-offset-2 ring-offset-[#121212] scale-110' : 'opacity-90 hover:opacity-100 hover:scale-105'" class="w-7 h-7 rounded-full border border-white/5 shadow-lg transition-all"></button>
                                </template>
                                <button class="w-7 h-7 rounded-full border-2 border-dashed border-brand-800 hover:border-brand-500 bg-brand-950/40 flex items-center justify-center relative transition-all hover:scale-105">
                                    <span class="text-brand-400 text-sm leading-none">+</span>
                                    <input type="color" x-ref="patternPicker" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-full" @input="updatePatternColor($event.target.value)">
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4 pt-4">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Gradasi Motif</label>
                                <button @click="showAllPatternGradients = !showAllPatternGradients" class="text-[10px] text-brand-400 hover:text-brand-300 font-bold uppercase transition-colors" x-text="showAllPatternGradients ? 'Tutup' : 'Semua'"></button>
                            </div>
                            <div class="grid grid-cols-7 gap-1.5">
                                <template x-for="(gp, index) in gradientPresets" :key="'pat-grad-'+gp.id">
                                    <button 
                                        x-show="showAllPatternGradients || index < 14"
                                        @click="updatePatternGradientFromPreset(gp)"
                                        :class="patternActiveGradientPreset === gp.id && activeState.partPatternGradientEnabled[activePatternPart] ? 'ring-2 ring-brand-400 ring-offset-2 ring-offset-[#121212] scale-110' : 'opacity-90 hover:opacity-100 hover:scale-105'"
                                        class="w-7 h-7 rounded-full border border-white/5 transition-all shadow-lg"
                                        :style="`background: ${gp.type === 'radial' ? 'radial-gradient(circle, ' + gp.color1 + ', ' + gp.color2 + ')' : 'linear-gradient(' + gp.angle + 'deg, ' + gp.color1 + ', ' + gp.color2 + ')'}`"
                                    ></button>
                                </template>
                            </div>

                            <div x-show="activeState.partPatternGradientEnabled[activePatternPart]" x-transition class="pt-2 space-y-4">
                                <div class="flex gap-2 p-1 bg-brand-950/60 rounded-lg border border-brand-800/20">
                                    <button @click="updatePatternGradient('partPatternGradientType', 'linear')" :class="activeState.partPatternGradientType[activePatternPart] === 'linear' ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-400'" class="flex-1 py-1.5 text-[9px] font-bold rounded-md transition-all uppercase">Linear</button>
                                    <button @click="updatePatternGradient('partPatternGradientType', 'radial')" :class="activeState.partPatternGradientType[activePatternPart] === 'radial' ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-400'" class="flex-1 py-1.5 text-[9px] font-bold rounded-md transition-all uppercase">Radial</button>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2 cursor-pointer group relative">
                                        <div class="w-8 h-8 rounded-lg border border-white/10 group-hover:border-brand-500 transition-colors" :style="`background-color: ${activeState.partPatternGradientColor1[activePatternPart]}`"></div>
                                        <input type="color" :value="activeState.partPatternGradientColor1[activePatternPart]" @input="updatePatternGradient('partPatternGradientColor1', $event.target.value)" class="absolute inset-0 opacity-0 cursor-pointer">
                                        <span class="text-[9px] font-mono text-brand-400 uppercase">Warna 1</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group relative">
                                        <div class="w-8 h-8 rounded-lg border border-white/10 group-hover:border-brand-500 transition-colors" :style="`background-color: ${activeState.partPatternGradientColor2[activePatternPart]}`"></div>
                                        <input type="color" :value="activeState.partPatternGradientColor2[activePatternPart]" @input="updatePatternGradient('partPatternGradientColor2', $event.target.value)" class="absolute inset-0 opacity-0 cursor-pointer">
                                        <span class="text-[9px] font-mono text-brand-400 uppercase">Warna 2</span>
                                    </label>
                                </div>
                                <div x-show="activeState.partPatternGradientType[activePatternPart] === 'linear'" x-transition>
                                    <input type="range" min="0" max="360" :value="activeState.partPatternGradientAngle[activePatternPart]" @input="updatePatternGradient('partPatternGradientAngle', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6 pt-6 mt-6 border-t border-brand-800/20">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Scale: <span class="text-brand-300" x-text="Number(partPatternScales[activePatternPart] || 0.3).toFixed(1)"></span></label>
                                        <button @click="resetPatternProperty('scale')" class="p-1 hover:text-brand-300 text-brand-500/70 transition-colors" title="Reset Scale">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        </button>
                                    </div>
                                    <input type="range" min="0.3" max="8" step="0.3" x-model.number="partPatternScales[activePatternPart]" @input="updatePatternScale($event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Angle: <span class="text-brand-300" x-text="partPatternAngles[activePatternPart]"></span>°</label>
                                        <button @click="resetPatternProperty('angle')" class="p-1 hover:text-brand-300 text-brand-500/70 transition-colors" title="Reset Angle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        </button>
                                    </div>
                                    <input type="range" min="0" max="360" step="1" x-model="partPatternAngles[activePatternPart]" @input="updatePatternAngle($event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Posisi X: <span class="text-brand-300" x-text="partPatternX[activePatternPart]"></span></label>
                                        <button @click="resetPatternProperty('X')" class="p-1 hover:text-brand-300 text-brand-500/70 transition-colors" title="Reset Posisi X">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        </button>
                                    </div>
                                    <input type="range" min="-300" max="900" step="1" x-model="partPatternX[activePatternPart]" @input="updatePatternPosition('X', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Posisi Y: <span class="text-brand-300" x-text="partPatternY[activePatternPart]"></span></label>
                                        <button @click="resetPatternProperty('Y')" class="p-1 hover:text-brand-300 text-brand-500/70 transition-colors" title="Reset Posisi Y">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        </button>
                                    </div>
                                    <input type="range" min="-300" max="900" step="1" x-model="partPatternY[activePatternPart]" @input="updatePatternPosition('Y', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button 
                                    @click="updatePatternFlip('X')"
                                    :class="partPatternFlipX[activePatternPart] ? 'bg-brand-900 border-brand-500' : 'bg-brand-950/40 border-brand-800/30'"
                                    class="flex-1 py-2 rounded-xl border flex items-center justify-center gap-2 text-[10px] font-bold uppercase transition-all text-brand-100"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="m15 15 3-3-3-3"/><path d="m9 9-3 3 3 3"/></svg>
                                    Flip H
                                </button>
                                <button 
                                    @click="updatePatternFlip('Y')"
                                    :class="partPatternFlipY[activePatternPart] ? 'bg-brand-900 border-brand-500' : 'bg-brand-950/40 border-brand-800/30'"
                                    class="flex-1 py-2 rounded-xl border flex items-center justify-center gap-2 text-[10px] font-bold uppercase transition-all text-brand-100"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18"/><path d="m9 15 3 3 3-3"/><path d="m15 9-3-3-3 3"/></svg>
                                    Flip V
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div x-show="activeMenu === 'text'" x-transition class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-brand-400/80 mb-3 uppercase tracking-[0.2em]">Input Teks</label>
                        <div class="flex gap-2">
                            <input type="text" x-model="textInput" @input="updateTextProperty('textInput', $event.target.value)" class="flex-1 bg-brand-950/40 border border-brand-800/30 rounded-xl px-4 py-3 text-brand-50 text-sm focus:border-brand-500 focus:outline-none transition-all" placeholder="Ketik teks...">
                            <button @click="addText()" class="bg-brand-900 hover:bg-brand-800 text-white p-3 rounded-xl transition-all shadow-xl shadow-black/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-brand-400/80 mb-3 uppercase tracking-[0.2em]">Pilihan Font</label>
                        <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto pr-1 custom-scrollbar">
                            <template x-for="f in availableFonts" :key="f.id">
                                <button 
                                    @click="updateFont('activeFont', f.id)"
                                    :class="activeFont === f.id ? 'border-brand-500 bg-brand-900/30 ring-1 ring-brand-500' : 'border-brand-800/30 hover:border-brand-700 bg-brand-950/20'"
                                    class="p-3 border rounded-xl transition-all text-center group"
                                >
                                    <div class="text-sm text-brand-50 truncate" :style="`font-family: '${f.id}'`" x-text="'Abc 123'"></div>
                                    <div class="text-[8px] text-brand-400/60 mt-1 uppercase font-bold tracking-widest" x-text="f.name"></div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Warna Teks</label>
                            <button @click="showAllTextColors = !showAllTextColors" class="text-[10px] text-brand-400 hover:text-brand-300 font-bold uppercase" x-text="showAllTextColors ? 'Tutup' : 'Semua'"></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1.5">
                            <template x-for="(color, index) in solidColors" :key="'txt-'+color">
                                <button x-show="showAllTextColors || index < 14" @click="updateTextProperty('activeColor', color)" :style="`background-color: ${color}`" :class="activeColor === color && !textGradientEnabled ? 'ring-2 ring-brand-400 ring-offset-2 ring-offset-[#121212] scale-110' : 'opacity-90 hover:opacity-100 hover:scale-105'" class="w-7 h-7 rounded-full border border-white/5 shadow-lg transition-all"></button>
                            </template>
                            <button class="w-7 h-7 rounded-full border-2 border-dashed border-brand-800 hover:border-brand-500 bg-brand-950/40 flex items-center justify-center relative transition-all hover:scale-105">
                                <span class="text-brand-400 text-sm leading-none pointer-events-none">+</span>
                                <input type="color" x-ref="textPicker" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-full" @input="updateTextProperty('activeColor', $event.target.value)">
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Gradasi Teks</label>
                            <button @click="showAllTextGradients = !showAllTextGradients" class="text-[10px] text-brand-400 hover:text-brand-300 font-bold uppercase transition-colors" x-text="showAllTextGradients ? 'Tutup' : 'Semua'"></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1.5">
                            <template x-for="(gp, index) in gradientPresets" :key="'txt-grad-'+gp.id">
                                <button 
                                    x-show="showAllTextGradients || index < 14"
                                    @click="applyTextGradientPreset(gp)"
                                    :class="textActiveGradientPreset === gp.id && textGradientEnabled ? 'ring-2 ring-brand-400 ring-offset-2 ring-offset-[#121212] scale-110' : 'opacity-90 hover:opacity-100 hover:scale-105'"
                                    class="w-7 h-7 rounded-full border border-white/5 transition-all shadow-lg"
                                    :style="`background: ${gp.type === 'radial' ? 'radial-gradient(circle, ' + gp.color1 + ', ' + gp.color2 + ')' : 'linear-gradient(' + gp.angle + 'deg, ' + gp.color1 + ', ' + gp.color2 + ')'}`"
                                ></button>
                            </template>
                        </div>

                        <div x-show="textGradientEnabled" x-transition class="pt-2 space-y-4">
                            <div class="flex gap-2 p-1 bg-brand-950/60 rounded-lg border border-brand-800/20">
                                <button @click="updateTextProperty('textGradientType', 'linear')" :class="textGradientType === 'linear' ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-400'" class="flex-1 py-1.5 text-[9px] font-bold rounded-md transition-all uppercase">Linear</button>
                                <button @click="updateTextProperty('textGradientType', 'radial')" :class="textGradientType === 'radial' ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-400'" class="flex-1 py-1.5 text-[9px] font-bold rounded-md transition-all uppercase">Radial</button>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 cursor-pointer group relative">
                                    <div class="w-8 h-8 rounded-lg border border-white/10 group-hover:border-brand-500 transition-colors" :style="`background-color: ${textGradientColor1}`"></div>
                                    <input type="color" :value="textGradientColor1" @input="updateTextProperty('textGradientColor1', $event.target.value)" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <span class="text-[9px] font-mono text-brand-400 uppercase">Warna 1</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group relative">
                                    <div class="w-8 h-8 rounded-lg border border-white/10 group-hover:border-brand-500 transition-colors" :style="`background-color: ${textGradientColor2}`"></div>
                                    <input type="color" :value="textGradientColor2" @input="updateTextProperty('textGradientColor2', $event.target.value)" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <span class="text-[9px] font-mono text-brand-400 uppercase">Warna 2</span>
                                </label>
                            </div>
                            <div x-show="textGradientType === 'linear'" x-transition>
                                <input type="range" min="0" max="360" :value="textGradientAngle" @input="updateTextProperty('textGradientAngle', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5 pt-6 mt-4 border-t border-brand-800/20">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Ukuran: <span class="text-brand-300" x-text="textFontSize"></span>px</label>
                            </div>
                            <input type="range" min="10" max="250" step="1" x-model.number="textFontSize" @input="updateTextProperty('textFontSize', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Jarak Huruf: <span class="text-brand-300" x-text="textCharSpacing"></span></label>
                            </div>
                            <input type="range" min="-100" max="1000" step="10" x-model.number="textCharSpacing" @input="updateTextProperty('textCharSpacing', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <label class="text-[10px] font-bold text-brand-400 uppercase tracking-widest">Lengkungan: <span class="text-brand-300" x-text="textArc"></span></label>
                            </div>
                            <input type="range" min="-300" max="300" step="1" x-model.number="textArc" @input="updateTextProperty('textArc', $event.target.value)" class="w-full h-1 bg-brand-800 rounded-lg appearance-none cursor-pointer accent-brand-500">
                        </div>
                    </div>

                </div>

                <div x-show="activeMenu === 'logo'" x-transition class="space-y-6">
                    <div @click="triggerLogoUpload()" class="group border-2 border-dashed border-brand-800/40 rounded-3xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:border-brand-500/50 hover:bg-brand-900/5 transition-all bg-brand-950/20">
                        <div class="w-16 h-16 rounded-2xl bg-brand-900/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <span class="text-xs font-bold text-brand-50 uppercase tracking-[0.2em]">Upload Logo / Foto</span>
                        <p class="text-[10px] text-brand-400/60 mt-2 uppercase max-w-[200px] leading-relaxed">Format JPG/PNG dengan latar belakang transparan disarankan.</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 bg-white relative flex flex-col items-center justify-center p-4 min-h-[400px] order-1 md:order-3 mb-16 md:mb-0">
            <div class="absolute top-6 left-1/2 -translate-x-1/2 z-40 flex items-center gap-3">
                <div class="bg-white/80 backdrop-blur-xl p-1.5 rounded-2xl border border-slate-200 shadow-xl flex gap-1">
                    <template x-for="v in [
                        {id: 'front', label: 'Depan'},
                        {id: 'back', label: 'Belakang'},
                        {id: 'pants', label: 'Celana'}
                    ]" :key="v.id">
                        <button 
                            @click="setView(v.id)"
                            :class="currentView === v.id ? 'bg-brand-900 text-white shadow-lg' : 'text-brand-300 hover:bg-brand-900/20'"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all uppercase tracking-widest"
                            x-text="v.label"
                        ></button>
                    </template>
                </div>

                <button 
                    x-show="currentView === 'back' || currentView === 'pants'" x-cloak
                    @click="confirmCopyFromFront()"
                    class="bg-brand-900 text-white p-2.5 rounded-2xl text-[10px] font-bold shadow-xl border border-brand-700/30 hover:bg-brand-800 transition-all flex items-center gap-2 h-full uppercase tracking-widest"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    <span class="hidden md:inline">Salin Depan</span>
                </button>
            </div>

            <div class="absolute top-6 left-6 z-40 flex items-center gap-2">
                <button @click="handleBack()" class="bg-white/80 backdrop-blur-xl text-slate-700 p-3 rounded-2xl border border-slate-200 hover:bg-slate-100 transition-all shadow-xl group" title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <a href="{{ url('/') }}" class="bg-white/80 backdrop-blur-xl text-slate-700 p-3 rounded-2xl border border-slate-200 hover:bg-slate-100 transition-all shadow-xl group" title="Ke Beranda">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </a>
            </div>
            
            <div class="absolute bottom-20 md:bottom-8 right-8 z-40 flex flex-col gap-3 items-end">
                <button @click="triggerSave()" class="bg-brand-900 hover:bg-brand-800 text-white w-14 h-14 rounded-2xl shadow-2xl flex items-center justify-center transition-all hover:scale-110 active:scale-95 border border-white/10 group" title="Simpan Desain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-12 transition-transform"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                </button>

                <div class="flex flex-col gap-2 bg-white/80 p-2 rounded-2xl backdrop-blur-md border border-slate-200 shadow-xl">
                    <button @click="undo()" :disabled="undoStack.length <= 1" class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center hover:bg-slate-200 disabled:opacity-30 transition-all border border-slate-200" title="Undo (Ctrl+Z)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                    </button>
                    <button @click="redo()" :disabled="redoStack.length === 0" class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center hover:bg-slate-200 disabled:opacity-30 transition-all border border-slate-200" title="Redo (Ctrl+Shift+Z)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3L21 13"/></svg>
                    </button>
                    <div class="h-[1px] bg-slate-200 mx-1"></div>
                    <button @click="zoom(0.2)" class="w-10 h-10 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors text-lg font-bold" title="Zoom In">+</button>
                    <button @click="zoom(-0.2)" class="w-10 h-10 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors text-xl font-bold" title="Zoom Out">-</button>
                    <button @click="resetZoom()" class="w-10 h-10 text-slate-600 hover:bg-slate-100 rounded-xl transition-all flex items-center justify-center group" title="Reset Zoom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-180 transition-transform duration-500"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    </button>
                </div>
            </div>

            <div id="canvas-container" class="relative bg-white rounded-[2rem] md:rounded-[3rem] p-4 md:p-10 border border-slate-200 shadow-2xl w-full max-w-[600px] aspect-square flex items-center justify-center overflow-hidden">
                <div class="w-full h-full flex items-center justify-center">
                    <canvas id="jersey-canvas" width="600" height="600" class="pointer-events-auto"></canvas>
                </div>

                <div 
                    x-show="showToolbar" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute z-[60] bg-brand-950/90 backdrop-blur-2xl border border-brand-700/50 rounded-2xl flex items-center gap-1 p-2 floating-toolbar-shadow pointer-events-auto"
                    :style="`top: ${toolbarPos.top}px; left: ${toolbarPos.left}px; transform: translateX(-50%);`"
                    @mousedown.stop
                >
                    <button @click="duplicateSelected()" class="p-2.5 text-brand-300 hover:text-white hover:bg-brand-800 rounded-xl transition-all group relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                        <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-brand-900 text-[9px] px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold uppercase tracking-widest border border-brand-700 shadow-xl">Duplikat</span>
                    </button>
                    
                    <div class="w-[1px] h-6 bg-brand-800/50 mx-1"></div>
                    
                    <button @click="flipObject('X')" class="p-2.5 text-brand-300 hover:text-white hover:bg-brand-800 rounded-xl transition-colors group relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="m15 15 3-3-3-3"/><path d="m9 9-3 3 3 3"/></svg>
                        <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-brand-900 text-[9px] px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold uppercase tracking-widest border border-brand-700 shadow-xl">Flip H</span>
                    </button>

                    <button @click="flipObject('Y')" class="p-2.5 text-brand-300 hover:text-white hover:bg-brand-800 rounded-xl transition-colors group relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18"/><path d="m9 15 3 3 3-3"/><path d="m15 9-3-3-3 3"/></svg>
                        <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-brand-900 text-[9px] px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold uppercase tracking-widest border border-brand-700 shadow-xl">Flip V</span>
                    </button>

                    <div class="w-[1px] h-6 bg-brand-800/50 mx-1"></div>
                    
                    <div x-show="activeObjectType === 'image' || activeObjectElement" class="contents">
                        <button @click="openCropper()" class="p-2.5 text-brand-300 hover:text-white hover:bg-brand-800 rounded-xl transition-all group relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 10V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2h-5"/><polyline points="14 3 14 8 20 8"/><path d="M3 14.28a5 5 0 0 1 5.42-3.72c2.47.3 4.58 2.21 4.58 4.72 0 2.5-1.5 4-3.5 4.5"/><path d="M3 14.28C3 17 5 19 8 19s5-2 5-4.72c0-2.5-2.1-4.42-4.58-4.72a5 5 0 0 0-5.42 3.72z"/></svg>
                            <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-brand-900 text-[9px] px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold uppercase tracking-widest border border-brand-700 shadow-xl">Pangkas</span>
                        </button>
                        <div class="w-[1px] h-6 bg-brand-800/50 mx-1"></div>
                    </div>

                    <button @click="deleteSelected()" class="p-2.5 text-rose-400 hover:text-white hover:bg-rose-600 rounded-xl transition-all group relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-rose-600 text-[9px] px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap font-bold uppercase tracking-widest shadow-xl">Hapus</span>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <div x-show="showCopyConfirmModal" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xl" x-transition>
        <div class="bg-brand-950 border border-brand-800/50 rounded-3xl w-full max-w-sm overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.8)]">
            <div class="p-6 border-b border-brand-800/30 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-900/40 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-400"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-brand-50 uppercase tracking-widest text-sm">Salin Desain</h3>
                    <p class="text-[10px] text-brand-400 mt-0.5 font-bold tracking-widest uppercase">Mirroring Depan</p>
                </div>
            </div>
            <div class="p-6">
                <p class="text-sm text-brand-100/80 leading-relaxed font-medium">Salin setelan <span class="text-brand-300 font-bold underline decoration-brand-500 underline-offset-4">Warna dan Motif</span> dari bagian Depan ke tampilan ini?</p>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button @click="showCopyConfirmModal = false" class="flex-1 py-3 rounded-2xl border border-brand-800 text-brand-400 hover:bg-brand-900/10 transition-all text-xs font-bold uppercase tracking-widest">Batal</button>
                <button @click="copyDesignFromFront()" class="flex-1 py-3 rounded-2xl bg-brand-900 hover:bg-brand-800 text-white shadow-xl transition-all text-xs font-bold uppercase tracking-widest">Ya, Salin</button>
            </div>
        </div>
    </div>

    <div x-show="showCropper" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/90 backdrop-blur-xl">
        <div class="bg-brand-950 border border-brand-800/50 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
            <div class="p-4 border-b border-brand-800/30 flex justify-between items-center">
                <h3 class="font-bold text-brand-50 uppercase tracking-widest text-sm">Pangkas Gambar</h3>
                <button @click="cancelCrop()" class="p-2 text-brand-400 hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
            </div>
            <div class="flex-1 bg-[#121212] relative overflow-hidden flex items-center justify-center min-h-[300px]">
                <img id="cropper-image" class="max-w-full max-h-full block">
            </div>
            <div class="p-6 border-t border-brand-800/30 flex justify-end gap-3 bg-brand-950/40">
                <button @click="cancelCrop()" class="px-6 py-2.5 rounded-2xl border border-brand-800 text-brand-400 hover:bg-brand-900/10 transition-all font-bold text-xs uppercase tracking-widest">Batal</button>
                <button @click="applyCrop()" class="px-8 py-2.5 rounded-2xl bg-brand-900 text-white hover:bg-brand-800 shadow-xl transition-all font-bold text-xs uppercase tracking-widest">Terapkan Pangkasan</button>
            </div>
        </div>
    </div>

    <!-- Modal Simpan Desain -->
    <div x-show="showSaveModal" x-cloak class="fixed inset-0 z-[130] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xl" x-transition>
        <div class="bg-brand-950 border border-brand-800/50 rounded-3xl w-full max-w-md overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.8)]">
            <div class="p-6 border-b border-brand-800/30 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-900/40 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-400"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-brand-50 uppercase tracking-widest text-sm">Simpan Desain</h3>
                    <p class="text-[10px] text-brand-400 mt-0.5 font-bold tracking-widest uppercase">Koleksi Desain Saya</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-brand-400 uppercase tracking-widest mb-2">Nama Desain</label>
                    <input type="text" x-model="designName" class="w-full bg-brand-900/30 border border-brand-800 text-white rounded-2xl px-4 py-3 focus:border-brand-500 focus:outline-none transition-all" placeholder="Contoh: Jersey Home 2024">
                </div>
                <p class="text-[11px] text-brand-400/60 leading-relaxed uppercase font-bold tracking-widest">Desain Anda akan disimpan ke dalam daftar "Desain Saya" dan dapat diedit kembali kapan saja.</p>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button @click="showSaveModal = false" class="flex-1 py-3 rounded-2xl border border-brand-800 text-brand-400 hover:bg-brand-900/10 transition-all text-xs font-bold uppercase tracking-widest">Batal</button>
                <button @click="saveDesign()" :disabled="!designName" class="flex-1 py-3 rounded-2xl bg-brand-900 hover:bg-brand-800 text-white shadow-xl transition-all text-xs font-bold uppercase tracking-widest disabled:opacity-50">Simpan Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Kembali -->
    <div x-show="showBackModal" x-cloak class="fixed inset-0 z-[130] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xl" x-transition>
        <div class="bg-brand-950 border border-brand-800/50 rounded-3xl w-full max-w-md overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.8)]">
            <div class="p-6 border-b border-brand-800/30 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-900/40 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-brand-400"><path d="m15 18-6-6 6-6"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-brand-50 uppercase tracking-widest text-sm">Keluar Customizer</h3>
                    <p class="text-[10px] text-brand-400 mt-0.5 font-bold tracking-widest uppercase">Konfirmasi Penyimpanan</p>
                </div>
            </div>
            <div class="p-6">
                <p class="text-sm text-brand-100/80 leading-relaxed font-medium">Apakah Anda ingin menyimpan perubahan desain sebelum keluar?</p>
            </div>
            <div class="px-6 pb-6 flex flex-col gap-2">
                <button @click="triggerSave()" class="w-full py-3 rounded-2xl bg-brand-900 hover:bg-brand-800 text-white shadow-xl transition-all text-xs font-bold uppercase tracking-widest">Ya, Simpan Desain</button>
                <div class="flex gap-2">
                    <button @click="window.history.back()" class="flex-1 py-3 rounded-2xl border border-rose-900/30 text-rose-400 hover:bg-rose-950/30 transition-all text-xs font-bold uppercase tracking-widest">Tidak, Buang Perubahan</button>
                    <button @click="showBackModal = false" class="flex-1 py-3 rounded-2xl border border-brand-800 text-brand-400 hover:bg-brand-900/10 transition-all text-xs font-bold uppercase tracking-widest">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden input for CSRF and Routes -->
    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
    <input type="hidden" id="save-design-url" value="{{ route('customer.designs.store') }}">
    @if(isset($design))
        <input type="hidden" id="update-design-url" value="{{ route('customer.designs.update', $design) }}">
        <input type="hidden" id="existing-design-data" value="{{ json_encode($design->design_json) }}">
        <input type="hidden" id="existing-design-name" value="{{ $design->name }}">
    @endif

</div>
@endsection