<script setup>
import { onMounted, ref, watch } from 'vue';

defineProps({
    label: String,
    modelValue: {
        type: Boolean,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    error: {
        type: String,
        default: null
    },
    id: {
        type: String,
        default: null
    },
    small: {
        type: Boolean,
        default: false
    },
    showLabel: {
        type: Boolean,
        default: true
    }
})

defineEmits(['update:modelValue']);

const input = ref(false);
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});


</script>

<template>
    <div class="form-check">

        <input type="checkbox" class="form-check-input form-check-primary form-check-glow" :class="error ? 'is-invalid' : ''" ref="input" :id="id" :value="modelValue" @change="$emit('update:modelValue', $event.target.checked)">

        <label v-if="showLabel" class="form-check-label" :for="id">Saya menyatakan patuh dan bersedia mengikuti ketentuan pelaksanaan ujian</label>

        <span v-if="error" class="text-danger">{{ error }}</span>

    </div>
</template>
