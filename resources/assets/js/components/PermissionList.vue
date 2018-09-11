<template>
    <div>
        <prompt-modal ref="prompt_modal"></prompt-modal>
        <div class="panel-default panel">
            <div class="panel-heading">
                <span style="font-weight: bold;">权限列表</span>
                <span class="pull-right"><a href="/permission/create" class="btn btn-primary btn-xs">新建权限</a></span>
            </div>
            <div>
                <form @submit.stop.prevent="searchPermission">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <v-select v-model="permission_selected" :label="label" :placeholder="placeholder" :options="list_permission"></v-select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">查询</button>
                    </div>
                </form>
            </div>
        </div>
        <v-table
                is-vertical-resize
                :vertical-resize-offset='60'
                is-horizontal-resize
                style="width:100%"
                :multiple-sort="false"
                :min-height="600"
                even-bg-color="#f2f2f2"
                :columns="tableConfig.columns"
                :table-data="tableConfig.tableData"
                row-hover-color="#eee"
                row-click-color="#edf7ff"
                @sort-change="sortChange"
                :paging-index="(pageIndex-1)*pageSize"
                @on-custom-comp="customCompFunc"
        ></v-table>
        <div class="mtop-20"></div>
        <v-pagination @page-change="pageChange" @page-size-change="pageSizeChange" :total="total" :page-size="pageSize" :layout="['total', 'prev', 'pager', 'next', 'sizer', 'jumper']"></v-pagination>
    </div>
</template>

<script>
    export default {
        name: "PermissionList",
        data(){
            return {
                placeholder : '选择权限',
                permission_selected : '', // 下拉框选中权限
                list_permission : [], // 权限列表
                label : 'name',
                tableDate : [],
                pageIndex:1,
                total : 0,
                pageSize:20,
                tableConfig: {
                    multipleSort: false,
                    tableData: [],
                    columns: [
                        {field: 'id', title: 'ID', width: 80, titleAlign: 'center', columnAlign: 'center',isResize:true},
                        {field: 'name', title: '权限名称', width: 150, titleAlign: 'center', columnAlign: 'center',isResize:true},
                        {field: 'slug', title: '权限Slug', width: 150, titleAlign: 'center', columnAlign: 'center',isResize:true},
                        {field: 'is_show', title: '是否展示', width: 150, titleAlign: 'center', columnAlign: 'center',isResize:true, formatter:function(rowData){
                                return rowData.is_show === 'T' ? '<span style="color:green">显示</span>' : '<span>不显示</span>';
                            }},
                        {field: 'model', title: 'Model', width: 80, titleAlign: 'center', columnAlign: 'center',isResize:true},
                        {field: 'description', title: '权限描述', width: 400, titleAlign: 'center', columnAlign: 'center',isResize:true},
                        {field: 'custome-adv', title: '操作',width: 200, titleAlign: 'center',columnAlign:'center',componentName:'operation',isResize:true}
                    ]
                }
            }
        },
        mounted : function(){
            // 获取数据源
            this.initList();
        },
        methods:{
            // 查询权限
            searchPermission : function(){
                let vm = this;
                this.$http.get('/api/permission', {responseType : 'json', params : {permission_id : !!this.permission_selected ? this.permission_selected.id : ''}})
                    .then(function (response) {
                        if (response.body.status === 0) {
                            // selected && table 数据初始化
                            vm.list_permission = vm.tableDate  = response.body.list_permissions;
                            vm.total = vm.tableDate.length;
                            vm.getTableData();
                        } else {
                            this.$refs.prompt_modal.open({
                                title : '提示',
                                body :  '网络故障,请稍后再试'
                            });
                        }
                    });
            },
            // 获取数据源
            initList: function(){
                let vm = this;
                this.$http.get('/api/permission', {responseType:'json'}).then(function(response){
                    console.log(response);
                    if (response.body.status === 0) {
                        // selected && table 数据初始化
                        vm.list_permission = vm.tableDate  = response.body.list_permissions;
                        vm.total = vm.tableDate.length;
                        vm.getTableData();
                    } else {
                        this.$refs.prompt_modal.open({
                            title : '提示',
                            body :  '网络故障,请稍后再试'
                        });
                    }
                });
            },
            getTableData(){
                this.tableConfig.tableData = this.tableDate.slice((this.pageIndex-1)*this.pageSize,(this.pageIndex)*this.pageSize)
            },
            pageChange(pageIndex){

                this.pageIndex = pageIndex;
                this.getTableData();
                console.log(pageIndex)
            },
            pageSizeChange(pageSize){

                this.pageIndex = 1;
                this.pageSize = pageSize;
                this.getTableData();
            },
            sortChange(params){

                if (params.height.length > 0){

                    this.tableConfig.tableData.sort(function (a, b) {

                        if (params.height === 'asc'){

                            return a.height - b.height;
                        }else if(params.height === 'desc'){

                            return b.height - a.height;
                        }else{

                            return 0;
                        }
                    });
                }
            },
            customCompFunc(params){

                console.log(params);
                let vm = this;
                if (params.type === 'delete'){ // do delete operation
                    // del
                    this.$http.post('/api/permission/' + params.rowData.id, {}, {responseType: 'json'}).then(function(response){
                        console.log(response);
                        if (!!response.body && response.body.status === 0) {
                            // 从列表中删除
                            Vue.delete( vm.tableDate, params.index);
                            this.getTableData();
                        } else if (!response.body){
                          this.$refs.prompt_modal.open({
                              title : '提示',
                              body : '抱歉，您没有删除权限哦'
                          });
                        }
                    });

                }else if (params.type === 'edit'){ // do edit operation
                    // 编辑
                    let url_edit = '/permission/' + params.rowData.id + '/edit';
                    window.open(url_edit, '_blank');
                }
            }
        },

    }

    // 自定义列组件
    Vue.component('operation', {
        template : `<ul class="list-inline">
<li><a href="" class="btn btn-primary btn-xs" @click.stop.prevent="update(rowData,index)">编辑</a></li>
<li><a href="" class="btn btn-danger btn-xs" @click.stop.prevent="deleteRow(rowData,index)">删除</a></li>
</ul>`,
        props: {
            rowData: {
                type: Object
            },
            field: {
                type: String
            },
            index: {
                type: Number
            }
        },
        methods: {
            update() {

                // 参数根据业务场景随意构造
                let params = {type: 'edit', index: this.index, rowData: this.rowData};
                this.$emit('on-custom-comp', params);
            },
            deleteRow() {
                // 参数根据业务场景随意构造
                let params = {type: 'delete', index: this.index,rowData: this.rowData};
                this.$emit('on-custom-comp', params);

            }
        }
    });
</script>

<style scoped>
    .mtop-20 {
        margin-top:15px
    }
</style>