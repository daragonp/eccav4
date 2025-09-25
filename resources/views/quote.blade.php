@if ($quote)
<section class="full-bleed px-none py-10 bg-white dark:bg-gray-900">
  <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8 px-4 sm:px-6 lg:px-8">
    <!-- Izquierda -->
    <div class="text-center md:text-left">
      <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight
                 text-[var(--green-dark)] dark:text-[var(--green-light)]">
        PALABRA DE VIDA<br>DEL DÍA
      </h2>

      <a href="{{ asset('documents/quote/' . $quote->video) }}"
         download="{{ asset('documents/quote/' . $quote->video) }}"
         class="relative mt-5 inline-flex items-center rounded-full
                bg-[var(--green-dark)] text-white px-5 py-2.5 font-medium shadow
                hover:brightness-110 transition
                focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2
                focus-visible:ring-[var(--green-light)]
                focus-visible:ring-offset-white dark:focus-visible:ring-offset-gray-900">
        Descarga PDF aquí
        <!-- badge circular -->
        <span class="absolute -right-3 -top-3 inline-flex h-9 w-9 items-center justify-center rounded-full
                      bg-white text-[var(--green-dark)] text-xs font-bold border-2 border-[var(--green-dark)]
                      shadow-sm">
          NEW
        </span>
      </a>
    </div>

    <!-- Derecha -->
    <div class="flex justify-center md:justify-end">
      <img src="{{ asset('images/bible/' . $quote->image) }}"
           alt="Imagen del día"
           class="max-h-[500px] w-auto rounded-md border-2 border-[var(--green-dark)]
                  dark:border-[var(--green-light)] shadow" />
    </div>
  </div>
</section>
@endif
