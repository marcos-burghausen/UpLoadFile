<template>
    <v-row justify="space-around" class="">
        <v-col cols="auto tabela" style="padding: 0;">
            <div style="display: flex; align-items: center; background: rgb(63, 161, 16); justify-content: space-between;">
                <font-awesome-icon class="icon" role="button" icon="fa-solid fa-arrow-left" @click="emits('nextStep')" />
                <h2 class="title__tabela">
                    Relação crédito x débito
                </h2>
                <div style="width: 40px;"></div>
            </div>
            <div class="tabelas">
                <v-table fixed-header height="350px" class="tabela__data">
                    <thead>
                        <tr>
                            <th>
                                <h4 class="title__cabecalha__tabela">
                                    Data
                                </h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, key) in coopAgencia" :key="key">
                            <td class="text-center">
                                <v-btn class="botao" v-bind="props"
                                    @click="carregarData(value.horas, value.data); limparHora()">
                                    {{ value.data }}
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <v-table fixed-header height="350px">
                    <thead>
                        <tr>
                            <th>
                                <h4 class="title__cabecalha__tabela">
                                    {{ date }}
                                </h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, key) in horasDia" :key="key">
                            <td class="text-center">
                                <v-btn class="botao" v-bind="props"
                                    @click="carregarHora(value.hora, value.total_credito, value.total_debito)">
                                    {{ value.hora }}
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <v-table fixed-header height="350px">
                    <thead>
                        <tr>
                            <th>
                                <h4 class="title__cabecalha__tabela">
                                    {{ novaHora }}
                                </h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="novaHora">
                            <td class="">
                                <p> Total de Crédito</p>
                                <p> Total de Débito</p>
                            </td>
                            <td class="">
                                <p>R$ {{ totalCredito }}</p>
                                <p>R$ {{ totalDebito }}</p>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </div>
        </v-col>
    </v-row>
</template>
<script setup lang="ts">
import { useDataStore } from "@/store/data";
import { ref } from 'vue';

const useData = useDataStore();

const emits = defineEmits(["nextStep"]);
const chamarTabela = ref(false);
const props = defineProps({
    coopAgencia: {}
});


const cdt_dbt_hora_dia = ref(useData.cdtDbtHoraDia.data);
const horasDia = ref('');
const date = ref('');
const coop = ref('');
const novaHora = ref('');
const totalCredito = ref('');
const totalDebito = ref('');


const carregarData = (vlrPorHora, data) => {
    horasDia.value = vlrPorHora;
    date.value = data;
}
const carregarHora = (hora, ttlCredito, ttlDebito) => {
    novaHora.value = hora;
    totalCredito.value = ttlCredito;
    totalDebito.value = ttlDebito;
}
const limparHora = () => {
    novaHora.value = '';
    totalCredito = '';
    totalDebito = '';
}

</script>
<style scoped>
.title__tabela {
    text-align: center;
    padding-block: 10px;
    font-size: 30px;
    color: #fefefe;
    background: rgb(63, 161, 16);
    border-radius: 5px 5px 0 0;
}

.botao {
    background: rgb(63, 161, 16);
    color: #fefefe;
}

.tabela {
    border: 2px solid rgb(63, 161, 16);
    border-radius: 5px;
}

.tabelas {
    display: flex;
    width: 700px;

}

.title__cabecalha__tabela {
    text-align: center;
    color: rgb(63, 161, 16);
}

.icon {
    width: 40px;
    font-size: 30px;
    color: #fefefe;
}
</style>