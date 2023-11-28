<template>
    <div class="container__dados">
        <h2 class="title">Cadastro</h2>
        <form class="form" @submit.prevent="create">
            <v-text-field v-model="user.name" label="Nome" aria-label="Informe seu e-mail para entrar no sistema."
                maxlength="128" variant="outlined" class="mb-3" type="text" :rules="[rules.requiredName]" />

            <v-text-field v-model="user.email" label="E-mail" aria-label="Informe seu e-mail para entrar no sistema."
                maxlength="128" variant="outlined" class="mb-3" type="email" :rules="[rules.requiredEmail]" />

            <v-text-field v-model="user.password" label="Senha" aria-label="Informe sua senha para entrar no sistema."
                maxlength="128" variant="outlined" :type="displayPassword ? 'text' : 'password'"
                :rules="[rules.requiredPassword]">
                <template #append-inner>
                    <font-awesome-icon class="icon" role="button" @click="displayPassword = !displayPassword"
                        :icon="displayPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" />
                </template>
            </v-text-field>

            <div class="container__button">
                <div @click="emits('nextStep')" class="btn__register">Login</div>
            </div>
            <button class="btn btn__submit" type="submit">cadastro</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

import cadastroApi from "@/api/Cadastro"

const emits = defineEmits(["nextStep"]);
const displayPassword = ref(false);
const user = ref({
    name: "",
    email: "",
    password: "",
});
const rules = {
    requiredName: (value: string) =>
        !!value || "O campo do Nome é obrigatório.",
    requiredEmail: (value: string) =>
        !!value || "O campo do E-mail é obrigatório.",
    requiredPassword: (value: string) =>
        !!value || "O campo da senha é obrigatório.",
};

async function create() {
    try {
        const { data } = await cadastroApi.registerUser(user.value);
        console.log(data);
        alert('cadastrado com sucesso');
        emits("nextStep");
    } catch (error) {

    }
}
</script>
<style scoped>
.container__dados {
    border-radius: 10px;
    border: 1px solid rgb(63, 161, 16);
    width: 600px;
    max-width: 95%;
    display: flex;
    flex-direction: column;
    justify-items: center;
    padding: 15px;
}

.title {
    font-size: 35px;
    font-weight: bold;
    color: #696969;
    text-align: center;
    margin-bottom: 30px;
}

.form {
    display: flex;
    flex-direction: column;
    width: 100% !important;
}

.icon {
    color: #696969;
    font-size: 23px;
}

.container__button {
    text-align: end;

}

.btn__register {
    color: #3fa110;
    cursor: pointer;
}

.btn__register:hover {
    text-decoration: underline;
}

.btn {
    border-radius: 15px;
    text-transform: uppercase;
    color: #fff;
    font-size: 10px;
    padding: 5px;
    cursor: pointer;
    font-weight: bold;
    width: 200px;
    align-self: center;
    border: none;
    margin-top: 1rem;
    font-size: 20px;
    background-color: rgb(63, 161, 16);
    border: 1px solid rgb(63, 161, 16);
    transition: background-color .5s;
}

.btn__submit:hover {
    background-color: #e1e1e1;
    color: rgb(63, 161, 16);
}



















.btn__link {
    margin-top: 60px;
    background-color: transparent;
    border: 1px solid #fff;
    transition: background-color .5s;
}

.btn__link:hover {
    background-color: #fff;
    color: #58af9b;
}

@media screen and (max-width: 1201px) {

    .box {
        width: 90%;
    }

    .container__dados {
        padding: 2rem 4rem;
    }


}

@media screen and (max-width: 920px) {

    .container__dados {
        width: 100%;
        padding: 2rem 4rem;
    }

    .container__decription {
        display: none;
    }

    .btn__register {
        display: inline;
        color: #0097a7;
        font-size: 16px;
        margin: 15px 0;
        background: transparent;
        border: none;
    }

    .container__button {
        display: flex;
        justify-content: space-between;
    }

}

@media screen and (max-width: 740px) {
    .container__dados {
        padding: 2rem 2rem;
    }

    .title {
        font-size: 30px;
    }
}

@media screen and (max-width: 440px) {
    .box {
        width: 95%;
    }

    .container__dados {
        padding: 2rem 1rem;
    }
}
</style>