@extends('layouts.main')

@section('title', 'Biblia')

@push('head')
  <style>
    [x-cloak]{display:none!important}
    
    /* Variables CSS personalizadas */
    :root {
      --green-dark: #2d5016;
      --green-light: #8aa446;
      --verse-font-size: 16px;
      --bg-color: #ffffff;
      --text-color: #111827;
      --verse-font-family: inherit;
      --verse-line-height: 1.6;
    }
    
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
      padding: 2rem 4.5rem;
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
      font-size: var(--verse-font-size, 1.25rem); 
      font-family: var(--verse-font-family, inherit);
      line-height: var(--verse-line-height, 1.8);
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
      /* Aplicar variables CSS */
      font-family: var(--verse-font-family, inherit);
      line-height: var(--verse-line-height, 1.6);
    }
    
    /* Estilos para el modo de lectura */
    .reading-mode {
      max-width: 42rem;
      margin: 0 auto;
    }
    
    .reading-mode .verse-item {
      padding: 1.5rem;
    }
    
    /* Estilos para el modo de lectura inmersiva */
    .immersive-content {
      max-width: 42rem;
      margin: 0 auto;
      /* Aplicar variables CSS */
      font-size: var(--verse-font-size, 1.25rem);
      font-family: var(--verse-font-family, inherit);
      line-height: var(--verse-line-height, 1.8);
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
      gap: 1.5rem;
      margin-top: 1rem;
    }
    
    .testament-section {
      margin-bottom: 1.5rem;
    }
    
    .testament-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--green-dark);
      margin-bottom: 0.75rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--green-light);
    }
    
    .dark .testament-title {
      color: var(--green-light);
    }
    
    .books-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 0.75rem;
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
    
    /* Estilos para estadísticas de búsqueda */
    .search-stats {
      background: #f9fafb;
      border-radius: 0.75rem;
      padding: 1rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
    }
    
    .dark .search-stats {
      background: #1f2937;
    }
    
    .stats-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--green-dark);
      margin-bottom: 0.75rem;
    }
    
    .dark .stats-title {
      color: var(--green-light);
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
    }
    
    .stat-item {
      text-align: center;
      padding: 0.5rem;
      border-radius: 0.5rem;
      background: white;
    }
    
    .dark .stat-item {
      background: #374151;
    }
    
    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--green-dark);
    }
    
    .dark .stat-value {
      color: var(--green-light);
    }
    
    .stat-label {
      font-size: 0.875rem;
      color: #6b7280;
    }
    
    .dark .stat-label {
      color: #9ca3af;
    }
    
    /* Estilos para resultados de búsqueda paginados */
    .search-results {
      margin-top: 1.5rem;
    }
    
    .result-item {
      padding: 1rem;
      border-radius: 0.75rem;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
      margin-bottom: 1rem;
      transition: all 0.2s ease;
    }
    
    .result-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,.1);
    }
    
    .dark .result-item {
      background: #1f2937;
      color: #e5e7eb;
    }
    
    .result-reference {
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--green-dark);
      margin-bottom: 0.5rem;
    }
    
    .dark .result-reference {
      color: var(--green-light);
    }
    
    .result-text {
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 0.5rem;
    }
    
    .result-context {
      font-size: 0.875rem;
      color: #6b7280;
      font-style: italic;
    }
    
    .dark .result-context {
      color: #9ca3af;
    }
    
    .result-actions {
      display: flex;
      gap: 0.5rem;
      margin-top: 0.75rem;
    }
    
    /* Estilos para notificaciones */
    .notification {
      position: fixed;
      bottom: 1rem;
      left: 50%;
      transform: translateX(-50%) translateY(100px);
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      background: var(--green-dark);
      color: white;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0,0,0,.2);
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 100;
    }
    
    .notification.show {
      transform: translateX(-50%) translateY(0);
      opacity: 1;
    }
    
    .notification.error {
      background: #ef4444;
    }
    
    /* Estilos para el visor de versículo individual */
    .single-verse-viewer {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 70;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      padding: 1rem;
      animation: fadeIn 0.3s ease-out;
    }
    
    .single-verse-card {
      max-width: 60rem;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      border-radius: 1.5rem;
      background: white;
      color: #111827;
      box-shadow: 0 20px 40px rgba(0,0,0,.3);
      animation: slideUp 0.3s ease-out;
      position: relative;
    }
    
    .dark .single-verse-card {
      background: #111827;
      color: #e5e7eb;
    }
    
    .single-verse-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 2rem;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .dark .single-verse-header {
      border-bottom-color: #374151;
    }
    
    .single-verse-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--green-dark);
    }
    
    .dark .single-verse-title {
      color: var(--green-light);
    }
    
    .single-verse-close {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background: #ef4444;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .single-verse-close:hover {
      transform: scale(1.1);
      background: #dc2626;
    }
    
    .single-verse-content {
      padding: 2rem;
      font-size: 1.5rem;
      line-height: 1.8;
      text-align: justify;
    }
    
    .single-verse-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 2rem;
      border-top: 1px solid #e5e7eb;
    }
    
    .dark .single-verse-footer {
      border-top-color: #374151;
    }
    
    .single-verse-navigation {
      display: flex;
      gap: 1rem;
    }
    
    .single-verse-actions {
      display: flex;
      gap: 1rem;
    }
    
    /* Estilos responsive */
    @media (max-width: 768px) {
      .verse-card {
        padding: 1.5rem 3.5rem;
      }
      
      .nav-arrow {
        width: 2.5rem;
        height: 2.5rem;
      }
      
      .nav-left {
        left: 0.5rem;
      }
      
      .nav-right {
        right: 0.5rem;
      }
      
      .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      }
      
      .chapter-selector {
        gap: 0.25rem;
      }
      
      .chapter-option {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 0.875rem;
      }
      
      .settings-panel {
        width: 90%;
        right: 5%;
      }
      
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .single-verse-card {
        max-width: 95%;
      }
      
      .single-verse-header,
      .single-verse-content,
      .single-verse-footer {
        padding: 1rem;
      }
      
      .single-verse-content {
        font-size: 1.25rem;
      }
    /* Estilos de subrayados personalizados */
    .hl-yellow { background-color: rgba(254, 240, 138, 0.5); color: #111827; border-bottom: 2px solid #eab308; padding: 2px 4px; border-radius: 4px; display: inline; }
    .hl-green { background-color: rgba(220, 252, 231, 0.5); color: #111827; border-bottom: 2px solid #22c55e; padding: 2px 4px; border-radius: 4px; display: inline; }
    .hl-blue { background-color: rgba(219, 234, 254, 0.5); color: #111827; border-bottom: 2px solid #3b82f6; padding: 2px 4px; border-radius: 4px; display: inline; }
    .hl-red { background-color: rgba(254, 226, 226, 0.5); color: #111827; border-bottom: 2px solid #ef4444; padding: 2px 4px; border-radius: 4px; display: inline; }
    .hl-orange { background-color: rgba(255, 237, 213, 0.5); color: #111827; border-bottom: 2px solid #f97316; padding: 2px 4px; border-radius: 4px; display: inline; }

    .dark .hl-yellow { background-color: rgba(234, 179, 8, 0.25); color: #fef08a; border-bottom: 2px solid #facc15; }
    .dark .hl-green { background-color: rgba(34, 197, 94, 0.25); color: #bbf7d0; border-bottom: 2px solid #4ade80; }
    .dark .hl-blue { background-color: rgba(59, 130, 246, 0.25); color: #bfdbfe; border-bottom: 2px solid #60a5fa; }
    .dark .hl-red { background-color: rgba(239, 68, 68, 0.25); color: #fecaca; border-bottom: 2px solid #f87171; }
    .dark .hl-orange { background-color: rgba(249, 115, 22, 0.25); color: #fed7aa; border-bottom: 2px solid #fb923c; }
    
    /* Círculos de selección de colores */
    .color-dot {
      width: 1rem;
      height: 1rem;
      border-radius: 50%;
      border: 1px solid rgba(0, 0, 0, 0.25);
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .color-dot:hover {
      transform: scale(1.25);
      box-shadow: 0 0 6px rgba(0,0,0,0.3);
    }
    .color-dot-lg {
      width: 1.25rem;
      height: 1.25rem;
    }
      }
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
  <template x-if="!libro && !q && !singleVerseMode">
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
      <template x-for="testament in testamentBooks" :key="testament.name">
        <div class="testament-section">
          <h3 class="testament-title" x-text="testament.name"></h3>
          <div class="books-grid">
            <template x-for="book in testament.books" :key="book.slug">
              <div class="book-option" 
                   :class="{ 'selected': libro === book.slug }"
                   @click="selectBook(book.slug)"
                   x-text="book.name"></div>
            </template>
          </div>
        </div>
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
  <div class="panel space-y-4" x-show="!showBookSelector && !showChapterSelector && !singleVerseMode">
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
        <input type="text"
               x-model="q"
               @input="onSearchInput"
               placeholder="Ej: dios amor, 'tez brillante', esperanza"
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
      
      <!-- Búsquedas Frecuentes -->
      <div class="mt-3" x-show="searchHistory.length > 0">
        <div class="flex justify-between items-center mb-1.5">
          <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Búsquedas frecuentes:
          </span>
          <button class="text-[10px] text-red-500 hover:underline" @click="clearSearchHistory()">Limpiar</button>
        </div>
        <div class="flex flex-wrap gap-1.5">
          <template x-for="item in searchHistory" :key="item.term">
            <button class="chip text-xs bg-gray-100 hover:bg-brand-light dark:bg-gray-800 dark:hover:bg-brand-dark flex items-center gap-1.5"
                    @click="q = item.term; buscar()">
              <span x-text="item.term"></span>
              <span class="badge bg-brand-dark dark:bg-brand-light text-white text-[9px] px-1.5 rounded-full" x-text="item.count"></span>
            </button>
          </template>
        </div>
      </div>
    </div>

    {{-- Opciones de visualización --}}
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2 flex-wrap">
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
        
        <!-- Botón de descarga offline -->
        <button class="chip flex items-center gap-1.5" @click="preloadBible()" :disabled="preloadStatus === 'loading'" :class="{ 'bg-blue-600 text-white': preloadStatus === 'success', 'bg-yellow-500 text-black': preloadStatus === 'loading' }">
          <template x-if="preloadStatus === 'idle'">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Descargar Biblia Offline
            </span>
          </template>
          <template x-if="preloadStatus === 'loading'">
            <span class="flex items-center gap-1">
              <svg class="animate-spin h-3 w-3 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              Descargando... <span x-text="preloadPercentage + '%'"></span>
            </span>
          </template>
          <template x-if="preloadStatus === 'success'">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Biblia Descargada
            </span>
          </template>
        </button>
      </div>
      <div class="flex items-center gap-2">
        <label class="text-sm font-medium">Tamaño de fuente:</label>
        <input type="range" min="14" max="24" x-model="fontSize" @input="updateFontSize" class="w-24">
      </div>
    </div>
  </div>

  {{-- Lector de capítulo --}}
  <template x-if="versiculos.length && !q && !singleVerseMode">
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
              <div class="verse-content" x-html="formatVerse(v.t, libro + '-' + cap + '-' + v.n)"></div>
            </div>
            <div class="flex flex-wrap items-center gap-2 mt-2">
              <button class="chip text-xs" @click="enterFocusByNumber(v.n)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Enfocar
              </button>
              <button class="chip text-xs" @click="showSingleVerse(libro, cap, v.n)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver solo
              </button>
              <button class="chip text-xs" @click="copiar(`${tituloCap}:${v.n} ${v.t}`)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Copiar
              </button>
              
              <!-- Paleta de colores para subrayar -->
              <div class="flex items-center gap-1.5 sm:ml-auto border-l pl-2 border-gray-200 dark:border-gray-700">
                <button class="color-dot bg-yellow-400" title="Cuando Dios habla (Amarillo)" @click="setHighlight(libro, cap, v.n, 'yellow')"></button>
                <button class="color-dot bg-green-400" title="Promesa (Verde)" @click="setHighlight(libro, cap, v.n, 'green')"></button>
                <button class="color-dot bg-blue-400" title="Pueblo de tez brillante (Azul)" @click="setHighlight(libro, cap, v.n, 'blue')"></button>
                <button class="color-dot bg-red-400" title="Mandato (Rojo)" @click="setHighlight(libro, cap, v.n, 'red')"></button>
                <button class="color-dot bg-orange-400" title="Se refiere al Mesías (Naranja)" @click="setHighlight(libro, cap, v.n, 'orange')"></button>
                <button class="w-4 h-4 rounded-full bg-gray-200 dark:bg-gray-600 border border-gray-400 flex items-center justify-center text-[10px] hover:scale-125 transition cursor-pointer" title="Quitar subrayado" @click="setHighlight(libro, cap, v.n, null)">×</button>
              </div>
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
  <template x-if="q && !cargandoBusqueda && !errorBusqueda && !singleVerseMode">
    <div class="panel mt-6" x-show="resultado.q && Array.isArray(resultado.results)">
      <h3 class="text-lg font-semibold mb-2">Resultados de búsqueda</h3>
      
      <!-- Estadísticas de búsqueda -->
      <div class="search-stats" x-show="resultado.stats">
        <h4 class="stats-title">Estadísticas de búsqueda</h4>
        <div class="stats-grid">
          <div class="stat-item">
            <div class="stat-value" x-text="resultado.stats.total_results || 0"></div>
            <div class="stat-label">Resultados totales</div>
          </div>
          <div class="stat-item">
            <div class="stat-value" x-text="resultado.stats.books_count || 0"></div>
            <div class="stat-label">Libros encontrados</div>
          </div>
          <div class="stat-item">
            <div class="stat-value" x-text="resultado.stats.old_testament || 0"></div>
            <div class="stat-label">Antiguo Testamento</div>
          </div>
          <div class="stat-item">
            <div class="stat-value" x-text="resultado.stats.new_testament || 0"></div>
            <div class="stat-label">Nuevo Testamento</div>
          </div>
        </div>
      </div>
      
      <!-- Coincidencia exacta -->
      <div x-show="resultado.exact_match" class="mb-4 p-4 border border-brand-light bg-brand-light/10 dark:bg-brand-dark/20 rounded-lg">
        <h4 class="text-md font-bold text-brand-dark dark:text-brand-light flex items-center gap-1.5">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
          </svg>
          Coincidencia Exacta: <span x-text="resultado.exact_match?.pretty"></span>
        </h4>
        <div class="mt-2 text-md italic" x-html="formatVerse(resultado.exact_match?.text, resultado.exact_match?.book + '-' + resultado.exact_match?.chapter + '-' + resultado.exact_match?.verse)"></div>
        <div class="mt-3 flex gap-2">
          <button class="chip text-xs" @click="abrir(resultado.exact_match, true)">Ir a la lectura</button>
          <button class="chip text-xs" @click="showSingleVerse(resultado.exact_match?.book, resultado.exact_match?.chapter, resultado.exact_match?.verse || 1)">Ver solo</button>
          <button class="chip text-xs" @click="copiar(resultado.exact_match?.pretty + ' ' + resultado.exact_match?.text)">Copiar</button>
        </div>
      </div>

      <template x-if="resultado.results.length === 0 && !resultado.exact_match">
        <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron resultados para "<span x-text="resultado.q"></span>".</p>
      </template>
      
      <!-- Resultados paginados -->
      <div class="search-results" x-show="resultado.results.length">
        <template x-for="(r, index) in resultado.results" :key="r.ref + '-' + r.verse">
          <div class="result-item">
            <div class="result-reference">
              <span x-text="r.ref"></span>
              <span class="ml-2 text-xs text-gray-500">Resultado #<span x-text="(resultado.pagination.current_page - 1) * resultado.pagination.per_page + index + 1"></span></span>
            </div>
            <div class="result-text" x-html="formatSearchVerse(r.highlighted, r.book + '-' + r.chapter + '-' + r.verse)"></div>
            <div class="result-context" x-text="r.snippet"></div>
            <div class="result-actions">
              <a class="content-link" :href="`#v-${r.verse}`"
                 @click.prevent="abrir(r, true)"
                 x-show="libro===r.book && +cap===r.chapter">
                 Enfocar este versículo
              </a>
              <button class="chip" @click="showSingleVerse(r.book, r.chapter, r.verse)">Ver solo</button>
              <button class="chip" @click="abrir(r, true)">Abrir</button>
              <button class="chip" @click="copiar(r.ref + ' ' + r.text)">Copiar</button>
            </div>
          </div>
        </template>
        
        <!-- Paginación de resultados -->
        <div class="pagination" x-show="resultado.pagination.total_pages > 1">
          <button @click="goToSearchPage(resultado.pagination.prev_page)" :disabled="!resultado.pagination.has_prev">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          
          <template x-for="page in searchPaginationPages" :key="page">
            <button @click="goToSearchPage(page)" 
                    :class="{ 'current-page': page === resultado.pagination.current_page }"
                    x-text="page"></button>
          </template>
          
          <button @click="goToSearchPage(resultado.pagination.next_page)" :disabled="!resultado.pagination.has_next">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
      
      {{-- Copyright en resultados de búsqueda --}}
      <div class="copyright-notice mt-4">
        <span class="bible-version">Biblia Reina-Valera 1960</span> - 
        Copyright © 1960 by American Bible Society (<a href="http://www.americanbible.org" target="_blank" rel="noopener">www.americanbible.org</a>)
      </div>
    </div>
  </template>

  {{-- VISOR CENTRADO DE VERSÍCULO --}}
  <template x-if="focusMode && !singleVerseMode">
    <div class="verse-overlay" @click.self="exitFocus()">
      <div class="verse-card relative">
        <button class="nav-arrow nav-left" @click="prevVerse()" :disabled="!hasPrevAll()"
                :aria-disabled="!hasPrevAll()" title="Anterior">‹</button>
        <button class="nav-arrow nav-right" @click="nextVerse()" :disabled="!hasNextAll()"
                :aria-disabled="!hasNextAll()" title="Siguiente">›</button>

        <div class="flex items-center justify-between mb-3 gap-3 flex-wrap">
          <div class="ref-chip" x-text="focusRef()"></div>
          <div class="flex gap-2 flex-wrap items-center">
            <!-- Paleta de colores para subrayar en visor centrado -->
            <div class="flex items-center gap-1 border-r pr-2 border-gray-200 dark:border-gray-700">
              <button class="color-dot bg-yellow-400 color-dot-lg" title="Cuando Dios habla (Amarillo)" @click="setHighlight(libro, cap, focusVerse()?.n, 'yellow')"></button>
              <button class="color-dot bg-green-400 color-dot-lg" title="Promesa (Verde)" @click="setHighlight(libro, cap, focusVerse()?.n, 'green')"></button>
              <button class="color-dot bg-blue-400 color-dot-lg" title="Pueblo de tez brillante (Azul)" @click="setHighlight(libro, cap, focusVerse()?.n, 'blue')"></button>
              <button class="color-dot bg-red-400 color-dot-lg" title="Mandato (Rojo)" @click="setHighlight(libro, cap, focusVerse()?.n, 'red')"></button>
              <button class="color-dot bg-orange-400 color-dot-lg" title="Se refiere al Mesías (Naranja)" @click="setHighlight(libro, cap, focusVerse()?.n, 'orange')"></button>
              <button class="w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-600 border border-gray-400 flex items-center justify-center text-xs hover:scale-125 transition cursor-pointer" title="Quitar subrayado" @click="setHighlight(libro, cap, focusVerse()?.n, null)">×</button>
            </div>

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

        <div class="verse-text" x-html="formatVerse(focusVerse()?.t, libro + '-' + cap + '-' + focusVerse()?.n)"></div>
        
        {{-- Copyright en visor centrado --}}
        <div class="verse-copyright">
          <span class="bible-version">Biblia Reina-Valera 1960</span> - 
          Copyright © 1960 by American Bible Society
        </div>
      </div>
    </div>
  </template>

  {{-- MODO INMERSIVO --}}
  <template x-if="immersiveMode && !singleVerseMode">
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
            <span x-html="formatVerse(v.t, libro + '-' + cap + '-' + v.n)"></span>
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

  {{-- VISOR DE VERSÍCULO INDIVIDUAL --}}
  <template x-if="singleVerseMode">
    <div class="single-verse-viewer" @click.self="exitSingleVerseMode()">
      <div class="single-verse-card">
        <div class="single-verse-header">
          <h2 class="single-verse-title" x-text="currentSingleVerse?.pretty"></h2>
          <button class="single-verse-close" @click="exitSingleVerseMode()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="single-verse-content" x-html="formatVerse(currentSingleVerse?.text, currentSingleVerse?.book + '-' + currentSingleVerse?.chapter + '-' + currentSingleVerse?.verse)"></div>
        
        <div class="single-verse-footer">
          <div class="single-verse-navigation">
            <button class="btn-brand" @click="navigateSingleVerse('prev')" 
                    :disabled="!currentSingleVerse?.navigation?.prev_verse && !currentSingleVerse?.navigation?.prev_chapter && !currentSingleVerse?.navigation?.prev_book">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Anterior
            </button>
            <button class="btn-brand" @click="navigateSingleVerse('next')"
                    :disabled="!currentSingleVerse?.navigation?.next_verse && !currentSingleVerse?.navigation?.next_chapter && !currentSingleVerse?.navigation?.next_book">
              Siguiente
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
          
          <div class="single-verse-actions flex items-center">
            <!-- Paleta de colores para subrayar en visor individual -->
            <div class="flex items-center gap-1 border-r pr-2 border-gray-200 dark:border-gray-700 mr-2">
              <button class="color-dot bg-yellow-400 color-dot-lg" title="Cuando Dios habla (Amarillo)" @click="setHighlight(currentSingleVerse?.book, currentSingleVerse?.chapter, currentSingleVerse?.verse, 'yellow')"></button>
              <button class="color-dot bg-green-400 color-dot-lg" title="Promesa (Verde)" @click="setHighlight(currentSingleVerse?.book, currentSingleVerse?.chapter, currentSingleVerse?.verse, 'green')"></button>
              <button class="color-dot bg-blue-400 color-dot-lg" title="Pueblo de tez brillante (Azul)" @click="setHighlight(currentSingleVerse?.book, currentSingleVerse?.chapter, currentSingleVerse?.verse, 'blue')"></button>
              <button class="color-dot bg-red-400 color-dot-lg" title="Mandato (Rojo)" @click="setHighlight(currentSingleVerse?.book, currentSingleVerse?.chapter, currentSingleVerse?.verse, 'red')"></button>
              <button class="color-dot bg-orange-400 color-dot-lg" title="Se refiere al Mesías (Naranja)" @click="setHighlight(currentSingleVerse?.book, currentSingleVerse?.chapter, currentSingleVerse?.verse, 'orange')"></button>
              <button class="w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-600 border border-gray-400 flex items-center justify-center text-xs hover:scale-125 transition cursor-pointer" title="Quitar subrayado" @click="setHighlight(currentSingleVerse?.book, currentSingleVerse?.chapter, currentSingleVerse?.verse, null)">×</button>
            </div>

            <button class="chip" @click="copiar(`${currentSingleVerse?.pretty} ${currentSingleVerse?.text}`)">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
              Copiar
            </button>
          </div>
        </div>
        
        {{-- Copyright en visor de versículo individual --}}
        <div class="verse-copyright">
          <span class="bible-version">Biblia Reina-Valera 1960</span> - 
          Copyright © 1960 by American Bible Society
        </div>
      </div>
    </div>
  </template>

  {{-- BOTÓN FLOTANTE DE LECTURA --}}
  <div class="floating-reading-button" x-show="libro && cap && !singleVerseMode" @click="toggleImmersiveMode">
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
    // Datos de libros organizados por testamento
    testamentBooks: [],  // [{name: "Antiguo Testamento", books: [...]}]
    libros: [],         // [{slug, name, chapters, testament, order}]
    caps: [],           // ["1","2",...]
    libro: '',          // slug actual
    cap: '',            // capítulo actual (string)
    versiculos: [],     // [{n, t}]
    tituloCap: '',
    q: '',
    resultado: { q:'', total:0, results:[] },
    cargandoBusqueda: false,
    errorBusqueda: '',
    
    // Estado del visor
    focusMode: false,
    focusIndex: -1, // índice dentro de versiculos
    
    // Estado del visor de versículo individual
    singleVerseMode: false,
    currentSingleVerse: null,
    loadingSingleVerse: false,
    
    // Estado de la UI
    showBookSelector: false,
    showChapterSelector: false,
    readingMode: false,
    usePagination: false,
    fontSize: 16,
    fontFamily: 'font-sans',
    lineHeight: 'leading-relaxed',
    showSettings: false,
    immersiveMode: false,
    
    // Paginación de capítulos
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
    
    // Subrayados y estadísticas
    highlights: {},      // {"libro-cap-vers": "color"}
    searchHistory: [],   // [{term: "palabra", count: 1}]
    
    // Estado de precarga offline
    preloadStatus: 'idle', // 'idle' | 'loading' | 'success'
    preloadPercentage: 0,
    fullBibleData: null,   // Toda la biblia descargada
    
    // Versículos a mostrar
    get displayVerses() {
      return this.versiculos;
    },
    
    // Páginas de paginación a mostrar (capítulos)
    get paginationPages() {
      const current = this.pagination.current_page;
      const total = this.pagination.total_pages;
      const delta = 2;
      
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
    
    // Páginas de paginación a mostrar (resultados de búsqueda)
    get searchPaginationPages() {
      const current = this.resultado.pagination.current_page;
      const total = this.resultado.pagination.total_pages;
      const delta = 2;
      
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
      const v = e.target.value || '';
      this.q = v;
      this.errorBusqueda = '';
      
      clearTimeout(this._searchTimer);
      
      if (v.trim().length < 2) { 
        this.resultado = {q:v, total:0, results:[]}; 
        return; 
      }
      
      this.cargandoBusqueda = true;
      this._searchTimer = setTimeout(() => this.buscar(), 400);
    },

    async init() {
      this.loadHighlights();
      this.loadSearchHistory();
      await this.cargarLibros();
      await this.cargarInicio();
      this.parseHash();
      this.loadSettings();
      this.applySettings();
      
      // Escuchar cambios de hash para navegación fluida
      window.addEventListener('hashchange', () => {
        this.parseHash();
      });
      
      // Inicializar caché local
      if ('caches' in window) {
        try {
          const cache = await caches.open('biblia-api-cache');
          const cachedFull = await cache.match('/biblia/api/exportar');
          if (cachedFull) {
            this.fullBibleData = await cachedFull.json();
            this.preloadStatus = 'success';
          }
        } catch(e) {
          console.warn('Cache no disponible:', e);
        }
      }
    },
    
    // Wrapper de fetch con soporte offline
    async customFetch(url) {
      if (!('caches' in window)) {
        const res = await fetch(url);
        return await res.json();
      }
      
      const cacheName = 'biblia-api-cache';
      const cache = await caches.open(cacheName);
      
      try {
        const response = await fetch(url);
        if (response.ok) {
          cache.put(url, response.clone());
          return await response.json();
        }
      } catch (err) {
        console.warn('Fallo de red, buscando en copia local...', err);
      }
      
      // Intentar recuperar el endpoint exacto en cache
      const cachedResponse = await cache.match(url);
      if (cachedResponse) {
        return await cachedResponse.json();
      }
      
      // Si no existe, extraer de la Biblia completa precargada
      if (this.fullBibleData) {
        const localData = this.emulateApiResponse(url);
        if (localData) {
          this.showNotification('Modo sin conexión: cargado localmente', 'info');
          return localData;
        }
      }
      
      throw new Error('Conexión perdida y recurso no guardado offline.');
    },
    
    // Emulador local de API de Biblia
    emulateApiResponse(url) {
      const decodedUrl = decodeURIComponent(url);
      
      // 1. Capítulos de un libro: /biblia/api/{libro}
      const chaptersMatch = decodedUrl.match(/\/api\/([a-z0-9\-]+)$/);
      if (chaptersMatch) {
        const libro = chaptersMatch[1];
        if (this.fullBibleData[libro]) {
          return Object.keys(this.fullBibleData[libro]);
        }
      }
      
      // 2. Versículos de un capítulo: /biblia/api/{libro}/{cap}
      const versesMatch = decodedUrl.match(/\/api\/([a-z0-9\-]+)\/(\d+)$/);
      if (versesMatch) {
        const libro = versesMatch[1];
        const cap = versesMatch[2];
        if (this.fullBibleData[libro] && this.fullBibleData[libro][cap]) {
          const rawVerses = this.fullBibleData[libro][cap];
          const verses = [];
          for (const num in rawVerses) {
            verses.push({ n: parseInt(num), t: rawVerses[num] });
          }
          return {
            book: libro,
            chapter: parseInt(cap),
            pretty: this.pretty(libro) + ' ' + cap,
            verses: verses
          };
        }
      }
      
      // 3. Versículos de un capítulo paginado: /biblia/api/{libro}/{cap}/page/{page}
      const pageMatch = decodedUrl.match(/\/api\/([a-z0-9\-]+)\/(\d+)\/page\/(\d+)$/);
      if (pageMatch) {
        const libro = pageMatch[1];
        const cap = pageMatch[2];
        const page = parseInt(pageMatch[3]);
        if (this.fullBibleData[libro] && this.fullBibleData[libro][cap]) {
          const rawVerses = this.fullBibleData[libro][cap];
          const allVerses = [];
          for (const num in rawVerses) {
            allVerses.push({ n: parseInt(num), t: rawVerses[num] });
          }
          const perPage = 20;
          const totalVerses = allVerses.length;
          const totalPages = Math.ceil(totalVerses / perPage);
          const offset = (page - 1) * perPage;
          const slice = allVerses.slice(offset, offset + perPage);
          return {
            book: libro,
            chapter: parseInt(cap),
            pretty: this.pretty(libro) + ' ' + cap,
            verses: slice,
            pagination: {
              current_page: page,
              total_pages: totalPages,
              total_verses: totalVerses,
              per_page: perPage,
              has_prev: page > 1,
              has_next: page < totalPages,
              prev_page: page > 1 ? page - 1 : null,
              next_page: page < totalPages ? page + 1 : null
            }
          };
        }
      }
      
      // 4. Versículo individual: /biblia/api/{libro}/{cap}/{vers}
      const singleVerseMatch = decodedUrl.match(/\/api\/([a-z0-9\-]+)\/(\d+)\/(\d+)$/);
      if (singleVerseMatch) {
        const libro = singleVerseMatch[1];
        const cap = singleVerseMatch[2];
        const vers = singleVerseMatch[3];
        if (this.fullBibleData[libro] && this.fullBibleData[libro][cap] && this.fullBibleData[libro][cap][vers]) {
          return {
            book: libro,
            chapter: parseInt(cap),
            verse: parseInt(vers),
            text: this.fullBibleData[libro][cap][vers],
            pretty: this.pretty(libro) + ' ' + cap + ':' + vers,
            navigation: {
              prev_verse: parseInt(vers) > 1 ? { verse: parseInt(vers) - 1 } : null,
              next_verse: this.fullBibleData[libro][cap][parseInt(vers) + 1] ? { verse: parseInt(vers) + 1 } : null
            }
          };
        }
      }
      
      return null;
    },
    
    // Descargar Biblia completa
    async preloadBible() {
      this.preloadStatus = 'loading';
      this.preloadPercentage = 0;
      this.showNotification('Iniciando descarga de la Biblia para uso offline...', 'info');
      
      try {
        const progressInterval = setInterval(() => {
          if (this.preloadPercentage < 90) {
            this.preloadPercentage += Math.floor(Math.random() * 8) + 2;
            if (this.preloadPercentage > 90) this.preloadPercentage = 90;
          }
        }, 250);

        const res = await fetch('/biblia/api/exportar');
        if (!res.ok) throw new Error('Error de red');
        const data = await res.json();
        
        clearInterval(progressInterval);
        this.preloadPercentage = 100;
        
        if ('caches' in window) {
          const cache = await caches.open('biblia-api-cache');
          const response = new Response(JSON.stringify(data), {
            headers: { 'Content-Type': 'application/json' }
          });
          await cache.put('/biblia/api/exportar', response);
        }
        
        this.fullBibleData = data;
        this.preloadStatus = 'success';
        this.showNotification('¡Biblia descargada! Ya está disponible 100% sin conexión.');
      } catch (err) {
        console.error(err);
        this.preloadStatus = 'idle';
        this.showNotification('Error al descargar la Biblia completa.', 'error');
      }
    },
    
    // Cargar los versículos iniciales
    async cargarInicio() {
      try {
        const data = await this.customFetch('{{ route("biblia.api.start") }}');
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
        readingMode: this.readingMode,
        usePagination: this.usePagination,
      };
      localStorage.setItem('biblia-settings', JSON.stringify(settings));
    },
    
    updateFontSize() {
      document.documentElement.style.setProperty('--verse-font-size', `${this.fontSize}px`);
      this.saveSettings();
    },

    updateFontFamily() {
      const fontMap = {
        'font-sans': 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif',
        'font-serif': 'ui-serif, Georgia, Cambria, "Times New Roman", Times, serif',
        'font-mono': 'ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace'
      };
      document.documentElement.style.setProperty('--verse-font-family', fontMap[this.fontFamily] || fontMap['font-sans']);
      this.saveSettings();
    },

    updateLineHeight() {
      const lineHeightMap = {
        'leading-snug': '1.25',
        'leading-normal': '1.5',
        'leading-relaxed': '1.75'
      };
      document.documentElement.style.setProperty('--verse-line-height', lineHeightMap[this.lineHeight] || lineHeightMap['leading-normal']);
      this.saveSettings();
    },

    applySettings() {
      this.updateFontSize();
      this.updateFontFamily();
      this.updateLineHeight();
    },

    toggleReadingMode() {
      this.readingMode = !this.readingMode;
      this.saveSettings();
    },
    
    togglePagination() {
      this.usePagination = !this.usePagination;
      if (this.usePagination && this.versiculos.length > 20) {
        this.loadPaginatedChapter(1);
      }
      this.saveSettings();
    },
    
    toggleImmersiveMode() {
      this.immersiveMode = !this.immersiveMode;
      if (this.immersiveMode) {
        this.focusMode = false;
      }
    },
    
    selectBook(slug) {
      this.libro = slug;
      this.showBookSelector = false;
      this.cargarCapitulos().then(() => {
        this.showChapterSelector = true;
      });
    },
    
    selectChapter(chapter) {
      this.cap = chapter;
      this.showChapterSelector = false;
      this.cargarCapitulo();
    },

    // ----- Hash tipo #Juan-3:16 -----
    parseHash() {
      if (!location.hash) {
        this.exitFocus();
        this.exitSingleVerseMode();
        return;
      }
      const h = decodeURIComponent(location.hash.slice(1));
      const m = h.match(/^(.+?)-(\d+)(?::(\d+))?$/);
      if (!m) return;
      
      const nombre = m[1].toLowerCase().replaceAll(' ', '-');
      const cap = m[2];
      const ver = m[3] || null;
      
      const book = this.libros.find(b => b.slug === nombre || b.name.toLowerCase() === m[1].toLowerCase());
      if (book) {
        const needLoadBook = this.libro !== book.slug;
        const needLoadCap = String(this.cap) !== String(cap);
        
        this.libro = book.slug;
        
        const proceed = () => {
          if (ver) {
            if (this.singleVerseMode) {
              this.showSingleVerse(this.libro, cap, ver);
            } else {
              this.enterFocusByNumber(ver);
            }
          } else {
            this.exitFocus();
            this.exitSingleVerseMode();
          }
        };
        
        if (needLoadBook) {
          this.cargarCapitulos().then(() => {
            this.cap = cap;
            this.cargarCapitulo().then(proceed);
          });
        } else if (needLoadCap) {
          this.cap = cap;
          this.cargarCapitulo().then(proceed);
        } else {
          proceed();
        }
      }
    },

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

    async cargarLibros() {
      try {
        const data = await this.customFetch('{{ route("biblia.api.books.organized") }}');
        
        this.testamentBooks = [
          {
            name: data.old_testament.name,
            books: data.old_testament.books
          },
          {
            name: data.new_testament.name,
            books: data.new_testament.books
          }
        ];
        
        this.libros = [
          ...data.old_testament.books,
          ...data.new_testament.books
        ];
      } catch (err) {
        console.error('Error al cargar libros', err);
      }
    },
    
    async cargarCapitulos() {
      this.cap = '';
      this.caps = [];
      this.versiculos = [];
      this.exitFocus();
      if (!this.libro) return;
      this.caps = await this.customFetch(`{{ url('/biblia/api') }}/${this.libro}`);
    },
    
    async cargarCapitulo() {
      this.versiculos = [];
      this.exitFocus();
      if (!this.libro || !this.cap) return;
      
      if (this.usePagination) {
        await this.loadPaginatedChapter(1);
      } else {
        const data = await this.customFetch(`{{ url('/biblia/api') }}/${this.libro}/${this.cap}`);
        this.versiculos = data.verses ?? [];
        this.tituloCap  = data.pretty ?? '';
      }
      
      const libroObj = this.libros.find(b => b.slug === this.libro);
      const hash = (libroObj?.name || this.libro).replaceAll(' ', '-') + '-' + this.cap;
      history.replaceState({}, '', '#' + encodeURIComponent(hash));
    },
    
    async loadPaginatedChapter(page = 1) {
      if (!this.libro || !this.cap) return;
      
      const data = await this.customFetch(`{{ url('/biblia/api') }}/${this.libro}/${this.cap}/page/${page}`);
      
      this.versiculos = data.verses ?? [];
      this.tituloCap = data.pretty ?? '';
      this.pagination = data.pagination ?? this.pagination;
    },
    
    goToPage(page) {
      if (page === '...' || page === this.pagination.current_page) return;
      this.loadPaginatedChapter(page);
    },
    
    goToSearchPage(page) {
      if (page === '...' || page === this.resultado.pagination.current_page) return;
      this.buscar(page);
    },

    async buscar(page = 1) {
      try {
        const q = this.q.trim();
        if (!q || q.length < 2) { 
          this.resultado = {q, total:0, results:[]}; 
          this.cargandoBusqueda=false; 
          return; 
        }
        
        // Guardar en historial
        this.recordSearch(q);
        
        let url = `{{ route('biblia.api.search') }}?q=${encodeURIComponent(q)}`;
        if (page > 1) {
          url += `&page=${page}`;
        }
        
        const data = await this.customFetch(url);
        
        this.resultado = {
          q: data.q ?? q,
          total: Array.isArray(data.results) ? data.results.length : (data.total ?? 0),
          results: Array.isArray(data.results) ? data.results : [],
          stats: data.stats ?? null,
          exact_match: data.exact_match ?? null,
          pagination: data.pagination ?? {
            current_page: 1,
            total_pages: 1,
            total_results: 0,
            per_page: 10,
            has_prev: false,
            has_next: false,
            prev_page: null,
            next_page: null,
          }
        };
        
        this.errorBusqueda = '';
      } catch (err) {
        console.error('Buscar error', err);
        this.errorBusqueda = 'No se pudo realizar la búsqueda.';
        this.resultado = { q:this.q, total:0, results:[] };
      } finally {
        this.cargandoBusqueda = false;
      }
    },

    async abrir(r, focus=false) {
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

    enterFocus(index){
      if (index < 0 || index >= this.versiculos.length) return;
      this.focusIndex = index;
      this.focusMode = true;
      
      // Actualizar hash
      const libroObj = this.libros.find(b => b.slug === this.libro);
      const hash = `${(libroObj?.name || this.libro).replaceAll(' ', '-')}-${this.cap}:${this.versiculos[index].n}`;
      location.hash = encodeURIComponent(hash);
    },
    enterFocusByNumber(n){
      const idx = this.versiculos.findIndex(v => +v.n === +n);
      if (idx >= 0) {
        this.focusIndex = idx;
        this.focusMode = true;
      }
      this.scrollToVerse(n);
    },
    exitFocus(){
      this.focusMode = false;
      this.focusIndex = -1;
      
      if (this.libro && this.cap) {
        const libroObj = this.libros.find(b => b.slug === this.libro);
        const hash = `${(libroObj?.name || this.libro).replaceAll(' ', '-')}-${this.cap}`;
        location.hash = encodeURIComponent(hash);
      }
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
        this.cap = String(this.caps[this.caps.length - 1]);
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
        this.cap = String(this.caps[0]);
        await this.cargarCapitulo();
        return true;
      }
      return false;
    },

    async showSingleVerse(libro, cap, vers) {
      this.loadingSingleVerse = true;
      this.singleVerseMode = true; 

      try {
        const data = await this.customFetch(`/biblia/api/${libro}/${cap}/${vers}`);
        this.currentSingleVerse = data;
        const bookName = this.libros.find(b => b.slug === libro)?.name || libro;
        history.replaceState({}, '', `#${encodeURIComponent(bookName.replaceAll(' ', '-'))}-${cap}:${vers}`);
      } catch (error) {
        console.error('Error al cargar versículo:', error);
        this.showNotification('No se pudo cargar el versículo', 'error');
        this.exitSingleVerseMode();
      } finally {
        this.loadingSingleVerse = false;
      }
    },

    exitSingleVerseMode() {
      this.singleVerseMode = false;
      this.currentSingleVerse = null;
      if (this.libro && this.cap) {
        const bookName = this.libros.find(b => b.slug === this.libro)?.name || this.libro;
        history.replaceState({}, '', `#${encodeURIComponent(bookName.replaceAll(' ', '-'))}-${this.cap}`);
      } else {
        history.replaceState({}, '', window.location.pathname);
      }
    },

    navigateSingleVerse(direction) {
      if (!this.currentSingleVerse || !this.currentSingleVerse.navigation) return;

      const nav = this.currentSingleVerse.navigation;
      let nextLibro = this.currentSingleVerse.book;
      let nextCap = this.currentSingleVerse.chapter;
      let nextVers = null;

      if (direction === 'prev') {
        if (nav.prev_verse) {
          nextVers = nav.prev_verse.verse;
        } else if (nav.prev_chapter) {
          nextCap = nav.prev_chapter.chapter;
          nextVers = nav.prev_chapter.last_verse;
        } else if (nav.prev_book) {
          nextLibro = nav.prev_book.book;
          nextCap = nav.prev_book.last_chapter;
          nextVers = nav.prev_book.last_verse;
        }
      } else {
        if (nav.next_verse) {
          nextVers = nav.next_verse.verse;
        } else if (nav.next_chapter) {
          nextCap = nav.next_chapter.chapter;
          nextVers = nav.next_chapter.first_verse;
        } else if (nav.next_book) {
          nextLibro = nav.next_book.book;
          nextCap = nav.next_book.first_chapter;
          nextVers = nav.next_book.first_verse;
        }
      }

      if (nextVers !== null) {
        this.showSingleVerse(nextLibro, nextCap, nextVers);
      }
    },

    // Subrayado de versículos
    loadHighlights() {
      const saved = localStorage.getItem('biblia-highlights');
      if (saved) {
        try {
          this.highlights = JSON.parse(saved);
        } catch (err) {
          console.error(err);
        }
      }
    },
    setHighlight(libro, cap, vers, color) {
      if (!libro || !cap || !vers) return;
      const key = `${libro}-${cap}-${vers}`;
      if (color) {
        this.highlights[key] = color;
      } else {
        delete this.highlights[key];
      }
      this.highlights = { ...this.highlights };
      localStorage.setItem('biblia-highlights', JSON.stringify(this.highlights));
      
      this.showNotification(color ? `Subrayado guardado` : `Subrayado eliminado`);
    },
    
    // Formateador de versículos para subrayados
    formatVerse(text, key) {
      if (!text) return '';
      
      const customColor = this.highlights[key];
      let isAutoBlue = false;
      const blueKeywords = [
        'etíope', 'etíopes', 'etiopía',
        'cusita', 'cusitas', 'cuseo', 'cuseos',
        'sabeos', 'sabá',
        'tez brillante', 'elevada estatura y tez brillante'
      ];
      
      if (!customColor) {
        const textLower = this.removeAccents(text.toLowerCase());
        for (const kw of blueKeywords) {
          if (textLower.includes(this.removeAccents(kw.toLowerCase()))) {
            isAutoBlue = true;
            break;
          }
        }
        if (key && (key.startsWith('isaias-18-2') || key.startsWith('isaias-18-7') || key.startsWith('cantares-1-5') || key.startsWith('cantares-1-6'))) {
          isAutoBlue = true;
        }
      }
      
      if (customColor) {
        return `<span class="hl-${customColor}">${text}</span>`;
      } else if (isAutoBlue) {
        let formattedText = text;
        const blueKeywordsToHighlight = [
          'etíope', 'etíopes', 'etiopía', 'etiopia', 'etiope', 'etiopes',
          'cusita', 'cusitas', 'cuseo', 'cuseos', 'cus',
          'sabeos', 'sabá', 'saba',
          'morena', 'negra', 'negro',
          'tez brillante'
        ];
        
        for (const kw of blueKeywordsToHighlight) {
          const pattern = this.getAccentInsensitivePattern(kw);
          formattedText = formattedText.replace(pattern, '<mark class="bg-blue-300 dark:bg-blue-800 text-blue-900 dark:text-blue-100 px-1 rounded font-semibold">$0</mark>');
        }
        return `<span class="hl-blue">${formattedText}</span>`;
      }
      
      return text;
    },
    
    formatSearchVerse(highlightedText, key) {
      if (!highlightedText) return '';
      const customColor = this.highlights[key];
      if (customColor) {
        return `<span class="hl-${customColor}">${highlightedText}</span>`;
      }
      
      let isAutoBlue = false;
      const blueKeywords = [
        'etíope', 'etíopes', 'etiopía',
        'cusita', 'cusitas', 'cuseo', 'cuseos',
        'sabeos', 'sabá',
        'tez brillante', 'elevada estatura y tez brillante'
      ];
      
      const textLower = this.removeAccents(highlightedText.toLowerCase());
      for (const kw of blueKeywords) {
        if (textLower.includes(this.removeAccents(kw.toLowerCase()))) {
          isAutoBlue = true;
          break;
        }
      }
      
      if (isAutoBlue) {
        return `<span class="hl-blue">${highlightedText}</span>`;
      }
      
      return highlightedText;
    },
    
    removeAccents(str) {
      const unwanted = {
        'á':'a', 'é':'e', 'í':'i', 'ó':'o', 'ú':'u',
        'à':'a', 'è':'e', 'ì':'i', 'ò':'o', 'ù':'u',
        'ä':'a', 'ë':'e', 'ï':'i', 'ö':'o', 'ü':'u',
        'Á':'a', 'É':'e', 'Í':'i', 'Ó':'o', 'Ú':'u',
        'À':'a', 'È':'e', 'Í':'i', 'Ó':'o', 'Ú':'u',
        'Ä':'a', 'Ë':'e', 'Ï':'i', 'Ö':'o', 'Ü':'u',
        'ñ':'n', 'Ñ':'n'
      };
      return str.split('').map(char => unwanted[char] || char).join('');
    },
    
    getAccentInsensitivePattern(term) {
      const normalized = this.removeAccents(term.toLowerCase());
      const escaped = normalized.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
      
      const map = {
        'a': '[aáàäâ]',
        'e': '[eéèëê]',
        'i': '[iíìïî]',
        'o': '[oóòöô]',
        'u': '[uúùüû]',
        'n': '[nñ]',
      };
      
      let pattern = '';
      for (let i = 0; i < escaped.length; i++) {
        const char = escaped[i];
        pattern += map[char] || char;
      }
      
      return new RegExp('(?<![a-zA-ZáéíóúÁÉÍÓÚñÑüÜ])' + pattern + '(?![a-zA-ZáéíóúÁÉÍÓÚñÑüÜ])', 'gi');
    },

    // Estadísticas de palabras buscadas
    loadSearchHistory() {
      const saved = localStorage.getItem('biblia-search-history');
      if (saved) {
        try {
          this.searchHistory = JSON.parse(saved);
        } catch (e) {
          console.error(e);
        }
      }
    },
    recordSearch(q) {
      if (!q || q.trim().length < 2) return;
      const termClean = q.trim().toLowerCase();
      
      let history = [...this.searchHistory];
      const index = history.findIndex(item => item.term === termClean);
      
      if (index !== -1) {
        history[index].count++;
      } else {
        history.push({ term: termClean, count: 1 });
      }
      
      history.sort((a, b) => b.count - a.count);
      this.searchHistory = history.slice(0, 10);
      localStorage.setItem('biblia-search-history', JSON.stringify(this.searchHistory));
    },
    clearSearchHistory() {
      this.searchHistory = [];
      localStorage.removeItem('biblia-search-history');
      this.showNotification('Historial de búsquedas limpiado');
    },

    copiarRefActual() {
      if (!this.libro || !this.cap) return;
      const libroObj = this.libros.find(b => b.slug === this.libro);
      this.copiar(`${libroObj?.name || this.libro} ${this.cap}`);
    },
    copiar(texto) {
      navigator.clipboard.writeText(texto).then(() => {
        this.showNotification('Copiado al portapapeles');
      });
    },
    
    showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.classList.add('show');
      }, 10);
      
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
          if (notification.parentNode) {
            document.body.removeChild(notification);
          }
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