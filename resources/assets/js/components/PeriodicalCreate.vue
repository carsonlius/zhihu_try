<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold;">创建期刊</span>
            <span class="pull-right"><a href="/mini/periodicals" class="btn btn-xs btn-info">期刊列表</a></span>
            <prompt-modal ref="prompt_modal"></prompt-modal>
        </div>
        <div class="panel-body">
            <sweet-modal :icon="icon_type" ref="modal_prompt" overlay-theme="dark" modal-theme="dark">
                <p style="white-space: pre-line">{{ msg_response }}</p>
                <button v-on:click="closeModel()" class="btn btn-primary pull-right">确认</button>
            </sweet-modal>
            <div class="form-horizontal">
                <div class="form-group">
                    <span v-show="errors.has('type')" class="alert-danger">{{ errors.first('type') }}</span>
                    <label for="slug" class="col-sm-2 control-label">请选择期刊的类型</label>
                    <div class="tree col-sm-6">
                        <v-select :options="list_type" v-model="type" data-vv-name="type" data-vv-as="* 请选择期刊的类型"
                                  v-validate.initial="'required'" id="type"></v-select>
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('title')" class="alert-danger">{{ errors.first('title') }}</span>
                    <label for="name" class="col-sm-2 control-label">期刊标题</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="title" data-vv-as="* 请输入标题"
                               v-validate.initial="'required'" data-vv-name="title" v-model.trim="title">
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('des')" class="alert-danger">{{ errors.first('des') }}</span>
                    <label for="name" class="col-sm-2 control-label">期刊名称</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="des" data-vv-as="* 请输入描述"
                               v-validate.initial="'required'" data-vv-name="des" v-model.trim="des">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'music' || type_choose === 'movie'">
                    <span v-show="errors.has('name')" class="alert-danger">{{ errors.first('name') }}</span>
                    <label for="name" class="col-sm-2 control-label">名字</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="name" data-vv-as="* 请输入名字"
                               v-validate.initial="'required'" data-vv-name="name" v-model.trim="name">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'music' || type_choose === 'text'">
                    <span v-show="errors.has('author')" class="alert-danger">{{ errors.first('author') }}</span>
                    <label for="name" class="col-sm-2 control-label">请输入作者</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="author" data-vv-as="* 请输入作者"
                               v-validate.initial="'required'" data-vv-name="author" v-model.trim="author">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'movie'">
                    <span v-show="errors.has('actor')" class="alert-danger">{{ errors.first('actor') }}</span>
                    <label for="name" class="col-sm-2 control-label">请输入主演</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="actor" data-vv-as="* 请输入主演"
                               v-validate.initial="'required'" data-vv-name="actor" v-model.trim="actor">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'text'">
                    <span v-show="errors.has('content')" class="alert-danger">{{ errors.first('content') }}</span>
                    <label for="name" class="col-sm-2 control-label">请输入内容</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="content" data-vv-as="* 请输入内容"
                               v-validate.initial="'content'" data-vv-name="content" v-model.trim="content">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'music'">
                    <label for="name" class="col-sm-2 control-label">请输入选择音乐</label>
                    <div class="col-sm-6">
                        <file-uploader :post_action="music_url" @uploaded="onUploadedMusic" :csrf_token="csrf_token"></file-uploader>
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('published')" class="alert-danger">{{ errors.first('published') }}</span>
                    <label for="slug" class="col-sm-2 control-label">请选择是否发布</label>
                    <div class="tree col-sm-6">
                        <v-select :options="list_published" v-model="published" data-vv-name="published" data-vv-as="* 请选择是否发布"
                                  v-validate.initial="'required'" id="published"></v-select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-8">
                        <button class="btn btn-primary  btn-sm pull-right" @click.prevent="create">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue';
    export default {
        name: "PeriodicalCreate",
        props : ['csrf_token'],
        data: function () {
            return {
                is_show : 'F',
                msg_response : '',
                icon_type : 'success',
                upload_active_attr : false, // 是否上传文件

                des : '',// 描述
                img : '', // 封面
                type : '', // 期刊类型
                title : '', // 期刊标题
                published : '', // 是否已经发布
                list_type : [
                    {value : 'music', label : '音乐'},
                    {value : 'movie', label : '电影'},
                    {value : 'text', label : '句子'},
                ], // 类型列表

                list_published : [
                    {label : '待发布', value: 1},
                    {label : '已发布', value: 2},
                    {label : '撤回', value: 3},
                ],

                // data 期刊详细配置
                content : '', // 内容 text
                author : '', // 作者 text  music
                actor : '', // 演员的名字  movie
                name : '', // 名字 movie music
                url : '',  // 地址 music

                // 上传相关的操作
                mp3_uploaded : false, // 音乐文件是否已经上传
                cover_uploaded : false, // 封面是否已经上传
                music_url : '/api/file/music', // 文件上传的地址
            }
        },
        computed:{
            // 期刊的类型
            type_choose(){
                return this.type  && Object.values(this.type).length > 0 ? this.type.value : '';
            },
        },
        methods: {
            // 文件已经完成上传
            onUploadedMusic(param){
                console.log(param, '文件已经完成上传');

            },
            // 关闭模态框
            closeModel(){
                this.$refs.modal_prompt.close();

                // 创建成功则跳转到列表
                if (this.icon_type === 'success') {
                    window.location.replace('/permission');
                }
            },

            // 新建期刊
            create() {
                let params = {
                    name: this.name,
                    slug: this.slug,
                    description: this.description,
                    model: this.model,
                    parent_name : this.permission_selected[0],
                    is_show: this.is_show
                };
                let url = '/api/permission';
                console.log(params);
                // 存储期刊
                let vm = this;
                this.$http.post(url, params, {responseType: 'json'}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.msg_response = '期刊"' + vm.name +'" 创建成功';
                    } else {
                        vm.msg_response = response.body.msg;
                        vm.icon_type = 'error';
                    }
                    vm.$refs.modal_prompt.open();
                });
            }
        }
    }
</script>

<style scoped>

</style>