<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const props = defineProps({
  events: Object
});

</script>

<template>
    <Head title="本日以降のイベント一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                本日以降のイベント一覧
            </h2>
        </template>

        <FlashMessage />
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                      <section class="text-gray-600 body-font">
                        <div class="container mx-auto">
                          <div class="flex justify-between">
                            <Link as="button" :href="route('events.past')" class="mb-4 text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none hover:bg-gray-600 rounded">過去のイベント一覧</Link>
                            <Link as="button" :href="route('events.create')" class="mb-4 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規登録</Link>
                          </div>
                          <div class="w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                              <thead>
                                <tr>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">イベント名</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日時</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了日時</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">定員</th>
                                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">表示・非表示</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="event in props.events.data" :key="event.id">
                                  <td class="px-4 py-3">
                                    <Link class="text-blue-500" :href="route('events.show', {event: event.id})">{{ event.name }}</Link>
                                  </td>
                                  <td class="px-4 py-3">{{ event.start_date }}</td>
                                  <td class="px-4 py-3">{{ event.end_date }}</td>
                                  <td class="px-4 py-3">{{ event.number_of_people != null ? event.number_of_people : 0 }}</td>
                                  <td class="px-4 py-3">{{ event.max_people }}</td>
                                  <td class="px-4 py-3">{{ event.is_visible }}</td>
                                </tr>
                              </tbody>
                            </table>
                            <Pagination :pagination="props.events" />
                          </div>
                        </div>
                      </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
