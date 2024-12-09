<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const props = defineProps({
  fromTodayEvents: Object,
  pastEvents: Object,
});

</script>

<template>
    <Head title="本日以降のイベント一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                予約済みのイベント一覧
            </h2>
        </template>

        <FlashMessage />
        <div class="pt-12 pb-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                <h2
                class="text-xl font-semibold leading-tight text-gray-800 px-4 py-4"
                >
                    本日以降のイベント一覧
                </h2>
                    <div class="p-6 text-gray-900">
                      <section class="text-gray-600 body-font">
                        <div class="container mx-auto">
                          <div class="w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                              <thead>
                                <tr>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">イベント名</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日時</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了日時</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="event in props.fromTodayEvents" :key="event.id">
                                  <td class="px-4 py-3">
                                    <Link class="text-blue-500" :href="route('mypage.show', {id: event.id})">{{ event.name }}</Link>
                                  </td>
                                  <td class="px-4 py-3">{{ event.start_date }}</td>
                                  <td class="px-4 py-3">{{ event.end_date }}</td>
                                  <td class="px-4 py-3">{{ event.number_of_people }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </section>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-2 pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                <h2
                class="text-xl font-semibold leading-tight text-gray-800 px-4 py-4"
                >
                    過去のイベント一覧
                </h2>
                    <div class="p-6 text-gray-900">
                      <section class="text-gray-600 body-font">
                        <div class="container mx-auto">
                          <div class="w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                              <thead>
                                <tr>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">イベント名</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日時</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了日時</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="event in props.pastEvents" :key="event.id">
                                  <td class="px-4 py-3">
                                    <Link class="text-blue-500" :href="route('mypage.show', {id: event.id})">{{ event.name }}</Link>
                                  </td>
                                  <td class="px-4 py-3">{{ event.start_date }}</td>
                                  <td class="px-4 py-3">{{ event.end_date }}</td>
                                  <td class="px-4 py-3">{{ event.number_of_people }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
