export default () => ({
    activeMenu: 'mockup',
    currentModel: 'v-neck-short',
    currentView: 'front', // 'front', 'back', 'pants'

    availableMockups: [
        {
            id: 'v-neck-short',
            label: 'V-Neck Short Sleeve',
            parts: {
                front: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                back: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                pants: ['left', 'right', 'belt', 'list1', 'list2']
            }
        },
        {
            id: 'v-neck combination-short',
            label: 'V-Neck Combination',
            parts: {
                front: ['body', 'collar', 'collar1', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                back: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                pants: ['left', 'right', 'belt', 'list1', 'list2']
            }
        },
        {
            id: 'o-neck-short',
            label: 'O-Neck Short Sleeve',
            parts: {
                front: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                back: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                pants: ['left', 'right', 'belt', 'list1', 'list2']
            }
        },

        {
            id: 'v-neck-long',
            label: 'V-Neck Long Sleeve',
            parts: {
                front: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                back: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                pants: ['left', 'right', 'belt', 'list1', 'list2']
            }
        },
        {
            id: 'v-neck combination-long',
            label: 'V-Neck Combination Long Sleeve',
            parts: {
                front: ['body', 'collar', 'collar1', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                back: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                pants: ['left', 'right', 'belt', 'list1', 'list2']
            }
        },

        {
            id: 'o-neck-long',
            label: 'O-Neck Long Sleeve',
            parts: {
                front: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                back: ['body', 'collar', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff'],
                pants: ['left', 'right', 'belt', 'list1', 'list2']
            }
        },


    ],

    // Labels for all possible parts
    partLabels: {
        body: 'Badan',
        left_sleeve: 'Lengan Kiri',
        right_sleeve: 'Lengan Kanan',
        left_cuff: 'Cuff Kiri',
        right_cuff: 'Cuff Kanan',
        collar: 'Kerah',
        collar1: 'Kerah 1',
        left: 'Celana Kiri',
        right: 'Celana Kanan',
        belt: 'Pinggang/Sabuk',
        list1: 'List 1',
        list2: 'List 2'
    },

    // VIEW STATES (Independent for Front, Back, Pants)
    viewStates: {
        front: {
            partColors: { body: '#ffffff', left_sleeve: '#ffffff', right_sleeve: '#ffffff', left_cuff: '#ffffff', right_cuff: '#ffffff', collar: '#ffffff', collar1: '#ffffff' },
            partColorMode: { body: 'solid', left_sleeve: 'solid', right_sleeve: 'solid', left_cuff: 'solid', right_cuff: 'solid', collar: 'solid', collar1: 'solid' },
            partActiveGradientPreset: { body: null, left_sleeve: null, right_sleeve: null, left_cuff: null, right_cuff: null, collar: null, collar1: null },
            partPatterns: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partPatternScales: { body: 0.3, left_sleeve: 0.3, right_sleeve: 0.3, collar: 0.3, collar1: 0.3, left_cuff: 0.3, right_cuff: 0.3 },
            partPatternAngles: { body: 0, left_sleeve: 0, right_sleeve: 0, collar: 0, collar1: 0, left_cuff: 0, right_cuff: 0 },
            partPatternFlipX: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partPatternFlipY: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partActivePatterns: { body: null, left_sleeve: null, right_sleeve: null, collar: null, collar1: null, left_cuff: null, right_cuff: null },
            partPatternColors: { body: '#000000', left_sleeve: '#000000', right_sleeve: '#000000', collar: '#000000', collar1: '#000000', left_cuff: '#000000', right_cuff: '#000000' },
            partPatternX: { body: 300, left_sleeve: 300, right_sleeve: 300, collar: 300, collar1: 300, left_cuff: 300, right_cuff: 300 },
            partPatternY: { body: 300, left_sleeve: 300, right_sleeve: 300, collar: 300, collar1: 300, left_cuff: 300, right_cuff: 300 },
            partGradientEnabled: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partGradientColor1: { body: '#06402B', left_sleeve: '#06402B', right_sleeve: '#06402B', collar: '#06402B', collar1: '#06402B', left_cuff: '#06402B', right_cuff: '#06402B' },
            partGradientColor2: { body: '#0f523a', left_sleeve: '#0f523a', right_sleeve: '#0f523a', collar: '#0f523a', collar1: '#0f523a', left_cuff: '#0f523a', right_cuff: '#0f523a' },
            partGradientAngle: { body: 90, left_sleeve: 90, right_sleeve: 90, collar: 90, collar1: 90, left_cuff: 90, right_cuff: 90 },
            partGradientType: { body: 'linear', left_sleeve: 'linear', right_sleeve: 'linear', collar: 'linear', collar1: 'linear', left_cuff: 'linear', right_cuff: 'linear' },
            partPatternGradientEnabled: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partPatternGradientColor1: { body: '#10b981', left_sleeve: '#10b981', right_sleeve: '#10b981', collar: '#10b981', collar1: '#10b981', left_cuff: '#10b981', right_cuff: '#10b981' },
            partPatternGradientColor2: { body: '#f59e0b', left_sleeve: '#f59e0b', right_sleeve: '#f59e0b', collar: '#f59e0b', collar1: '#f59e0b', left_cuff: '#f59e0b', right_cuff: '#f59e0b' },
            partPatternGradientAngle: { body: 90, left_sleeve: 90, right_sleeve: 90, collar: 90, collar1: 90, left_cuff: 90, right_cuff: 90 },
            partPatternGradientType: { body: 'linear', left_sleeve: 'linear', right_sleeve: 'linear', collar: 'linear', collar1: 'linear', left_cuff: 'linear', right_cuff: 'linear' }
        },
        back: {
            partColors: { body: '#ffffff', left_sleeve: '#ffffff', right_sleeve: '#ffffff', left_cuff: '#ffffff', right_cuff: '#ffffff', collar: '#ffffff', collar1: '#ffffff' },
            partColorMode: { body: 'solid', left_sleeve: 'solid', right_sleeve: 'solid', left_cuff: 'solid', right_cuff: 'solid', collar: 'solid', collar1: 'solid' },
            partActiveGradientPreset: { body: null, left_sleeve: null, right_sleeve: null, left_cuff: null, right_cuff: null, collar: null, collar1: null },
            partPatterns: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partPatternScales: { body: 0.3, left_sleeve: 0.3, right_sleeve: 0.3, collar: 0.3, collar1: 0.3, left_cuff: 0.3, right_cuff: 0.3 },
            partPatternAngles: { body: 0, left_sleeve: 0, right_sleeve: 0, collar: 0, collar1: 0, left_cuff: 0, right_cuff: 0 },
            partPatternFlipX: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partPatternFlipY: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partActivePatterns: { body: null, left_sleeve: null, right_sleeve: null, collar: null, collar1: null, left_cuff: null, right_cuff: null },
            partPatternColors: { body: '#000000', left_sleeve: '#000000', right_sleeve: '#000000', collar: '#000000', collar1: '#000000', left_cuff: '#000000', right_cuff: '#000000' },
            partPatternX: { body: 300, left_sleeve: 300, right_sleeve: 300, collar: 300, collar1: 300, left_cuff: 300, right_cuff: 300 },
            partPatternY: { body: 300, left_sleeve: 300, right_sleeve: 300, collar: 300, collar1: 300, left_cuff: 300, right_cuff: 300 },
            partGradientEnabled: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partGradientColor1: { body: '#06402B', left_sleeve: '#06402B', right_sleeve: '#06402B', collar: '#06402B', collar1: '#06402B', left_cuff: '#06402B', right_cuff: '#06402B' },
            partGradientColor2: { body: '#0f523a', left_sleeve: '#0f523a', right_sleeve: '#0f523a', collar: '#0f523a', collar1: '#0f523a', left_cuff: '#0f523a', right_cuff: '#0f523a' },
            partGradientAngle: { body: 90, left_sleeve: 90, right_sleeve: 90, collar: 90, collar1: 90, left_cuff: 90, right_cuff: 90 },
            partGradientType: { body: 'linear', left_sleeve: 'linear', right_sleeve: 'linear', collar: 'linear', collar1: 'linear', left_cuff: 'linear', right_cuff: 'linear' },
            partPatternGradientEnabled: { body: false, left_sleeve: false, right_sleeve: false, collar: false, collar1: false, left_cuff: false, right_cuff: false },
            partPatternGradientColor1: { body: '#10b981', left_sleeve: '#10b981', right_sleeve: '#10b981', collar: '#10b981', collar1: '#10b981', left_cuff: '#10b981', right_cuff: '#10b981' },
            partPatternGradientColor2: { body: '#f59e0b', left_sleeve: '#f59e0b', right_sleeve: '#f59e0b', collar: '#f59e0b', collar1: '#f59e0b', left_cuff: '#f59e0b', right_cuff: '#f59e0b' },
            partPatternGradientAngle: { body: 90, left_sleeve: 90, right_sleeve: 90, collar: 90, collar1: 90, left_cuff: 90, right_cuff: 90 },
            partPatternGradientType: { body: 'linear', left_sleeve: 'linear', right_sleeve: 'linear', collar: 'linear', collar1: 'linear', left_cuff: 'linear', right_cuff: 'linear' }
        },
        pants: {
            partColors: { left: '#ffffff', right: '#ffffff', belt: '#ffffff', list1: '#ffffff', list2: '#ffffff' },
            partColorMode: { left: 'solid', right: 'solid', belt: 'solid', list1: 'solid', list2: 'solid' },
            partActiveGradientPreset: { left: null, right: null, belt: null, list1: null, list2: null },
            partPatterns: { left: false, right: false, belt: false, list1: false, list2: false },
            partPatternScales: { left: 0.3, right: 0.3, belt: 0.3, list1: 0.3, list2: 0.3 },
            partPatternAngles: { left: 0, right: 0, belt: 0, list1: 0, list2: 0 },
            partPatternFlipX: { left: false, right: false, belt: false, list1: false, list2: false },
            partPatternFlipY: { left: false, right: false, belt: false, list1: false, list2: false },
            partActivePatterns: { left: null, right: null, belt: null, list1: null, list2: null },
            partPatternColors: { left: '#000000', right: '#000000', belt: '#000000', list1: '#000000', list2: '#000000' },
            partPatternX: { left: 300, right: 300, belt: 300, list1: 300, list2: 300 },
            partPatternY: { left: 300, right: 300, belt: 300, list1: 300, list2: 300 },
            partGradientEnabled: { left: false, right: false, belt: false, list1: false, list2: false },
            partGradientColor1: { left: '#06402B', right: '#06402B', belt: '#06402B', list1: '#06402B', list2: '#06402B' },
            partGradientColor2: { left: '#0f523a', right: '#0f523a', belt: '#0f523a', list1: '#0f523a', list2: '#0f523a' },
            partGradientAngle: { left: 90, right: 90, belt: 90, list1: 90, list2: 90 },
            partGradientType: { left: 'linear', right: 'linear', belt: 'linear', list1: 'linear', list2: 'linear' },
            partPatternGradientEnabled: { left: false, right: false, belt: false, list1: false, list2: false },
            partPatternGradientColor1: { left: '#10b981', right: '#10b981', belt: '#10b981', list1: '#10b981', list2: '#10b981' },
            partPatternGradientColor2: { left: '#f59e0b', right: '#f59e0b', belt: '#f59e0b', list1: '#f59e0b', list2: '#f59e0b' },
            partPatternGradientAngle: { left: 90, right: 90, belt: 90, list1: 90, list2: 90 },
            partPatternGradientType: { left: 'linear', right: 'linear', belt: 'linear', list1: 'linear', list2: 'linear' }
        }
    },

    // Getters for active view state
    get activeState() { return this.viewStates[this.currentView]; },
    get partColors() { return this.activeState.partColors; },
    get partColorMode() { return this.activeState.partColorMode || {}; },
    get partActiveGradientPreset() { return this.activeState.partActiveGradientPreset || {}; },
    get partPatterns() { return this.activeState.partPatterns; },
    get partPatternScales() { return this.activeState.partPatternScales; },
    get partPatternAngles() { return this.activeState.partPatternAngles; },
    get partPatternFlipX() { return this.activeState.partPatternFlipX; },
    get partPatternFlipY() { return this.activeState.partPatternFlipY; },
    get partActivePatterns() { return this.activeState.partActivePatterns; },
    get partPatternColors() { return this.activeState.partPatternColors; },
    get partPatternX() { return this.activeState.partPatternX; },
    get partPatternY() { return this.activeState.partPatternY; },
    get patternColor() { return this.partPatternColors[this.activePatternPart] || '#000000'; },
    get partPatternGradientEnabled() { return this.activeState.partPatternGradientEnabled || {}; },
    get partPatternGradientColor1() { return this.activeState.partPatternGradientColor1 || {}; },
    get partPatternGradientColor2() { return this.activeState.partPatternGradientColor2 || {}; },
    get partPatternGradientAngle() { return this.activeState.partPatternGradientAngle || {}; },
    get partPatternGradientType() { return this.activeState.partPatternGradientType || {}; },

    // Dynamic parts based on LOADED layers
    get parts() {
        const availableParts = [];
        const potentialParts = this.currentView === 'pants'
            ? ['left', 'right', 'belt', 'list1', 'list2']
            : ['body', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff', 'collar', 'collar1'];

        potentialParts.forEach(pid => {
            if (this.layers[pid]) {
                availableParts.push({ id: pid, label: this.partLabels[pid] || pid });
            }
        });
        return availableParts;
    },

    activePart: 'body',
    activePatternPart: 'body',

    availableFonts: [
        { id: 'sans-serif', name: 'Standard' },
        { id: 'AC Milan 4th', name: 'AC Milan' },
        { id: 'Brøndby IF', name: 'Brøndby' },
        { id: 'Girondins Bordeaux', name: 'Bordeaux' },
        { id: 'Iraq 2025', name: 'Iraq' },
        { id: 'Osasuna 25-26', name: 'Osasuna' },
        { id: 'PSG Fourth', name: 'PSG' },
        { id: 'Palermo FC', name: 'Palermo' },
        { id: 'Portugal WC 2026', name: 'Portugal' },
        { id: 'SC Freiburg', name: 'Freiburg' },
        { id: 'South Africa', name: 'South Africa' },
        { id: 'Spain WC 2026', name: 'Spain' }
    ],

    // UNIVERSAL TEXT STATE
    textInput: '',
    activeFont: 'sans-serif',
    activeColor: '#000000ff',
    textFontSize: 40,
    textCharSpacing: 0,
    textArc: 0,
    textGradientEnabled: false,
    textGradientColor1: '#06402B',
    textGradientColor2: '#0f523a',
    textGradientAngle: 90,
    textGradientType: 'linear',

    isLoading: true,
    isPanelOpen: true,
    showCropper: false,
    cropper: null,
    currentZoom: 1,
    showToolbar: false,
    toolbarPos: { top: 0, left: 0 },
    applyToAll: false,
    showAllSolidColors: false,
    showAllGradients: false,
    showAllPatternColors: false,
    showAllPatternGradients: false,
    showAllTextColors: false,
    showAllTextGradients: false,
    patternActiveGradientPreset: null,
    textActiveGradientPreset: null,
    showCopyConfirmModal: false,
    activeObjectType: '',
    activeObjectElement: false,
    undoStack: [],
    redoStack: [],
    isHistoryAction: false,

    menus: [
        { id: 'mockup', label: 'Mockup', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 12 12 17 22 12"/><polyline points="2 17 12 22 22 17"/></svg>' },
        { id: 'color', label: 'Warna', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>' },
        { id: 'pattern', label: 'Motif', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>' },
        { id: 'text', label: 'Teks', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" x2="15" y1="20" y2="20"/><line x1="12" x2="12" y1="4" y2="20"/></svg>' },
        { id: 'logo', label: 'Logo', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>' }
    ],

    colors: ['#ffffff', '#000000', '#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#475569', '#1e293b'],

    // Extended solid color palette (like reference image)
    solidColors: [
        // Row 1: Blacks & Grays
        '#000000', '#333333', '#555555', '#888888', '#bbbbbb', '#dddddd', '#ffffff',
        // Row 2: Reds & Pinks
        '#cc0000', '#ff3333', '#ff6666', '#ff99aa', '#ff66cc', '#cc33aa', '#990066',
        // Row 3: Blues
        '#003399', '#0055cc', '#0088ff', '#33aaff', '#66ccff', '#99ddff', '#ccf0ff',
        // Row 4: Greens
        '#004400', '#006600', '#00aa00', '#33cc33', '#66ee66', '#99ff99', '#ccffcc',
        // Row 5: Yellows & Oranges
        '#cc8800', '#ffaa00', '#ffcc00', '#ffee00', '#ff6600', '#ff3300', '#cc1100',
        // Row 6: Purples & Teals
        '#330066', '#6600cc', '#9933ff', '#cc66ff', '#008080', '#00aaaa', '#33cccc'
    ],

    // Gradient presets (like reference image - each is color1 → color2)
    gradientPresets: [
        // Row 1: Monochrome
        { id: 'gp1', color1: '#000000', color2: '#888888', angle: 135, type: 'linear' },
        { id: 'gp2', color1: '#888888', color2: '#ffffff', angle: 135, type: 'linear' },
        { id: 'gp3', color1: '#ffffff', color2: '#aaaaaa', angle: 90, type: 'linear' },
        { id: 'gp4', color1: '#4a4a00', color2: '#c8b882', angle: 135, type: 'linear' },
        { id: 'gp5', color1: '#6b3fa0', color2: '#c8a060', angle: 135, type: 'linear' },
        { id: 'gp6', color1: '#000033', color2: '#003366', angle: 135, type: 'linear' },
        // Row 2: Colorful
        { id: 'gp7', color1: '#66ccff', color2: '#ff66cc', angle: 135, type: 'linear' },
        { id: 'gp8', color1: '#ff2244', color2: '#8800ff', angle: 135, type: 'linear' },
        { id: 'gp9', color1: '#6600cc', color2: '#0044ff', angle: 135, type: 'linear' },
        { id: 'gp10', color1: '#ff8800', color2: '#ffee00', angle: 90, type: 'linear' },
        { id: 'gp11', color1: '#00cc66', color2: '#00ccff', angle: 135, type: 'linear' },
        { id: 'gp12', color1: '#003399', color2: '#00ccff', angle: 135, type: 'linear' },
        // Row 3: Vibrant
        { id: 'gp13', color1: '#00aa44', color2: '#aaff00', angle: 90, type: 'linear' },
        { id: 'gp14', color1: '#008888', color2: '#00ff88', angle: 135, type: 'linear' },
        { id: 'gp15', color1: '#aacc00', color2: '#00cc88', angle: 90, type: 'linear' },
        { id: 'gp16', color1: '#ffcc00', color2: '#ff6600', angle: 90, type: 'linear' },
        { id: 'gp17', color1: '#ff66aa', color2: '#aa00ff', angle: 135, type: 'linear' },
        { id: 'gp18', color1: '#cccccc', color2: '#ffffff', angle: 45, type: 'radial' }
    ],
    patterns: [
        { id: 'p1', name: 'Motif 1', url: '/assets/patterns/pattern1.png' },
        { id: 'p2', name: 'Motif 2', url: '/assets/patterns/pattern2.png' },
        { id: 'p3', name: 'Motif 3', url: '/assets/patterns/pattern3.png' },
        { id: 'p4', name: 'Motif 4', url: '/assets/patterns/pattern4.png' },
        { id: 'p5', name: 'Motif 5', url: '/assets/patterns/pattern5.png' },
        { id: 'p6', name: 'Motif 6', url: '/assets/patterns/pattern6.png' },
        { id: 'p7', name: 'Motif 7', url: '/assets/patterns/pattern7.png' },
        { id: 'p8', name: 'Motif 8', url: '/assets/patterns/pattern8.png' },
        { id: 'p9', name: 'Motif 9', url: '/assets/patterns/pattern9.png' },
        { id: 'p10', name: 'Motif 10', url: '/assets/patterns/pattern10.png' },
        { id: 'p11', name: 'Motif 11', url: '/assets/patterns/pattern11.png' },
        { id: 'p12', name: 'Motif 12', url: '/assets/patterns/pattern12.png' },
        { id: 'p13', name: 'Motif 13', url: '/assets/patterns/pattern13.png' },
        { id: 'p14', name: 'Motif 14', url: '/assets/patterns/pattern14.png' },
        { id: 'p15', name: 'Motif 15', url: '/assets/patterns/pattern15.png' },
        { id: 'p16', name: 'Motif 16', url: '/assets/patterns/pattern16.png' },
        { id: 'p17', name: 'Motif 17', url: '/assets/patterns/pattern17.png' },

    ],
    layers: { shadows: null, highlights: null },
    designObjects: [],
    canvas: null,

    get activeMenuLabel() { return this.menus.find(m => m.id === this.activeMenu)?.label || ''; },

    init() {
        this.$nextTick(() => {
            this.initCanvas().catch(err => {
                console.error('Initialization failed:', err);
                this.isLoading = false;
            });
            window.addEventListener('resize', () => this.resizeCanvas());
            window.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'z') { e.preventDefault(); if (e.shiftKey) this.redo(); else this.undo(); }
                if ((e.key === 'Delete' || e.key === 'Backspace') && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) { this.deleteSelected(); }
            });
        });
    },

    async initCanvas() {
        try {
            if (!window.fabric) throw new Error('Fabric.js not found!');
            this.canvas = new window.fabric.Canvas('jersey-canvas', {
                preserveObjectStacking: true, backgroundColor: 'transparent',
                width: 600, height: 600, targetFindTolerance: 10, perPixelTargetFind: true
            });

            this.resizeCanvas();
            await this.loadInitialLayers();
            this.saveHistory();
            this.setupZoomEvents();
            this.setupToolbarEvents();
            this.setupHistoryEvents();
            this.isLoading = false;
        } catch (error) { console.error('Error in initCanvas:', error); this.isLoading = false; }
    },

    setupHistoryEvents() {
        this.canvas.on('object:modified', () => { if (this.isHistoryAction) return; this.saveHistory(); });
        this.canvas.on('object:added', (e) => {
            if (this.isHistoryAction || e.target.isSystemLayer) return;
            this.saveHistory();
        });
        this.canvas.on('object:removed', (e) => {
            if (this.isHistoryAction || e.target.isSystemLayer) return;
            this.saveHistory();
        });
    },

    saveHistory() {
        if (this.isHistoryAction) return;
        const state = {
            viewStates: JSON.parse(JSON.stringify(this.viewStates)),
            textState: { input: this.textInput, font: this.activeFont, color: this.activeColor, size: this.textFontSize, spacing: this.textCharSpacing, arc: this.textArc },
            designObjects: this.designObjects.map(obj => obj.toObject(['clipPath', 'isSystemLayer', 'view', 'arc'])),
            currentModel: this.currentModel,
            currentView: this.currentView
        };
        this.undoStack.push(JSON.stringify(state));
        if (this.undoStack.length > 25) this.undoStack.shift();
        this.redoStack = [];
    },

    async undo() {
        if (this.undoStack.length <= 1) return;
        const currentStateStr = this.undoStack.pop();
        this.redoStack.push(currentStateStr);
        const prevState = JSON.parse(this.undoStack[this.undoStack.length - 1]);
        await this.applyState(prevState);
    },

    async redo() {
        if (this.redoStack.length === 0) return;
        const nextStateStr = this.redoStack.pop();
        // Save the remaining redo stack before applyState can clear it
        const savedRedoStack = [...this.redoStack];
        this.undoStack.push(nextStateStr);
        await this.applyState(JSON.parse(nextStateStr));
        // Restore redo stack (applyState -> loadInitialLayers/updatePattern -> saveHistory clears it)
        this.redoStack = savedRedoStack;
    },

    async applyState(state) {
        this.isHistoryAction = true;
        this.isLoading = true;
        this.viewStates = state.viewStates;
        this.textInput = state.textState.input;
        this.activeFont = state.textState.font;
        this.activeColor = state.textState.color;
        this.textFontSize = state.textState.size;
        this.textCharSpacing = state.textState.spacing;
        this.textArc = state.textState.arc;
        this.currentModel = state.currentModel;
        this.currentView = state.currentView;

        const fabric = window.fabric;
        const objects = await fabric.util.enlivenObjects(state.designObjects);
        this.designObjects = objects;

        await this.loadInitialLayers();
        await this.updatePattern();
        this.renderLayers();
        this.isHistoryAction = false;
        this.isLoading = false;
    },

    setupToolbarEvents() {
        this.canvas.on({
            'selection:created': (e) => this.syncUI(e),
            'selection:updated': (e) => this.syncUI(e),
            'selection:cleared': () => { this.showToolbar = false; },
            'object:moving': (e) => this.updateToolbar(e),
            'object:scaling': (e) => this.updateToolbar(e),
            'object:rotating': (e) => this.updateToolbar(e)
        });
    },

    syncUI(e) {
        this.updateToolbar(e);
        const obj = e.selected[0];
        if (obj && (obj.type === 'text' || obj.type === 'i-text')) {
            this.activeMenu = 'text';
            this.isPanelOpen = true;
            this.textInput = obj.text;
            this.activeFont = obj.fontFamily;
            this.activeColor = obj.fill;
            this.textFontSize = obj.fontSize;
            this.textCharSpacing = obj.charSpacing;
            this.textArc = obj.arc || 0;
            this.resizeCanvas();
        }
    },

    async setModel(modelId) {
        if (this.currentModel === modelId) return;
        this.isLoading = true; this.currentModel = modelId;
        const wasHistoryAction = this.isHistoryAction;
        this.isHistoryAction = true;
        await this.loadInitialLayers(); await this.updatePattern(); this.renderLayers();
        this.isHistoryAction = wasHistoryAction;
        this.isLoading = false; this.saveHistory();
    },

    async setView(view) {
        if (this.currentView === view) return;
        this.isLoading = true; this.currentView = view;

        // Dynamic active part selection based on what exists in the new view
        const mockup = this.availableMockups.find(m => m.id === this.currentModel);
        const viewParts = mockup?.parts[view] || [];

        if (view === 'pants') {
            this.activePart = viewParts.includes('left') ? 'left' : viewParts[0];
        } else {
            this.activePart = viewParts.includes('body') ? 'body' : viewParts[0];
        }
        this.activePatternPart = this.activePart;

        const wasHistoryAction = this.isHistoryAction;
        this.isHistoryAction = true;
        await this.loadInitialLayers(); await this.updatePattern(); this.renderLayers();
        this.isHistoryAction = wasHistoryAction;
        this.isLoading = false;
    },

    async loadInitialLayers() {
        const assetPath = `/assets/mockups/${this.currentModel}/${this.currentView}/`;

        // Clean up previous layers to avoid "ghost" layers from previous mockups
        const oldLayers = Object.keys(this.layers);
        oldLayers.forEach(key => {
            if (this.layers[key] && key !== 'partPatterns') {
                if (this.canvas) this.canvas.remove(this.layers[key]);
                this.layers[key] = null;
            }
        });

        this.layers = { shadows: null, highlights: null, partPatterns: this.layers.partPatterns || {} };

        try {
            const mockup = this.availableMockups.find(m => m.id === this.currentModel);
            const potentialPartIds = mockup?.parts[this.currentView] || (this.currentView === 'pants'
                ? ['left', 'right', 'belt', 'list1', 'list2']
                : ['body', 'left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff', 'collar']);

            // Load actual layers
            for (const pid of potentialPartIds) {
                const layer = await this.loadImage(assetPath + pid + '.png', { isSystemLayer: true, selectable: false, evented: false });
                if (layer) this.layers[pid] = layer;
            }

            // Load shadows & highlights (always expected)
            this.layers.shadows = await this.loadImage(assetPath + 'shadows.png', { isSystemLayer: true, selectable: false, evented: false, globalCompositeOperation: 'multiply' });
            this.layers.highlights = await this.loadImage(assetPath + 'highlights.png', { isSystemLayer: true, selectable: false, evented: false, globalCompositeOperation: 'screen' });

            // Re-apply colors to new layers
            Object.keys(this.layers).forEach(pid => {
                if (this.partColors.hasOwnProperty(pid) && this.layers[pid]) {
                    this.applyFilterToLayer(pid, this.partColors[pid]);
                }
            });

            // Rebuild gradient layers for parts that have gradient enabled
            if (!this.layers.partGradients) this.layers.partGradients = {};
            for (const pid of Object.keys(this.activeState.partGradientEnabled || {})) {
                if (this.activeState.partGradientEnabled[pid] && this.layers[pid]) {
                    await this.applyGradientToLayer(pid);
                } else {
                    this.layers.partGradients[pid] = null;
                }
            }

            this.renderLayers();
        } catch (err) { console.error('Error loading layers:', err); }
    },

    async applyFilterToLayer(partId, color) {
        const layer = this.layers[partId];
        if (!layer || !window.fabric) return;

        // Use 'multiply' for better color accuracy on white fabric
        layer.filters = [new window.fabric.filters.BlendColor({
            color: color,
            mode: 'multiply',
            alpha: 1
        })];
        layer.applyFilters();
    },

    async _applySolidToSingle(partId, color) {
        if (!this.partColors || !this.partColors.hasOwnProperty(partId)) return;
        if (this.activeState.partColorMode) this.activeState.partColorMode[partId] = 'solid';
        if (this.activeState.partGradientEnabled) this.activeState.partGradientEnabled[partId] = false;
        if (this.activeState.partActiveGradientPreset) this.activeState.partActiveGradientPreset[partId] = null;
        if (this.layers.partGradients && this.layers.partGradients[partId]) this.layers.partGradients[partId] = null;
        this.partColors[partId] = color;
        await this.applyFilterToLayer(partId, color);
    },

    async updatePartColor(partId, color, saveHistory = true) {
        if (this.applyToAll) {
            // Apply to all visible parts
            for (const p of this.parts) {
                await this._applySolidToSingle(p.id, color);
            }
        } else {
            await this._applySolidToSingle(partId, color);
        }
        this.renderLayers();
        if (saveHistory) this.saveHistory();
    },

    async _applyGradientPresetToSingle(partId, preset) {
        if (!this.activeState.partGradientEnabled) return;
        if (this.activeState.partColorMode) this.activeState.partColorMode[partId] = 'gradient';
        this.activeState.partGradientEnabled[partId] = true;
        this.activeState.partActiveGradientPreset[partId] = preset.id;
        this.activeState.partGradientColor1[partId] = preset.color1;
        this.activeState.partGradientColor2[partId] = preset.color2;
        this.activeState.partGradientAngle[partId] = preset.angle;
        this.activeState.partGradientType[partId] = preset.type;
        await this.applyGradientToLayer(partId);
    },

    async applyGradientPreset(partId, preset) {
        if (this.applyToAll) {
            for (const p of this.parts) {
                await this._applyGradientPresetToSingle(p.id, preset);
            }
        } else {
            await this._applyGradientPresetToSingle(partId, preset);
        }
        this.renderLayers();
        this.saveHistory();
    },

    async updatePatternGradientFromPreset(preset) {
        this.patternActiveGradientPreset = preset.id;
        const pid = this.activePatternPart;

        // Nyalakan mode gradasi dan set warnanya
        this.activeState.partPatternGradientEnabled[pid] = true;
        this.activeState.partPatternGradientColor1[pid] = preset.color1;
        this.activeState.partPatternGradientColor2[pid] = preset.color2;
        this.activeState.partPatternGradientAngle[pid] = preset.angle;
        this.activeState.partPatternGradientType[pid] = preset.type;

        // Langsung terapkan filter tanpa memanggil fungsi toggle
        let hasUpdated = false;
        if (this.layers.partPatterns && this.layers.partPatterns[pid]) {
            const pLayer = this.layers.partPatterns[pid];
            if (pLayer && window.fabric) {
                await this.applyPatternFilters(pid, pLayer);
                hasUpdated = true;
                if (this.canvas) this.canvas.requestRenderAll();
            }
        }

        if (!hasUpdated) await this.updatePattern();
        this.saveHistory();
    },

    applyTextGradientPreset(preset) {
        this.textActiveGradientPreset = preset.id;
        this.updateTextProperty('textGradientEnabled', true);
        this.updateTextProperty('textGradientColor1', preset.color1);
        this.updateTextProperty('textGradientColor2', preset.color2);
        this.updateTextProperty('textGradientAngle', preset.angle);
        this.updateTextProperty('textGradientType', preset.type);
    },

    async updatePartGradient(partId, key, val) {
        if (!this.activeState.partGradientEnabled.hasOwnProperty(partId)) return;
        if (key) { this.activeState[key][partId] = val; }
        else { this.activeState.partGradientEnabled[partId] = !this.activeState.partGradientEnabled[partId]; }

        await this.applyGradientToLayer(partId);
        this.renderLayers();
        if (!this.isHistoryAction) this.saveHistory();
    },

    async applyGradientToLayer(partId) {
        if (!this.layers[partId]) return;
        if (!this.layers.partGradients) this.layers.partGradients = {};

        if (!this.activeState.partGradientEnabled[partId]) {
            this.layers.partGradients[partId] = null;
            return;
        }

        const color1 = this.activeState.partGradientColor1[partId];
        const color2 = this.activeState.partGradientColor2[partId];
        const angle = this.activeState.partGradientAngle[partId];
        const type = this.activeState.partGradientType[partId];

        const maskLayer = await this.layers[partId].clone(['isSystemLayer']);
        const maskGroup = new window.fabric.Group([maskLayer], { absolutePositioned: true, originX: 'center', originY: 'center', left: 300, top: 300 });

        let gradient;
        if (type === 'linear') {
            const rad = angle * (Math.PI / 180);
            const x1 = 300 - Math.cos(rad) * 300;
            const y1 = 300 - Math.sin(rad) * 300;
            const x2 = 300 + Math.cos(rad) * 300;
            const y2 = 300 + Math.sin(rad) * 300;
            gradient = new window.fabric.Gradient({
                type: 'linear',
                coords: { x1, y1, x2, y2 },
                colorStops: [{ offset: 0, color: color1 }, { offset: 1, color: color2 }]
            });
        } else {
            gradient = new window.fabric.Gradient({
                type: 'radial',
                coords: { x1: 300, y1: 300, x2: 300, y2: 300, r1: 0, r2: 450 },
                colorStops: [{ offset: 0, color: color1 }, { offset: 1, color: color2 }]
            });
        }

        const gRect = new window.fabric.Rect({
            width: 600, height: 600, left: 300, top: 300, originX: 'center', originY: 'center',
            fill: gradient, selectable: false, evented: false, isSystemLayer: true, clipPath: maskGroup
        });

        this.layers.partGradients[partId] = gRect;
    },

    async updatePatternGradient(key, val) {
        const pid = this.activePatternPart;
        if (key) {
            this.activeState[key][pid] = val;
        } else {
            // Toggle enabled
            this.activeState.partPatternGradientEnabled[pid] = !this.activeState.partPatternGradientEnabled[pid];
        }

        let hasUpdated = false;
        if (this.layers.partPatterns && this.layers.partPatterns[pid]) {
            const pLayer = this.layers.partPatterns[pid];
            if (pLayer && window.fabric) {
                await this.applyPatternFilters(pid, pLayer);
                hasUpdated = true;
                if (this.canvas) this.canvas.requestRenderAll();
            }
        }

        if (!hasUpdated) await this.updatePattern();
        if (!this.isHistoryAction) this.saveHistory();
    },

    async updatePatternColor(color) {
        const pid = this.activePatternPart;
        this.activeState.partPatternColors[pid] = color;
        this.activeState.partPatternGradientEnabled[pid] = false;

        let hasUpdated = false;
        if (this.layers.partPatterns && this.layers.partPatterns[pid]) {
            const pLayer = this.layers.partPatterns[pid];
            if (pLayer && window.fabric) {
                await this.applyPatternFilters(pid, pLayer);
                hasUpdated = true;
                if (this.canvas) this.canvas.requestRenderAll();
            }
        }

        if (!hasUpdated) await this.updatePattern();
        if (!this.isHistoryAction) this.saveHistory();
    },

    async applyPatternFilters(pid, pLayer) {
        const isGradient = this.activeState.partPatternGradientEnabled[pid];
        const filters = [];

        if (isGradient) {
            const gradImg = await this.generateGradientImage(
                pLayer.width, pLayer.height,
                this.activeState.partPatternGradientColor1[pid] || '#10b981',
                this.activeState.partPatternGradientColor2[pid] || '#f59e0b',
                this.activeState.partPatternGradientAngle[pid] || 90,
                this.activeState.partPatternGradientType[pid] || 'linear'
            );

            // 1. Trik: Ubah motif dasar (hitam) menjadi putih terlebih dahulu
            filters.push(new window.fabric.filters.BlendColor({
                color: '#ffffff',
                mode: 'add',
                alpha: 1
            }));

            // 2. Warnai motif putih tersebut dengan gambar gradasi (mode multiply sangat stabil)
            filters.push(new window.fabric.filters.BlendImage({
                image: gradImg,
                mode: 'multiply',
                alpha: 1
            }));
        } else {
            const color = this.activeState.partPatternColors[pid] || '#000000';
            filters.push(new window.fabric.filters.BlendColor({
                color: color,
                mode: 'add', // Untuk warna solid, mode 'add' sudah cukup
                alpha: 1
            }));
        }

        pLayer.filters = filters;
        pLayer.applyFilters();
    },

    async updatePattern() {
        this.isLoading = true;

        // Clear existing pattern layers from canvas and memory
        if (this.layers.partPatterns) {
            Object.values(this.layers.partPatterns).forEach(p => {
                if (p && this.canvas) this.canvas.remove(p);
            });
        }
        this.layers.partPatterns = {};

        for (const pid of Object.keys(this.partPatterns)) {
            const patternId = this.partActivePatterns[pid];
            const isEnabled = this.partPatterns[pid];
            const patternObj = this.patterns.find(p => p.id === patternId);
            if (!isEnabled || !patternObj || !this.layers[pid]) { this.layers.partPatterns[pid] = null; continue; }

            const maskLayer = await this.layers[pid].clone(['isSystemLayer']);
            const maskGroup = new window.fabric.Group([maskLayer], { absolutePositioned: true, originX: 'center', originY: 'center', left: 300, top: 300 });

            const pLayer = await this.loadImage(patternObj.url, {
                selectable: false, evented: false, isSystemLayer: true, clipPath: maskGroup,
                scaleX: this.partPatternScales[pid] || 1, scaleY: this.partPatternScales[pid] || 1,
                angle: this.partPatternAngles[pid] || 0, flipX: this.partPatternFlipX[pid] || false, flipY: this.partPatternFlipY[pid] || false,
                left: this.activeState.partPatternX[pid] ?? 300, top: this.activeState.partPatternY[pid] ?? 300
            });

            if (pLayer) {
                await this.applyPatternFilters(pid, pLayer);
            }
            this.layers.partPatterns[pid] = pLayer;
        }
        this.renderLayers(); this.isLoading = false; if (!this.isHistoryAction) this.saveHistory();
    },

    flipObject(axis) {
        const obj = this.canvas.getActiveObject();
        if (!obj) return;
        if (axis === 'X') obj.set('flipX', !obj.flipX); else obj.set('flipY', !obj.flipY);
        this.canvas.requestRenderAll(); this.saveHistory();
    },

    updatePatternFlip(axis) {
        const pid = this.activePatternPart;
        if (axis === 'X') this.activeState.partPatternFlipX[pid] = !this.activeState.partPatternFlipX[pid];
        else this.activeState.partPatternFlipY[pid] = !this.activeState.partPatternFlipY[pid];
        this.updatePattern();
    },

    updatePatternScale(val) {
        const pid = this.activePatternPart; this.activeState.partPatternScales[pid] = parseFloat(val);
        if (this.layers.partPatterns && this.layers.partPatterns[pid]) { this.layers.partPatterns[pid].set({ scaleX: val, scaleY: val }); this.canvas.requestRenderAll(); }
    },

    updatePatternAngle(val) {
        const pid = this.activePatternPart; this.activeState.partPatternAngles[pid] = parseFloat(val);
        if (this.layers.partPatterns && this.layers.partPatterns[pid]) { this.layers.partPatterns[pid].set({ angle: val }); this.canvas.requestRenderAll(); }
    },

    updatePatternPosition(axis, val) {
        const pid = this.activePatternPart;
        const prop = axis === 'X' ? 'partPatternX' : 'partPatternY';
        this.activeState[prop][pid] = parseFloat(val);
        if (this.layers.partPatterns && this.layers.partPatterns[pid]) {
            this.layers.partPatterns[pid].set(axis === 'X' ? 'left' : 'top', parseFloat(val));
            this.canvas.requestRenderAll();
        }
    },

    resetPatternProperty(prop) {
        if (this.isLoading) return;
        const pid = this.activePatternPart;
        if (prop === 'scale') { this.activeState.partPatternScales[pid] = 0.3; this.updatePatternScale(0.3); }
        else if (prop === 'angle') { this.activeState.partPatternAngles[pid] = 0; this.updatePatternAngle(0); }
        else if (prop === 'X') { this.activeState.partPatternX[pid] = 300; this.updatePatternPosition('X', 300); }
        else if (prop === 'Y') { this.activeState.partPatternY[pid] = 300; this.updatePatternPosition('Y', 300); }
        this.saveHistory();
    },

    togglePartPattern(partId) {
        this.activeState.partPatterns[partId] = !this.activeState.partPatterns[partId];
        if (this.activeState.partPatterns[partId] && !this.activeState.partActivePatterns[partId]) {
            if (this.patterns.length > 0) this.activeState.partActivePatterns[partId] = this.patterns[0].id;
        }
        this.updatePattern();
    },

    async getMockupClipMask() {
        const maskLayers = [];
        const mockup = this.availableMockups.find(m => m.id === this.currentModel);
        const partIds = mockup?.parts[this.currentView] || [];

        for (const pid of partIds) {
            if (this.layers[pid]) maskLayers.push(await this.layers[pid].clone(['isSystemLayer']));
        }
        return maskLayers.length ? new window.fabric.Group(maskLayers, { absolutePositioned: true, originX: 'center', originY: 'center', left: 300, top: 300 }) : null;
    },

    renderLayers() {
        if (!this.canvas) return;
        const wasHistoryAction = this.isHistoryAction;
        this.isHistoryAction = true; // Protect against events during clear/add
        this.canvas.clear();

        // KELOMPOKKAN LAYER: Utama (bawah) dan Detail/Trim (atas)
        const mainParts = this.currentView === 'pants' ? ['left', 'right'] : ['body', 'left_sleeve', 'right_sleeve'];
        const trimParts = this.currentView === 'pants' ? ['belt', 'list1', 'list2'] : ['collar', 'collar1', 'left_cuff', 'right_cuff'];

        // Fungsi bantuan untuk menggambar per kelompok (Base -> Gradient -> Pattern)
        const renderGroup = (partsArray) => {
            // 1. Base Parts (Colors)
            partsArray.forEach(key => { if (this.layers[key]) this.canvas.add(this.layers[key]); });

            // 2. Gradients (Masked)
            if (this.layers.partGradients) {
                partsArray.forEach(key => {
                    const g = this.layers.partGradients[key];
                    if (g && this.activeState.partGradientEnabled[key]) this.canvas.add(g);
                });
            }

            // 3. Patterns (Masked)
            if (this.layers.partPatterns) {
                partsArray.forEach(key => {
                    const p = this.layers.partPatterns[key];
                    if (p) this.canvas.add(p);
                });
            }
        };

        // RENDER URUTAN: Utama dulu, baru ditimpa Detail (Kerah & Cuff)
        renderGroup(mainParts);
        renderGroup(trimParts);

        // 4. Design Objects (Logos, Text)
        this.designObjects.filter(obj => obj.view === this.currentView).forEach(obj => this.canvas.add(obj));

        // 5. Shadows & Highlights (Paling Atas)
        if (this.layers.shadows) this.canvas.add(this.layers.shadows);
        if (this.layers.highlights) this.canvas.add(this.layers.highlights);

        this.canvas.renderAll();
        this.isHistoryAction = wasHistoryAction;
    },

    async updateLogoProperty(key, val) {
        const obj = this.canvas.getActiveObject();
        if (!obj || obj.isSystemLayer) return;

        if (key === 'isSolidColor') {
            obj.isSolidColor = val;
            this.applyLogoFilters(obj);
        } else if (key === 'solidColor') {
            obj.solidColor = val;
            this.applyLogoFilters(obj);
        }
        this.canvas.requestRenderAll();
        this.saveHistory();
    },

    async applyLogoFilters(obj) {
        if (!obj || !window.fabric) return;
        const filters = [];
        if (obj.isSolidColor) {
            filters.push(new window.fabric.filters.BlendColor({
                color: obj.solidColor || '#000000',
                mode: 'add', // Turns non-transparent pixels to this color
                alpha: 1
            }));
        }
        obj.filters = filters;
        obj.applyFilters();
        this.canvas.requestRenderAll();
    },

    async updateFont(type, fontId) {
        this.activeFont = fontId;
        try {
            await document.fonts.load(`10px "${fontId}"`);
        } catch (error) {
            console.warn("API Font dilewati, tetap menerapkan font...", error);
        }
        this.updateTextProperty('activeFont', fontId);
        this.saveHistory();
    },

    async updateTextProperty(key, val) {
        const activeObj = this.canvas.getActiveObject();
        this[key] = val;

        if (activeObj && (activeObj.type === 'i-text' || activeObj.type === 'text')) {
            if (key === 'textInput') activeObj.set('text', val);

            if (key === 'activeFont') {
                try { await document.fonts.load(`10px "${val}"`); } catch (e) { }
                activeObj.set({ fontFamily: val });
                this.canvas.requestRenderAll();
            }

            if (key === 'textFontSize') activeObj.set({ fontSize: parseFloat(val) });
            if (key === 'textCharSpacing') activeObj.set({ charSpacing: parseFloat(val) });

            if (key === 'textArc') {
                activeObj.arc = parseFloat(val);
                this.applyArcToText(activeObj);
            }

            if (key === 'activeColor') {
                this.textGradientEnabled = false;
            }

            // Fill & Gradient Logic
            if (['activeColor', 'textGradientEnabled', 'textGradientColor1', 'textGradientColor2', 'textGradientAngle', 'textGradientType'].includes(key)) {
                if (this.textGradientEnabled) {
                    const w = activeObj.width || 100;
                    const h = activeObj.height || 100;
                    let coords;
                    if (this.textGradientType === 'linear') {
                        const rad = this.textGradientAngle * (Math.PI / 180);
                        const halfLen = Math.max(w, h) / 2;
                        // For fabric.Gradient on objects, coords center is 0,0 
                        coords = {
                            x1: -Math.cos(rad) * halfLen,
                            y1: -Math.sin(rad) * halfLen,
                            x2: Math.cos(rad) * halfLen,
                            y2: Math.sin(rad) * halfLen
                        };
                    } else {
                        coords = { x1: 0, y1: 0, r1: 0, x2: 0, y2: 0, r2: Math.max(w, h) / 2 };
                    }
                    const gradient = new window.fabric.Gradient({
                        type: this.textGradientType,
                        gradientUnits: 'pixels',
                        coords: coords,
                        colorStops: [{ offset: 0, color: this.textGradientColor1 }, { offset: 1, color: this.textGradientColor2 }]
                    });
                    activeObj.set('fill', gradient);
                } else {
                    activeObj.set('fill', this.activeColor);
                }
            }

            this.canvas.renderAll();
            this.saveHistory();
        }
    },

    applyArcToText(obj) {
        if (!obj || !obj.text) return;
        const charCount = obj.text.length;
        const arcValue = obj.arc || 0;
        const fontSize = obj.fontSize;
        const charSpacing = obj.charSpacing || 0;

        // Reset styles for fresh render
        obj.set({ styles: {} });
        if (Math.abs(arcValue) < 1) return;

        // HIGHLY FLEXIBLE ARC LOGIC
        // Radius is inversely proportional to arc value
        const radius = 20000 / arcValue;
        const circumference = 2 * Math.PI * Math.abs(radius);
        const anglePerChar = ((fontSize * 0.7) + (charSpacing / 10)) / radius;
        const totalAngle = anglePerChar * (charCount - 1);
        const startAngle = -totalAngle / 2;

        obj.styles[0] = {};
        for (let i = 0; i < charCount; i++) {
            const charAngle = startAngle + (i * anglePerChar);
            const xOffset = Math.sin(charAngle) * radius;
            const yOffset = radius - (Math.cos(charAngle) * radius);

            // Invert Y for upside down arcs
            const finalY = arcValue > 0 ? yOffset : yOffset;

            obj.styles[0][i] = {
                deltaY: -finalY,
                // Fabric.js character rotation is tricky, we'll focus on Y offset for now
            };
        }
    },

    async addText() {
        const clipMask = await this.getMockupClipMask();

        try {
            await document.fonts.load(`10px "${this.activeFont}"`);
        } catch (e) {
            console.warn("API Font dilewati...", e);
        }

        const newText = new window.fabric.IText(this.textInput || 'TEKS BARU', {
            left: 300, top: 300, originX: 'center', originY: 'center', fill: this.activeColor,
            fontFamily: this.activeFont, fontSize: this.textFontSize, charSpacing: this.textCharSpacing,
            cornerStyle: 'circle', cornerColor: '#06402B', clipPath: clipMask, view: this.currentView, arc: this.textArc
        });

        if (this.textArc !== 0) this.applyArcToText(newText);

        // Perbaikan Flow Teks
        this.designObjects.push(newText);
        this.renderLayers();
        this.canvas.setActiveObject(newText);

        if (this.textGradientEnabled) {
            this.updateTextProperty('textGradientEnabled', true);
        }

        this.saveHistory();
    },

    generateGradientImage(width, height, color1, color2, angle, type) {
        const canvas = document.createElement('canvas');
        canvas.width = Math.max(width || 600, 100);
        canvas.height = Math.max(height || 600, 100);
        const ctx = canvas.getContext('2d');

        let grd;
        if (type === 'linear') {
            const rad = angle * (Math.PI / 180);
            const halfLen = Math.max(canvas.width, canvas.height) / 2;
            const cx = canvas.width / 2; const cy = canvas.height / 2;
            grd = ctx.createLinearGradient(
                cx - Math.cos(rad) * halfLen, cy - Math.sin(rad) * halfLen,
                cx + Math.cos(rad) * halfLen, cy + Math.sin(rad) * halfLen
            );
        } else {
            grd = ctx.createRadialGradient(canvas.width / 2, canvas.height / 2, 0, canvas.width / 2, canvas.height / 2, Math.max(canvas.width, canvas.height) / 2);
        }
        grd.addColorStop(0, color1 || '#06402B');
        grd.addColorStop(1, color2 || '#0f523a');
        ctx.fillStyle = grd;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        return new Promise((resolve) => {
            const imgEl = new Image();
            imgEl.onload = () => resolve(new window.fabric.Image(imgEl));
            imgEl.src = canvas.toDataURL('image/png');
        });
    },

    confirmCopyFromFront() {
        this.showCopyConfirmModal = true;
    },

    async copyDesignFromFront() {
        this.showCopyConfirmModal = false;
        {
            this.isLoading = true;
            const fromState = this.viewStates['front'];
            const toState = this.viewStates[this.currentView];

            for (let targetKey in toState.partColors) {
                let srcKey = targetKey;
                if (!fromState.partColors.hasOwnProperty(srcKey)) {
                    if (['left', 'right'].includes(targetKey)) srcKey = 'body';
                    else if (['belt', 'list1', 'list2'].includes(targetKey)) srcKey = 'collar';
                    else continue;
                }

                toState.partColors[targetKey] = fromState.partColors[srcKey];
                toState.partColorMode[targetKey] = fromState.partColorMode[srcKey];
                toState.partActiveGradientPreset[targetKey] = fromState.partActiveGradientPreset[srcKey];
                toState.partPatterns[targetKey] = fromState.partPatterns[srcKey];
                toState.partPatternScales[targetKey] = fromState.partPatternScales[srcKey];
                toState.partPatternAngles[targetKey] = fromState.partPatternAngles[srcKey];

                // --- PERBAIKAN: Mirroring Posisi X & Flip Motif ---
                if ((this.currentView === 'back' || this.currentView === 'pants') &&
                    ['left_sleeve', 'right_sleeve', 'left_cuff', 'right_cuff', 'left', 'right'].includes(targetKey)) {
                    // Cerminkan posisi X agar motif pas masuk ke dalam area mask yang pindah sisi
                    toState.partPatternX[targetKey] = 600 - fromState.partPatternX[srcKey];
                    // Cerminkan juga arah FlipX agar coraknya presisi sama seperti dari depan
                    toState.partPatternFlipX[targetKey] = !fromState.partPatternFlipX[srcKey];
                } else {
                    toState.partPatternX[targetKey] = fromState.partPatternX[srcKey];
                    toState.partPatternFlipX[targetKey] = fromState.partPatternFlipX[srcKey];
                }
                // ----------------------------------------------------

                toState.partPatternFlipY[targetKey] = fromState.partPatternFlipY[srcKey];
                toState.partActivePatterns[targetKey] = fromState.partActivePatterns[srcKey];
                toState.partPatternColors[targetKey] = fromState.partPatternColors[srcKey];
                toState.partPatternY[targetKey] = fromState.partPatternY[srcKey];
                toState.partGradientEnabled[targetKey] = fromState.partGradientEnabled[srcKey];
                toState.partGradientColor1[targetKey] = fromState.partGradientColor1[srcKey];
                toState.partGradientColor2[targetKey] = fromState.partGradientColor2[srcKey];
                toState.partGradientAngle[targetKey] = fromState.partGradientAngle[srcKey];
                toState.partGradientType[targetKey] = fromState.partGradientType[srcKey];
                toState.partPatternGradientEnabled[targetKey] = fromState.partPatternGradientEnabled[srcKey];
                toState.partPatternGradientColor1[targetKey] = fromState.partPatternGradientColor1[srcKey];
                toState.partPatternGradientColor2[targetKey] = fromState.partPatternGradientColor2[srcKey];
                toState.partPatternGradientAngle[targetKey] = fromState.partPatternGradientAngle[srcKey];
                toState.partPatternGradientType[targetKey] = fromState.partPatternGradientType[srcKey];
            }

            await this.loadInitialLayers();
            await this.updatePattern();
            this.renderLayers();
            this.isLoading = false;
            this.saveHistory();
        }
    },

    updateToolbar(e) {
        const obj = e.target || (e.selected && e.selected[0]);
        if (!obj || obj.isSystemLayer) {
            this.showToolbar = false;
            this.activeObjectType = '';
            this.activeObjectElement = false;
            return;
        }

        this.activeObjectType = obj.type;
        this.activeObjectElement = !!obj._element;

        const rect = obj.getBoundingRect(); const vpt = this.canvas.viewportTransform;
        this.toolbarPos = { top: (rect.top * vpt[0] + vpt[5]) - 70, left: (rect.left * vpt[0] + vpt[4]) + (rect.width * vpt[0] / 2) };
        this.showToolbar = true;
    },

    async duplicateSelected() {
        const obj = this.canvas.getActiveObject(); if (!obj) return;
        const clone = await obj.clone(['clipPath', 'isSystemLayer', 'view', 'arc']);
        clone.set({ left: obj.left + 20, top: obj.top + 20 });
        this.designObjects.push(clone);
        this.renderLayers();
        this.canvas.setActiveObject(clone);
        this.saveHistory();
    },

    setupZoomEvents() {
        this.canvas.on('mouse:wheel', (opt) => {
            let zoom = this.canvas.getZoom() * (0.999 ** opt.e.deltaY); zoom = Math.min(Math.max(zoom, 0.5), 5);
            this.canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom); this.currentZoom = zoom; this.showToolbar = false;
            opt.e.preventDefault(); opt.e.stopPropagation();
        });
    },

    zoom(delta) { let zoom = Math.min(Math.max(this.canvas.getZoom() + delta, 0.5), 5); this.canvas.zoomToPoint({ x: 300, y: 300 }, zoom); this.currentZoom = zoom; },
    resetZoom() { this.canvas.setZoom(1); this.canvas.setViewportTransform([1, 0, 0, 1, 0, 0]); this.currentZoom = 1; this.resizeCanvas(); },
    async loadImage(url, options = {}) {
        try { const img = await window.fabric.FabricImage.fromURL(url, { crossOrigin: 'anonymous' }); img.scaleToWidth(600); img.set({ originX: 'center', originY: 'center', left: 300, top: 300, ...options }); return img; } catch (err) { return null; }
    },

    triggerLogoUpload() {
        // Use a fresh dynamic input each time — avoids browser 'change' not firing on repeat/same-file
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.style.display = 'none';
        input.onchange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = async (ev) => {
                const img = await window.fabric.FabricImage.fromURL(ev.target.result);
                img.scaleToWidth(100);
                const clipMask = await this.getMockupClipMask();
                img.set({ left: 300, top: 250, originX: 'center', originY: 'center', cornerStyle: 'circle', cornerColor: '#06402B', clipPath: clipMask, view: this.currentView });
                this.designObjects.push(img);
                this.renderLayers();
                this.canvas.setActiveObject(img);
                this.saveHistory();
            };
            reader.readAsDataURL(file);
            // Remove the temporary input from DOM after done
            document.body.removeChild(input);
        };
        document.body.appendChild(input);
        input.click();
    },

    openCropper(imageSrc) {
        let src = imageSrc;
        const activeObj = this.canvas.getActiveObject();
        if (!src && activeObj && (activeObj.type === 'image' || activeObj._element)) {
            // Gunakan gambar aslinya, bukan gambar canvas yang sudah di skala/rotasi
            src = activeObj._element ? activeObj._element.src : activeObj.getSrc();
        }

        if (!src && activeObj) {
            src = activeObj.toDataURL({ format: 'png', multiplier: 2 });
        }

        if (!src) return;
        this.showCropper = true;

        this.$nextTick(() => {
            const imageElement = document.getElementById('cropper-image');
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            imageElement.onload = () => {
                this.cropper = new Cropper(imageElement, {
                    viewMode: 1,
                    movable: true,
                    zoomable: true,
                    rotatable: true,
                    scalable: true,
                    autoCropArea: 1,
                    background: false
                });
                imageElement.onload = null;
            };
            imageElement.src = src;
        });
    },

    async applyCrop() {
        if (!this.cropper) return;
        const croppedCanvas = this.cropper.getCroppedCanvas();
        const croppedDataUrl = croppedCanvas.toDataURL('image/png');

        const activeObj = this.canvas.getActiveObject();
        if (activeObj && !activeObj.isSystemLayer && (activeObj.type === 'image' || activeObj._element)) {
            const cropData = this.cropper.getData(); // x, y, width, height dalam koordinat asli

            // local center of crop box relative to original image center
            const localCx = cropData.x + (cropData.width / 2) - (activeObj.width / 2);
            const localCy = cropData.y + (cropData.height / 2) - (activeObj.height / 2);

            // apply scaling
            const scaledLocalCx = localCx * activeObj.scaleX;
            const scaledLocalCy = localCy * activeObj.scaleY;

            // apply rotation (angle in degrees)
            const rad = activeObj.angle * Math.PI / 180;
            const cos = Math.cos(rad);
            const sin = Math.sin(rad);

            const dx = scaledLocalCx * cos - scaledLocalCy * sin;
            const dy = scaledLocalCx * sin + scaledLocalCy * cos;

            const newLeft = activeObj.left + dx;
            const newTop = activeObj.top + dy;

            const img = await window.fabric.FabricImage.fromURL(croppedDataUrl);
            img.set({
                left: newLeft,
                top: newTop,
                scaleX: activeObj.scaleX,
                scaleY: activeObj.scaleY,
                angle: activeObj.angle,
                originX: activeObj.originX || 'center',
                originY: activeObj.originY || 'center',
                cornerStyle: activeObj.cornerStyle,
                cornerColor: activeObj.cornerColor,
                clipPath: activeObj.clipPath,
                view: activeObj.view
            });
            this.canvas.remove(activeObj);
            this.designObjects = this.designObjects.filter(o => o !== activeObj);
            this.designObjects.push(img);
            this.canvas.add(img);
            this.canvas.setActiveObject(img);
        } else {
            // New Upload
            const img = await window.fabric.FabricImage.fromURL(croppedDataUrl);
            img.scaleToWidth(100);
            const clipMask = await this.getMockupClipMask();
            img.set({ left: 300, top: 250, originX: 'center', originY: 'center', cornerStyle: 'circle', cornerColor: '#6366f1', clipPath: clipMask, view: this.currentView });
            this.designObjects.push(img);
            this.canvas.add(img);
            this.canvas.setActiveObject(img);
        }

        this.renderLayers();
        this.saveHistory();
        this.cancelCrop();
    },

    cancelCrop() {
        this.showCropper = false;
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
    },
    setActiveMenu(menuId) { if (this.activeMenu === menuId) this.togglePanel(); else { this.activeMenu = menuId; this.isPanelOpen = true; setTimeout(() => this.resizeCanvas(), 350); } },
    deleteSelected() { const obj = this.canvas.getActiveObject(); if (obj) { this.canvas.remove(obj); this.designObjects = this.designObjects.filter(o => o !== obj); this.showToolbar = false; } },
    resizeCanvas() { try { const container = document.querySelector('main .relative'); if (!container || !this.canvas || this.currentZoom !== 1) return; const size = Math.min(container.clientWidth - 40, 600); this.canvas.setDimensions({ width: size, height: size }); this.canvas.setZoom(size / 600); } catch (err) { } },
    togglePanel() { this.isPanelOpen = !this.isPanelOpen; setTimeout(() => this.resizeCanvas(), 350); }
});