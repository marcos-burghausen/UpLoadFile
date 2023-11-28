<template>
    <div class="container__upload">
        <h1 class="title">Faça upload para analizar os dados</h1>
        <form @submit.prevent="uploadFile"
            style="margin: 0 0 20px 0; display: flex; flex-direction: column; align-items: center;">
            <v-file-input @change="handleFileUpload" style="width: 100%;" show-size counter multiple
                label="Escolher arquivo"></v-file-input>
            <!-- <input type="file" @change="handleFileUpload"> -->
            <div>

                <v-btn class="mt-2" rounded="lg" color="rgb(63, 161, 16)" type="submit">Enviar</v-btn>
            </div>
        </form>
        <div class="progress_bar">
            <div class="progress" :style="{ width: progress + '%' }"></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from '@/plugins/axios';
import { reactive } from 'vue';
import { type Ref, ref } from 'vue';

let file: Ref<File> = ref(null as unknown as File);
let uploadUrl = ref('/api/upload');
// let uploadUrl1 = ref('/api/file-upload');
let progress = ref(0);
let fileName = reactive({
    'name': ''
})


const handleFileUpload = (event: Event) => {
    // @ts-expect-error
    file.value = event.target.files[0];
}
const uploadFile = async () => {
    const startTime = performance.now();
    if (!file.value) {
        console.error('Nenhum arquivo selecionado para fazer upload.');
        return;
    }

    const chunkSize = ref(1413 * 1413);
    const fileSize = ref(file.value.size);
    const chunks = ref(Math.ceil(fileSize.value / chunkSize.value));
    let currentChunk = ref(0);


    while (currentChunk.value < chunks.value) {
        const start = ref(currentChunk.value * chunkSize.value);
        const end = ref(Math.min(start.value + chunkSize.value, fileSize.value));
        const chunk = ref(file.value.slice(start.value, end.value));

        const formData = new FormData();
        formData.append('file', chunk.value, file.value.name);
        formData.append('chunkNumber', currentChunk.value + 1);
        formData.append('chunkSize', chunk.value.size);
        formData.append('currentChunkSize', chunk.value.size);
        formData.append('totalSize', file.value.size);
        formData.append('type', file.value.type);
        formData.append('identifier', file.value.size + '-' + file.value.name);
        formData.append('chunkName', file.value.name);
        formData.append('resumableRelativePath', file.value.name);
        formData.append('totalChunks', chunks.value);
        progress.value = Math.round((currentChunk.value / chunks.value) * 100);
        currentChunk.value++;

        const response = await axios.post(uploadUrl.value, formData, {
            headers: {
                'Content-Type': "multipart/form-data"
            }
        });
        if (response.data.fileName) {
            fileName.name = response.data.fileName
        }
        console.log(response);

    }
    const endTime = performance.now();
    const elapsedTime = endTime - startTime;
    // const res = await axios.post(uploadUrl1.value, fileName);
    // console.log(res);
    const currentTime = new Date().toLocaleTimeString();
    alert(`Tempo de processamento: ${elapsedTime} milissegundos`);
}
</script>

<style scoped>
.container__upload {
    display: flex;
    flex-direction: column;
    width: 50%;
}

.title {
    text-align: center;
    margin-bottom: 20px;
    color: rgb(63, 161, 16);
}

.progress_bar {
    /* border: 1px solid black; */
    height: 10px;
    width: 100%;
}

.progress {
    height: 100%;
    background: rgb(63, 161, 16);
}
</style>
