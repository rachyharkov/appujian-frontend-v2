<script setup>
import Sidebar from './Partial/Sidebar.vue';
import Header from './Partial/Header.vue';
import Footer from './Partial/Footer.vue';
</script>

<template>
    <div class="container">
        <Header/>
        <div id="main-content" style="min-height: calc(100vh - 15vh);">
            <slot/>
        </div>
        <Footer/>
    </div>
</template>
