<?php
// Include the database connection
include 'connection.php';
include 'header.php';
include "auth_check.php";

// Fetch events from the database
$sql = "SELECT name, start_time, design_time, planning_time, control_time FROM manufacturing";
$result = $conn->query($sql);

// Create an array to store the events
$events = array();

// Process the fetched events
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subEvents = array(
            array(
                'title' => $row['name'],
                'start' => $row['start_time'],
                'end' => $row['design_time'],
                'color' => '#bf4040' // Specify a color for this time interval
            ),
            array(
                'title' => $row['name'],
                'start' => $row['design_time'],
                'end' => $row['planning_time'],
                'color' => '#7fcc66' // Specify a color for this time interval
            ),
            array(
                'title' => $row['name'],
                'start' => $row['planning_time'],
                'end' => $row['control_time'],
                'color' => '#40a6bf' // Specify a color for this time interval
            )
        );

        // Add sub-events as separate events
        foreach ($subEvents as $subEvent) {
            $events[] = array(
                'title' => $subEvent['title'],
                'start' => $subEvent['start'],
                'end' => $subEvent['end'],
                'color' => $subEvent['color']
            );
        }
    }
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <style>
        #calendar {
            margin: 0 auto;
			margin-right: 250px;
			float: right;
			max-width: 1000px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 10px;
			margin-left: 30px;
			margin-top: 30px;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
        }
		h5 {
			margin-top: 30px;
			margin-left: 30px;
		}
    </style>
</head>
<body>
    <h3>Schedule Calendar</h3>
    <div id="calendar"></div>
    <div id="legend">
        <h5>Legend</h5>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #bf4040;"></div>
            Design Department
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #7fcc66;"></div>
            Planning Department
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #40a6bf;"></div>
            Control Department
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: <?php echo json_encode($events); ?>,
                eventRender: function(event, element) {
                    if (event.subEvents) {
                        for (var i = 0; i < event.subEvents.length; i++) {
                            var subEvent = event.subEvents[i];
                            var subEventElement = $('<div class="sub-event">').text(subEvent.title);
                            subEventElement.css('background-color', subEvent.color);
                            subEventElement.appendTo(element.find('.fc-content'));
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
