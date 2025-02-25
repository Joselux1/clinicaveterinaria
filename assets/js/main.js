
document.addEventListener("DOMContentLoaded", function () { 
   
    let donutOptions = {
        series: [44, 55, 41, 17], // Datos de las secciones
        chart: {
            type: 'donut',
            height: 350
        },
        labels: ['Ventas', 'Marketing', 'Desarrollo', 'Soporte'],
        colors: ['#FF4560', '#008FFB', '#00E396', '#FEB019'], // Colores personalizados
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    let donutChart = new ApexCharts(document.querySelector("#donutGrafico"), donutOptions);
    donutChart.render();

    
    let barChartOptions = {
        series: [
            {
                name: "Series 1",
                data: [
                    { x: 'W1', y: 22 },
                    { x: 'W2', y: 29 },
                    { x: 'W3', y: 13 },
                    { x: 'W4', y: 32 }
                ]
            },
            {
                name: "Series 2",
                data: [
                    { x: 'W1', y: 43 },
                    { x: 'W2', y: 43 },
                    { x: 'W3', y: 43 },
                    { x: 'W4', y: 43 }
                ]
            }
        ],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true // Habilita apilamiento
        },
        plotOptions: {
            bar: {
                horizontal: false
            }
        },
        title: {
            text: "Comparación Semanal de Series"
        },
        xaxis: {
            type: 'category'
        },
        yaxis: {
            title: {
                text: "Valores"
            }
        }
    };
    let barChart = new ApexCharts(document.querySelector("#barChartGrafico"), barChartOptions);
    barChart.render();
});
$(document).ready(function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        locale: 'es',
        firstDay: 1,

        events: window.location.origin+'/clinicaveterinaria/public/eventos/obtener',

        select: function (info) {
            const title = prompt('Título del evento:');
            if (title) {
                $.ajax({
                    url: window.location.origin+'/clinicaveterinaria/public/eventos/guardar',
                    method: 'POST',
                    contentType: 'application/json',  
                    data: JSON.stringify({
                        TITULO: title,
                        FECHA_INICIO: info.startStr,
                        FECHA_FIN: info.endStr || info.startStr,
                        DESCRIPCION_ES: ''
                    }),
                    success: function () {
                        calendar.refetchEvents();
                    }
                });
                
            }
        },
    });

    calendar.render();
});

