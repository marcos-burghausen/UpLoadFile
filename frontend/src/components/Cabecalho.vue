<template>
    <header class="header">
        <font-awesome-icon class="mdicon" role="button" @click="$emit('expandirMenu')" icon="fa-solid fa-bars" />
        <template v-if="useAuth.isAuthenticated">
            <h3 class="text-white me-3">{{ name }}</h3>
            <v-btn @click="logout" rounded="lg" color="rgb(63, 161, 16)">Sair</v-btn>
        </template>
    </header>
</template>

<script setup lang="ts">
import { useAuthStore } from "@/store/auth";
import { useRouter } from "vue-router";
import { ref, computed } from "vue";
import authApi from "@/api/auth"

const props = defineProps({
    menuExpandido: Boolean
});
const router = useRouter();
const useAuth = useAuthStore();

let name = ref(useAuth.userName);

async function logout() {
    try {
        const res = await authApi.logout();
        useAuth.clear();
        router.push({ name: "home" });
    } catch (error) {
        console.log(error);
    }
}
</script>

<style scoped>
.header {
    padding-inline: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
    border-bottom: solid 1px rgb(63, 161, 16);
}

.mdicon {
    color: rgb(63, 161, 16);
    cursor: pointer;
    padding: 10px;
    font-size: 30px;
}
</style>