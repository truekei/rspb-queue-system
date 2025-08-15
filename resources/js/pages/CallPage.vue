<script setup>
import { ref, onMounted, computed } from "vue";
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select'
import { Button } from "@/components/ui/button";
import { ChevronRight } from "lucide-vue-next";
import axios from "axios";

const staffList = ref([]);
const counters = ref([
  { selectedStaff: null, currentQueue: null },
  { selectedStaff: null, currentQueue: null },
  { selectedStaff: null, currentQueue: null },
]);

// Panggil / selesaikan antrian
const handleQueueAction = async (counterIndex) => {
  let counter = counters.value[counterIndex];

  if (!counter.currentQueue) {
    // Panggil antrian baru
    const res = await axios.post("/api/queue/call", {
      staff_id: counter.selectedStaff,
    });
    counter.currentQueue = res.data.number;
  } else {
    // Selesaikan antrian
    await axios.post("/api/queue/finish", {
      staff_id: counter.selectedStaff,
      queue_number: counter.currentQueue,
    });
    counter.currentQueue = null;
  }
};

// Ambil list staff dari API
onMounted(async () => {
  try {
    const res = await axios.get('/api/staff')
    staffList.value = res.data.data
  } catch (error) {
    console.error('Gagal ambil staff:', error)
  }
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
            :disabled="!counter.selectedStaff"
            @click="handleQueueAction(index)"
            >
            {{ counter.currentQueue ? "Selesaikan Antrian" : "Panggil Antrian" }}
            </Button>
        </CardContent>
        </Card>
    </div>
</template>