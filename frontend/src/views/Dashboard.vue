<template>
  <!-- <TheContainer> -->
  <Cabecalho v-on:expandirMenu="menuExpandido = !menuExpandido" :menuExpandido="menuExpandido" />
  <div style="display: flex; height: calc(100vh - 60px);">
    <MenuLateral :menuExpandido="menuExpandido" @updateStep="updateStep" />
    <div class="container__body">
      <Upload v-if="step === 0" @next-step="step = 0" />
      <Data v-if="step === 1" @next-step="step = 1" />
    </div>
  </div>
  <!-- </TheContainer> -->
</template>

<script setup lang="ts">
import Data from "@/views/data/DataView.vue";
import Upload from "@/views/upload/UploadView.vue";
import MenuLateral from "@/components/MenuLateral.vue"
import Cabecalho from "@/components/Cabecalho.vue";
import { ref } from "vue";

const step = ref(0);
const menuExpandido = ref(false);

const updateStep = (newValue: number) => {
  step.value = newValue;
  menuExpandido.value = !menuExpandido;
}

</script>
<style scoped>
.container__body {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>