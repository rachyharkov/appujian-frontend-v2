<script setup>
    import { Link } from '@inertiajs/vue3';
    import AppHead from '@/Shared/Components/AppHead.vue';

</script>
<template>
    <AppHead title="Dashboard" />
    <div class="card mt-5">
        <div class="card-header">
          <h4 class="card-title">Ujian Selesai</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-primary my-3">
                <h4 class="alert-heading"><i class="bi bi-stars me-2"></i>Ujian Selesai</h4>
                <p> Halo kembali <b>{{ $page.props.auth.murid.nama }}</b>, ujian yang kamu kerjakan sudah selesai. Terima kasih sudah berpartisipasi dalam ujian ini. Semoga hasilnya memuaskan ya.</p>
            </div>
        </div>
        <div class="card-footer text-center">
            <Link :href="route('logout')" method="post" as="button" class="btn btn-primary btn-lg px-5">Logout</Link>
        </div>
    </div>
</template>
