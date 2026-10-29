{{--
  Partial compartido: selector en cascada (libro → capítulo → versículo) + carrito
  acumulativo para componer el "Versículo del día" desde la Reina Valera.

  Parámetros Blade:
    - $textFieldId : id del textarea destino que se autollena ('textInput' | 'verse_text').
    - $initialRefs : array de refs ya guardadas [{book,chapter,verse,pretty,text}] para rehidratar
                     (vacío al crear, o repoblado desde old('verses_refs') / $tableM->verses_refs).

  Consume SOLO las APIs existentes de la Biblia:
    GET /biblia/api/libros        -> [{slug,name,...}]
    GET /biblia/api/{libro}       -> ["1","2",...]
    GET /biblia/api/{libro}/{cap} -> {pretty, verses:[{n,t}]}

  El JS va inline en un IIFE re-ejecutable (funciona en página completa y en fragmento AJAX).
--}}

{{-- Contenedor raíz con marca de inicialización para el IIFE.
     data-initial-refs usa COMILLAS DOBLES y json_encode con flags JSON_HEX_* (no @json crudo):
     el text de la RV lleva comillas y rompería el atributo sin estos flags. --}}
<div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6 space-y-4"
     data-verse-cart
     data-text-target="{{ $textFieldId }}"
     data-initial-refs="{{ json_encode($initialRefs ?? [], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}">

  <div class="flex items-center gap-2">
    <i class="fa-solid fa-book-bible text-brand-green"></i>
    <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Seleccionar de la Biblia (Reina Valera)</h4>
  </div>

  {{-- Aviso de regeneración (visible sólo si cart.length > 0; lo togglea el JS) --}}
  <p data-cart-notice class="hidden text-xs rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-2 text-amber-800 dark:text-amber-300">
    El texto se generará automáticamente desde los versículos seleccionados al guardar.
    Para escribir texto libre, usa “Desvincular (texto libre)”.
  </p>

  {{-- Selector en cascada: 3 selects + botón Agregar --}}
  <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
    <select data-select="book" aria-label="Libro"
            class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-green focus:border-brand-green">
      <option value="">Libro…</option>
    </select>
    <select data-select="chapter" aria-label="Capítulo" disabled
            class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-green focus:border-brand-green disabled:opacity-50">
      <option value="">Capítulo…</option>
    </select>
    <select data-select="verse" aria-label="Versículo" disabled
            class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-green focus:border-brand-green disabled:opacity-50">
      <option value="">Versículo…</option>
    </select>
    <button type="button" data-cart-add class="btn btn-secondary justify-center" disabled>
      <i class="fa-solid fa-plus"></i> Agregar
    </button>
  </div>

  {{-- Vista previa del texto del versículo a agregar (se llena al elegir versículo) --}}
  <p data-verse-preview class="text-xs text-slate-500 dark:text-slate-400"></p>

  {{-- Lista del carrito (ol para transmitir orden; cada li con quitar + subir/bajar) --}}
  <ol data-cart-list class="space-y-2" aria-label="Versículos seleccionados"></ol>

  {{-- Vista previa de la referencia combinada --}}
  <div class="text-sm">
    <span class="font-medium text-slate-700 dark:text-slate-300">Referencia:</span>
    <span data-cart-reference class="text-slate-600 dark:text-slate-400">—</span>
  </div>

  {{-- Único botón: vacía el carrito conservando el texto del textarea (visible sólo con carrito no vacío) --}}
  <div class="flex gap-2">
    <button type="button" data-cart-unlink class="btn btn-ghost btn-sm hidden">
      <i class="fa-solid fa-link-slash"></i> Desvincular (texto libre)
    </button>
  </div>

  {{-- Campo oculto con el array JSON del carrito en orden --}}
  <input type="hidden" name="verses_refs" data-cart-field value="">

  {{-- Aviso de error de red de las APIs de biblia --}}
  <p data-cart-neterror class="hidden text-xs text-red-600 dark:text-red-400"></p>
</div>

<script>
(function () {
  // Separador del formato de texto: em dash U+2014 rodeado de espacios (mismo que el servidor).
  var DASH = ' \u2014 ';

  // Caché en memoria por carga de página (compartida entre instancias para no repetir fetch).
  // window._verseCartCache se reutiliza si el fragmento del modal se inyecta por AJAX.
  var cache = (window._verseCartCache = window._verseCartCache || {
    books: null,        // array de {slug,name}
    chapters: {},       // slug -> array de capítulos
    verses: {}          // "slug/cap" -> array de {n,t}
  });

  // GET JSON con manejo de error homogéneo.
  function getJson(url) {
    return fetch(url, { headers: { 'Accept': 'application/json' } }).then(function (r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    });
  }

  function escapeHtml(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function initCart(root) {
    if (root.dataset.cartReady === '1') return;   // idempotente: una sola vez por contenedor
    root.dataset.cartReady = '1';

    // Nodos relativos al contenedor (sin IDs globales → varias instancias coexisten).
    var bookSel    = root.querySelector('[data-select="book"]');
    var chapterSel = root.querySelector('[data-select="chapter"]');
    var verseSel   = root.querySelector('[data-select="verse"]');
    var addBtn     = root.querySelector('[data-cart-add]');
    var previewEl  = root.querySelector('[data-verse-preview]');
    var listEl     = root.querySelector('[data-cart-list]');
    var refEl      = root.querySelector('[data-cart-reference]');
    var noticeEl   = root.querySelector('[data-cart-notice]');
    var unlinkBtn  = root.querySelector('[data-cart-unlink]');
    var fieldEl    = root.querySelector('[data-cart-field]');
    var netErrEl   = root.querySelector('[data-cart-neterror]');

    var textTargetId = root.dataset.textTarget || '';
    function textarea() { return textTargetId ? document.getElementById(textTargetId) : null; }

    // Estado del carrito: array de {book,chapter,verse,pretty,text} en orden.
    var cart = [];

    function showNetError(msg) {
      if (!netErrEl) return;
      netErrEl.textContent = msg || 'No se pudo cargar la Biblia; puedes escribir el texto a mano.';
      netErrEl.classList.remove('hidden');
    }
    function clearNetError() {
      if (!netErrEl) return;
      netErrEl.textContent = '';
      netErrEl.classList.add('hidden');
    }

    function prettyLine(it) { return (it.pretty || '') + DASH + (it.text || ''); }

    // Autollena el textarea destino cuando el carrito NO está vacío.
    function syncTextarea() {
      var ta = textarea();
      if (!ta) return;
      ta.value = cart.map(prettyLine).join('\n');
    }

    // Refleja el estado actual en lista, referencia, campo oculto, aviso y botón.
    function render() {
      // Lista
      listEl.innerHTML = '';
      cart.forEach(function (it, i) {
        var li = document.createElement('li');
        li.className = 'flex items-start gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-2';
        li.innerHTML =
          '<div class="flex-1 min-w-0">' +
            '<p class="text-sm font-semibold text-slate-800 dark:text-slate-200">' + escapeHtml(it.pretty) + '</p>' +
            '<p class="text-xs text-slate-500 dark:text-slate-400 truncate">' + escapeHtml(it.text) + '</p>' +
          '</div>' +
          '<div class="flex items-center gap-1 flex-shrink-0">' +
            '<button type="button" data-cart-up="' + i + '" class="btn btn-ghost btn-sm" aria-label="Subir" title="Subir"' + (i === 0 ? ' disabled' : '') + '><i class="fa-solid fa-arrow-up"></i></button>' +
            '<button type="button" data-cart-down="' + i + '" class="btn btn-ghost btn-sm" aria-label="Bajar" title="Bajar"' + (i === cart.length - 1 ? ' disabled' : '') + '><i class="fa-solid fa-arrow-down"></i></button>' +
            '<button type="button" data-cart-remove="' + i + '" class="btn btn-ghost btn-sm text-red-600 dark:text-red-400" aria-label="Quitar" title="Quitar"><i class="fa-solid fa-xmark"></i></button>' +
          '</div>';
        listEl.appendChild(li);
      });

      // Referencia combinada (pretty unidos por '; ')
      refEl.textContent = cart.length ? cart.map(function (it) { return it.pretty; }).join('; ') : '—';

      if (cart.length > 0) {
        // Carrito con ítems: autollenar textarea, sincronizar oculto, mostrar aviso y botón.
        syncTextarea();
        fieldEl.value = JSON.stringify(cart);
        if (noticeEl) noticeEl.classList.remove('hidden');
        if (unlinkBtn) unlinkBtn.classList.remove('hidden');
      } else {
        // Carrito vacío: se CONSERVA el textarea tal cual, oculto = '', ocultar aviso y botón.
        fieldEl.value = '';
        if (noticeEl) noticeEl.classList.add('hidden');
        if (unlinkBtn) unlinkBtn.classList.add('hidden');
      }
    }

    // Habilita/deshabilita el botón Agregar según haya terna completa.
    function refreshAddState() {
      var ok = !!(bookSel.value && chapterSel.value && verseSel.value);
      addBtn.disabled = !ok;
    }

    function setOptions(sel, items, placeholder) {
      sel.innerHTML = '';
      var ph = document.createElement('option');
      ph.value = '';
      ph.textContent = placeholder;
      sel.appendChild(ph);
      items.forEach(function (it) {
        var opt = document.createElement('option');
        opt.value = it.value;
        opt.textContent = it.label;
        sel.appendChild(opt);
      });
    }

    // ---- Carga de datos desde /biblia/api/* (con caché) ----

    function loadBooks() {
      function fill(books) {
        setOptions(bookSel, books.map(function (b) { return { value: b.slug, label: b.name }; }), 'Libro…');
      }
      if (cache.books) { fill(cache.books); return; }
      getJson('/biblia/api/libros').then(function (books) {
        cache.books = books;
        fill(books);
        clearNetError();
      }).catch(function () {
        showNetError();
      });
    }

    function loadChapters(slug) {
      function fill(chapters) {
        setOptions(chapterSel, chapters.map(function (c) { return { value: String(c), label: 'Capítulo ' + c }; }), 'Capítulo…');
        chapterSel.disabled = false;
      }
      if (cache.chapters[slug]) { fill(cache.chapters[slug]); return; }
      getJson('/biblia/api/' + encodeURIComponent(slug)).then(function (chapters) {
        cache.chapters[slug] = chapters;
        fill(chapters);
        clearNetError();
      }).catch(function () {
        showNetError();
      });
    }

    function loadVerses(slug, cap) {
      var key = slug + '/' + cap;
      function fill(verses) {
        setOptions(verseSel, verses.map(function (v) { return { value: String(v.n), label: 'Versículo ' + v.n }; }), 'Versículo…');
        verseSel.disabled = false;
      }
      if (cache.verses[key]) { fill(cache.verses[key]); return; }
      getJson('/biblia/api/' + encodeURIComponent(slug) + '/' + encodeURIComponent(cap)).then(function (data) {
        var verses = (data && data.verses) ? data.verses : [];
        cache.verses[key] = verses;
        // Guardar también el pretty del capítulo para la vista previa.
        cache.verses[key]._pretty = (data && data.pretty) ? data.pretty : '';
        fill(verses);
        clearNetError();
      }).catch(function () {
        showNetError();
      });
    }

    // Texto 't' del versículo elegido, desde la caché.
    function currentVerseText() {
      var key = bookSel.value + '/' + chapterSel.value;
      var verses = cache.verses[key] || [];
      var n = verseSel.value;
      for (var i = 0; i < verses.length; i++) {
        if (String(verses[i].n) === String(n)) return verses[i].t || '';
      }
      return '';
    }

    // 'pretty' de un versículo: "<nombre libro> <cap>:<v>" (p.ej. "Juan 3:16").
    function currentPretty() {
      var bookLabel = bookSel.options[bookSel.selectedIndex] ? bookSel.options[bookSel.selectedIndex].textContent : bookSel.value;
      return bookLabel + ' ' + chapterSel.value + ':' + verseSel.value;
    }

    // ---- Eventos de los selects ----

    bookSel.addEventListener('change', function () {
      chapterSel.innerHTML = '<option value="">Capítulo…</option>';
      chapterSel.disabled = true;
      verseSel.innerHTML = '<option value="">Versículo…</option>';
      verseSel.disabled = true;
      previewEl.textContent = '';
      refreshAddState();
      if (bookSel.value) loadChapters(bookSel.value);
    });

    chapterSel.addEventListener('change', function () {
      verseSel.innerHTML = '<option value="">Versículo…</option>';
      verseSel.disabled = true;
      previewEl.textContent = '';
      refreshAddState();
      if (bookSel.value && chapterSel.value) loadVerses(bookSel.value, chapterSel.value);
    });

    verseSel.addEventListener('change', function () {
      if (verseSel.value) {
        previewEl.textContent = currentPretty() + DASH + currentVerseText();
      } else {
        previewEl.textContent = '';
      }
      refreshAddState();
    });

    // ---- Agregar ----
    addBtn.addEventListener('click', function () {
      if (!bookSel.value || !chapterSel.value || !verseSel.value) return;
      var book = bookSel.value;
      var chapter = parseInt(chapterSel.value, 10);
      var verse = parseInt(verseSel.value, 10);
      // No permitir duplicados exactos (misma terna book/chapter/verse).
      var dup = cart.some(function (it) {
        return it.book === book && Number(it.chapter) === chapter && Number(it.verse) === verse;
      });
      if (dup) return;
      cart.push({
        book: book,
        chapter: chapter,
        verse: verse,
        pretty: currentPretty(),
        text: currentVerseText()
      });
      render();
    });

    // ---- Quitar / subir / bajar (delegado SOLO sobre la lista de este contenedor) ----
    listEl.addEventListener('click', function (e) {
      var up = e.target.closest('[data-cart-up]');
      var down = e.target.closest('[data-cart-down]');
      var rm = e.target.closest('[data-cart-remove]');
      if (up) {
        var iu = parseInt(up.getAttribute('data-cart-up'), 10);
        if (iu > 0) { var t = cart[iu - 1]; cart[iu - 1] = cart[iu]; cart[iu] = t; render(); }
      } else if (down) {
        var id = parseInt(down.getAttribute('data-cart-down'), 10);
        if (id < cart.length - 1) { var t2 = cart[id + 1]; cart[id + 1] = cart[id]; cart[id] = t2; render(); }
      } else if (rm) {
        var ir = parseInt(rm.getAttribute('data-cart-remove'), 10);
        cart.splice(ir, 1);
        render();
      }
    });

    // ---- Desvincular (texto libre): vaciar carrito conservando el textarea ----
    if (unlinkBtn) {
      unlinkBtn.addEventListener('click', function () {
        cart = [];
        render();   // conserva el textarea, oculta aviso y botón, oculto = ''
      });
    }

    // ---- Rehidratación desde data-initial-refs ----
    (function rehydrate() {
      var refs;
      try {
        refs = JSON.parse(root.dataset.initialRefs || '[]');
      } catch (err) {
        cart = [];   // degradación segura: NO tocar el textarea
        return;
      }
      if (!Array.isArray(refs) || refs.length === 0) return;
      cart = refs.map(function (r) {
        return {
          book: r.book,
          chapter: r.chapter,
          verse: r.verse,
          pretty: r.pretty || '',
          text: r.text || ''
        };
      });
      render();   // lista + referencia + re-sincroniza textarea + muestra aviso/botones
    })();

    // Poblar libros y estado inicial del botón Agregar.
    loadBooks();
    refreshAddState();
  }

  function initAll() {
    document.querySelectorAll('[data-verse-cart]:not([data-cart-ready="1"])').forEach(initCart);
  }

  // Ejecutar ya si el nodo existe (fragmento AJAX re-inyectado) o esperar al DOM (página completa).
  if (document.querySelector('[data-verse-cart]')) {
    initAll();
  } else {
    document.addEventListener('DOMContentLoaded', initAll);
  }
})();
</script>
