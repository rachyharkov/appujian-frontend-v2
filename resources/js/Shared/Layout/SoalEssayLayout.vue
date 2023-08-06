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

        <div v-html="soal.soal"></div>
        <div class="form-group with-title mb-3">
            <textarea class="form-control" rows="5" @input="$emit('dijawab', soal.id, soal.type_soal, $event.target.value)">{{ jawaban_murid }}</textarea>
            <label>Ketik Jawaban Kamu</label>
        </div>
    </div>
</template>
