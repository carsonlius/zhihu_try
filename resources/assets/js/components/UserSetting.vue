<template>
    <div>
        <sweet-modal :icon="icon_type" ref="modal_prompt" overlay-theme="light" modal-theme="dark">
            <p style="white-space: pre-line">{{ msg_prompt }}</p>
            <button v-on:click="closeModel()" class="btn btn-primary pull-right">确认</button>
        </sweet-modal>
        <form class="form-horizontal" @submit.stop.prevent="update">
            <div class="form-group">
                <label class="col-sm-2 control-label">现居地址</label>
                <div class="col-sm-10">
                    <area-select :level="level" :placeholders="placeholder" type="text" v-model='area' :data="data"></area-select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">个人简介</label>
                <div class="col-sm-10">
                    <textarea v-model="profile" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-sm pull-right btn-margin" type="submit">更新资料</button>
            </div>
        </form>
    </div>
</template>

<script>
    import { SweetModal, SweetModalTab  } from 'sweet-modal-vue';
    import {pca, pcaa} from 'area-data';
    export default {
        name: "UserSetting",
        props: ['settings'],
        data: function () {
            return {
                icon_type : 'success',
                redirect_url :'',
                msg_prompt : '',
                level: 2,
                area: [],
                placeholder: ['选择省', '选择市', '选择区'],
                profile: '',
                selected: []
            }
        },
        mounted : function(){
            this.initParams();
        },
        methods : {
            closeModel: function(){
                this.$refs.modal_prompt.close();
                if (this.redirect_url) {
                    let vm = this;
                    setTimeout(function(){
                        window.location.replace(vm.redirect_url);
                    }, 500);
                }
            },
            initParams: function(){
              console.log(this.settings, typeof this.settings);
              let settings = JSON.parse(this.settings);
              console.log(settings, typeof settings);
              this.profile = settings.bio;
              this.area = settings.area ? settings.area : [];
              },
            update : function(){
              let url = '/api/setting';
              let params = {
                  bio : this.profile,
                  area : this.area
              };
              this.$http.post(url, params, {responseType: 'json'}).then(function (response) {
                  console.log(response);
                  if (response.body.status === 0) {
                      this.msg_prompt = '更新配置成功！';
                      this.redirect_url = '/';
                      this.$refs.modal_prompt.open();
                  } else {
                      this.msg_prompt = '更新配置失败！';
                      this.icon_type = 'error';
                      this.$refs.modal_prompt.open();
                  }
              });
            }
        },
        computed: {
            data: function () {
                return pcaa;
            }
        }

    }
</script>

<style scoped>
    .btn-margin {
        margin-right: 1%;
    }
</style>