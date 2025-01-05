$(document).ready(function() {
    $('#worship-table').DataTable({
        "language": {
            "sEmptyTable":     "No se encontraron registros",
            "sInfo":           "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "sInfoEmpty":      "Mostrando 0 a 0 de 0 entradas",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ entradas)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Mostrar _MENU_ entradas",
            "sLoadingRecords": "Cargando...",
            "sProcessing":     "Procesando...",
            "sSearch":         "Buscar:",
            "sZeroRecords":    "No se encontraron registros",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna ascendente",
                "sSortDescending": ": Activar para ordenar la columna descendente"
            }
        }
    });
});
