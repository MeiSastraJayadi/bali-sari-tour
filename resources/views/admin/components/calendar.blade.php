<!-- Calendar Card -->

<style>
    .fc-day-today {
      background-color: #8cc1f7 !important; /* Dark gray (Bootstrap dark) */
      color: #fff !important;               /* White text */
    }
</style>

<div class="col-lg-6 col-md-6 col-sm-6 col-12">
    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Calendar</h3>
        </div>
        <div class="card-body p-0">
          <!-- Calendar Container -->
          <div id="calendar" style="width: 100%"></div>
        </div>
    </div>    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      var calendarEl = document.getElementById('calendar');
  
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // month view
        headerToolbar: false,        // hide nav toolbar
        selectable: false,           // disable selection
        editable: false,             // disable editing
        dayMaxEvents: false,         // no event limit indicator
        events: []                   // empty events (just calendar)
      });
  
      calendar.render();
    });
</script>