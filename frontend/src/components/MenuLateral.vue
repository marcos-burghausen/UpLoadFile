<template>
    <div>
        <nav class="menu-lateral" :class="{ expandido: menuExpandido }">
            <div class="container__logo">
            </div>
            <ul>
                <li v-for="(item, index) in itensSideBar" :class="{ efeitoClick: elementoAtivoSideBar === index }"
                    :key="index" @click="elementoAtivoSideBar = index; stepNewValue(index);">
                    <span class="txt__link"
                        :class="{ displayNone: !menuExpandido, efeitoClick: elementoAtivoSideBar === index }">{{
                            item.name
                        }}</span>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute()

let elementoAtivoSideBar = ref();


const props = defineProps({
    menuExpandido: Boolean,
    step: Number
});

const emit = defineEmits([
    'updateStep',
]);

const stepNewValue = (value: number) => {
    emit('updateStep', value);
}

const itensSideBar = ref([
    { name: "Upload", icon: "view-dashboard" },
    { name: "Analisar dados", icon: "arrow-top-right-bold-outline" },
]);
</script>

<style scoped>
.menu-lateral {
    margin: 0 15px 0 0;
    width: 0;
    transition: 0.5s;
    position: absolute;
}

.container__logo {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;

}

.container__logo img {
    margin-left: 10px;
    width: 70px;
}

.displayNone {
    display: none;
}

.title {
    color: #fefefe;
    font-size: 20px;
    margin: 0;
    text-align: center;
    padding: 10px 0;
}



.menu-lateral ul {
    padding-left: 10px;
}

.icon {
    margin-left: 13px;
    color: #77d08e;
}

.txt__link {
    margin-left: 20px;
    transition: 0.5s;
    color: #fefefe;
    text-align: center !important;
}

.menu-lateral ul li {
    list-style: none;
    background: rgb(63, 161, 16);
    margin: 10px 0;
    padding-block: 5px;
    border-radius: 5px;
    border: solid 1px rgb(63, 161, 16);
}

.efeitoClick {
    background: #e1e1e1 !important;
    color: rgb(63, 161, 16) !important;
}

.menu-lateral ul li a {
    text-decoration: none;
    font-size: 20px;
    padding: 10px 7%;
    display: flex;
}

.expandido {
    width: 160px;
}
</style>
