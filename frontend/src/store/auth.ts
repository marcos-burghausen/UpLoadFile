import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { type Token } from '@/types/token';

export const useAuthStore = defineStore('auth', () => {
    const userName = ref(localStorage.getItem("user"));
    const token = ref(
        localStorage.getItem("token")
            ? JSON.parse(localStorage.getItem("token") as string)
            : "");

    function setUser(userValue: string) {
        localStorage.setItem('user', userValue);
        userName.value = userValue;
    }


    function setToken(tokenValue: Token) {
        token.value = tokenValue;
        localStorage.setItem('token', JSON.stringify(token.value));
    }

    const isAuthenticated = computed(() => {
        return token.value && userName.value;
    })

    function clear() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('dataMaiorMenorNumRegistro');
        localStorage.removeItem('qtdVlrCoopAg');
        localStorage.removeItem('diaSemanaMaiorMovPx1');
        localStorage.removeItem('dataMaiorMenorValor');
        localStorage.removeItem('diaSemanaMaiorMovRx1');
        localStorage.removeItem('cdtDbtHoraDia');
        token.value = '';
        userName.value = '';
    }

    return {
        token,
        userName,
        isAuthenticated,
        setToken,
        setUser,
        clear,
    }

})
