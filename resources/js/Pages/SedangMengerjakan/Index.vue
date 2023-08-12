<script setup>
    import { Link, router, usePage } from '@inertiajs/vue3';
    import AppHead from '@/Shared/Components/AppHead.vue';
    import Checkbox from '@/Shared/Components/Form/Checkbox.vue'
    import SoalPilihanGandaLayout from '@/Shared/Layout/SoalPilihanGandaLayout.vue';
    import SoalEssayLayout from '@/Shared/Layout/SoalEssayLayout.vue';
    import dynamicEventBus from '@/utils/helper/dynamicEventBus.js';
    import { checkProgress, finishExam } from '@/utils/helper/syncProgress.js';
    import Swal from 'sweetalert2';
    import { toastAlert } from '@/utils/helper/sweetalert';
</script>

<script>

    export default {
        data() {
            return {
                nomor: 0,
                jawaban_murid_pilgan: {},
                jawaban_murid_essay: {},
                timer_interval: null,
                sisa_waktu: '',
                sisa_waktu_unix: 0,
                waktu_selesai: usePage().props.data_jadwal.waktu_selesai,
                waktu_selesai_unix: new Date(usePage().props.data_jadwal.waktu_selesai).getTime(),
            }
        },
        watch: {
            jawaban_muridd: {
                handler: function (val) {
                    console.log(val)
                },
                deep: true
            }
        },
        methods: {
            dijawab(id_soal,type_soal, jawaban) {
                // console.log(id_soal, jawaban)
                if (type_soal == 'pilgan') {
                    this.jawaban_murid_pilgan[id_soal] = {
                        jawaban: jawaban
                    }
                } else {
                    this.jawaban_murid_essay[id_soal] = {
                        jawaban: jawaban
                    }
                }
            },
            confirmSelesaiUjian() {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan bisa mengubah jawaban setelah menekan tombol selesai ujian!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, selesai ujian!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mohon Tunggu',
                            html: 'Sedang menyimpan pengerjaan',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        router.visit(route('finish-exam'), {
                            method: 'post',
                            data: {
                                jawaban_murid: {
                                    pilgan: this.jawaban_murid_pilgan,
                                    essay: this.jawaban_murid_essay
                                },
                                id_murid: usePage().props.auth.murid.id_murid,
                                id_ujian: usePage().props.data_jadwal.ujian_id,
                                logged_at: new Date().getTime()
                            },
                            replace: true,
                            onFinish: () => {
                                Swal.close()
                            }
                        })
                    }
                })
            },
            onInterval() {

                var countDownDate = new Date(this.waktu_selesai).getTime();

                var now = new Date().getTime();
                var distance = countDownDate - now;

                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                hours = hours < 10 ? '0' + hours : hours;
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                minutes = minutes < 10 ? '0' + minutes : minutes;
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                seconds = seconds < 10 ? '0' + seconds : seconds;

                this.sisa_waktu = 'Sisa Waktu: ' + hours + ":" + minutes + ":" + seconds
                // console.log(this.sisa_waktu)
                // this.sisa_waktu = 'Sisa Waktu: ' + minutes + ":" + seconds
                this.sisa_waktu_unix = distance

                if (minutes <= 20 ) {
                    document.getElementById('waktu').classList.add('bg-warning')
                    // document.getElementById('waktu').style.setProperty('--alertColor', '#eab308')
                }

                if (minutes < 6) {
                    document.getElementById('waktu').classList.remove('bg-warning')
                    document.getElementById('waktu').classList.add('bg-danger')
                    document.getElementById('waktu').style.setProperty('--alertColor', '#f87171')
                }

                // console.log(this.sisa_waktu)

                if (this.sisa_waktu_unix < 0) {
                    clearInterval(this.timer_interval)
                    this.sisa_waktu = 'Waktu Habis'

                    console.log('mengumpulkan jawaban...')
                    // var last_jawaban = getJawabannya();
                    // Livewire.emit('selesaikanUjian', last_jawaban);
                }
            }
        },
        watch: {
            nomor: function (val) {
                dynamicEventBus.emit('syncProgressHeader', {
                    jawaban_murid: {
                        pilgan: this.jawaban_murid_pilgan,
                        essay: this.jawaban_murid_essay
                    },
                    id_murid: usePage().props.auth.murid.id_murid,
                    id_ujian: usePage().props.data_jadwal.ujian_id,
                    logged_at: new Date().getTime()
                }); // emit event to update progress header component
            }
        },
        mounted() {
            // this.timer_interval = setInterval(this.onInterval, 1000);
            checkProgress(
                {
                    id_murid: usePage().props.auth.murid.id_murid,
                    id_ujian: usePage().props.data_jadwal.ujian_id,
                }
            ).then((resp) => {
                if(localStorage.getItem('progress') != null) {
                    const progress = JSON.parse(localStorage.getItem('progress'))
                    this.jawaban_murid_essay = progress.essay
                    this.jawaban_murid_pilgan = progress.pilgan
                    toastAlert({
                        title: 'Progress Dipulihkan',
                        text: 'Perangkat kamu menyimpan progress terakhir pengerjaan',
                        icon: 'success'
                    })
                } else {

                    const yang_udah_dikerjain = JSON.parse(resp.data.yang_udah_dikerjain)

                    this.jawaban_murid_essay = yang_udah_dikerjain.essay
                    this.jawaban_murid_pilgan = yang_udah_dikerjain.pilgan

                    toastAlert({
                        title: 'Progress Dipulihkan',
                        text: 'Mengambil dari server, selamat mengerjakan',
                        icon: 'success'
                    })
                }

            }).catch((err) => {
                if(localStorage.getItem('progress') != null) {
                    const progress = JSON.parse(localStorage.getItem('progress'))
                    this.jawaban_murid_essay = progress.essay
                    this.jawaban_murid_pilgan = progress.pilgan
                    toastAlert({
                        title: 'Progress Dipulihkan',
                        text: 'Kesalahan saat mengambil dari server, progress terakhir pengerjaan dipulihkan dari perangkat',
                        icon: 'success'
                    })
                }

                console.log(err)
            })
        },
        components: {
            AppHead,
            SoalPilihanGandaLayout,
            SoalEssayLayout
        }
    }
</script>

<template>
    <AppHead title="Dashboard" />
    <div class="card mt-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-5 position-relative">
                    <div class="d-flex justify-content-between flex-row">
                        <span class="badge bg-danger rounded-pill" id="waktu">{{ sisa_waktu }}</span>
                        <span class="badge bg-primary rounded-pill">{{ Object.keys(jawaban_murid_pilgan).length + Object.keys(jawaban_murid_essay).length }} dari {{ $page.props.soals.length }} Terisi</span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex flex-justify-between gap-1">
                        <button v-for="i in $page.props.soals.length" :key="i" class="btn"
                        :class="{'btn-primary': nomor == i - 1, 'btn-outline-primary': nomor != i - 1}"
                        @click="nomor = i - 1">{{ i }}</button>
                    </div>
                </div>
                <div class="col-md-8 d-flex">
                    <span v-text="nomor + 1" class="me-3"></span>
                    <template v-if="$page.props.soals[nomor].type_soal == 'pilgan'" >
                        <SoalPilihanGandaLayout :soal="$page.props.soals[nomor]" :jawaban_murid="jawaban_murid_pilgan[$page.props.soals[nomor].id]?.jawaban" :nomor="nomor" @dijawab="dijawab" :key="nomor"/>
                    </template>
                    <template v-else>
                        <SoalEssayLayout v-if="$page.props.soals[nomor].type_soal == 'essay'" :soal="$page.props.soals[nomor]" :jawaban_murid="jawaban_murid_essay[$page.props.soals[nomor].id]?.jawaban" :nomor="nomor" @dijawab="dijawab" :key="nomor"/>
                    </template>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-row gap-3 justify-content-end">
            <button class="btn btn-primary" @click="nomor = nomor - 1" v-if="nomor > 0">Sebelumnya</button>
            <button class="btn btn-primary" @click="nomor = nomor + 1" v-if="nomor < $page.props.soals.length - 1">Selanjutnya</button>
            <button class="btn btn-success" v-if="nomor == $page.props.soals.length - 1" @click="confirmSelesaiUjian">Selesai</button>
        </div>
    </div>
</template>

<style scoped>
    .animasi-ping::after {
        border-radius: 4px;
        left: 0;
        z-index: 0;
        content: "";
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        position: absolute;
        margin: 0 auto;
        background: var(--alertColor);
        opacity: 0;
        animation: ping 1s ease-out infinite;
    }

    @keyframes ping {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        75%, 100% {
            transform: scale(1.1);
            opacity: 0;
        }
    }

</style>
@/utils/helper/localEventBus
@/utils/helper/dynamicEventBus.js@/utils/helper/dynamicEventBus.js
