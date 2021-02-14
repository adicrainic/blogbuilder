<template>
    <div>
        <h1>Hook Tutorial</h1>
        <h1>My name is {{name}} </h1>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: 'adrian c'
        }
    },
    //hooks
    beforeCreate() {
    },
    created() {
        //instantiated before html is rendered
        //call mostly initial data from it
        //data and other function are available but not html ex this.name is available
    },
    mounted() {
        //instantiated after html is build
        //access stuff like localstorage
        //access html elements
    },
    beforeMount() {
    }
}
</script>

<style scoped>

</style>
