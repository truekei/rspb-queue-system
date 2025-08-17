<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card'
import { ToggleGroup, ToggleGroupItem } from "@/components/ui/toggle-group"
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog'
import { ChevronLeft } from "lucide-vue-next"
import axios from 'axios'

const type = ref('')
const queueNumber = ref('')
const loading = ref(false)
const showDialog = ref(false)

const takeQueue = async () => {
  if (!type.value) return
  loading.value = true
  try {
    const res = await axios.post('/api/queue', {
      type: type.value
    })
    queueNumber.value = res.data.data.number
    showDialog.value = true
  } catch (error) {
    console.error(error)
    alert('Gagal mengambil nomor antrian')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="flex justify-start bg-gray-100 p-4">
    <Link :href="route('home')">
      <Button class="h-10"><ChevronLeft class="w-4 h-4" /> Kembali</Button>
    </Link>
  </div>
  <div class="flex flex-col items-center justify-center bg-gray-100 p-4">
    <Card class="w-full max-w-lg">
      <CardHeader>
        <CardTitle class="text-xl font-bold">Pengambilan Nomor Antrian</CardTitle>
        <CardDescription>Pilih jenis antrian yang ingin diambil</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="flex flex-col gap-4">
            <ToggleGroup type="single" v-model="type" class="w-full">
            <ToggleGroupItem 
              value="R" 
              class="bg-green-600 hover:bg-green-500 data-[state=on]:bg-green-700"
              >
              <span class="text-white">Reservasi</span>
            </ToggleGroupItem>
            <ToggleGroupItem 
              value="W" 
              class="bg-blue-600 hover:bg-blue-500 data-[state=on]:bg-blue-700"
            >
              <span class="text-white">Walk-in</span>
            </ToggleGroupItem>
            </ToggleGroup>
          <Button @click="takeQueue" :disabled="loading || !type">
            {{ loading ? 'Mengambil...' : 'Ambil Nomor' }}
          </Button>
        </div>
      </CardContent>
    </Card>

    <!-- Dialog untuk menampilkan nomor antrian -->
    <Dialog v-model:open="showDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Nomor Antrian Anda</DialogTitle>
          <DialogDescription>
            Silakan tunggu hingga nomor Anda dipanggil.
          </DialogDescription>
        </DialogHeader>
        <div class="text-center my-6">
          <p class="text-4xl font-bold">{{ queueNumber }}</p>
        </div>
        <DialogFooter>
          <Button @click="showDialog = false">OK</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>