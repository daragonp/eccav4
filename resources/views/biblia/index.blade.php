@extends('layouts.main')

@section('title', 'Biblia')

@push('head')
  <style>
    [x-cloak]{display:none!important}
    
    /* Estilos mejorados para el visor de versículos */
    .verse-overlay {
      position: fixed; inset: 0; z-index: 60;
      display: grid; place-items: center;
      background: rgba(0,0,0,.6);
      backdrop-filter: blur(4px);
      padding: 1rem;
      animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .verse-card {
      max-width: 60rem; width: 100%;
      border-radius: 1.5rem;
      background: white; color: #111827;
      padding: 2rem 4.5rem; /* espacio para flechas */
      box-shadow: 0 20px 40px rgba(0,0,0,.3);
      animation: slideUp 0.3s ease-out;
      position: relative;
      overflow: hidden;
    }
    
    .verse-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, var(--green-dark), var(--green-light));
    }
    
    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    .dark .verse-card{ 
      background:#111827; 
      color:#e5e7eb; 
      box-shadow: 0 20px 40px rgba(0,0,0,.5);
    }
    
    .nav-arrow{
      position:absolute; top:50%; transform: translateY(-50%);
      width: 3rem; height: 3rem; border-radius: 50%;
      display:grid; place-items:center; font-weight:700;
      background: var(--green-dark); color: white;
      box-shadow: 0 4px 12px rgba(0,0,0,.25);
      user-select:none;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    
    .nav-arrow:hover {
      transform: translateY(-50%) scale(1.1);
      background: var(--green-light);
    }
    
    .nav-arrow[disabled]{ 
      opacity:.4; 
      cursor:not-allowed;
      transform: translateY(-50%);
    }
    
    .nav-arrow[disabled]:hover {
      transform: translateY(-50%);
      background: var(--green-dark);
    }
    
    .nav-left{ left: 1rem; }
    .nav-right{ right: 1rem; }
    
    .ref-chip{
      font-size:.875rem; 
      opacity:.9; 
      padding:.25rem .75rem; 
      border-radius:9999px;
      background: var(--green-light);
      color: white;
      font-weight: 600;
    }
    
    .verse-text{ 
      font-size:1.25rem; 
      line-height:1.8; 
      text-align: justify;
      margin: 1.5rem 0;
    }
    
    .verse-text mark {
      background-color: rgba(138, 164, 70, 0.3);
      padding: 0 2px;
      border-radius: 2px;
    }
    
    .dark .verse-text mark {
      background-color: rgba(138, 164, 70, 0.5);
    }
    
    /* Estilos para la lista de versículos */
    .verse-list {
      display: grid;
      gap: 1rem;
    }
    
    .verse-item {
      padding: 1rem;
      border-radius: 0.75rem;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
    }
    
    .verse-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--green-light);
      transform: scaleY(0);
      transition: transform 0.2s ease;
    }
    
    .verse-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,.1);
    }
    
    .verse-item:hover::before {
      transform: scaleY(1);
    }
    
    .dark .verse-item {
      background: #1f2937;
      color: #e5e7eb;
    }
    
    .verse-number {
      font-weight: 700;
      color: var(--green-dark);
      margin-right: 0.5rem;
      min-width: 2ch;
      display: inline-block;
    }
    
    .dark .verse-number {
      color: var(--green-light);
    }
    
    .verse-content {
      display: inline;
    }
    
    /* Estilos para la paginación */
    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
      margin-top: 1.5rem;
    }
    
    .pagination button {
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      background: white;
      color: var(--green-dark);
      border: 1px solid var(--green-light);
      font-weight: 600;
      transition: all 0.2s ease;
    }
    
    .pagination button:hover:not(:disabled) {
      background: var(--green-light);
      color: white;
    }
    
    .pagination button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    
    .pagination .current-page {
      background: var(--green-dark);
      color: white;
    }
    
    .dark .pagination button {
      background: #374151;
      color: #e5e7eb;
    }
    
    .dark .pagination .current-page {
      background: var(--green-light);
      color: var(--green-dark);
    }
    
    /* Estilos para la búsqueda */
    .search-highlight {
      background-color: rgba(138, 164, 70, 0.2);
      padding: 0 2px;
      border-radius: 2px;
    }
    
    .dark .search-highlight {
      background-color: rgba(138, 164, 70, 0.3);
    }
    
    /* Estilos para el panel de bienvenida */
    .welcome-panel {
      background: linear-gradient(135deg, var(--green-dark), var(--green-light));
      color: white;
      padding: 2rem;
      border-radius: 1rem;
      margin-bottom: 2rem;
      box-shadow: 0 10px 20px rgba(0,0,0,.1);
    }
    
    .welcome-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    
    .welcome-text {
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }
    
    .welcome-verse {
      font-style: italic;
      font-size: 1.1rem;
      line-height: 1.7;
      padding: 1rem;
      background: rgba(255,255,255,0.1);
      border-radius: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .welcome-reference {
      text-align: right;
      font-size: 0.9rem;
      opacity: 0.8;
    }
    
    /* Estilos para el botón de acción */
    .action-button {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      background: white;
      color: var(--green-dark);
      border-radius: 9999px;
      font-weight: 600;
      transition: all 0.2s ease;
      box-shadow: 0 4px 8px rgba(0,0,0,.1);
    }
    
    .action-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,.15);
    }
    
    /* Estilos para el modo de lectura */
    .reading-mode {
      max-width: 42rem;
      margin: 0 auto;
    }
    
    .reading-mode .verse-item {
      padding: 1.5rem;
      font-size: 1.1rem;
      line-height: 1.8;
    }
    
    /* Estilos para el indicador de progreso */
    .progress-indicator {
      height: 4px;
      background: rgba(138, 164, 70, 0.2);
      border-radius: 2px;
      margin: 1rem 0;
      overflow: hidden;
    }
    
    .progress-bar {
      height: 100%;
      background: var(--green-light);
      border-radius: 2px;
      transition: width 0.3s ease;
    }
    
    /* Estilos para el selector de libros mejorado */
    .book-selector {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 0.75rem;
      margin-top: 1rem;
    }
    
    .book-option {
      padding: 0.75rem;
      border-radius: 0.5rem;
      background: white;
      border: 1px solid var(--green-light);
      cursor: pointer;
      transition: all 0.2s ease;
      text-align: center;
      font-weight: 600;
    }
    
    .book-option:hover {
      background: var(--green-light);
      color: white;
      transform: translateY(-2px);
    }
    
    .book-option.selected {
      background: var(--green-dark);
      color: white;
    }
    
    .dark .book-option {
      background: #374151;
      color: #e5e7eb;
    }
    
    .dark .book-option.selected {
      background: var(--green-light);
      color: var(--green-dark);
    }
    
    /* Estilos para el selector de capítulos mejorado */
    .chapter-selector {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 1rem;
    }
    
    .chapter-option {
      width: 3rem;
      height: 3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: white;
      border: 1px solid var(--green-light);
      cursor: pointer;
      transition: all 0.2s ease;
      font-weight: 600;
    }
    
    .chapter-option:hover {
      background: var(--green-light);
      color: white;
      transform: scale(1.1);
    }
    
    .chapter-option.selected {
      background: var(--green-dark);
      color: white;
    }
    
    .dark .chapter-option {
      background: #374151;
      color: #e5e7eb;
    }
    
    .dark .chapter-option.selected {
      background: var(--green-light);
      color: var(--green-dark);
    }
    
    /* Estilos para el botón flotante de lectura */
    .floating-reading-button {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      width: 3.5rem;
      height: 3.5rem;
      border-radius: 50%;
      background: var(--green-dark);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(0,0,0,.2);
      cursor: pointer;
      transition: all 0.2s ease;
      z-index: 50;
    }
    
    .floating-reading-button:hover {
      transform: scale(1.1);
      background: var(--green-light);
    }
    
    /* Estilos para el panel de configuración */
    .settings-panel {
      position: fixed;
      top: 5rem;
      right: 1rem;
      width: 20rem;
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 10px 25px rgba(0,0,0,.15);
      padding: 1.5rem;
      z-index: 40;
      transform: translateX(calc(100% + 2rem));
      transition: transform 0.3s ease;
    }
    
    .settings-panel.open {
      transform: translateX(0);
    }
    
    .dark .settings-panel {
      background: #1f2937;
      color: #e5e7eb;
    }
    
    .settings-title {
      font-size: 1.25rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--green-dark);
    }
    
    .dark .settings-title {
      color: var(--green-light);
    }
    
    .settings-option {
      margin-bottom: 1rem;
    }
    
    .settings-label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .settings-control {
      width: 100%;
      padding: 0.5rem;
      border-radius: 0.375rem;
      border: 1px solid #d1d5db;
      background: white;
    }
    
    .dark .settings-control {
      background: #374151;
      border-color: #4b5563;
      color: #e5e7eb;
    }
    
    /* Estilos para el botón de configuración */
    .settings-button {
      position: fixed;
      top: 5rem;
      right: 1rem;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background: var(--green-dark);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 8px rgba(0,0,0,.15);
      cursor: pointer;
      transition: all 0.2s ease;
      z-index: 40;
    }
    
    .settings-button:hover {
      transform: scale(1.1);
      background: var(--green-light);
    }
    
    /* Estilos para el modo de lectura inmersiva */
    .immersive-mode {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: white;
      z-index: 70;
      padding: 2rem;
      overflow-y: auto;
      animation: fadeIn 0.3s ease-out;
    }
    
    .dark .immersive-mode {
      background: #111827;
      color: #e5e7eb;
    }
    
    .immersive-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .dark .immersive-header {
      border-bottom-color: #374151;
    }
    
    .immersive-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--green-dark);
    }
    
    .dark .immersive-title {
      color: var(--green-light);
    }
    
    .immersive-close {
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      background: #ef4444;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .immersive-close:hover {
      transform: scale(1.1);
      background: #dc2626;
    }
    
    .immersive-content {
      max-width: 42rem;
      margin: 0 auto;
      font-size: 1.25rem;
      line-height: 1.8;
    }
    
    .immersive-verse {
      margin-bottom: 1.5rem;
      padding: 1rem;
      border-radius: 0.5rem;
      transition: all 0.2s ease;
    }
    
    .immersive-verse:hover {
      background: rgba(138, 164, 70, 0.1);
    }
    
    .dark .immersive-verse:hover {
      background: rgba(138, 164, 70, 0.2);
    }
    
    .immersive-verse-number {
      font-weight: 700;
      color: var(--green-dark);
      margin-right: 0.5rem;
    }
    
    .dark .immersive-verse-number {
      color: var(--green-light);
    }
    
    /* Estilos para el copyright */
    .copyright-notice {
      font-size: 0.75rem;
      color: #6b7280;
      text-align: center;
      margin-top: 1rem;
      padding: 0.5rem;
      border-top: 1px solid #e5e7eb;
    }
    
    .dark .copyright-notice {
      color: #9ca3af;
      border-top-color: #374151;
    }
    
    .copyright-notice a {
      color: var(--green-dark);
      text-decoration: underline;
    }
    
    .dark .copyright-notice a {
      color: var(--green-light);
    }
    
    .copyright-notice .bible-version {
      font-weight: 600;
      color: var(--green-dark);
    }
    
    .dark .copyright-notice .bible-version {
      color: var(--green-light);
    }
    
    .copyright-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.25rem 0.5rem;
      background: rgba(138, 164, 70, 0.1);
      border-radius: 0.25rem;
      font-size: 0.7rem;
      font-weight: 600;
      color: var(--green-dark);
      margin-bottom: 0.5rem;
    }
    
    .dark .copyright-badge {
      background: rgba(138, 164, 70, 0.2);
      color: var(--green-light);
    }
    
    .immersive-copyright {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(5px);
      font-size: 0.75rem;
      color: #6b7280;
      text-align: center;
      z-index: 71;
    }
    
    .dark .immersive-copyright {
      background: rgba(17, 24, 39, 0.9);
      color: #9ca3af;
    }
    
    .verse-copyright {
      font-size: 0.7rem;
      color: #9ca3af;
      text-align: center;
      margin-top: 1rem;
      font-style: italic;
    }
    
    .dark .verse-copyright {
      color: #6b7280;
    }
  </style>
@endpush

@section('content')
<div class="page-container page-hero" x-data="biblia()" x-init="init()" x-cloak
     @keydown.window.arrow-left.prevent="focusMode && prevVerse()"
     @keydown.window.arrow-right.prevent="focusMode && nextVerse()"
     @keydown.window.escape="focusMode && exitFocus()"
     :class="{ 'reading-mode': readingMode }">

  <h1 class="page-title">Biblia</h1>
  <div class="hr-brand"></div>

  {{-- Panel de bienvenida --}}
  <template x-if="!libro && !q">
    <div class="welcome-panel">
      <h2 class="welcome-title">Bienvenido a la Biblia Online</h2>
      <p class="welcome-text">Explora la Palabra de Dios con nuestra herramienta de lectura bíblica. Puedes buscar por libro, capítulo o versículo, o simplemente navegar por los diferentes libros de la Biblia.</p>
      <div class="welcome-verse" x-text="startVerses.length ? startVerses[0].t : ''"></div>
      <div class="welcome-reference" x-text="startVerses.length ? `${startPretty}:${startVerses[0].n}` : ''"></div>
      <button class="action-button" @click="showBookSelector = true">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
        Explorar la Biblia
      </button>
      
      {{-- Copyright en panel de bienvenida --}}
      <div class="copyright-badge mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Versión Reina-Valera 1960
      </div>
    </div>
  </template>

  {{-- Selector de libros --}}
  <div class="panel" x-show="showBookSelector" x-transition>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold text-brand-dark dark:text-brand-light">Selecciona un libro</h2>
      <button class="chip" @click="showBookSelector = false">Cerrar</button>
    </div>
    <div class="book-selector">
      <template x-for="b in libros" :key="b.slug">
        <div class="book-option" 
             :class="{ 'selected': libro === b.slug }"
             @click="selectBook(b.slug)"
             x-text="b.name"></div>
      </template>
    </div>
    
    {{-- Copyright en selector de libros --}}
    <div class="copyright-notice mt-4">
      <span class="bible-version">Biblia Reina-Valera 1960</span> - 
      Copyright © 1960 by American Bible Society (<a href="http://www.americanbible.org" target="_blank" rel="noopener">www.americanbible.org</a>)
    </div>
  </div>

  {{-- Selector de capítulos --}}
  <div class="panel" x-show="showChapterSelector && libro" x-transition>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold text-brand-dark dark:text-brand-light">
        <span x-text="libros.find(b => b.slug === libro)?.name || ''"></span> - Selecciona un capítulo
      </h2>
      <button class="chip" @click="showChapterSelector = false">Cerrar</button>
    </div>
    <div class="chapter-selector">
      <template x-for="c in caps" :key="c">
        <div class="chapter-option" 
             :class="{ 'selected': cap === String(c) }"
             @click="selectChapter(String(c))"
             x-text="c"></div>
      </template>
    </div>
    
    {{-- Copyright en selector de capítulos --}}
    <div class="copyright-notice mt-4">
      <span class="bible-version">Biblia Reina-Valera 1960</span> - 
      Copyright © 1960 by American Bible Society (<a href="http://www.americanbible.org" target="_blank" rel="noopener">www.americanbible.org</a>)
    </div>
  </div>

  {{-- Controles principales --}}
  <div class="panel space-y-4" x-show="!showBookSelector && !showChapterSelector">
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
      <div class="flex items-end gap-2">
        <button class="btn-brand" @click="copiarRefActual()" :disabled="!libro || !cap">
          Copiar referencia
        </button>
        <button class="chip" @click="showBookSelector = true" :disabled="libros.length === 0">
          Ver todos
        </button>
      </div>
    </div>

    {{-- Buscador --}}
    <div class="mt-4">
      <label class="block text-sm font-medium mb-1">Buscar en toda la Biblia</label>
      <div class="relative">
        <input type="search"
               x-model="q"
               @input="onSearchInput"
               placeholder="Ej: fe, amor, no temas…"
               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 pr-10" />
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>
      <div class="mt-1 text-sm">
        <span class="text-gray-500 dark:text-gray-400" x-show="q.length && !cargandoBusqueda && !errorBusqueda">
          <span x-text="resultado.total"></span> resultados para "<span x-text="resultado.q"></span>".
        </span>
        <span class="text-brand-dark dark:text-brand-light" x-show="cargandoBusqueda">Cargando…</span>
        <span class="text-red-600 dark:text-red-400" x-show="errorBusqueda" x-text="errorBusqueda"></span>
      </div>
    </div>

    {{-- Opciones de visualización --}}
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <button class="chip" @click="toggleReadingMode" :class="{ 'bg-brand-dark text-white': readingMode }">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          Modo lectura
        </button>
        <button class="chip" @click="togglePagination" :class="{ 'bg-brand-dark text-white': usePagination }" x-show="versiculos.length > 20">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2" />
          </svg>
          Paginación
        </button>
      </div>
      <div class="flex items-center gap-2">
        <label class="text-sm font-medium">Tamaño de fuente:</label>
        <input type="range" min="14" max="24" x-model="fontSize" @input="updateFontSize" class="w-24">
      </div>
    </div>
  </div>

  {{-- Lector de capítulo --}}
  <template x-if="versiculos.length && !q">
    <div class="panel mt-6">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-xl font-semibold text-brand-dark dark:text-brand-light" x-text="tituloCap"></h2>
        <div class="text-sm text-gray-500 dark:text-gray-400" x-show="versiculos.length">
          <span x-text="versiculos.length"></span> versículos
        </div>
      </div>
      
      <!-- Indicador de progreso -->
      <div class="progress-indicator">
        <div class="progress-bar" :style="`width: ${progressPercentage}%`"></div>
      </div>
      
      <!-- Lista de versículos -->
      <div class="verse-list">
        <template x-for="v in displayVerses" :key="v.n">
          <li :id="'v-' + v.n" class="verse-item scroll-mt-24">
            <div class="flex items-start">
              <span class="verse-number" x-text="v.n + '.'"></span>
              <div class="verse-content" x-html="v.t"></div>
            </div>
            <div class="flex gap-2 mt-2">
              <button class="chip text-xs" @click="enterFocusByNumber(v.n)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Enfocar
              </button>
              <button class="chip text-xs" @click="copiar(`${tituloCap}:${v.n} ${v.t}`)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Copiar
              </button>
            </div>
          </li>
        </template>
      </div>
      
      <!-- Paginación -->
      <div class="pagination" x-show="usePagination && pagination.total_pages > 1">
        <button @click="goToPage(pagination.prev_page)" :disabled="!pagination.has_prev">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        
        <template x-for="page in paginationPages" :key="page">
          <button @click="goToPage(page)" 
                  :class="{ 'current-page': page === pagination.current_page }"
                  x-text="page"></button>
        </template>
        
        <button @click="goToPage(pagination.next_page)" :disabled="!pagination.has_next">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
      
      {{-- Copyright en lector de capítulo --}}
      <div class="copyright-notice">
        <span class="bible-version">Biblia Reina-Valera 1960</span> - 
        Copyright © 1960 by American Bible Society (<a href="http://www.americanbible.org" target="_blank" rel="noopener">www.americanbible.org</a>)
      </div>
    </div>
  </template>

  {{-- Resultados de búsqueda --}}
  <template x-if="q && !cargandoBusqueda && !errorBusqueda">
    <div class="panel mt-6" x-show="resultado.q && Array.isArray(resultado.results)">
      <h3 class="text-lg font-semibold mb-2">Resultados de búsqueda</h3>
      <template x-if="resultado.results.length === 0">
        <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron resultados para "<span x-text="resultado.q"></span>".</p>
      </template>
      <ul class="space-y-3" x-show="resultado.results.length">
        <template x-for="r in resultado.results" :key="r.ref + '-' + r.verse">
          <li class="border-b border-black/10 dark:border-white/10 pb-3">
            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="r.ref"></div>
            <div class="my-2" x-html="r.highlighted"></div>
            <div class="text-xs mt-1 text-gray-500 dark:text-gray-400" x-text="r.snippet"></div>
            <div class="mt-2 flex gap-2">
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
      
      {{-- Copyright en resultados de búsqueda --}}
      <div class="copyright-notice mt-4">
        <span class="bible-version">Biblia Reina-Valera 1960</span> - 
        Copyright © 1960 by American Bible Society (<a href="http://www.americanbible.org" target="_blank" rel="noopener">www.americanbible.org</a>)
      </div>
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
            <button class="chip" @click="copiar(focusRef() + ' ' + focusVerse()?.t)">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
              Copiar
            </button>
            <button class="chip" @click="scrollToFocused()">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Ver en lista
            </button>
            <button class="chip" @click="toggleImmersiveMode">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
              </svg>
              Modo inmersivo
            </button>
            <button class="chip" @click="exitFocus()">Cerrar</button>
          </div>
        </div>

        <div class="verse-text" x-html="focusVerse()?.t"></div>
        
        {{-- Copyright en visor centrado --}}
        <div class="verse-copyright">
          <span class="bible-version">Biblia Reina-Valera 1960</span> - 
          Copyright © 1960 by American Bible Society
        </div>
      </div>
    </div>
  </template>

  {{-- MODO INMERSIVO --}}
  <template x-if="immersiveMode">
    <div class="immersive-mode">
      <div class="immersive-header">
        <h2 class="immersive-title" x-text="tituloCap"></h2>
        <button class="immersive-close" @click="toggleImmersiveMode">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <div class="immersive-content">
        <template x-for="v in versiculos" :key="v.n">
          <div class="immersive-verse" :id="'immersive-v-' + v.n">
            <span class="immersive-verse-number" x-text="v.n + '.'"></span>
            <span x-html="v.t"></span>
          </div>
        </template>
      </div>
      
      {{-- Copyright en modo inmersivo --}}
      <div class="immersive-copyright">
        <span class="bible-version">Biblia Reina-Valera 1960</span> - 
        Copyright © 1960 by American Bible Society (<a href="http://www.americanbible.org" target="_blank" rel="noopener">www.americanbible.org</a>)
      </div>
    </div>
  </template>

  {{-- BOTÓN FLOTANTE DE LECTURA --}}
  <div class="floating-reading-button" x-show="libro && cap" @click="toggleImmersiveMode">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
    </svg>
  </div>

  {{-- PANEL DE CONFIGURACIÓN --}}
  <div class="settings-panel" :class="{ 'open': showSettings }">
    <h3 class="settings-title">Configuración de lectura</h3>
    
    <div class="settings-option">
      <label class="settings-label">Tamaño de fuente</label>
      <input type="range" min="14" max="24" x-model="fontSize" @input="updateFontSize" class="settings-control">
    </div>
    
    <div class="settings-option">
      <label class="settings-label">Tipo de fuente</label>
      <select x-model="fontFamily" @change="updateFontFamily" class="settings-control">
        <option value="font-sans">Sans Serif</option>
        <option value="font-serif">Serif</option>
        <option value="font-mono">Monospace</option>
      </select>
    </div>
    
    <div class="settings-option">
      <label class="settings-label">Espaciado de línea</label>
      <select x-model="lineHeight" @change="updateLineHeight" class="settings-control">
        <option value="leading-relaxed">Relajado</option>
        <option value="leading-normal">Normal</option>
        <option value="leading-snug">Compacto</option>
      </select>
    </div>
    
    <div class="settings-option">
      <label class="settings-label">Tema</label>
      <select x-model="theme" @change="updateTheme" class="settings-control">
        <option value="light">Claro</option>
        <option value="dark">Oscuro</option>
        <option value="sepia">Sepia</option>
      </select>
    </div>
    
    {{-- Copyright en panel de configuración --}}
    <div class="copyright-notice mt-4">
      <span class="bible-version">Biblia Reina-Valera 1960</span> - 
      Copyright © 1960 by American Bible Society
    </div>
  </div>

  {{-- BOTÓN DE CONFIGURACIÓN --}}
  <div class="settings-button" @click="showSettings = !showSettings">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
  </div>

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
    
    // Estado de la UI
    showBookSelector: false,
    showChapterSelector: false,
    readingMode: false,
    usePagination: false,
    fontSize: 16,
    fontFamily: 'font-sans',
    lineHeight: 'leading-relaxed',
    theme: 'light',
    showSettings: false,
    immersiveMode: false,
    
    // Paginación
    pagination: {
      current_page: 1,
      total_pages: 1,
      total_verses: 0,
      per_page: 20,
      has_prev: false,
      has_next: false,
      prev_page: null,
      next_page: null,
    },
    
    // Versículos iniciales
    startVerses: [],
    startPretty: '',
    
    // Versículos a mostrar (con paginación si está activa)
    get displayVerses() {
      if (this.usePagination) {
        return this.versiculos;
      }
      return this.versiculos;
    },
    
    // Páginas de paginación a mostrar
    get paginationPages() {
      const current = this.pagination.current_page;
      const total = this.pagination.total_pages;
      const delta = 2; // Número de páginas a mostrar antes y después de la actual
      
      let range = [];
      let rangeWithDots = [];
      let l;

      for (let i = 1; i <= total; i++) {
        if (i == 1 || i == total || (i >= current - delta && i <= current + delta)) {
          range.push(i);
        }
      }

      range.forEach((i) => {
        if (l) {
          if (i - l === 2) {
            rangeWithDots.push(l + 1);
          } else if (i - l !== 1) {
            rangeWithDots.push('...');
          }
        }
        rangeWithDots.push(i);
        l = i;
      });

      return rangeWithDots;
    },
    
    // Porcentaje de progreso de lectura
    get progressPercentage() {
      if (!this.versiculos.length) return 0;
      return Math.round((this.focusIndex + 1) / this.versiculos.length * 100);
    },
    
    // Debouncer
    _searchTimer: null,
    onSearchInput(e){
      const v = (e.target.value || '').trim();
      this.q = v;
      this.errorBusqueda = '';
      clearTimeout(this._searchTimer);
      if (!v || v.length < 2) { 
        this.resultado = {q:v, total:0, results:[]}; 
        return; 
      }
      this.cargandoBusqueda = true;
      this._searchTimer = setTimeout(() => this.buscar(), 400);
    },

    async init() {
      await this.cargarLibros();
      await this.cargarInicio();
      this.parseHash();
      this.loadSettings();
    },
    
    // Cargar los versículos iniciales
    async cargarInicio() {
      try {
        const res = await fetch('{{ route("biblia.api.start") }}');
        const data = await res.json();
        this.startVerses = data.verses || [];
        this.startPretty = data.pretty || '';
      } catch (err) {
        console.error('Error al cargar versículos iniciales', err);
      }
    },
    
    // Cargar configuración guardada
    loadSettings() {
      const settings = localStorage.getItem('biblia-settings');
      if (settings) {
        try {
          const parsed = JSON.parse(settings);
          this.fontSize = parsed.fontSize || 16;
          this.fontFamily = parsed.fontFamily || 'font-sans';
          this.lineHeight = parsed.lineHeight || 'leading-relaxed';
          this.theme = parsed.theme || 'light';
          this.readingMode = parsed.readingMode || false;
          this.usePagination = parsed.usePagination || false;
          
          this.applySettings();
        } catch (err) {
          console.error('Error al cargar configuración', err);
        }
      }
    },
    
    // Guardar configuración
    saveSettings() {
      const settings = {
        fontSize: this.fontSize,
        fontFamily: this.fontFamily,
        lineHeight: this.lineHeight,
        theme: this.theme,
        readingMode: this.readingMode,
        usePagination: this.usePagination,
      };
      localStorage.setItem('biblia-settings', JSON.stringify(settings));
    },
    
    // Aplicar configuración
    applySettings() {
      this.updateFontSize();
      this.updateFontFamily();
      this.updateLineHeight();
      this.updateTheme();
    },
    
    // Actualizar tamaño de fuente
    updateFontSize() {
      document.documentElement.style.setProperty('--verse-font-size', `${this.fontSize}px`);
      this.saveSettings();
    },
    
    // Actualizar tipo de fuente
    updateFontFamily() {
      const verseElements = document.querySelectorAll('.verse-content, .verse-text, .immersive-content');
      verseElements.forEach(el => {
        el.className = el.className.replace(/font-\w+/g, '');
        el.classList.add(this.fontFamily);
      });
      this.saveSettings();
    },
    
    // Actualizar espaciado de línea
    updateLineHeight() {
      const verseElements = document.querySelectorAll('.verse-content, .verse-text, .immersive-content');
      verseElements.forEach(el => {
        el.className = el.className.replace(/leading-\w+/g, '');
        el.classList.add(this.lineHeight);
      });
      this.saveSettings();
    },
    
    // Actualizar tema
    updateTheme() {
      if (this.theme === 'dark') {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
      
      if (this.theme === 'sepia') {
        document.documentElement.style.setProperty('--bg-color', '#f7f3e9');
        document.documentElement.style.setProperty('--text-color', '#5c4b37');
      } else {
        document.documentElement.style.removeProperty('--bg-color');
        document.documentElement.style.removeProperty('--text-color');
      }
      
      this.saveSettings();
    },
    
    // Toggle modo de lectura
    toggleReadingMode() {
      this.readingMode = !this.readingMode;
      this.saveSettings();
    },
    
    // Toggle paginación
    togglePagination() {
      this.usePagination = !this.usePagination;
      if (this.usePagination && this.versiculos.length > 20) {
        this.loadPaginatedChapter(1);
      }
      this.saveSettings();
    },
    
    // Toggle modo inmersivo
    toggleImmersiveMode() {
      this.immersiveMode = !this.immersiveMode;
      if (this.immersiveMode) {
        this.focusMode = false;
      }
    },
    
    // Seleccionar libro
    selectBook(slug) {
      this.libro = slug;
      this.showBookSelector = false;
      this.cargarCapitulos().then(() => {
        this.showChapterSelector = true;
      });
    },
    
    // Seleccionar capítulo
    selectChapter(chapter) {
      this.cap = chapter;
      this.showChapterSelector = false;
      this.cargarCapitulo();
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
          this.cargarCapitulo().then(() => { 
            if (ver) this.enterFocusByNumber(+ver) 
          });
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
      
      if (this.usePagination) {
        await this.loadPaginatedChapter(1);
      } else {
        const res = await fetch(`{{ url('/biblia/api') }}/${this.libro}/${this.cap}`);
        const data = await res.json();
        this.versiculos = data.verses ?? [];
        this.tituloCap  = data.pretty ?? '';
      }
      
      // Actualiza hash legible: #Juan-3
      const libroObj = this.libros.find(b => b.slug === this.libro);
      const hash = (libroObj?.name || this.libro).replaceAll(' ', '-') + '-' + this.cap;
      history.replaceState({}, '', '#' + encodeURIComponent(hash));
    },
    
    // Cargar capítulo con paginación
    async loadPaginatedChapter(page = 1) {
      if (!this.libro || !this.cap) return;
      
      const res = await fetch(`{{ url('/biblia/api') }}/${this.libro}/${this.cap}/page/${page}`);
      const data = await res.json();
      
      this.versiculos = data.verses ?? [];
      this.tituloCap = data.pretty ?? '';
      this.pagination = data.pagination ?? this.pagination;
    },
    
    // Ir a una página específica
    goToPage(page) {
      if (page === '...' || page === this.pagination.current_page) return;
      this.loadPaginatedChapter(page);
    },

    // ----- Buscador (con manejo de errores) -----
    async buscar() {
      try {
        const q = this.q.trim();
        if (!q || q.length < 2) { 
          this.resultado = {q, total:0, results:[]}; 
          this.cargandoBusqueda=false; 
          return; 
        }
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
      if (this.hasPrevLocal()) { 
        this.focusIndex--; 
        this.centerListOnFocused(); 
        return; 
      }
      if (await this.goToPrevChapter()) { 
        this.focusIndex = this.versiculos.length - 1; 
        this.centerListOnFocused(); 
        return; 
      }
      if (await this.goToPrevBook()) { 
        this.focusIndex = this.versiculos.length - 1; 
        this.centerListOnFocused(); 
      }
    },
    async nextVerse(){
      if (this.hasNextLocal()) { 
        this.focusIndex++; 
        this.centerListOnFocused(); 
        return; 
      }
      if (await this.goToNextChapter()) { 
        this.focusIndex = 0; 
        this.centerListOnFocused(); 
        return; 
      }
      if (await this.goToNextBook()) { 
        this.focusIndex = 0; 
        this.centerListOnFocused(); 
      }
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
        // Mostrar notificación en lugar de alerta
        this.showNotification('Copiado al portapapeles');
      });
    },
    
    // Mostrar notificación
    showNotification(message, type = 'success') {
      // Crear elemento de notificación
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      
      // Añadir al DOM
      document.body.appendChild(notification);
      
      // Animación de entrada
      setTimeout(() => {
        notification.classList.add('show');
      }, 10);
      
      // Eliminar después de 3 segundos
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    },
    
    irA(r) { 
      this.scrollToVerse(r.verse); 
    },
  }
}
</script>
@endsection