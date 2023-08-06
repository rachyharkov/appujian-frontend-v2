<script setup>
defineProps({
    soal: {
        type: Object,
        default: null
    },
    jawaban_murid: String
})

defineEmits(['dijawab'])
</script>
<template>
    <div class="container overflow-auto">
        <div>Jawaban Loe {{ jawaban_murid }}</div>
        <div>tipe soal {{ soal.type_soal }}</div>
        <div>id soal {{ soal.id }}</div>
        <img v-if="soal.image" :src="soal.image" class="w-full h-auto rounded-lg" alt="">
        <div v-html="soal.soal"></div>
        <div class="form-check" v-for="(pilihan, index) in JSON.parse(soal.pilihan)" :key="index">
            <input class="form-check-input" type="radio" name="jawaban_soal_ini" :id="index" :value="index.split('_').pop()" @input="$emit('dijawab', soal.id, soal.type_soal, $event.target.value)" :checked="jawaban_murid == index.split('_').pop()">
            <label class="form-check-label" :for="index">{{ pilihan }}</label>
        </div>
    </div>
</template>
