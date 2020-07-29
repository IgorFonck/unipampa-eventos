document.addEventListener('DOMContentLoaded', function() {

    var eventosCriados = eventosInit.eventosCriados;
    var eventosJson = JSON.parse(eventosCriados);
    //console.log(eventosCriados);

    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        themeSystem: 'bootstrap',
        events: eventosJson,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        eventRender: function (event, element) {
            $(element).tooltip({ title: event.title });
        }
    });

    calendar.render();

});