<template>
    <section class="app-main">
        <transition name="fade-transform" mode="out-in">
            <router-view :key="key" />
        </transition>
    </section>
</template>

<script>
    export default {
        name: 'AppMain',
        computed: {
            key() {
                console.log(this.$route.path)
                return this.$route.path
            }
        }
    }
</script>