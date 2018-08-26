<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold;">编辑权限</span>
            <span class="pull-right"><a href="/permission" class="btn btn-xs btn-info">权限列表</a></span>
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
                        <input type="text" class="form-control" id="name" data-vv-as="权限名称"
                               v-validate.initial="'required'" data-vv-name="name" v-model="name">
                    </div>
                </div>
                <div class="form-group">
                    <span v-show="errors.has('slug')" class="alert-danger">{{ errors.first('slug') }}</span>
                    <label for="slug" class="col-sm-2 control-label">Slug</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="slug" v-model="slug" data-vv-as="唯一标识"
                               v-validate.initial="'required'" id="slug">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">父级ID</label>
                    <div class="col-sm-6">
                        <v-select :options="list_permissions" v-model="parent_permission"></v-select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Model</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" v-model="model">
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-6">
                        <textarea v-model="description" class="form-control" data-vv-name="description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-8">
                        <button class="btn btn-primary  btn-sm pull-right" @click.prevent="editPermission">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue';
    export default {
        name: "PermissionEdit",
        props:['permission'],
        data: function () {
            return {
                permission_obj : {},
                msg_response : '',
                icon_type : 'success',
                model : '',
                name: '',
                slug: '',
                description: '',
                parent_permission : {label : '一级菜单', id:0},
                list_permission_response : [],
            }
        },
        computed : {
            list_permissions : function () {
                let list_permission = this.list_permission_response.map(function (item) {
                    item.label = item.name;
                    return item;
                });
                list_permission.unshift({label : '一级菜单', id:0});
                return list_permission;
            }
        },
        created () {
            // 初始化组件
            this.initComponent();
            this.iniPermissionList();
        },
        methods: {
            // 获取权限列表
            iniPermissionList(){
                let vm = this;
                this.$http.get('/api/permission').then(function(response){
                    if (response.body.status === 0) {
                        vm.list_permission_response = response.body.list_permissions;
                    }
                });
            },

            // 初始化参数
            initComponent() {
                this.permission_obj = JSON.parse(this.permission);
                this.name = this.permission_obj.name;
                this.slug = this.permission_obj.slug;
                this.description = this.permission_obj.description;
                this.model = this.permission_obj.model;
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
            editPermission: function () {
                let params = {
                    name: this.name,
                    slug: this.slug,
                    description: this.description,
                    permission_id : this.permission_obj.id,
                    parent_id : this.parent_permission.id
                };
                let url = '/api/permission/edit';

                // 存储权限
                let vm = this;
                this.$http.post(url, params, {responseType: 'json'}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.msg_response = '权限"' + vm.name +'" 编辑成功';
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