<template>
    <CarMatForm />
</template>

<script>
import CarMatForm from "./components/CarMatForm.vue";

export default {
    name: "App",
    components: {
        CarMatForm,
    },
};
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
}
</style>
