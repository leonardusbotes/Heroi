<script setup>
import Button from 'primevue/button';

import Dialog from 'primevue/dialog';

import Knob from 'primevue/knob';


import { ref } from 'vue';
const visible = ref(false);

</script>

<template>
  <div class="p-6 space-y-4">
    <h1 class="text-xl font-bold">PrimeVue Test</h1>
    <Button label="Show" @click="visible = true" />
    <Knob v-model="value" />

<Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
    <div class="flex items-center gap-4 mb-4">
        <label for="username" class="font-semibold w-24">Username</label>
    </div>
    <div class="flex items-center gap-4 mb-8">
        <label for="email" class="font-semibold w-24">Email</label>
    </div>
    <div class="flex justify-end gap-2">
        <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
        <Button type="button" label="Save" @click="visible = false"></Button>
    </div>
</Dialog>
  </div>
</template>