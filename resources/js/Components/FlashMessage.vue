<script setup>
</script>

<template>
    <div v-if="$page.props.flash.status === 'success'" class="bg-blue-300 text-white py-4">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{ $page.props.flash.message }}
      </div>
    </div>
    <div v-if="$page.props.flash.status === 'danger'" class="bg-red-300 text-white py-4">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{ $page.props.flash.message }}
      </div>
    </div>
</template>