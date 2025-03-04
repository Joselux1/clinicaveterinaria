


document.addEventListener("DOMContentLoaded", function () { 
    document.querySelector("form").addEventListener("submit", function () {
        document.getElementById("btnGuardar").disabled = true;
    });

    if (document.getElementById("donutGrafico")) {
        let donutOptions = {
            series: [44, 55, 41, 17],
            chart: { type: 'donut', height: 350 },
            labels: ['Ventas', 'Marketing', 'Desarrollo', 'Soporte'],
            colors: ['#FF4560', '#008FFB', '#00E396', '#FEB019'],
            legend: { position: 'bottom' },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { width: 300 },
                    legend: { position: 'bottom' }
                }
            }]
        };

        let donutChart = new ApexCharts(document.getElementById("donutGrafico"), donutOptions);
        donutChart.render();
    }

    if (document.getElementById("barChartGrafico")) {
        let barChartOptions = {
            series: [
                { name: "Series 1", data: [{ x: 'W1', y: 22 }, { x: 'W2', y: 29 }, { x: 'W3', y: 13 }, { x: 'W4', y: 32 }] },
                { name: "Series 2", data: [{ x: 'W1', y: 43 }, { x: 'W2', y: 43 }, { x: 'W3', y: 43 }, { x: 'W4', y: 43 }] }
            ],
            chart: { type: 'bar', height: 350, stacked: true },
            plotOptions: { bar: { horizontal: false } },
            title: { text: "Comparación Semanal de Series" },
            xaxis: { type: 'category' },
            yaxis: { title: { text: "Valores" } }
        };

        let barChart = new ApexCharts(document.getElementById("barChartGrafico"), barChartOptions);
        barChart.render();
    }
});

$(document).ready(function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        locale: 'es',
        firstDay: 1,
        buttonText: {
            today: 'Hoy'
           
        },
        events: window.location.origin + '/clinicaveterinaria/public/eventos/obtener',

        select: function (info) {
            console.log("Fecha seleccionada:", info.startStr); // Para verificar en consola

            // Rellenar los campos del formulario con la fecha seleccionada
            $("#FECHA_INICIO").val(info.startStr + "T00:00");
            $("#FECHA_FIN").val(info.endStr ? info.endStr + "T23:59" : info.startStr + "T23:59");

            // Limpiar campos de texto
            $("#TITULO").val("");
            $("#DESCRIPCION_ES").val("");
            $("#DESCRIPCION_ENG").val("");

            // Mostrar el modal
            $("#kt_modal_add_event").modal("show");
        },

        eventClick: function (info) {
            console.log("Evento clickeado", info.event.id);

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.location.origin + '/clinicaveterinaria/public/eventos/borrarEventos/' + info.event.id,
                        method: 'POST',
                        data: { _method: 'DELETE' },
                        success: function () {
                            Swal.fire('Eliminado!', 'El evento ha sido eliminado.', 'success');
                            calendar.refetchEvents();
                        },
                        error: function (jqXHR) {
                            console.error("Error al eliminar el evento: ", jqXHR.responseText);
                            Swal.fire('Error!', 'No se pudo eliminar el evento.', 'error');
                        }
                    });
                }
            });
        }
    });

    calendar.render();

    $(document).ready(function () {
    $("#FECHA_INICIO, #FECHA_FIN").on("change", function () {
        let fechaInicio = new Date($("#FECHA_INICIO").val());
        let fechaFin = new Date($("#FECHA_FIN").val());

        if (fechaFin < fechaInicio) {
            alert("La fecha de fin no puede ser anterior a la fecha de inicio");
            $("#FECHA_FIN").val($("#FECHA_INICIO").val()); // Ajusta la fecha de fin
        }
    });
});


    // Manejo del envío del formulario con validación y alertas
    $("#eventoForm").submit(function (e) {
        e.preventDefault(); 
        $.ajax({
            url: $(this).attr("action"),
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.status === "error") {
                    let errorMessage = "";

                    // Construir mensaje de error con los campos inválidos
                    for (const campo in response.errors) {
                        errorMessage += `• ${response.errors[campo]} <br>`;
                    }

                    // Mostrar alerta con los errores
                    Swal.fire({
                        icon: "error",
                        title: "Error al guardar el evento",
                        html: errorMessage,
                        confirmButtonText: "Entendido",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "Evento guardado",
                        text: "El evento se ha guardado correctamente.",
                        confirmButtonText: "OK",
                    }).then(() => {
                    
                        $("#kt_modal_add_event").modal("hide");
                        calendar.refetchEvents();
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error inesperado",
                    text: "Hubo un problema al intentar guardar el evento.",
                    confirmButtonText: "Cerrar",
                });
            },
        });
    });
});

