<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header>
        <nav class="navbar navbar-light">
            <div class="container d-block">
                <a href="index.html"><i class="bi bi-chevron-left"></i></a>
                <a class="navbar-brand ms-4" href="index.html"><h4 class="d-inline-block">e-Ujian</h4></a>
                <div class="dropdown float-end">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ $page.props.auth.murid.nama }}</h6>
                                <p class="mb-0 text-sm text-gray-600">{{ $page.props.auth.user.username }}</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="/assets/static/images/faces/1.jpg">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem;">
                        <li>
                            <h6 class="dropdown-header">Halo, {{ $page.props.auth.murid.nama }}</h6>
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
