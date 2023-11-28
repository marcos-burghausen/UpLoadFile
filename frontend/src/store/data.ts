// Utilities
import { defineStore } from 'pinia'
import { ref } from 'vue';

export const useDataStore = defineStore("data", () => {

    const dataMaiorMenorNumRegistros = ref(
        localStorage.getItem("dataMaiorMenorNumRegistros")
            ? JSON.parse(localStorage.getItem("dataMaiorMenorNumRegistros") as string)
            : "");

    const dataMaiorMenorValor = ref(
        localStorage.getItem("dataMaiorMenorValor")
            ? JSON.parse(localStorage.getItem("dataMaiorMenorValor") as string)
            : "");

    const diaSemanaMaiorMovRx1 = ref(
        localStorage.getItem("diaSemanaMaiorMovRx1")
            ? JSON.parse(localStorage.getItem("diaSemanaMaiorMovRx1") as string)
            : "");

    const diaSemanaMaiorMovPx1 = ref(
        localStorage.getItem("diaSemanaMaiorMovPx1")
            ? JSON.parse(localStorage.getItem("diaSemanaMaiorMovPx1") as string)
            : "");

    const cdtDbtHoraDia = ref(
        localStorage.getItem("cdtDbtHoraDia")
            ? JSON.parse(localStorage.getItem("cdtDbtHoraDia") as string)
            : "");

    const qtdVlrCoopAg = ref(
        localStorage.getItem("qtdVlrCoopAg")
            ? JSON.parse(localStorage.getItem("qtdVlrCoopAg") as string)
            : "");

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function setMaiorMenorNumRegistro(numRegistro): void {
        dataMaiorMenorNumRegistros.value = numRegistro;
        localStorage.setItem('dataMaiorMenorNumRegistro', JSON.stringify(dataMaiorMenorNumRegistros.value));
    }

    function setMaiorMenorValor(data): void {
        dataMaiorMenorValor.value = data;
        localStorage.setItem('dataMaiorMenorValor', JSON.stringify(dataMaiorMenorValor.value));
    }

    function setDiaSemanaMaiorMovRx1(diaSemana): void {
        diaSemanaMaiorMovRx1.value = diaSemana;
        localStorage.setItem('diaSemanaMaiorMovRx1', JSON.stringify(diaSemanaMaiorMovRx1.value));
    }

    function setDiaSemanaMaiorMovPx1(diaSemana): void {
        diaSemanaMaiorMovPx1.value = diaSemana;
        localStorage.setItem('diaSemanaMaiorMovPx1', JSON.stringify(diaSemanaMaiorMovPx1.value));
    }

    function setQtdVlrCoopAg(data): void {
        qtdVlrCoopAg.value = data;
        localStorage.setItem('qtdVlrCoopAg', JSON.stringify(qtdVlrCoopAg.value));
    }

    function setCdtDbtHoraDia(data): void {
        cdtDbtHoraDia.value = data;
        localStorage.setItem('cdtDbtHoraDia', JSON.stringify(cdtDbtHoraDia.value));
    }

    return {
        dataMaiorMenorNumRegistros,
        dataMaiorMenorValor,
        diaSemanaMaiorMovRx1,
        diaSemanaMaiorMovPx1,
        cdtDbtHoraDia,
        qtdVlrCoopAg,
        setMaiorMenorNumRegistro,
        setMaiorMenorValor,
        setDiaSemanaMaiorMovRx1,
        setDiaSemanaMaiorMovPx1,
        setCdtDbtHoraDia,
        setQtdVlrCoopAg,
    };

})