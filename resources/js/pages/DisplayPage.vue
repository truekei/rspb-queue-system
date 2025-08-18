<script setup>
import { ref, onMounted } from 'vue'
import { useEchoPublic } from '@laravel/echo-vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { ChevronLeft, ChevronRight } from "lucide-vue-next"
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
        if (e.announce) {
            playBell();
        }
        fetchQueue();
    },
);
useEchoPublic(
    'public-queue-channel',
    "LoadDisplayCountersEvent",
    (e) => {
        counters.value = e.counters;
    },
);

const updateCounterDisplay = (counterIndex, staffName, queueNumber) => {
    const counter = counters.value[counterIndex];
    counter.selectedStaff = staffName;
    counter.currentQueue = queueNumber;
};

const playBell = () => {
    const audio = new Audio('/sounds/bell.mp3');
    audio.volume = 0.5;
    audio.play().catch(error => {
        console.error('Gagal memutar suara:', error);
    });
};

onMounted(() => {
    fetchQueue();

    // Kirim broadcast ke pemanggilan
    axios.post('/api/display-load').catch(error => {
        console.error('Error sending broadcast:', error);
    });
})
</script>

<template>
    <div class="p-6 space-y-6 bg-gray-100">
        <div class="flex">
            <div class="flex-1 text-start">
                <Link :href="route('dashboard')">
                    <Button><ChevronLeft class="w-4 h-4" /> Lihat Dashboard</Button>
                </Link>
            </div>
            <div class="flex-1 text-center">
                <p class="text-2xl font-bold">Sistem Antrian Digital</p>
            </div>
            <div class="flex-1 text-end">
                <Link :href="route('call')">
                    <Button>Panggil Antrian <ChevronRight class="w-4 h-4" /></Button>
                </Link>
            </div>
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