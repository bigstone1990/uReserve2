<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NumberInput from '@/Components/NumberInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { Japanese } from "flatpickr/dist/l10n/ja.js"
import { ref, onMounted, onBeforeUnmount } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import TextLabel from '@/Components/TextLabel.vue';
import { nl2br } from '@/common';

const props = defineProps({
    event: Object,
    event_date: String,
    start_time: String,
    end_time: String,
    reservable_people: Number,
    isReserved: Object
});

const reserve_people = ref(1);

const page = usePage();

const form = useForm({
    event_id: props.event.id,
    user_id: page.props.auth.user.id,
    reserve_people: reserve_people.value,
})

const submit = () => {
    form.post(route('events.reserve', {id: form.event_id}));
}

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
                            <form @submit.prevent="submit">
                                <div>
                                    <TextLabel value="イベント名" />
                                    <div class="mt-1 block w-full">{{ props.event.name }}</div>
                                </div>
    
                                <div class="mt-4">
                                    <TextLabel value="イベント詳細" />
                                    <div class="mt-1 block w-full" v-html="nl2br(props.event.information)"></div>
                                </div>
    
                                <div class="md:flex justify-between">
                                    <div class="mt-4">
                                        <TextLabel value="イベント日付" />
                                        <div class="mt-1 block w-full">{{ props.event_date }}</div>
                                    </div>
        
                                    <div class="mt-4">
                                        <TextLabel value="開始時間" />
                                        <div class="mt-1 block w-full">{{ props.start_time }}</div>
                                    </div>
        
                                    <div class="mt-4">
                                        <TextLabel value="終了時間" />
                                        <div class="mt-1 block w-full">{{ props.end_time }}</div>
                                    </div>
                                </div>
    
                                <div class="flex justify-between items-center mt-4">
                                    <div>
                                        <TextLabel value="定員数" />
                                        <div class="mt-1 block w-full">{{ props.event.max_people }}</div>
                                    </div>
                                </div>
    
                                <div class="flex justify-between items-center mt-4">
                                    <div>
                                        <TextLabel value="予約人数" />
                                        <div class="mt-1 block w-full">
                                            <template v-if="props.reservable_people <= 0">
                                                <span>このイベントは満員です</span>
                                            </template>
                                            <template v-else>
                                                <select v-model="reserve_people">
                                                    <option v-for="people in props.reservable_people" :key="people" :value="people">{{ people }}</option>
                                                </select>
                                            </template>
                                        </div>
                                    </div>
                                </div>
    
                                <div v-if="props.isReserved !== null">
                                    <span>このイベントはすでに予約済みです</span>
                                </div>
                                <div v-else-if="props.reservable_people > 0" class="mt-4 flex items-center justify-center">
                                    <PrimaryButton
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                    >
                                        予約する
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>