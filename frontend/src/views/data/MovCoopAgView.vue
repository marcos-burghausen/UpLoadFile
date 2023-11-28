<template>
    <v-row justify="space-around">
        <v-col cols="auto">
            <v-dialog transition="dialog-bottom-transition" width="auto">
                <template v-slot:activator="{ props }">
                    <div
                        style="display: flex; align-items: center; background: rgb(63, 161, 16); justify-content: space-between;">
                        <font-awesome-icon class="icon" role="button" icon="fa-solid fa-arrow-left"
                            @click="emits('nextStep')" />
                        <h2 class="title__tabela">
                            Movimentação por cooperativa
                        </h2>
                        <div style="width: 40px;"></div>
                    </div>

                    <v-table class="tabela" fixed-header height="350px">
                        <thead>
                            <tr>
                                <th>
                                    <h4 class="title__cabecalha__tabela">
                                        Cooperativa
                                    </h4>
                                </th>
                                <th>
                                    <h4 class="title__cabecalha__tabela">
                                        Qtd movimentação
                                    </h4>
                                </th>
                                <th>
                                    <h4 class="title__cabecalha__tabela">
                                        Valor crédito
                                    </h4>
                                </th>
                                <th>
                                    <h4 class="title__cabecalha__tabela">
                                        Valor débito
                                    </h4>
                                </th>
                                <th>
                                    <h4 class="title__cabecalha__tabela">
                                        total movimentado
                                    </h4>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, key) in coopAgencia" :key="key">
                                <td class="text-center">
                                    <v-btn class="botao" v-bind="props"
                                        @click="chamarTabela = true; carregarAgencias(value.agencias, value.coop)">
                                        {{ value.coop }}
                                    </v-btn>
                                </td>
                                <td class="text-center">{{ value.total_registros }}</td>
                                <td class="text-center">{{ value.total_credito_coop }}</td>
                                <td class="text-center">{{ value.total_debito_coop }}</td>
                                <td class="text-center">{{ value.total_credito_coop + value.total_debito_coop }}</td>
                            </tr>
                        </tbody>
                    </v-table>
                </template>
                <template v-slot:default="{ isActive }">
                    <v-card>
                        <div>
                            <h2 class="title__tabela">
                                Movimentação por Ag da Coop: {{ coop }}
                            </h2>
                        </div>
                        <v-table v-if="chamarTabela" class="mt-5" fixed-header height="300px">
                            <thead>
                                <tr>
                                    <th>
                                        <h3 class="title__cabecalha__tabela">
                                            Agencia
                                        </h3>
                                    </th>
                                    <th>
                                        <h3 class="title__cabecalha__tabela">
                                            Qtd movimentação
                                        </h3>
                                    </th>
                                    <th>
                                        <h3 class="title__cabecalha__tabela">
                                            Valor crédito
                                        </h3>
                                    </th>
                                    <th>
                                        <h3 class="title__cabecalha__tabela">
                                            Valor débito
                                        </h3>
                                    </th>
                                    <th>
                                        <h3 class="title__cabecalha__tabela">
                                            total movimentado
                                        </h3>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="( value, key ) in  dados_ag " :key="key">
                                    <td class="text-center">{{ value.agencia }}</td>
                                    <td class="text-center">{{ value.count }}</td>
                                    <td class="text-center">{{ value.total_credito }}</td>
                                    <td class="text-center">{{ value.total_debito }}</td>
                                    <td class="text-center">{{ value.valor_movimentado }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                        <v-card-actions class="justify-end">
                            <v-btn class="botao" variant="text" @click="isActive.value = false">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </template>
            </v-dialog>
        </v-col>
    </v-row>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { useDataStore } from "@/store/data";

const useData = useDataStore();

const chamarTabela = ref(false);
const emits = defineEmits(["nextStep"]);
const props = defineProps({
    coopAgencia: {}
});


const cdt_dbt_hora_dia = ref(useData.cdtDbtHoraDia.data);
const dados_ag = ref('');
const coop = ref('');

const carregarAgencias = (agencias, key) => {
    dados_ag.value = agencias;
    coop.value = key;
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
    border-radius: 0 0 5px 5px;

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