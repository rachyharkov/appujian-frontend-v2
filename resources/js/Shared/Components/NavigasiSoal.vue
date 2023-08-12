<script>
    export default {
        props: {
            currentNomor: Number,
            soals: Array,
        },
        data() {
            return {
                is_show: false,
            }
        },
        emits: ['changeSoal'],
        watch: {
            is_show(value) {
                if(value) {
                    document.getElementById('iwantthistobebackdrop').classList.add('sidebar-backdrop');
                } else {
                    document.getElementById('iwantthistobebackdrop').classList.remove('sidebar-backdrop');
                }
            }
        },
        mounted() {
            const hideSidebar = () => {
                const sidebar = document.getElementById('iwantthistobebackdrop');
                this.is_show = false;
            }

            document.getElementById('iwantthistobebackdrop').addEventListener('click', hideSidebar);
        }
    }
</script>

<template>
    <div class="nav-soal-wrapper w-100" :class="{ 'show': is_show }" >
        <button class="btn btn-primary btn-nav-soal" @click="is_show = !is_show">
            <i class="bi bi-chevron-up" style="position: relative;top: -20px;"></i>
        </button>
        <div class="d-flex gap-3 flex-wrap w-100 p-4" style="background-color: white; margin-top: -40px; height: 145px; overflow-y: scroll;">
            <button v-for="i in $page.props.soals.length" :key="i" class="btn" style="width: 40px; height: 40px;" :class="{'btn-primary': currentNomor == i - 1, 'btn-outline-primary': currentNomor != i - 1}" @click="
                $emit('changeSoal', i - 1);
            ">{{ i }}</button>
        </div>
    </div>
</template>

<style scoped>

    .nav-soal-wrapper .btn-nav-soal {
        width: 5rem;
        height: 5rem;
        border-radius: 50%;
        position: relative;
        z-index: -1;
        top: -10px;
        transition: all 0.5s ease-in-out;
    }

    .nav-soal-wrapper.show .btn-nav-soal {
        width: 5rem;
        height: 5rem;
        border-radius: 50%;
        top: 0px;
        z-index: -1;
        transition: all 0.5s ease-in-out;
    }

    .nav-soal-wrapper {
        position: fixed;
        bottom: -18.4%;
        display: flex;
        z-index: 10;
        left: 0;
        flex-direction: column;
        align-items: center;
        transition: 0.5s ease-in-out;
    }

    .nav-soal-wrapper.show {
        bottom: 0;
        transition: 0.5s ease-in-out;
    }


    .nav-soal-wrapper .btn-nav-soal i::before {
        transition: 0.5s ease-in-out;
    }

    .nav-soal-wrapper.show .btn-nav-soal i::before {
        transform: rotate(180deg);
        transition: 0.5s ease-in-out;
    }


    @media (min-width: 768px) {
        .nav-soal-wrapper {
            display: none;
        }
    }

</style>
