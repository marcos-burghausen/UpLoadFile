<template>
  <TheContainer>
    <Login v-if="step === 0" @next-step="step = 1" />
    <Register v-if="step === 1" @next-step="step = 0" />
  </TheContainer>
</template>

<script setup lang="ts">
import TheContainer from "@/components/Container.vue";
import Login from "@/views/access/LoginView.vue";
import Register from "@/views/access/RegisterView.vue";


import { type Ref, ref } from 'vue';

const step = ref(0);


</script>

<style scoped></style>
