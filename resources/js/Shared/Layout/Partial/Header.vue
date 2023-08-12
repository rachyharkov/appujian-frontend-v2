<script>
    import { onMounted, onUnmounted, ref } from 'vue'
    import { Link } from '@inertiajs/vue3';
    import dynamicEventBus from '@/utils/helper/dynamicEventBus.js';
    import { syncProgress } from '@/utils/helper/syncProgress.js';
    import photoProfileDummy from '/public/assets/static/images/faces/1.jpg';

    export default {
        setup() {
            return {
                photoProfileDummy,
            }
        },
        data() {
            return {
                is_syncing: false,
                sync_result: null,
            }
        },
        methods: {
            syncProgressnya(data) {
                this.is_syncing = true
                this.sync_result = null

                syncProgress(data).then((res) => {

                    if(res.code == 'ERR_NETWORK') {
                        this.sync_result = 'failed'
                    } else {
                        this.sync_result = 'success'
                    }
                    this.is_syncing = false
                }).catch((err) => {
                    this.is_syncing = false
                    this.sync_result = 'failed'
                    console.log(err)
                })
                // this.is_syncing = true;
                // this.sync_result = null;
                // this.$inertia.post('/sedang-mengerjakan/sync-progress', {}, {
                //     onStart: () => {
                //         this.is_syncing = true;
                //         this.sync_result = null;
                //     },
                //     onProgress: (event) => {
                //         console.log(event)
                //     },
                //     onSuccess: (page) => {
                //         this.is_syncing = false;
                //         this.sync_result = 'success';
                //         console.log(page)
                //     },
                //     onError: (error) => {
                //         this.is_syncing = false;
                //         this.sync_result = 'failed';
                //         console.log(error)
                //     },
                // })
            }
        },
        mounted() {
            // console.log(this.emitter)
            dynamicEventBus.on('syncProgressHeader', e => this.syncProgressnya(e));
            dynamicEventBus.on('syncProgressHeaderSetToNull', e => this.sync_result = null);

        },
        unmounted() {
            dynamicEventBus.off('syncProgressHeader',e => this.syncProgressnya(e));
            dynamicEventBus.off('syncProgressHeaderSetToNull', e => this.sync_result = null);
        },
        components: {
            Link
        }
    }
</script>

<template>
    <header>
        <nav class="navbar navbar-light">
            <div class="container d-block">
                <a class="navbar-brand" href="#">
                    <h4 class="d-inline-block mt-2" v-if="$page.props.data_ujian != undefined">{{ $page.props.data_ujian.nama }}</h4>
                    <h4 class="d-inline-block mt-2" v-else>e-Ujian</h4>
                </a>
                <div class="dropdown float-end">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ $page.props.auth.murid.nama }}</h6>
                                <p class="mb-0 text-sm text-gray-600">{{ $page.props.auth.user.username }}</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img :src="photoProfileDummy">
                                </div>
                                <div v-if="is_syncing" style="width: 17px; height: 17px; right: 5px; bottom: -1px" class="bg-warning rounded-circle position-absolute text-primary z-index-1 anim-rotate">
                                    <i class="bi bi-arrow-repeat text-white position-relative" style="font-size: 0.8rem; margin-left: 0.14rem; top: -0.36rem;"></i>
                                </div>
                                <div v-if="sync_result == 'success' " style="width: 17px; height: 17px; right: 5px; bottom: -1px" class="bg-success rounded-circle position-absolute text-primary z-index-1">
                                    <i class="bi bi-check text-white position-relative" style="font-size: 0.8rem; margin-left: 0.14rem; top: -0.36rem;"></i>
                                </div>
                                <div v-if="sync_result == 'failed' " style="width: 17px; height: 17px; right: 5px; bottom: -1px" class="bg-danger rounded-circle position-absolute text-primary z-index-1">
                                    <i class="bi bi-cloud-slash-fill text-white position-relative" style="font-size: 0.8rem; margin-left: 0.14rem; top: -0.36rem;"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem;">
                        <li>
                            <h6 class="dropdown-header">Halo, {{ $page.props.auth.murid.nama }}</h6>
                            <p class="dropdown-header p-custom" v-if="is_syncing">Progress kamu sedang disinkronisasi, silahkan kerjakan ujian mu seperti biasa ya...</p>
                            <p class="dropdown-header p-custom" v-if="sync_result == 'success'">Progress kamu sudah disinkronisasi, tidak perlu khawatir jawaban hilang 🥳</p>
                            <p class="dropdown-header p-custom" v-if="sync_result == 'failed'">Terjadi kesalahan, tetap selesaikan ujian seperti biasa yaa karena progress kamu tersimpan di perangkat untuk sementara 🥺 (informasi lebih lanjut hubungi pengawas)</p>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <Link href="/logout" method="post" as="button" class="dropdown-item"><i class="icon-mid bi bi-box-arrow-right me-2"></i>Logout</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

</template>

<style scoped>
    .anim-rotate {
        animation: rotate 2s linear infinite;
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg)
        }
    }

    .p-custom {
        width: 300px;
        white-space: break-spaces;
        text-wrap: balance;
    }
</style>
