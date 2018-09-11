<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold;">创建权限</span>
            <span class="pull-right"><a href="/permission" class="btn btn-xs btn-info">权限列表</a></span>
            <prompt-modal ref="prompt_modal"></prompt-modal>
        </div>
        <div class="panel-body">
            <sweet-modal :icon="icon_type" ref="modal_prompt" overlay-theme="dark" modal-theme="dark">
                <p style="white-space: pre-line">{{ msg_response }}</p>
                <button v-on:click="closeModel()" class="btn btn-primary pull-right">确认</button>
            </sweet-modal>
            <div class="form-horizontal">
                <div class="form-group">
                    <span v-show="errors.has('name')" class="alert-danger">{{ errors.first('name') }}</span>
                    <label for="name" class="col-sm-2 control-label">权限名称</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="name" data-vv-as="* 权限名称"
                               v-validate.initial="'required'" data-vv-name="name" v-model.trim="name">
                    </div>
                </div>

                <div class="form-group">
                    <span v-show="errors.has('slug')" class="alert-danger">{{ errors.first('slug') }}</span>
                    <label for="slug" class="col-sm-2 control-label">Slug</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" data-vv-name="slug" v-model.trim="slug" data-vv-as="* 唯一标识(如果是最低级别，请匹配路由)"
                               v-validate.initial="'required'" id="slug">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2">是否显示</label>
                    <div class="col-sm-6">
                        <label class="radio-inline">
                            <input type="radio" v-model.trim="is_show" value="F"> 不显示
                        </label>
                        <label class="radio-inline">
                            <input type="radio" v-model.trim="is_show" value="T"> 显示
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Model</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" v-model.trim="model" id="model">
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-6">
                        <textarea v-model.trim="description" class="form-control" data-vv-name="description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <span class="alert alert-info control-label">* 如果不选择,则默认一级权限</span>
                    <label for="slug" class="col-sm-2 control-label">选择父级权限</label>
                    <div class="tree col-sm-6">
                        <v-select-tree :data='treeData' v-model.lazy="permission_selected" :searchable="searchable"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-8">
                        <button class="btn btn-primary  btn-sm pull-right" @click.prevent="createPermission">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue';
    export default {
        name: "PermissionCreate",
        data: function () {
            return {
                is_show : 'F',
                msg_response : '',
                icon_type : 'success',
                model : '',
                name: '',
                slug: '',
                description: '',
                list_permission_response : [],
                lang: 'zh',
                searchable: true,
                treeData: [], // 节点树
                permission_selected : ['请选择父级权限'],
            }
        },
        mounted (){
            // 获取权限列表
            this.iniPermissionList();
        },
        methods: {
            // 获取权限列表
            iniPermissionList(){
                let vm = this;
                this.$http.get('/api/permission/tree', {responseType: 'json'}).then(function (response) {
                    if (response.body.status === 0) {
                        vm.treeData = response.body.list_permission;
                    } else {
                        this.$refs.prompt_modal.open({
                            title : '提示',
                            body: '网络故障,请稍后再试'
                        });
                    }
                });
            },

            // 关闭模态框
            closeModel : function(){
                this.$refs.modal_prompt.close();

                // 创建成功则跳转到列表
                if (this.icon_type === 'success') {
                    window.location.replace('/permission');
                }
            },

            // 新建权限
            createPermission: function () {
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

                // 存储权限
                let vm = this;
                this.$http.post(url, params, {responseType: 'json'}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.msg_response = '权限"' + vm.name +'" 创建成功';
                    } else {
                        vm.msg_response = response.body.msg;
                        vm.icon_type = 'error';
                    }
                    vm.$refs.modal_prompt.open();
                });
            },

            search : function() {
                this.$refs.tree.searchNodes(this.searchword)
            }
        }
    }
</script>

<style scoped>

</style>