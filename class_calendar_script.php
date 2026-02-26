<script>
$(function() {

    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        locale: 'es',
        droppable: true,
        drop: function(date, allDay) {

            var originalEventObject = $(this).data('eventObject');

            var copiedEventObject = $.extend({}, originalEventObject);

            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;


            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            if ($('#drop-remove').is(':checked')) {

                $(this).remove();
            }

        },
        editable: true,

        events: [
            <?php
                try {
                    $event_query = $pdo_conn->prepare("SELECT * FROM event WHERE teacher_class_id = :teacher_class_id OR teacher_class_id = ''");
                    $event_query->bindParam(':teacher_class_id', $get_id, PDO::PARAM_INT);
                    $event_query->execute();
                    while ($event_row = $event_query->fetch(PDO::FETCH_ASSOC)) {
                        $title = htmlspecialchars($event_row['event_title']);
                        $start = htmlspecialchars($event_row['date_start']);
                        $end = htmlspecialchars($event_row['date_end']);
                ?> {
                title: '<?php echo $title; ?>',
                start: '<?php echo $start; ?>',
                end: '<?php echo $end; ?>'
            },
            <?php }
                } catch (PDOException $e) {
                    echo "Error al obtener los eventos: " . $e->getMessage();
                } ?>
        ]

    });
});
</script>