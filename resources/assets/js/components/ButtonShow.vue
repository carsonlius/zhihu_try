<template>
    <div class="container">
        <input type="text" v-model="wang">

    </div>
</template>

<script>
    export default {
        name: "button-show",
        data : function () {
            return {
                wang : 'hello world'
            }
        }
    }
</script>

<style scoped>

</style>