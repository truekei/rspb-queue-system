<script setup>
import { ref, onMounted, computed } from "vue";
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select'
import { Button } from "@/components/ui/button";
import {
  AlertDialog,
  AlertDialogContent,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog"
import axios from "axios";

const staffList = ref([]);
const counters = ref([
  { selectedStaff: null, currentQueue: null, loading: false },
  { selectedStaff: null, currentQueue: null, loading: false },
  { selectedStaff: null, currentQueue: null, loading: false },
]);
const queueList = ref([]);
const r_count = ref(2);
const noQueueDialog = ref(false);
let pollingInterval = null;

const fetchQueue = async () => {
  try {
    const res = await axios.get('/api/queue')
    queueList.value = res.data.data
  } catch (error) {
    console.error('Gagal ambil antrian:', error)
  }
}

// Panggil / selesaikan antrian
const handleQueueAction = async (counterIndex) => {
  let counter = counters.value[counterIndex];

  if (!counter.currentQueue) {
    if (queueList.value.length === 0) {
      noQueueDialog.value = true;
      return;
    }

    // Ambil antrian berdasarkan algoritma 2R:1W
    if (queueList.value[0].type === 'R' && r_count.value < 2) {
        counter.currentQueue = queueList.value.shift().number;
        r_count.value++;
    } else if (queueList.value.length > 1 && queueList.value[1].type === 'R' && r_count.value < 2) {
        counter.currentQueue = queueList.value.splice(1, 1)[0].number;
        r_count.value++;
    } else if (queueList.value[0].type === 'W') {
        counter.currentQueue = queueList.value.shift().number;
        r_count.value = 0;
    } else {
        counter.currentQueue = queueList.value.shift().number;
        r_count.value = 1;
    }

    // Update status antrian menjadi 'called'
    try {
      await axios.post(`/api/queue/${counter.currentQueue}/called`, {
        staff_id: counter.selectedStaff
      });
    } catch (error) {
      console.error('Gagal update status antrian:', error);
    }

    // Create log antrian
    try {
      await axios.post('/api/log', {
        staff_id: counter.selectedStaff,
        queue_number: counter.currentQueue
      });
    } catch (error) {
      console.error('Gagal membuat log:', error);
    }

    // Broadcast data ke display
    try {
      await axios.post('/api/display-data', {
        counter: counterIndex,
        staff_id: counter.selectedStaff,
        queue: counter.currentQueue
      });
    } catch (error) {
      console.error('Gagal kirim data counter:', error);
    }

  } else {
    counter.loading = true;
    
    // Update status antrian menjadi 'done'
    try {
      await axios.post(`/api/queue/${counter.currentQueue}/done`);
    } catch (error) {
      console.error('Gagal update status antrian:', error);
    }

    // Update log
    try {
      await axios.post(`/api/log/${counter.currentQueue}`);
    } catch (error) {
      console.error('Gagal membuat log:', error);
    } finally {
      counter.loading = false;
      counter.currentQueue = null;
    }
    
    
    // Broadcast ke display
    try {
      await axios.post('/api/display-data', {
        counter: counterIndex,
        staff_id: counter.selectedStaff,
        queue: null
      });
    } catch (error) {
      console.error('Gagal kirim data counter:', error);
    }
  }
};

onMounted(async () => {
  // Ambil list staff dari API
  try {
    const res = await axios.get('/api/staff')
    staffList.value = res.data.data
  } catch (error) {
    console.error('Gagal ambil staff:', error)
  }

  // Ambil list antrian dari API setiap 10 detik
  fetchQueue()
  pollingInterval = setInterval(fetchQueue, 10000)
})

// Ambil semua staff_id yang sedang dipilih di loket lain
const selectedStaffIds = computed(() =>
  counters.value.map(c => c.selectedStaff).filter(id => id !== null)
)

// Cek apakah staff ini harus di-disable
const isStaffDisabled = (staffId, currentCounterId) => {
  return selectedStaffIds.value.includes(staffId) &&
         counters.value.find(l => l.id === currentCounterId)?.staff_id !== staffId
}
</script>

<template>
    <AlertDialog :open="noQueueDialog" @openChange="noQueueDialog = $event">
        <AlertDialogContent>
        <AlertDialogHeader>
            <AlertDialogTitle>Tidak Ada Antrian</AlertDialogTitle>
        </AlertDialogHeader>
        <AlertDialogFooter>
            <Button @click="noQueueDialog = false">OK</Button>
        </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
    <div class="flex-1 text-center m-4">
        <h2 class="text-lg font-bold">Panggil Antrian</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
        <Card v-for="(counter, index) in counters" :key="index" class="p-4 shadow-lg">
        <CardHeader>
            <CardTitle>Loket {{ index + 1 }}</CardTitle>
        </CardHeader>

        <CardContent>
            <!-- Dropdown pilih staff -->
            <Select v-model="counter.selectedStaff" class="mb-4">
                <label for="staff-select-{{ index }}" class="block mb-2 text-sm font-medium text-gray-900">Pilih Staff</label>
                <SelectTrigger class="w-full">
                    <SelectValue placeholder="Pilih Staff" />
                </SelectTrigger>
                <SelectContent>
                    <!-- Opsi Kosong -->
                    <SelectItem :value="null">Kosong</SelectItem>

                    <!-- Opsi Staff -->
                    <SelectItem
                        v-for="staff in staffList"
                        :key="staff.id"
                        :value="staff.id"
                        :disabled="isStaffDisabled(staff.id, index)"
                    >
                        {{ staff.name }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <!-- Display nomor antrian -->
            <div class="text-center my-4">
            <p class="text-gray-500">Nomor Antrian</p>
            <p class="text-4xl font-bold">
                {{ counter.currentQueue ?? "-" }}
            </p>
            </div>

            <!-- Tombol panggil/selesaikan -->
            <Button
              class="w-full"
              :disabled="!counter.selectedStaff || counter.loading"
              @click="handleQueueAction(index)"
            >
              {{ 
                counter.loading 
                  ? "Menyelesaikan..." 
                  : (counter.currentQueue ? "Selesaikan Antrian" : "Panggil Antrian") 
              }}
            </Button>
        </CardContent>
        </Card>
    </div>
    
</template>