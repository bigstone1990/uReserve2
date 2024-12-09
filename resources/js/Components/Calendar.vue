<script setup>
import CalendarTime from '@/Components/CalendarTime.vue';
import CalendarDay from '@/Components/CalendarDay.vue';

defineProps({
  week_calendar: Array,
  day_name: Array,
  week_of_event: Array,
});

</script>

<template>
  <div class="flex justify-between">
    <CalendarTime class="border-s border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[0]" :day_name="day_name[0]" :events="week_of_event[0]" class="flex-1 border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[1]" :day_name="day_name[1]" :events="week_of_event[1]" class="flex-1 border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[2]" :day_name="day_name[2]" :events="week_of_event[2]" class="flex-1 border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[3]" :day_name="day_name[3]" :events="week_of_event[3]" class="flex-1 border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[4]" :day_name="day_name[4]" :events="week_of_event[4]" class="flex-1 border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[5]" :day_name="day_name[5]" :events="week_of_event[5]" class="flex-1 border-t border-b border-green-400" />
    <CalendarDay :day="week_calendar[6]" :day_name="day_name[6]" :events="week_of_event[6]" class="flex-1 border-e border-t border-b border-green-400" />
  </div>
</template>

<style scoped></style>