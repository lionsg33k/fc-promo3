<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form class="hidden" method="post" class="" action="{{ route('calendar.store') }}">
                @csrf
                <input name="start" id="start" type="datetime-local">
                <input name="end" id="end" type="datetime-local">
                <button id="submitEvent">submit</button>
            </form>

            <div class="">
                <form class="hidden" id="updateForm" method="post" action="">
                    @csrf @method('PUT')
                    <input id="updatedStart" name="start" type="hidden">
                    <input id="updatedEnd" name="end" type="hidden">
                    <button id="submitUpdate"></button>
                </form>
            </div>
            @include('components.delete_event')

            <div class="w-full h-[90vh] bg-white rounded-3xl border-none p-3" id="calendar"></div>


            <script>
                document.addEventListener('DOMContentLoaded', async function() {
                    let response = await axios.get("/calendar/create")
                    let events = response.data.events

                    let nowDate = new Date()
                    let day = nowDate.getDate()
                    let month = nowDate.getMonth() + 1
                    let hours = nowDate.getHours()
                    let minutes = nowDate.getMinutes()
                    let minTimeAllowed =
                        `${nowDate.getFullYear()}-${month < 10 ? "0"+month : month}-${day < 10 ? "0"+day : day}T${hours < 10 ? "0"+hours : hours}:${minutes < 10 ? "0"+minutes : minutes}:00`
                    start.min = minTimeAllowed;


                    var myCalendar = document.getElementById('calendar');



                    var calendar = new FullCalendar.Calendar(myCalendar, {

                        headerToolbar: {
                            left: 'prev,next,dayGridMonth,timeGridWeek,timeGridDay',
                            center: 'title',
                            right: 'listMonth,listWeek,listDay'
                        },


                        views: {
                            listDay: { // Customize the name for listDay
                                buttonText: 'Day Events',

                            },
                            listWeek: { // Customize the name for listWeek
                                buttonText: 'Week Events'
                            },
                            listMonth: { // Customize the name for listMonth
                                buttonText: 'Month Events'
                            },
                            timeGridWeek: {
                                buttonText: 'Week', // Customize the button text
                            },
                            timeGridDay: {
                                buttonText: "Day",
                            },
                            dayGridMonth: {
                                buttonText: "Month",
                            },

                        },


                        initialView: "timeGridWeek", // initial view  =   l view li kayban  mni kan7ol l  calendar
                        slotMinTime: "09:00:00", // min  time  appear in the calendar
                        slotMaxTime: "19:00:00", // max  time  appear in the calendar
                        nowIndicator: true, //  indicator  li kaybyen  l wa9t daba   fin  fl calendar
                        selectable: true, //   kankhali l user  i9ad  i selectioner  wast l calendar
                        selectMirror: true, //  overlay   that show  the selected area  ( details  ... )
                        selectOverlap: false, //  nkhali ktar mn event f  nfs  l wa9t  = e.g:   5 nas i reserviw nfs lblasa  f nfs l wa9t
                        weekends: true, // n7ayed  l weekends    ola nkhalihom 
                        editable: true,
                        droppable: true,


                        // events  hya  property dyal full calendar
                        //  kat9bel array dyal objects  khass  i kono jayin 3la chkl  object fih  start  o end  7it hya li kayfahloha
                        events: events,

                        eventDrop: (info) => {
                            updateEvent(info)

                        },
                        eventResize: (info) => {

                            updateEvent(info)
                        },

                        eventClick: (info) => {

                            let eventId = info.event._def.publicId

                            if (validateOwner(info)) {
                                deleteEventForm.action = `/calendar/delete/${eventId}`
                                deleteEventBtn.click()
                            }

                        },

                        selectAllow: (info) => {

                            return info.start >= nowDate;
                        },

                        select: (info) => {
                            console.log(info);


                            if (info.end.getDate() - info.start.getDate() > 0 && !info.allDay) {
                                return
                            }

                            if (info.allDay) {
                                start.value = info.startStr + " 09:00:00"
                                end.value = info.startStr + " 19:00:00"

                            } else {
                                start.value = info.startStr.slice(0, info.startStr.length - 6)
                                end.value = info.endStr.slice(0, info.endStr.length - 6)
                            }

                            submitEvent.click()
                        },

                    });

                    calendar.render();

                    function updateEvent(info) {

                        let eventInfo = info.event._def
                        let eventTime = info.event._instance.range

                        if (eventTime.start > nowDate && validateOwner(info)) {
                            function formattedDate(time) {
                                let date = new Date(time);
                                return date.toISOString().slice(0, 19);
                            }

                            updatedStart.value = formattedDate(eventTime.start)
                            updatedEnd.value = formattedDate(eventTime.end)



                            updateForm.action = `/calendar/update/${eventInfo.publicId}`

                            submitUpdate.click()

                        } else {
                            window.location.reload()
                        }
                    };

                    function validateOwner(info) {
                        let owner = info.event._def.extendedProps.owner
                        let authUser = `{{ Auth::user()->id }}`

                        return owner == authUser
                    }


                })
            </script>


        </div>
    </div>


</x-app-layout>
