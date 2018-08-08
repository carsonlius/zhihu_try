<template>
    <div style="text-align: center">
        <sweet-modal :icon="icon_type" ref="modal_prompt" overlay-theme="dark" modal-theme="dark">
            <p style="white-space: pre-line">{{ msg_error }}</p>
            <button v-on:click="closeModel()" class="btn btn-primary pull-right">确认</button>
        </sweet-modal>
        <img :src="imgDataUrl" height="200px" width="160px">
        <div style="margin-top:10px"><button class="btn btn-primary" @click="toggleShow">修改头像</button></div>
        <my-upload field="img_avatar"
                   @crop-success="cropSuccess"
                   @crop-upload-success="cropUploadSuccess"
                   @crop-upload-fail="cropUploadFail"
                   v-model="show"
                   :width="300"
                   :height="300"
                   url="/avatar"
                   :params="params"
                   :headers="headers"
                   img-format="png"></my-upload>
    </div>
</template>

<script>
    import 'babel-polyfill'; // es6 shim
    import myUpload from 'vue-image-crop-upload';
    import { SweetModal, SweetModalTab  } from 'sweet-modal-vue';
    export default {
        name: "AvatarUser",
        props : ['avatar_user', 'csrf_token'],
        data: function(){
            return {
                show: false,
                params: {
                    _token: this.csrf_token,
                    name: 'avatar'
                },
                headers: {
                    smail: '*_~'
                },
                imgDataUrl: this.avatar_user,
                msg_error : '',
                icon_type : 'success'
            }
        },
        components: {
            'my-upload': myUpload,
        },
        methods: {
            closeModel : function(){
              this.$refs.modal_prompt.close();
            },
            toggleShow() {
                this.show = !this.show;
            },
            /**
             * crop success
             *
             * [param] imgDataUrl
             * [param] field
             */
            cropSuccess(imgDataUrl, field){
                console.log('-------- crop success --------');
                this.imgDataUrl = imgDataUrl;
            },
            /**
             * upload success
             *
             * [param] jsonData   服务器返回数据，已进行json转码
             * [param] field
             */
            cropUploadSuccess(response, field){
                console.log('-------- uploading --------');
                console.log(response);
                console.log('field: ' + field);
                if (response.status !== 0) {
                    this.msg_error = '上传出错，请稍后再试';
                    this.icon_type = 'error';
                    this.$refs.modal_prompt.open();
                } else {
                    this.imgDataUrl = response.img_url;
                    console.log(this.imgDataUrl);
                }
            },
            /**
             * upload fail
             *
             * [param] status    server api return error status, like 500
             * [param] field
             */
            cropUploadFail(status, field){
                this.msg_error = '上传出错，请稍后再试';
                this.icon_type = 'error';
                this.$refs.modal_prompt.open();
                console.log('-------- upload fail --------');
                console.log(status);
                console.log('field: ' + field);
            }
        }
    }
</script>

<style scoped>

</style>