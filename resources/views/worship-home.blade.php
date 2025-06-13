@extends('layouts.main')

@section('title', 'Culto dominical')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <input type="date" id="datePicker" class="form-control w-auto" />
            <h2>Historial</h2>
        </div>

        @foreach ($verses as $month => $group)
            <div class="text-start">
                <span class="month-badge">{{ $month }}</span>
            </div>
            <table class="table table-bordered table-responsive table-hover text-center align-middle">
                <thead class="table-warning">
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
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#pdfModal"
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
                <tfoot class="table-warning">
                    <tr>
                        <th>Fecha</th>
                        <th>Imagen</th>
                        <th>Documento</th>
                    </tr>
                </tfoot>
            </table>
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
        document.getElementById('datePicker').addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                window.open(`/verses/${selectedDate}`, '_blank');
            }
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
