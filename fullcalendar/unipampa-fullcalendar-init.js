document.addEventListener('DOMContentLoaded', function() {

    var eventosCriados = eventosInit.eventosCriados;
    var eventosJson = JSON.parse(eventosCriados);

    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        themeSystem: 'bootstrap',
        height: 'auto',
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        fixedWeekCount: false,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        businessHours: {
            daysOfWeek: [0,1,2,3,4,5,6],
            startTime: '08:00',
            endTime: '18:00',
        },
        eventDidMount: function (info) {
            info.el.title = info.event.title;
            /* var tooltip = new Tooltip(info.el, {
                title: info.event.title,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            }); */
        },
        events: eventosJson,
        viewClassNames: function(){
            var calButtons = document.getElementsByClassName("btn-primary");
            var i;
            for (i = 0; i < calButtons.length; i++) {
                calButtons[i].classList.add("btn-verde-unipampa");
            }
        }
    });

    calendar.render();

});
