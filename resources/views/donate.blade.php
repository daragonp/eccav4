@extends('layouts.main')

@section('title', 'Donación')

{{-- Cabecera (opcional): título grande + barra de acento --}}
@section('page-hero')
  <div>
    <h1 class="page-title">Donación</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Apreciado aportante, sírvase escribir los datos solicitados en el formulario para realizar su donación.</p>
  </div>
@endsection

@section('content')
  <section class="panel">
    <form action="adddonor" method="post">
      @csrf

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium" for="name">Nombre completo</label>
          <input name="name" type="text" id="name" required
                 class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--green-light)] dark:bg-gray-800 dark:border-gray-600" />
        </div>

        <div>
          <label class="block mb-1 font-medium" for="donar">Monto a donar</label>
          <input name="donate" type="number" id="donar" required
                 class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--green-light)] dark:bg-gray-800 dark:border-gray-600" />
        </div>

        <div>
          <label class="block mb-1 font-medium" for="email">Email</label>
          <input name="email" type="email" id="email" required
                 class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--green-light)] dark:bg-gray-800 dark:border-gray-600" />
        </div>

        <div>
          <label class="block mb-1 font-medium" for="address">Dirección</label>
          <input name="address" type="text" id="address"
                 class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--green-light)] dark:bg-gray-800 dark:border-gray-600" />
        </div>

        <div>
          <label class="block mb-1 font-medium" for="phone">Celular</label>
          <input name="phone" type="number" id="phone"
                 class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--green-light)] dark:bg-gray-800 dark:border-gray-600" />
        </div>

        <div class="sm:col-span-2">
          <label class="block mb-1 font-medium" for="message">¿Desea enviar algún mensaje?</label>
          <textarea name="message" id="message" rows="2"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--green-light)] dark:bg-gray-800 dark:border-gray-600"></textarea>
        </div>
      </div>

      <input name="merchantId"      type="hidden" value="{{env('PAY_U_MARKET')}}">
      <input name="accountId"       type="hidden" value="{{env('PAY_U_ACC_ID')}}">
      <input name="description"     type="hidden" value="{{$description}}">
      <input name="referenceCode"   type="hidden" value="TestPayU">
      <input name="amount"          type="hidden" value="20000">
      <input name="tax"             type="hidden" value="3193">
      <input name="taxReturnBase"   type="hidden" value="16806">
      <input name="currency"        type="hidden" value="COP">
      <input name="signature"       type="hidden" value="7ee7cf808ce6a39b17481c54f2c57acc">
      <input name="test"            type="hidden" value="0">
      <input name="buyerEmail"      type="hidden" value="test@test.com">
      <input name="responseUrl"     type="hidden" value="http://www.test.com/response">
      <input name="confirmationUrl" type="hidden" value="http://www.test.com/confirmation">

      <div class="mt-6">
        <button name="submit" type="submit"
                class="inline-block rounded-lg bg-[var(--green-dark)] text-white px-5 py-2.5 font-medium hover:opacity-90 transition">
          Realizar donación
        </button>
      </div>
    </form>
  </section>
@endsection
