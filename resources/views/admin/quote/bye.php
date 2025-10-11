{{-- Campos del formulario de edición para Verses (usado dentro del modal por fila) --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div>
    <label class="block text-sm font-semibold mb-2">Imagen actual</label>
    @if(!empty($tableM->image))
      <img src="{{ asset('images/bible/'.$tableM->image) }}"
           alt="Imagen actual"
           class="h-48 w-full object-cover rounded-lg ring-1 ring-slate-200 dark:ring-slate-800">
    @else
      <div class="text-slate-500 text-sm">Sin imagen</div>
    @endif
  </div>

  <div class="space-y-4">
    <div>
      <label for="video_{{ $tableM->id }}" class="block text-sm mb-1">Documento PDF</label>
      <input id="video_{{ $tableM->id }}" type="file" name="video" accept=".pdf"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      @if(!empty($tableM->video))
        <a href="{{ asset('documents/quote/'.$tableM->video) }}" target="_blank" class="text-sm underline mt-1 inline-block">Ver PDF actual</a>
      @endif
    </div>

    <div>
      <label for="image_{{ $tableM->id }}" class="block text-sm mb-1">Nueva imagen</label>
      <input id="image_{{ $tableM->id }}" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>

    <div>
      <label for="date_{{ $tableM->id }}" class="block text-sm mb-1">Fecha</label>
      <input id="date_{{ $tableM->id }}" type="date" name="date" value="{{ $tableM->date }}"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>

    <div>
      <label for="audio_{{ $tableM->id }}" class="block text-sm mb-1">Audio (opcional)</label>
      <input id="audio_{{ $tableM->id }}" type="file" name="audio" accept="audio/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      @if(!empty($tableM->audio))
        <audio controls class="mt-2 w-full max-w-xs">
          <source src="{{ asset('audio/quote/'.$tableM->audio) }}">
        </audio>
      @endif
    </div>
  </div>
</div>
