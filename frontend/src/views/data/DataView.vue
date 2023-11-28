<template>
    <!-- <TheContainer> -->
    <Consultas v-if="step === 0" @next-step="step = 1" />
    <MovCoopAgView v-if="step === 1" @next-step="step = 0" />
    <!-- </TheContainer> -->
</template>

<script setup lang="ts">
import TheContainer from "@/components/Container.vue";
import Consultas from "@/views/data/ConsultasView.vue"
import MovCoopAgView from "@/views/data/MovCoopAgView.vue";

import { ref } from "vue";

const step = ref(0);


</script>
<style scoped></style>