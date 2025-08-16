<script setup>
import { ref, onMounted } from 'vue'
import { useEchoPublic } from '@laravel/echo-vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { ChevronRight } from "lucide-vue-next"
import axios from "axios";

const counters = ref([
    { selectedStaff: null, currentQueue: null },
    { selectedStaff: null, currentQueue: null },
    { selectedStaff: null, currentQueue: null },
]);
const queueList = ref([]);

const fetchQueue = async () => {
    try {
        const res = await axios.get('/api/queue')
        queueList.value = res.data.data
    } catch (error) {
        console.error('Gagal ambil antrian:', error)
    }
}

useEchoPublic(
    'public-queue-channel',
    "CallQueueEvent",
    (e) => {
        updateCounterDisplay(e.counter, e.staffName, e.queueNumber);
        fetchQueue();
    },
);

const updateCounterDisplay = (counterIndex, staffName, queueNumber) => {
    const counter = counters.value[counterIndex];
    counter.selectedStaff = staffName;
    counter.currentQueue = queueNumber;
};

onMounted(() => {
    fetchQueue();
})
</script>

<template>
    <div class="p-6 space-y-6">
        <div class="flex-1 text-center">
            <p class="text-xl font-bold">Sistem Antrian Digital</p>
        </div>
        <!-- Loket Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
            <Card v-for="(counter, index) in counters" :key="index" class="p-4 shadow-lg">
            <CardHeader>
                <CardTitle>Loket {{ index + 1 }}</CardTitle>
            </CardHeader>

            <CardContent>
                <!-- Display staff -->
                <div class="my-4">
                <span class="text-gray-500">Staff: </span>
                <span class="font-bold">
                    {{ counter.selectedStaff ?? "-" }}
                </span>
                </div>
                <!-- Display nomor antrian -->
                <div class="text-center my-4">
                <p class="text-gray-500">Nomor Antrian</p>
                <p class="text-4xl font-bold">
                    {{ counter.currentQueue ?? "-" }}
                </p>
                </div>
            </CardContent>
            </Card>
        </div>

        <!-- Waiting Queue Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Reservasi -->
        <Card>
            <CardHeader>
            <CardTitle>Reservasi (R)</CardTitle>
            </CardHeader>
            <CardContent>
            <ul>
                <li
                v-for="q in queueList.filter(q => q.type === 'R' && q.status === 'waiting')"
                :key="q.number"
                class="border-b py-1"
                >
                {{ q.number }}
                </li>
            </ul>
            </CardContent>
        </Card>

        <!-- Walk-in -->
        <Card>
            <CardHeader>
            <CardTitle>Walk-in (W)</CardTitle>
            </CardHeader>
            <CardContent>
            <ul>
                <li
                v-for="q in queueList.filter(q => q.type === 'W' && q.status === 'waiting')"
                :key="q.number"
                class="border-b py-1"
                >
                {{ q.number }}
                </li>
            </ul>
            </CardContent>
        </Card>
        </div>

        <div class="flex justify-end">
        <Link :href="route('queue')">
        <Button class="h-10">Ambil Antrian <ChevronRight class="w-4 h-4" /></Button>
        </Link>
        </div>
    </div>
</template>