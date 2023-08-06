<script setup>
    import { Link, router } from '@inertiajs/vue3';
    import AppHead from '@/Shared/Components/AppHead.vue';
    import Checkbox from '@/Shared/Components/Form/Checkbox.vue'
import SoalPilihanGandaLayout from '@/Shared/Layout/SoalPilihanGandaLayout.vue';
import SoalEssayLayout from '@/Shared/Layout/SoalEssayLayout.vue';

</script>

<script>
    export default {
        data() {
            return {
                nomor: 0,
                jawaban_murid: [],
            }
        },
        methods: {
            dijawab(jawaban) {
                this.jawaban_murid[this.nomor] = jawaban;
            }
        }
    }
</script>

<template>
    <AppHead title="Dashboard" />
    <div class="card mt-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <div class="d-flex flex-justify-between gap-1">
                        <button v-for="i in $page.props.soals.length" :key="i" class="btn btn-primary" @click="nomor = i - 1">{{ i }}</button>
                    </div>
                </div>
                <div class="col-md-8 d-flex">
                    <span v-text="nomor + 1" class="me-3"></span>
                    <SoalPilihanGandaLayout v-if="$page.props.soals[nomor].type_soal == 'pilgan'" :soal="$page.props.soals[nomor]" :jawaban_murid="jawaban_murid[nomor]" :nomor="nomor"  @dijawab="dijawab" :key="nomor"/>
                    <SoalEssayLayout v-else :soal="$page.props.soals[nomor]" :jawaban_murid="jawaban_murid[nomor]" :nomor="nomor"  @dijawab="dijawab" :key="nomor"/>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-row gap-3 justify-content-end">
            <button class="btn btn-primary" @click="nomor = nomor - 1" v-if="nomor > 0">Sebelumnya</button>
            <button class="btn btn-primary" @click="nomor = nomor + 1" v-if="nomor < $page.props.soals.length - 1">Selanjutnya</button>
            <button class="btn btn-success" v-if="nomor == $page.props.soals.length - 1" @click="$inertia.post(route('murid.selesai_ujian', $page.props.data_ujian.id), {jawaban_murid: jawaban_murid})">Selesai</button>
        </div>
    </div>
</template>
