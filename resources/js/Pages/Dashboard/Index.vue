<script setup>
    import { Link, router, usePage } from '@inertiajs/vue3';
    import AppHead from '@/Shared/Components/AppHead.vue';
    import Checkbox from '@/Shared/Components/Form/Checkbox.vue'
    import { onMounted, ref, watch } from 'vue';

    const isReady = ref(false);
    const isInProgress = ref(false);
    const percent = ref(0);

    const page = usePage();

    router.on('start', () => {
        isInProgress.value = true;
    })

    router.on('progress', (e) => {
        percent.value = e.detail.percent;
    })

    router.on('finish', () => {
        isInProgress.value = false;
    })


</script>
<template>
    <AppHead title="Dashboard" />
    <div class="card mt-5">
        <div class="card-header">
          <h4 class="card-title">Selamat Datang</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-primary my-3" v-if="page.props.data_ujian == undefined || page.props.data_ujian == false">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Mohon Perhatiannya</h4>
                <p> Halo <b>{{ page.props.auth.murid.nama }}</b>, Selamat Datang di portal ujian! kamu sudah berhasil login ya, silahkan dengarkan instruksi dari pengawas dengan seksama. Jika sudah siap, centang kotak dibawah ini untuk menyatakan bahwa kamu siap untuk melaksanakan ujian, lalu klik tombol lanjutkan.</p>
            </div>

            <div class="alert alert-primary my-3" v-if="page.props.data_ujian?.status === 1 && $page.props.data_ujian?.sesi != 'sudah_selesai'">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Siap Ujian?</h4>
                <p>Silahkan cek dulu informasi mengenai ujian yang akan mulai, jika sudah siap, klik tombol mulai ujian. Semoga lancar dan sukses yaa 😄🫶!</p>
            </div>

            <div class="alert alert-primary my-3" v-if="$page.props.data_ujian?.sesi == 'sudah_selesai'">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Kamu sudah selesai ujian</h4>
                <p>Ujian berikut sudah kamu tuntaskan, terima kasih sudah mengikuti ujian. Semoga nilainya bagus 😄🫶!</p>
            </div>

            <div class="alert alert-warning my-3" v-if="page.props.data_ujian?.status === 0">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Kelihatannya Masih belum siap nih 😅</h4>
                <p>Silahkan laporkan ke pengawas untuk meng-aktifkan ujian segera ya!. Semangat 💪🏻!</p>
            </div>

            <div class="alert alert-warning my-3" v-if="page.props.data_ujian === false">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Sangat Bersemangat ya! 😅</h4>
                <p>Belum ada ujian yang mulai, tapi kamu bisa coba beberapa saat lagi 💪🏻!</p>
            </div>
            <Checkbox v-model="isReady" id="isReady" label="Saya siap untuk melaksanakan ujian" v-if="$page.props.data_ujian?.sesi != 'sudah_selesai'"></Checkbox>
            <div v-if="page.props.data_ujian">

                <table style="margin: auto; text-align: left;">
                    <tr>
                        <td><b>Mata Pelajaran</b></td>
                        <td style="width: 10px;">:</td>
                        <td>{{page.props.data_ujian.mata_pelajaran}}</td>
                    </tr>
                    <tr>
                        <td><b>Nama Ujian</b></td>
                        <td style="width: 10px;">:</td>
                        <td>{{page.props.data_ujian.nama_ujian}}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu Mulai</b></td>
                        <td>:</td>
                        <td>{{page.props.data_ujian.waktu_mulai}}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu Selesai</b></td>
                        <td>:</td>
                        <td>{{page.props.data_ujian.waktu_selesai}}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu Pengerjaan</b></td>
                        <td>:</td>
                        <td>{{page.props.data_ujian.durasi}} Menit</td>
                    </tr>
                    <tr v-if="page.props.data_ujian.sesi == 'sudah_selesai'">
                        <td><b>Waktu Selesai Pengerjaan</b></td>
                        <td>:</td>
                        <td>{{page.props.data_ujian.waktu_selesai_pengerjaan}}</td>
                    </tr>
                </table>'
            </div>
        </div>
        <div class="card-footer text-center">
            <Link :href="route('dashboard')" :only="['data_ujian']" method="post" as="button" class="btn btn-primary btn-lg px-5" :disabled="!isReady" :replace="true" v-if="!isInProgress && (page.props.data_ujian == undefined || page.props.data_ujian == false)">Lanjutkan</Link>
            <div v-if="page.props.data_ujian">

                <Link :href="route('mengerjakan')" method="post" as="button" class="btn btn-primary btn-lg px-5" :disabled="!isReady" v-if="!isInProgress && page.props.data_ujian.status === 1 && $page.props.data_ujian?.sesi != 'sudah_selesai'"
                :data="{
                    id_murid: page.props.auth.murid.id,
                    id_jadwal: page.props.data_ujian.id_jadwal
                }" :replace="true"
                >Mulai Ujian</Link>

                <Link :href="route('dashboard')" :only="['data_ujian']" method="post" as="button" class="btn btn-primary btn-lg px-5" :disabled="!isReady" v-if="!isInProgress && page.props.data_ujian.status === 0" :replace="true">Coba Lagi</Link>
            </div>
            <span class="text-muted" v-if="isInProgress">Mencari jadwal...</span>
        </div>
    </div>
</template>
