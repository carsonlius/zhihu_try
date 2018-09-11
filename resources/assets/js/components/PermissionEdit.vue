<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold;">编辑权限</span>
            <span class="pull-right"><a href="/permission" class="btn btn-xs btn-info">权限列表</a></span>
        </div>
        <div class="panel-body">
            <prompt-modal ref="prompt_modal"></prompt-modal>
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
                    <span class="alert alert-info control-label">* 如果不选择,则默认一级权限</span>
                    <label for="slug" class="col-sm-2 control-label">选择父级权限</label>
                    <div class="tree col-sm-6">
                        <v-select-tree :data='treeData' v-model.lazy="parent_permission" :searchable="searchable"/>
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
    export default {
        name: "PermissionEdit",
        props:['permission'],
        data: function () {
            return {
                is_show : '',
                permission_obj : {},
                msg_response : '',
                icon_type : 'success',
                model : '',
                name: '',
                slug: '',
                description: '',
                parent_permission : [],
                treeData : [],
                searchable : true
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
                let params = {
                    params : {
                        parent_id : this.permission_obj.parent_id
                    },
                    responseType: 'json'
                };
                this.$http.get('/api/permission/show/tree', params).then(function(response){
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.treeData = response.body.permission.tree;
                        vm.parent_permission.unshift(response.body.permission.parent_name);
                    } else {
                        vm.$refs.prompt_modal.open({
                            title : '提示',
                            body : response.body.msg
                        });
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
                this.is_show = this.permission_obj.is_show;
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
                    parent_name : !!this.parent_permission ?  this.parent_permission[0] : '请选择父级权限',
                    is_show : this.is_show
                };
                // 存储权限
                let vm = this;
                this.$http.post('/api/permission/edit', params, {responseType: 'json'}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.msg_response = '权限"' + vm.name +'" 编辑成功';
                        vm.$refs.prompt_modal.open({
                            title : '编辑权限提示',
                            body : '权限编辑成功',
                            btn_name_left : '权限列表',
                            btn_name_right : '角色管理',
                            btn_url_right : '/Role',
                            btn_url_left : '/permission',

                        });
                    } else {
                        vm.$refs.prompt_modal.open({
                            title : '编辑权限提示',
                            body : '权限编辑失败',
                        });
                    }

                });
            }
        }
    }
</script>

<style scoped>

</style>