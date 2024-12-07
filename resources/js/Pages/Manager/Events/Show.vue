<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NumberInput from '@/Components/NumberInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { Japanese } from "flatpickr/dist/l10n/ja.js"
import { ref, onMounted, onBeforeUnmount } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import TextLabel from '@/Components/TextLabel.vue';
import { nl2br } from '@/common';

defineProps({
    event: Object,
    users: Object,
    event_date: String,
    start_time: String,
    end_time: String,
    reservations: Array,
});

const formatDateTime = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}年${month}月${day}日 ${hours}時${minutes}分`;
}

</script>

<template>
    <Head title="イベント詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                イベント詳細
            </h2>
        </template>
        <FlashMessage />
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="max-w-2xl mx-auto">    
                            <div>
                                <TextLabel value="イベント名" />
                                <div class="mt-1 block w-full">{{ event.name }}</div>
                            </div>

                            <div class="mt-4">
                                <TextLabel value="イベント詳細" />
                                <div class="mt-1 block w-full" v-html="nl2br(event.information)"></div>
                            </div>

                            <div class="md:flex justify-between">
                                <div class="mt-4">
                                    <TextLabel value="イベント日付" />
                                    <div class="mt-1 block w-full">{{ event_date }}</div>
                                </div>
    
                                <div class="mt-4">
                                    <TextLabel value="開始時間" />
                                    <div class="mt-1 block w-full">{{ start_time }}</div>
                                </div>
    
                                <div class="mt-4">
                                    <TextLabel value="終了時間" />
                                    <div class="mt-1 block w-full">{{ end_time }}</div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <div>
                                    <TextLabel value="定員数" />
                                    <div class="mt-1 block w-full">{{ event.max_people }}</div>
                                </div>
                                <div>
                                    <TextLabel value="表示・非表示" />
                                    <div v-if="event.is_visible" class="mt-1 block w-full">表示</div>
                                    <div v-else class="mt-1 block w-full">非表示</div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-center">
                                <Link v-if="(event_date + ' ' + start_time) >= formatDateTime(new Date)" as="button" :href="route('events.edit', {event: event.id})" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">編集</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="users.length !== 0" class="py-4">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="max-w-2xl mx-auto">
                            <TextLabel value="予約状況" />
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">予約者名</th>
                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">予約人数</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="reservation in reservations" :key="reservation.id">
                                        <template v-if="reservation.canceled_date == null">
                                            <td class="px-4 py-3">{{ reservation.name }}</td>
                                            <td class="px-4 py-3">{{ reservation.number_of_people }}</td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>