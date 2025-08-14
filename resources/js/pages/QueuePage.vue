<script setup>
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog'
import axios from 'axios'

const type = ref('')
const queueNumber = ref('')
const loading = ref(false)
const showDialog = ref(false)

const takeQueue = async () => {
  if (!type.value) return
  loading.value = true
  try {
    const res = await axios.post('http://localhost:8000/api/queue', {
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
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
    <Card class="w-full max-w-md">
      <CardHeader>
        <CardTitle class="text-xl font-bold">Pengambilan Nomor Antrian</CardTitle>
        <CardDescription>Pilih jenis antrian yang ingin diambil</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="flex flex-col gap-4">
          <Select v-model="type">
            <SelectTrigger>
              <SelectValue placeholder="Pilih jenis antrian" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="R">Reservasi</SelectItem>
              <SelectItem value="W">Walk-in</SelectItem>
            </SelectContent>
          </Select>
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