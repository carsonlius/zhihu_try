<template>
        <sweet-modal :icon="icon_type" overlay-theme="dark" modal-theme="dark">
            <p style="white-space: pre-line">{{ msg_error }}</p>
            <button v-on:click="closeModel()" class="btn btn-primary pull-right">确认</button>
        </sweet-modal>
</template>

<script>
    export default {
        name: "ModalPrompt",
        data : function(){
            return {
                icon_type : 'success',
                msg_error : '请写入提示哦',
            }
        },
        methods : {
            closeModel : function () {

            }
        }
    }
</script>

<style scoped>

</style>