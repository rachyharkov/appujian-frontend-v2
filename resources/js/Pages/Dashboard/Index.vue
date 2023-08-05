<script setup>
    import { Link, router } from '@inertiajs/vue3';
    import AppHead from '@/Shared/Components/AppHead.vue';
    import PageHeading from '@/Shared/Components/PageHeading.vue'
    import Checkbox from '@/Shared/Components/Form/Checkbox.vue'
    import { onMounted, ref } from 'vue';

    const isReady = ref(false);
    const isInProgress = ref(false);
    const percent = ref(0);

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
            <div class="alert alert-primary my-3">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Mohon Perhatiannya</h4>
                <p> Halo <b>{{ $page.props.auth.murid.nama }}</b>, Selamat Datang di portal ujian! kamu sudah berhasil login ya, silahkan dengarkan instruksi dari pengawas dengan seksama. Jika sudah siap, centang kotak dibawah ini untuk menyatakan bahwa kamu siap untuk melaksanakan ujian, lalu klik tombol lanjutkan.</p>
            </div>
            <Checkbox v-model="isReady" id="isReady" label="Saya siap untuk melaksanakan ujian"></Checkbox>
        </div>
        <div class="card-footer text-center">
            <Link href="/mulai-ujian" method="post" as="button" class="btn btn-primary btn-lg px-5" :disabled="!isReady" v-if="!isInProgress"
            :data="{
                'isReady': isReady,
                'isInProgress': isInProgress,
            }"
            >Lanjutkan</Link>
            <span class="text-muted" v-else>Mempersiapkan ujian...</span>
        </div>
    </div>
</template>
