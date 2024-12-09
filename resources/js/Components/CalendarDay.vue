<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  day: String,
  day_name: String,
  events: Object
});
</script>

<template>
  <div>
    <div class="py-1 px-2 border border-gray-200 text-center">{{ day }}</div>
    <div class="py-1 px-2 border border-gray-200 text-center">{{ day_name }}</div>
    <div v-for="event in events" :key="event.index" :class="{'bg-green-400': event.reserved}" class="py-1 px-2 h-8 border border-gray-200">
      <Link v-if="event.id" :href="route('events.detail', {id: event.id})">{{ event.name }}</Link>
    </div>
  </div>
</template>