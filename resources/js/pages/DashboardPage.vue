<script setup>
import { ref, onMounted } from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

const dashboardData = ref({
  activeQueues: 0,
  activeStaff: 0,
  topStaff: []
});

const loading = ref(true);

async function fetchDashboard() {
  loading.value = true;
  try {
    const res = await fetch("/api/dashboard");
    const data = await res.json();
    dashboardData.value = data.data;
  } catch (error) {
    console.error("Error fetching dashboard:", error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchDashboard();
});
</script>

<template>
    <div class="p-6 space-y-6 bg-gray-100">
        <div class="flex">
            <h1 class="text-2xl font-bold">Dashboard Antrian</h1>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <Card>
            <CardHeader>
            <CardTitle>Jumlah Antrian Aktif</CardTitle>
            </CardHeader>
            <CardContent>
                <p class="text-3xl font-bold">{{ dashboardData.activeQueues.R + dashboardData.activeQueues.W }}</p>
                <div class="grid grid-cols-2 grid-rows-1 gap-4 pt-4">
                    <Card class="w-full max-w-lg">
                        <CardHeader>
                            <CardTitle>Reservasi</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-3xl font-bold">{{ dashboardData.activeQueues.R }}</p>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle>Walk-in</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-3xl font-bold">{{ dashboardData.activeQueues.W }}</p>
                        </CardContent>
                    </Card>
                </div>
            </CardContent>
        </Card>

        <div class="grid grid-col-1 gap-4">
            <div class="grid grid-cols-3 gap-4">
                <Card>
                    <CardHeader>
                    <CardTitle>Jumlah Staff Aktif</CardTitle>
                    </CardHeader>
                    <CardContent>
                    <p class="text-3xl font-bold">{{ dashboardData.activeStaff.length }}</p>
                    </CardContent>
                </Card>
                <Card class="col-span-2">
                    <CardHeader>
                    <CardTitle>Top 3 Staff</CardTitle>
                    </CardHeader>
                    <CardContent>
                    <p 
                    class="text-3xl font-bold"
                    v-for="staff, index in dashboardData.topStaff"
                    :key="staff.id"
                    >
                        {{ index + 1 }}. {{ staff.name }}</p>
                    </CardContent>
                </Card>
            </div>
            <Button :disabled="loading" @click="fetchDashboard">
                {{ loading ? "Loading..." : "Refresh Data" }}
            </Button>
        </div>
        </div>

        <!-- Top Staff -->
        <Card>
        <CardHeader>
            <CardTitle>Daftar Staff Aktif</CardTitle>
        </CardHeader>
        <CardContent>
            <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b">
                <th class="py-2 px-4">Nama</th>
                <th class="py-2 px-4">Antrian Dilayani</th>
                <th class="py-2 px-4">Rata-rata Waktu Pelayanan</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="staff in dashboardData.activeStaff" :key="staff.id" class="border-b">
                <td class="py-2 px-4">{{ staff.name }}</td>
                <td class="py-2 px-4">{{ staff.total_served }}</td>
                <td class="py-2 px-4">
                    {{ staff.time_average ? Math.round(staff.time_average * 100) / 100 + ' detik' : '-' }}
                </td>
                </tr>
            </tbody>
            </table>
        </CardContent>
        </Card>
    </div>
</template>