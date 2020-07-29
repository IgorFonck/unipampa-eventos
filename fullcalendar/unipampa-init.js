document.addEventListener('DOMContentLoaded', function() {

    var eventosCriados = eventosInit.eventosCriados;
    var eventosJson = JSON.parse(eventosCriados);

    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        themeSystem: 'bootstrap',
        /*headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth, timeGridWeek'
        },*/
        events: eventosJson,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        }
        
    });

    calendar.render();

});