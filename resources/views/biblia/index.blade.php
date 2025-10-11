@extends('layouts.main')

@section('title', 'Biblia')

@push('head')
  <style>
    [x-cloak]{display:none!important}
    /* Visor centrado */
    .verse-overlay {
      position: fixed; inset: 0; z-index: 60;
      display: grid; place-items: center;
      background: rgba(0,0,0,.45);
      backdrop-filter: blur(2px);
      padding: 1rem;
    }
    .verse-card {
      max-width: 60rem; width: 100%;
      border-radius: 1rem;
      background: white; color: #111827;
      padding: 1.25rem 4.5rem; /* espacio para flechas */
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }
    .dark .verse-card{ background:#111827; color:#e5e7eb; }
    .nav-arrow{
      position:absolute; top:50%; transform: translateY(-50%);
      width: 2.75rem; height: 2.75rem; border-radius: 9999px;
      display:grid; place-items:center; font-weight:700;
      background: rgba(255,255,255,.9); color:#111827;
      box-shadow: 0 4px 10px rgba(0,0,0,.25);
      user-select:none;
    }
    .dark .nav-arrow{ background: rgba(17,24,39,.9); color:#e5e7eb; }
    .nav-arrow[disabled]{ opacity:.35; cursor:not-allowed }
    .nav-left{ left: 1rem; }
    .nav-right{ right: 1rem; }
    .ref-chip{
      font-size:.75rem; opacity:.9; padding:.15rem .5rem; border-radius:9999px;
      background: rgba(0,0,0,.06);
    }
    .dark .ref-chip{ background: rgba(255,255,255,.08); }
    .verse-text{ font-size:1.125rem; line-height:1.7; }
  </style>
@endpush

@section('content')
<div class="page-container page-hero" x-data="biblia()" x-init="init()" x-cloak
     @keydown.window.arrow-left.prevent="focusMode && prevVerse()"
     @keydown.window.arrow-right.prevent="focusMode && nextVerse()"
     @keydown.window.escape="focusMode && exitFocus()">

  <h1 class="page-title">Biblia</h1>
  <div class="hr-brand"></div>

  {{-- Controles principales --}}
  <div class="panel space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div>
        <label class="block text-sm font-medium mb-1">Libro</label>
        <select x-model="libro" @change="cargarCapitulos()" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
          <option value="">Seleccione…</option>
          <template x-for="b in libros" :key="b.slug">
            <option :value="b.slug" x-text="b.name"></option>
          </template>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Capítulo</label>
        <select x-model="cap" @change="cargarCapitulo()" :disabled="!libro" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
          <option value="">Seleccione…</option>
          <template x-for="c in caps" :key="c">
            <option :value="c" x-text="c"></option>
          </template>
        </select>
      </div>
      <div class="flex items-end">
        <button class="btn-brand" @click="copiarRefActual()" :disabled="!libro || !cap">
          Copiar referencia
        </button>
      </div>
    </div>

    {{-- Buscador --}}
    <div class="mt-4">
      <label class="block text-sm font-medium mb-1">Buscar en toda la Biblia</label>
      <input type="search"
             x-model="q"
             @input="onSearchInput"
             placeholder="Ej: fe, amor, no temas…"
             class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800" />
      <div class="mt-1 text-sm">
        <span class="text-gray-500 dark:text-gray-400" x-show="q.length && !cargandoBusqueda && !errorBusqueda">
          <span x-text="resultado.total"></span> resultados para “<span x-text="resultado.q"></span>”.
        </span>
        <span class="text-brand-dark dark:text-brand-light" x-show="cargandoBusqueda">Cargando…</span>
        <span class="text-red-600 dark:text-red-400" x-show="errorBusqueda" x-text="errorBusqueda"></span>
      </div>
    </div>
  </div>

  {{-- Lector de capítulo --}}
  <template x-if="versiculos.length">
    <div class="panel mt-6">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-xl font-semibold text-brand-dark dark:text-brand-light" x-text="tituloCap"></h2>
        <div class="text-sm text-gray-500 dark:text-gray-400" x-show="versiculos.length">
          <span x-text="versiculos.length"></span> versículos
        </div>
      </div>
      <div class="prose dark:prose-invert max-w-none">
        <ol class="space-y-2">
          <template x-for="v in versiculos" :key="v.n">
            <li :id="'v-' + v.n" class="scroll-mt-24">
              <button class="inline-flex items-start text-left hover:bg-black/5 dark:hover:bg-white/5 rounded px-1 -mx-1"
                      @click="enterFocusByNumber(v.n)">
                <span class="font-semibold mr-2 min-w-[2ch]" x-text="v.n + '.'"></span>
                <span x-text="v.t"></span>
              </button>
              <button class="chip ml-2 text-xs" @click="copiar(tituloCap + ':' + v.n + ' ' + v.t)">Copiar</button>
            </li>
          </template>
        </ol>
      </div>
    </div>
  </template>

  {{-- Resultados de búsqueda --}}
  <template x-if="q && !cargandoBusqueda && !errorBusqueda">
    <div class="panel mt-6" x-show="resultado.q && Array.isArray(resultado.results)">
      <h3 class="text-lg font-semibold mb-2">Resultados</h3>
      <template x-if="resultado.results.length === 0">
        <p class="text-sm text-gray-500 dark:text-gray-400">Sin resultados.</p>
      </template>
      <ul class="space-y-3" x-show="resultado.results.length">
        <template x-for="r in resultado.results" :key="r.ref + '-' + r.verse">
          <li class="border-b border-black/10 dark:border-white/10 pb-3">
            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="r.ref"></div>
            <div x-text="r.text"></div>
            <div class="text-xs mt-1 text-gray-500 dark:text-gray-400" x-text="r.snippet"></div>
            <div class="mt-1 flex gap-2">
              <a class="content-link" :href="`#v-${r.verse}`"
                 @click.prevent="abrir(r, true)"
                 x-show="libro===r.book && +cap===r.chapter">
                 Enfocar este versículo
              </a>
              <button class="chip" @click="abrir(r, true)">Abrir</button>
              <button class="chip" @click="copiar(r.ref + ' ' + r.text)">Copiar</button>
            </div>
          </li>
        </template>
      </ul>
    </div>
  </template>

  {{-- VISOR CENTRADO DE VERSÍCULO --}}
  <template x-if="focusMode">
    <div class="verse-overlay" @click.self="exitFocus()">
      <div class="verse-card relative">
        <button class="nav-arrow nav-left" @click="prevVerse()" :disabled="!hasPrevAll()"
                :aria-disabled="!hasPrevAll()" title="Anterior">‹</button>
        <button class="nav-arrow nav-right" @click="nextVerse()" :disabled="!hasNextAll()"
                :aria-disabled="!hasNextAll()" title="Siguiente">›</button>

        <div class="flex items-center justify-between mb-3 gap-3">
          <div class="ref-chip" x-text="focusRef()"></div>
          <div class="flex gap-2">
            <button class="chip" @click="copiar(focusRef() + ' ' + focusVerse()?.t)">Copiar</button>
            <button class="chip" @click="scrollToFocused()">Ver en lista</button>
            <button class="chip" @click="exitFocus()">Cerrar</button>
          </div>
        </div>

        <div class="verse-text" x-text="focusVerse()?.t"></div>
      </div>
    </div>
  </template>

</div>

{{-- Alpine component --}}
<script>
function biblia() {
  return {
    libros: [],      // [{slug, name, chapters}]
    caps: [],        // ["1","2",...]
    libro: '',       // slug actual
    cap: '',         // capítulo actual (string)
    versiculos: [],  // [{n, t}]
    tituloCap: '',
    q: '',
    resultado: { q:'', total:0, results:[] },
    cargandoBusqueda: false,
    errorBusqueda: '',

    // Estado del visor
    focusMode: false,
    focusIndex: -1, // índice dentro de versiculos

    // Debouncer
    _searchTimer: null,
    onSearchInput(e){
      const v = (e.target.value || '').trim();
      this.q = v;
      this.errorBusqueda = '';
      clearTimeout(this._searchTimer);
      if (!v || v.length < 2) { this.resultado = {q:v, total:0, results:[]}; return; }
      this.cargandoBusqueda = true;
      this._searchTimer = setTimeout(() => this.buscar(), 400);
    },

    async init() {
      await this.cargarLibros();
      this.parseHash();
    },

    // ----- Hash tipo #Juan-3:16 -----
    parseHash() {
      if (!location.hash) return;
      const h = decodeURIComponent(location.hash.slice(1));
      const m = h.match(/^(.+?)-(\d+)(?::(\d+))?$/);
      if (!m) return;
      const nombre = m[1].toLowerCase().replaceAll(' ', '-');
      const cap = m[2];
      const ver = m[3] || null;
      const book = this.libros.find(b => b.slug === nombre || b.name.toLowerCase() === m[1].toLowerCase());
      if (book) {
        this.libro = book.slug;
        this.cargarCapitulos().then(() => {
          this.cap = cap;
          this.cargarCapitulo().then(() => { if (ver) this.enterFocusByNumber(+ver) });
        });
      }
    },

    // ----- Scroll utilidades -----
    scrollToVerse(v) {
      requestAnimationFrame(() => {
        const el = document.getElementById('v-' + v);
        el?.scrollIntoView({ behavior:'smooth', block:'center' });
      });
    },
    scrollToFocused() {
      const v = this.focusVerse();
      if (!v) return;
      this.exitFocus();
      this.scrollToVerse(v.n);
    },

    // ----- Carga de datos -----
    async cargarLibros() {
      const res = await fetch('{{ route("biblia.api.books") }}');
      this.libros = await res.json();
    },
    async cargarCapitulos() {
      this.cap = '';
      this.caps = [];
      this.versiculos = [];
      this.exitFocus();
      if (!this.libro) return;
      const res = await fetch(`{{ url('/biblia/api') }}/${this.libro}`);
      this.caps = await res.json();
    },
    async cargarCapitulo() {
      this.versiculos = [];
      this.exitFocus();
      if (!this.libro || !this.cap) return;
      const res = await fetch(`{{ url('/biblia/api') }}/${this.libro}/${this.cap}`);
      const data = await res.json();
      this.versiculos = data.verses ?? [];
      this.tituloCap  = data.pretty ?? '';
      // Actualiza hash legible: #Juan-3
      const libroObj = this.libros.find(b => b.slug === this.libro);
      const hash = (libroObj?.name || this.libro).replaceAll(' ', '-') + '-' + this.cap;
      history.replaceState({}, '', '#' + encodeURIComponent(hash));
    },

    // ----- Buscador (con manejo de errores) -----
    async buscar() {
      try {
        const q = this.q.trim();
        if (!q || q.length < 2) { this.resultado = {q, total:0, results:[]}; this.cargandoBusqueda=false; return; }
        const url = `{{ route('biblia.api.search') }}?q=${encodeURIComponent(q)}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        // Validar estructura
        this.resultado = {
          q: data.q ?? q,
          total: Array.isArray(data.results) ? data.results.length : (data.total ?? 0),
          results: Array.isArray(data.results) ? data.results : []
        };
        this.errorBusqueda = '';
      } catch (err) {
        console.error('Buscar error', err);
        this.errorBusqueda = 'No se pudo buscar. Revisa la consola (F12) y las rutas.';
        this.resultado = { q:this.q, total:0, results:[] };
      } finally {
        this.cargandoBusqueda = false;
      }
    },

    async abrir(r, focus=false) {
      // Carga capítulo si es distinto y posiciona/enfoca
      const needLoadBook = this.libro !== r.book;
      const needLoadCap  = +this.cap !== +r.chapter;
      this.libro = r.book;
      if (needLoadBook) await this.cargarCapitulos();
      this.cap = String(r.chapter);
      if (needLoadBook || needLoadCap) await this.cargarCapitulo();

      const idx = this.versiculos.findIndex(v => +v.n === +r.verse);
      if (idx >= 0) {
        if (focus) this.enterFocus(idx);
        else this.scrollToVerse(r.verse);
      } else {
        this.scrollToVerse(r.verse);
      }
    },

    // ====== Visor centrado y navegación ======
    focusMode: false,
    focusIndex: -1,

    enterFocus(index){
      if (index < 0 || index >= this.versiculos.length) return;
      this.focusIndex = index;
      this.focusMode = true;
    },
    enterFocusByNumber(n){
      const idx = this.versiculos.findIndex(v => +v.n === +n);
      if (idx >= 0) this.enterFocus(idx);
      this.scrollToVerse(n);
    },
    exitFocus(){
      this.focusMode = false;
      this.focusIndex = -1;
    },

    hasPrevLocal(){ return this.focusIndex > 0; },
    hasNextLocal(){ return this.focusIndex >= 0 && this.focusIndex < this.versiculos.length - 1; },
    hasPrevChapter(){
      const i = this.caps.findIndex(c => String(c) === String(this.cap));
      return i > 0;
    },
    hasNextChapter(){
      const i = this.caps.findIndex(c => String(c) === String(this.cap));
      return i >= 0 && i < this.caps.length - 1;
    },
    currentBookIndex(){ return this.libros.findIndex(b => b.slug === this.libro); },
    hasPrevBook(){ return this.currentBookIndex() > 0; },
    hasNextBook(){ return this.currentBookIndex() >= 0 && this.currentBookIndex() < this.libros.length - 1; },

    hasPrevAll(){ return this.hasPrevLocal() || this.hasPrevChapter() || this.hasPrevBook(); },
    hasNextAll(){ return this.hasNextLocal() || this.hasNextChapter() || this.hasNextBook(); },

    async prevVerse(){
      if (this.hasPrevLocal()) { this.focusIndex--; this.centerListOnFocused(); return; }
      if (await this.goToPrevChapter()) { this.focusIndex = this.versiculos.length - 1; this.centerListOnFocused(); return; }
      if (await this.goToPrevBook())   { this.focusIndex = this.versiculos.length - 1; this.centerListOnFocused(); }
    },
    async nextVerse(){
      if (this.hasNextLocal()) { this.focusIndex++; this.centerListOnFocused(); return; }
      if (await this.goToNextChapter()) { this.focusIndex = 0; this.centerListOnFocused(); return; }
      if (await this.goToNextBook())   { this.focusIndex = 0; this.centerListOnFocused(); }
    },
    centerListOnFocused(){
      const v = this.focusVerse();
      if (!v) return;
      this.scrollToVerse(v.n);
    },
    focusVerse(){ return this.focusIndex >= 0 ? this.versiculos[this.focusIndex] : null; },
    focusRef(){
      const v = this.focusVerse();
      if (!v) return '';
      return `${this.tituloCap}:${v.n}`;
    },

    async goToPrevChapter(){
      const idx = this.caps.findIndex(c => String(c) === String(this.cap));
      if (idx > 0) {
        this.cap = String(this.caps[idx - 1]);
        await this.cargarCapitulo();
        return true;
      }
      return false;
    },
    async goToNextChapter(){
      const idx = this.caps.findIndex(c => String(c) === String(this.cap));
      if (idx >= 0 && idx < this.caps.length - 1) {
        this.cap = String(this.caps[idx + 1]);
        await this.cargarCapitulo();
        return true;
      }
      return false;
    },
    async goToPrevBook(){
      const bidx = this.currentBookIndex();
      if (bidx > 0) {
        const prevBook = this.libros[bidx - 1];
        this.libro = prevBook.slug;
        await this.cargarCapitulos();
        this.cap = String(this.caps[this.caps.length - 1]); // último capítulo
        await this.cargarCapitulo();
        return true;
      }
      return false;
    },
    async goToNextBook(){
      const bidx = this.currentBookIndex();
      if (bidx >= 0 && bidx < this.libros.length - 1) {
        const nextBook = this.libros[bidx + 1];
        this.libro = nextBook.slug;
        await this.cargarCapitulos();
        this.cap = String(this.caps[0]); // primer capítulo
        await this.cargarCapitulo();
        return true;
      }
      return false;
    },

    // ----- Utilidades -----
    copiarRefActual() {
      if (!this.libro || !this.cap) return;
      const libroObj = this.libros.find(b => b.slug === this.libro);
      this.copiar(`${libroObj?.name || this.libro} ${this.cap}`);
    },
    copiar(texto) {
      navigator.clipboard.writeText(texto).then(() => {
        window.Swal?.fire?.({ icon:'success', text:'Copiado', timer:900, showConfirmButton:false });
      });
    },
    irA(r) { this.scrollToVerse(r.verse); },
  }
}
</script>
@endsection
