<script setup>
import flasher from '@flasher/flasher';
import Header from './Partial/Header.vue';
import Footer from './Partial/Footer.vue';
</script>

<script>
    export default {
        props: {
            messages: Object,
        },
        watch: {
            messages(value) {
                flasher.render(value);
            }
        }
    }
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
