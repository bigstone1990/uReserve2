<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NumberInput from '@/Components/NumberInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { Japanese } from "flatpickr/dist/l10n/ja.js"
import { ref, onMounted, onBeforeUnmount } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    event_name: '',
    information: '',
    event_date: '',
    start_time: '',
    end_time: '',
    max_people: 1,
    is_visible: 1,
})

const submit = () => {
    form.post(route('events.store'));
};

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
}

onMounted(() => {
    fp_event_date = flatpickr("#event_date", {
        "locale": Japanese,
        minDate: "today",
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
});

onBeforeUnmount(() => {
    if (fp_event_date) {
        fp_event_date.destroy();
    }
    if (fp_start_time) {
        fp_start_time.destroy();
    }
    if (fp_end_time) {
        fp_end_time.destroy();
    }
});

</script>

<template>
    <Head title="イベント新規登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                イベント新規登録
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
                            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                                {{ status }}
                            </div>
    
                            <form @submit.prevent="submit">
                                <div>
                                    <InputLabel for="event_name" value="イベント名" />
    
                                    <TextInput
                                        id="event_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.event_name"
                                        required
                                        autofocus
                                    />
    
                                    <InputError class="mt-2" :message="form.errors.event_name" />
                                </div>
    
                                <div class="mt-4">
                                    <InputLabel for="information" value="イベント詳細" />
    
                                    <TextareaInput
                                        id="information"
                                        class="mt-1 block w-full"
                                        v-model="form.information"
                                        required
                                        rows="3"
                                    />
    
                                    <InputError class="mt-2" :message="form.errors.information" />
                                </div>

                                <div class="md:flex justify-between">
                                    <div class="mt-4">
                                        <InputLabel for="event_date" value="イベント日付" />
        
                                        <TextInput
                                            id="event_date"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.event_date"
                                            required
                                        />
        
                                        <InputError class="mt-2" :message="form.errors.event_date" />
                                    </div>
        
                                    <div class="mt-4">
                                        <InputLabel for="start_time" value="開始時間" />
        
                                        <TextInput
                                            id="start_time"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.start_time"
                                            required
                                        />
        
                                        <InputError class="mt-2" :message="form.errors.start_time" />
                                    </div>
        
                                    <div class="mt-4">
                                        <InputLabel for="end_time" value="終了時間" />
        
                                        <TextInput
                                            id="end_time"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.end_time"
                                            required
                                        />
        
                                        <InputError class="mt-2" :message="form.errors.end_time" />
                                    </div>
                                </div>
    
                                <div class="flex justify-between items-center mt-4">
                                    <div>
                                        <InputLabel for="max_people" value="定員数" />
            
                                        <NumberInput
                                            id="max_people"
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="form.max_people"
                                            required
                                        />
    
                                        <InputError class="mt-2" :message="form.errors.max_people" />
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" value="1" v-model="form.is_visible" class="mr-1">表示
                                        <input type="radio" value="0" v-model="form.is_visible" class="ml-4 mr-1">非表示
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-center">
                                    <PrimaryButton
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                    >
                                        新規登録
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
.flatpickr-current-month {
    display: flex !important;
    flex-direction: row-reverse !important;
    align-items: center !important;
    justify-content: center !important;
}
</style>