<script setup>
import CalendarLayout from '@/Layouts/CalendarLayout.vue';
import Calendar from '@/Components/Calendar.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { Japanese } from "flatpickr/dist/l10n/ja.js";
import { ref, onMounted, onBeforeUnmount, reactive } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

import axios from 'axios';
import { customDateToString, customDateTimeToString, isEmpty } from '@/common';

const current_datetime = ref(customDateTimeToString(new Date))
const event_date = ref(customDateToString(new Date));

const week_calendar = ref([]);
const day_name = ref([]);
// const events = ref([]);
const week_of_event = ref([]);

let fp_event_date = null;
let fp_start_time = null;
let fp_end_time = null;

const fp_time_settings = {
    "locale": Japanese,
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    minTime: "10:00",
    maxTime: "20:00",
    minuteIncrement: 30,
}

onMounted(() => {
    fp_event_date = flatpickr("#event_date", {
        "locale": Japanese,
        maxDate: new Date().fp_incr(30),
        onReady: function (selectedDates, dateStr, instance) {
            const yearElement = instance.calendarContainer.querySelector(".cur-year");
            if (yearElement) {
                const yearLabel = document.createElement("span");
                yearLabel.textContent = "年";
                yearElement.parentNode.before(yearLabel);
            }
        },
    });
    fp_start_time = flatpickr("#start_time", fp_time_settings);
    fp_end_time = flatpickr("#end_time", fp_time_settings);
    getCalendar();
});

onBeforeUnmount(() => {
    if (!isEmpty(fp_event_date)) {
        fp_event_date.destroy();
    }
    if (!isEmpty(fp_start_time)) {
        fp_start_time.destroy();
    }
    if (!isEmpty(fp_end_time)) {
        fp_end_time.destroy();
    }
});

const getCalendar = async () => {
    try {
        await axios.get('/api/calendar/', {
            params: {
                start_date: event_date.value
            }
        }).then(
            res => {
                week_calendar.value = res.data.week_calendar.slice();
                day_name.value = res.data.day_name.slice();
                // events.value = res.data.events.slice();
                week_of_event.value = res.data.week_of_event.slice();
                // console.log(res.data.week_of_event);
                // console.log(res.data.day_of_event);
            }
        )
    } catch (error) {
        
    }
}
</script>

<template>
    <Head title="イベントカレンダー" />

    <CalendarLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                イベントカレンダー
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- <div class="event-calendar border border-red-400 mx-auto sm:px-6 lg:px-8 bg-orange-400">あ</div> -->
                <div
                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                <div class="p-6 text-gray-900">
                        <div class="text-center text-sm">
                            日付を選択してください。本日から最大30日先まで選択可能です。
                        </div>
                        <TextInput
                            id="event_date"
                            type="text"
                            class="mt-1 mb-1 block mx-auto"
                            v-model="event_date"
                            @input="getCalendar"
                        />
                        <!-- <div>{{ current_datetime }}</div> -->
                        <Calendar :week_calendar="week_calendar" :day_name="day_name" :week_of_event="week_of_event"/>
                        <!-- <div v-for="event in events" :key="event.id">
                            {{ event.start_date }} {{ event.name }}
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </CalendarLayout>
</template>

<style scoped>
.event-calendar {
    width: 1000px;
}
</style>