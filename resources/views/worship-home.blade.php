@extends('layouts.main')

@section('title', 'Palabra de vida')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <input type="date" id="datePicker" max="{{ date('Y-m-d') }}" class="form-control w-auto" />
            <h2>Historial</h2>
        </div>

        @foreach ($verses as $month => $group)
            <div class="text-start">
                <span class="month-badge">{{ $month }}</span>
            </div>
            <div class="table-responsive-custom mb-4">
                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="table-success">
                        <tr>
                            <th>Fecha</th>
                            <th>Imagen</th>
                            <th>Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group as $verse)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($verse->date)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ asset('images/bible/' . $verse->image) }}"
                                        data-title="Derechos Reservados E.C.C.A." data-lightbox="verse-{{ $verse->date }}">
                                        <img src="{{ asset('images/bible/' . $verse->image) }}" alt="Imagen"
                                            style="width: 80px;">
                                    </a>
                                </td>

                                <td>
                                    @if ($verse->video)
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#pdfModal"
                                            data-pdf="{{ asset('documents/quote/' . $verse->video) }}">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> <strong>Ver PDF</strong>
                                        </button>
                                    @else
                                        <p>Pendiente de documento</p>
                                    @endif
                                </td>
                                {{--  <td>
                            <a href="{{route('verses.show', $verse->date) }}" target="_blank" class="btn btn-sm btn-primary">LEER</a>
                        </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-success">
                        <tr>
                            <th>Fecha</th>
                            <th>Imagen</th>
                            <th>Documento</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach
    </div>
    <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Vista previa del PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" style="height: 80vh;">
                    <iframe src="" width="100%" height="100%" style="border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Lista de fechas disponibles en formato YYYY-MM-DD
        const availableDates = @json($availableDates); // Esta variable debes pasarla desde el controlador

        document.addEventListener('DOMContentLoaded', function() {
            const datePicker = document.getElementById('datePicker');
            const today = new Date().toISOString().split('T')[0];

            // Establece la fecha máxima como hoy
            datePicker.setAttribute('max', today);

            datePicker.addEventListener('change', function() {
                const selectedDate = new Date(this.value).toISOString().split('T')[0];

                // Validar si es fecha futura
                if (selectedDate > today) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fecha inválida',
                        text: 'No puedes seleccionar una fecha futura.',
                        confirmButtonColor: '#2a3d1f',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    return;
                }

                // Validar si hay datos para esa fecha
                if (availableDates.includes(selectedDate)) {
                    window.open(`/single-feed/${selectedDate}`, '_blank');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin contenido',
                        text: 'No hay registro disponible para esta fecha.',
                        confirmButtonColor: '#2a3d1f',
                        confirmButtonText: 'Aceptar'
                    });
                    this.value = '';
                }
            });
        });
    </script>

    <script>
        const pdfModal = document.getElementById('pdfModal');
        pdfModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const pdfUrl = button.getAttribute('data-pdf');
            const iframe = pdfModal.querySelector('iframe');
            iframe.src = pdfUrl;
        });
        pdfModal.addEventListener('hidden.bs.modal', function() {
            const iframe = pdfModal.querySelector('iframe');
            iframe.src = '';
        });
    </script>

@endsection
