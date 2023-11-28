<template>
    <Loading v-if="isLoading" />
    <div v-if="step === 0" class="informacoes">
        <div class="informacao">
            <div class="qtd__mov">
                <div class="qtd__cabecalho">
                    <h2 class="title">
                        Data com maior e menor quantidade de movimentações
                    </h2>
                </div>
                <div class="container__botao">
                    <v-btn class="botao" @click="qtdMov">
                        Buscar dados
                    </v-btn>
                </div>
            </div>
            <div v-if="maiorNumRegistro && menorNumRegistro" class="container__informacao">
                <div class="container__dados">
                    <span class="title__dados">Maior quantidade de movimentação foi no dia {{ dataMaiorRegistros }}, com {{
                        maiorNumRegistro }} movimentações.</span>
                </div>
                <div class="container__dados">
                    <span class="title__dados">Menor quantidade de movimentação foi no dia {{ dataMenorRegistros }}, com {{
                        menorNumRegistro }} movimentações.</span>
                </div>
            </div>
        </div>

        <div class="informacao">
            <div class="qtd__mov">
                <div class="qtd__cabecalho">
                    <h2 class="title">
                        Data com maior e menor soma de movimentações
                    </h2>
                </div>
                <div class="container__botao">
                    <v-btn class="botao" @click="vlrMov">
                        Buscar dados
                    </v-btn>
                </div>
            </div>
            <div v-if="dataMaiorValor && dataMenorValor" class="container__informacao">
                <div class="">
                    <span class="title__dados">Maior valor movimentado foi dia {{ dataMaiorValor }} com o valor de R$ {{
                        maiorVlrMov }} </span>
                </div>
                <div class="container__dados">
                    <span class="title__dados">Menor valor movimentado foi dia {{ dataMenorValor }} com o valor de R$ {{
                        menorVlrMov }} </span>
                </div>
            </div>
        </div>

        <div class="informacao">
            <div class="qtd__mov">
                <div class="qtd__cabecalho">
                    <h2 class="title">
                        Dia da semana com maior movimentação “RX1” e “PX1”
                    </h2>
                </div>
                <div class="container__botao">
                    <v-btn class="botao" @click="rx1Px1">
                        Buscar dados
                    </v-btn>
                </div>
            </div>
            <div v-if="diaSemanaMaiorMovRx1 && diaSemanaMaiorMovPx1" class="container__informacao">
                <div class="container__dados">
                    <span class="title__dados">Dia da semana com maior movimentação do código RX1 na {{ diaSemanaMaiorMovRx1
                    }}</span>
                </div>
                <div class="container__dados">
                    <span class="title__dados">Dia da semana com maior movimentação do código RX1 na {{ diaSemanaMaiorMovPx1
                    }}</span>
                </div>
            </div>
        </div>

        <div class="informacao">
            <div class="qtd__mov">
                <div class="qtd__cabecalho">
                    <h2 class="title">
                        Quantidade e valor movimentado por coop/agência
                    </h2>
                </div>
                <div class="container__botao">
                    <v-btn class="botao" @click="coopAgencia">
                        Buscar dados
                    </v-btn>
                </div>
            </div>
        </div>

        <div class="informacao">
            <div class="qtd__mov">
                <div class="qtd__cabecalho">
                    <h2 class="title">
                        Relação de créditos x débitos ao longo das horas do dia
                    </h2>
                </div>
                <div class="container__botao">
                    <v-btn class="botao" @click="relacaoCdtDbt">
                        Buscar dados
                    </v-btn>
                </div>
            </div>
        </div>


    </div>
    <MovCoopAgView v-if="step === 1" :coopAgencia="qtdVlrCoopAag" @next-step="step = 0" />
    <HoraView v-if="step === 2" :coopAgencia="cdtDbtHoraDia" @next-step="step = 0" />
</template>
<script setup lang="ts">
import { ref } from "vue";
import { useDataStore } from "@/store/data";
import dataApi from "@/api/data";
import Loading from "@/components/Loading.vue";
import MovCoopAgView from "./MovCoopAgView.vue";
import HoraView from "@/views/data/HoraView-copy.vue"

const useData = useDataStore();

const step = ref(0);

const isLoading = ref(false);
const maiorNumRegistro = ref(useData.dataMaiorMenorNumRegistros.maiorQuantidade);
const menorNumRegistro = ref(useData.dataMaiorMenorNumRegistros.menorQuantidade);
const dataMaiorRegistros = ref(useData.dataMaiorMenorNumRegistros.dataMaiorQuantidade);
const dataMenorRegistros = ref(useData.dataMaiorMenorNumRegistros.dataMenorQuantidade);

const dataMaiorValor = ref(useData.dataMaiorMenorValor.dataMaiorMovimentacao._id);
const dataMenorValor = ref(useData.dataMaiorMenorValor.dataMenorMovimentacao._id);
const maiorVlrMov = ref(useData.dataMaiorMenorValor.dataMaiorMovimentacao.totalMovimentado);
const menorVlrMov = ref(useData.dataMaiorMenorValor.dataMenorMovimentacao.totalMovimentado);

const diaSemanaMaiorMovRx1 = ref(useData.diaSemanaMaiorMovRx1.diaSemana);
const diaSemanaMaiorMovPx1 = ref(useData.diaSemanaMaiorMovPx1.diaSemana);

const qtdVlrCoopAag = ref(useData.qtdVlrCoopAg);
const cdtDbtHoraDia = ref(useData.cdtDbtHoraDia);

const qtdMov = async () => {
    isLoading.value = true;
    try {
        const response = await dataApi.qtdMov();
        useData.setMaiorMenorNumRegistro(response.data.dataMaiorMenorNumRegistros[0]);
        maiorNumRegistro.value = response.data.dataMaiorMenorNumRegistros[0].maiorQuantidade;
        menorNumRegistro.value = response.data.dataMaiorMenorNumRegistros[0].menorQuantidade;
        dataMaiorRegistros.value = response.data.dataMaiorMenorNumRegistros[0].dataMaiorQuantidade;
        dataMenorRegistros.value = response.data.dataMaiorMenorNumRegistros[0].dataMenorQuantidade;
    } catch (error) {

    } finally {
        isLoading.value = false;
    }
}

const vlrMov = async () => {
    isLoading.value = true;
    try {
        const response = await dataApi.vlrMov();
        useData.setMaiorMenorValor(response.data.maiorMenorMov);
        dataMaiorValor.value = response.data.maiorMenorMov.dataMaiorMovimentacao._id;
        dataMenorValor.value = response.data.maiorMenorMov.dataMenorMovimentacao._id;
        maiorVlrMov.value = response.data.maiorMenorMov.dataMaiorMovimentacao.totalMovimentado;
        menorVlrMov.value = response.data.maiorMenorMov.dataMenorMovimentacao.totalMovimentado;
    } catch (error) {

    } finally {
        isLoading.value = false;
    }
}

const rx1Px1 = async () => {
    isLoading.value = true;
    try {
        const response = await dataApi.rx1Px1();
        useData.setDiaSemanaMaiorMovRx1(response.data.RX1GroupedByWeekday[0]);
        useData.setDiaSemanaMaiorMovPx1(response.data.PX1GroupedByWeekday[0]);
        diaSemanaMaiorMovRx1.value = response.data.RX1GroupedByWeekday[0].diaSemana;
        diaSemanaMaiorMovPx1.value = response.data.PX1GroupedByWeekday[0].diaSemana;
    } catch (error) {

    } finally {
        isLoading.value = false;
    }
}

const coopAgencia = async () => {
    isLoading.value = true;
    try {
        const response = await dataApi.coopAgencia();
        qtdVlrCoopAag.value = response.data.qtdVlrCoopAg;
        useData.setQtdVlrCoopAg(response.data.qtdVlrCoopAg);
        step.value = 1;
    } catch (error) {

    } finally {
        isLoading.value = false;
    }
}

const relacaoCdtDbt = async () => {
    isLoading.value = true;
    try {
        const response = await dataApi.relacaoCdtDbt();
        cdtDbtHoraDia.value = response.data.cdtDbtHoraDia;
        useData.setCdtDbtHoraDia(response.data.cdtDbtHoraDia);
        step.value = 2;
    } catch (error) {

    } finally {
        isLoading.value = false;
    }
}
</script>
<style scoped>
.informacoes {
    display: flex;
    flex-direction: column;
    width: 70%;
}

.informacao {
    margin-block: 5px;
    border: solid 1px rgb(63, 161, 16);
    border-radius: 5px;
}

.qtd__mov {
    display: flex;
    align-items: center;
    border-bottom: solid 1px rgb(63, 161, 16);
    padding: 5px 20px;
}

.qtd__cabecalho {
    width: 80%;
}

.title {
    /* text-align: center; */
    color: rgb(63, 161, 16);
    padding: 10px 0;
}

.container__botao {
    width: 20%;
    text-align: end;
}

.container__informacao {
    /* display: flex; */
    padding: 10px 20px;
}

.titulo__card {
    text-align: center;
}

.informacao__card {
    display: flex;
    justify-content: space-around;
    margin-bottom: solid 1px rgb(63, 161, 16);
}

.container__dados {}

.title__dados {
    /* text-align: center; */
    width: 33%;
}

.container__dia__registro {
    display: flex;
    justify-content: space-around;
}



.botao {
    background: rgb(63, 161, 16);
    color: #fefefe;
}
</style>