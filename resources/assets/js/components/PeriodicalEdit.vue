<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <prompt-modal ref="prompt_cmp"></prompt-modal>
            <span style="font-weight: bold;">编辑期刊</span>
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
                    <label for="type" class="col-sm-2 control-label">期刊类型</label>
                    <div class="col-sm-6">
                        <v-select :options="list_type" v-model="type" data-vv-name="type" data-vv-as="* 期刊类型"
                                  v-validate="'required'" id="type"></v-select>
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('title')" class="alert-danger">{{ errors.first('title') }}</span>
                    <label for="name" class="col-sm-2 control-label">期刊标题</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="title" data-vv-as="* 期刊标题"
                               v-validate="'required'" data-vv-name="title" v-model.trim="title">
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('des')" class="alert-danger">{{ errors.first('des') }}</span>
                    <label for="name" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="des" data-vv-as="* 描述"
                               v-validate="'required'" data-vv-name="des" v-model.trim="des">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'music' || type_choose === 'movie'">
                    <span v-show="errors.has('name')" class="alert-danger">{{ errors.first('name') }}</span>
                    <label for="name" class="col-sm-2 control-label">名字</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="name" data-vv-as="* 名字"
                               v-validate="'required'" data-vv-name="name" v-model.trim="name">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'music' || type_choose === 'text'">
                    <span v-show="errors.has('author')" class="alert-danger">{{ errors.first('author') }}</span>
                    <label for="name" class="col-sm-2 control-label">作者</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="author" data-vv-as="* 作者"
                               v-validate="'required'" data-vv-name="author" v-model.trim="author">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'movie'">
                    <span v-show="errors.has('actor')" class="alert-danger">{{ errors.first('actor') }}</span>
                    <label for="name" class="col-sm-2 control-label">主演</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="actor" data-vv-as="* 主演"
                               v-validate="'required'" data-vv-name="actor" v-model.trim="actor">
                    </div>
                </div>

                <div class="form-group" v-if="type_choose === 'text'">
                    <span v-show="errors.has('content')" class="alert-danger">{{ errors.first('content') }}</span>
                    <label for="name" class="col-sm-2 control-label">内容</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="content" data-vv-as="* 内容"
                               v-validate="'content'" data-vv-name="content" v-model.trim="content">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">封面</label>
                    <div class="col-sm-4">
                        <img :src="img" alt="封面" class="cover-img">
                    </div>
                    <div class="col-sm-6">
                        <file-single-uploader uploaded_type="uploadedimg"
                                @exception="onException" :post_action="upload_url" @uploadedimg="onUploadedImg" :size="1024 * 1024"></file-single-uploader>
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('published')" class="alert-danger">{{ errors.first('published') }}</span>
                    <label for="published" class="col-sm-2 control-label">是否发布</label>
                    <div class="tree col-sm-6">
                        <v-select :options="list_published" v-model="published" data-vv-name="published" data-vv-as="* 是否发布"
                                  v-validate="'required'" id="published"></v-select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-8">
                        <button class="btn btn-primary  btn-sm pull-right" @click.prevent="update">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue';
    export default {
        name: "PeriodicalEdit",
        props : ['periodical'],
        data: function () {
            return {
                msg_response : '',
                icon_type : 'success',

                des : '',// 描述
                img : '', // 封面
                type : '', // 期刊类型
                title : '', // 期刊标题
                published : '', // 是否已经发布
                periodical_index : '', // 期号
                list_type : [
                    {value : 'music', label : '音乐'}
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
                upload_url : '/api/file', // 文件上传的地址

                periodical_id : '',  //期刊ID
            }
        },
        computed:{
            // 期刊的类型
            type_choose(){
                return this.type  && Object.values(this.type).length > 0 ? this.type.value : '';
            },
        },

        mounted(){
            // 初始化环境
            this._initEnv();
        },
        methods: {
            // 初始化环境
            _initEnv(){
                let periodical = JSON.parse(this.periodical);

                // 初始化普通属性
                this.des = periodical.des;
                this.title = periodical.title;
                this.img = periodical.img;
                this.periodical_id = periodical.id;
                this.periodical_index = periodical.periodical_index;
                this.type = this.list_type.find(item=>{
                    return item.value === periodical.type;
                });
                this.published = this.list_published.find(item=>{
                    return item.value === parseInt(periodical.published);
                });

                // 特殊字段初始化
                let data = JSON.parse(periodical.data);
                this.content = data.content ? data.content : '';
                this.author = data.author ? data.author : '';
                this.actor = data.actor ? data.actor : '';
                this.url = data.url ? data.url : '';
                this.name = data.name ? data.name : '';
            },

            // 监听异常情况
            onException(params){
                this.$refs.prompt_cmp.open(params);
            },

            // 文件已经完成上传
            onUploadedImg(param){
                console.log(param, '上传的文件的状态发生了变化');
                this.img = param.file_name;
            },
            // 关闭模态框
            closeModel(){
                this.$refs.modal_prompt.close();

                // 创建成功则跳转到列表
                if (this.icon_type === 'success') {
                    window.location.replace('/mini/periodicals');
                }
            },

            // 新建期刊
            update() {
                // 获取参数
                let params = this._genParams();
                if (!params) {
                    return;
                }

                // update
                let url = '/api/mini/periodicals';
                axios.patch(url, params, {responseType: 'json'}).then( (response)=> {
                    console.log(response);
                    if (response.data.status === 0) {
                        this.msg_response = '期刊更新成功';
                    } else {
                        this.msg_response = response.data.msg;
                        this.icon_type = 'error';
                    }
                    this.$refs.modal_prompt.open();
                });
            },

            // 生成参数
            _genParams(){
                let params = {
                    id : this.periodical_id, // 期刊ID
                    des : this.des,// 描述
                    img : this.img, // 封面
                    type : this.type && Object.values(this.type).length !== 0 ? this.type.value : '', // 期刊类型
                    title : this.title, // 期刊标题
                    published : this.published && Object.values(this.published).length !== 0 ? this.published.value : '', // 是否已经发布
                    content : this.content, // 内容 text
                    author : this.author, // 作者 text  music
                    actor : this.actor, // 演员的名字  movie
                    name : this.name, // 名字 movie music
                };

                // 常规参数是否是完成了
                try {
                    !params.type && this._errorShow('请选择期刊类型');
                    !params.des && this._errorShow('请输入描述');
                    !params.title && this._errorShow('请输入标题');
                    !params.img && this._errorShow('请上传封面');
                    !params.published && this._errorShow('请选择发布的状态');

                    params.type === 'text' && !params.content && this._errorShow('请输入内容');
                    params.type !== 'movie' && !params.author && this._errorShow('请输入作者');
                    params.type === 'movie' && !params.actor && this._errorShow('请输入演员');
                    params.type !== 'text' && !params.name && this._errorShow('请输入名字');
                    return params;
                } catch (e) {
                    return false;
                }

            },
            // 异常处理
            _errorShow(body){
                this.$refs.prompt_cmp.open({
                    title:'提示',
                    body
                });
                throw '参数异常';
            }
        }
    }
</script>

<style scoped>
    .cover-img {
        width: 300px;
        height: 200px;
    }

</style>