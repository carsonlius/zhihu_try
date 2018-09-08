<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                        用户权限列表
                    </div>
                    <div class="panel-body">
                        <form class="form-inline" @submit.prevent="initUserList">
                            <div class="form-group">
                                <v-select :options="list_user" v-model="user" label="name" :placeholder="placeholder"></v-select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-primary">查询</button>
                            </div>
                        </form>
                    </div>
                    <prompt-modal ref="prompt_modal"></prompt-modal>
                </div>
                <v-table
                        is-vertical-resize
                        :vertical-resize-offset='60'
                        is-horizontal-resize
                        style="width:100%"
                        :multiple-sort="false"
                        :min-height="350"
                        even-bg-color="#f2f2f2"
                        :columns="tableConfig.columns"
                        :table-data="tableConfig.tableData"
                        row-hover-color="#eee"
                        row-click-color="#edf7ff"
                        @sort-change="sortChange"
                        :paging-index="(pageIndex-1)*pageSize"
                        @on-custom-comp="customCompFunc"
                ></v-table>

                <div class="mt20 mtop-20 mb20 bold"></div>
                <v-pagination @page-change="pageChange" @page-size-change="pageSizeChange" :total="total" :page-size="pageSize"
                              :layout="['total', 'prev', 'pager', 'next', 'sizer', 'jumper']"></v-pagination>

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "UserRoleList",
        data() {
            return {
                placeholder : '查找用户',
                list_user : [], // 全量用户列表
                user : '', // 选中的用户
                tableDate: [],
                pageIndex: 1,
                total: 0,
                pageSize: 20,
                tableConfig: {
                    multipleSort: false,
                    tableData: [],
                    columns: [
                        {
                            field: 'id',
                            title: 'ID',
                            width: 80,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true,
                            titleCellClassName: 'title_column'
                        },
                        {
                            field: 'name',
                            title: '用户',
                            width: 150,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true,
                            titleCellClassName: 'title_column'
                        },
                        {
                            field: 'role_list',
                            title: '角色',
                            width: 150,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true,
                            titleCellClassName: 'title_column',
                            formatter : function (rowData,rowIndex,pagingIndex,field) {
                                let field_role = '';
                                rowData.roles.map(function(item){
                                    field_role += '<a class="btn btn-primary btn-xs" target="_blank" href="/Role/permission?role_id=' + item.id +'&role_name='+item.name+'">'+item.name + '</a>&nbsp;';
                                });
                                return field_role;
                            }
                        },
                        {
                            field: 'custome-adv',
                            title: '操作',
                            width: 200,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            componentName: 'table-operation',
                            isResize: true,
                            titleCellClassName: 'title_column'
                        }
                    ]
                }
            }
        },
        mounted: function () {
            // 获取数据源
            this.initList();
        },
        methods: {
            // 获取数据源
            initList: function () {
                // 初始化筛选列表
                this.initUserSelect();

                // 初始化用户列表
                this.initUserList();
            },
            // 初始化用户
            initUserList : function() {
                let vm = this;

                let params = {
                    user_id : this.user.id
                };
                this.$http.post('/api/user/role', params, {responseType: 'json',}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.tableDate = response.body.list_user;
                        vm.total = vm.tableDate.length;
                        vm.getTableData();
                    } else {
                        this.$refs.prompt_modal.open({
                            title : '提示',
                            body: '网络故障，请稍后再试'
                        });
                    }
                });

            },
            // 初始化筛选列表
            initUserSelect: function(){
                let vm = this;
                this.$http.get('/api/user/list', {responseType: 'json'}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.list_user = response.body.list_user;
                    } else {
                        this.$refs.prompt_modal.open({
                            title : '提示',
                            body: '网络故障，请稍后再试'
                        });
                    }
                });

            },
            getTableData() {
                this.tableConfig.tableData = this.tableDate.slice((this.pageIndex - 1) * this.pageSize, (this.pageIndex) * this.pageSize)
            },
            pageChange(pageIndex) {

                this.pageIndex = pageIndex;
                this.getTableData();
                console.log(pageIndex)
            },
            pageSizeChange(pageSize) {

                this.pageIndex = 1;
                this.pageSize = pageSize;
                this.getTableData();
            },
            sortChange(params) {
                console.log('排序', params);
            },
            customCompFunc(params) {
                switch (params.type) {
                    case 'edit':
                        // 编辑
                        let url_edit = '/user/role?user_id=' + params.rowData.id + '&user_name=' + params.rowData.name;
                        window.open(url_edit, '_blank');
                        break;
                    case 'delete':
                        console.log('触发了删除事件');
                        break;
                }
            }
        },

    }


    // 自定义列组件
    Vue.component('table-operation',{
        template:`<span>
        <a href="" class="btn btn-primary btn-xs" @click.stop.prevent="update(rowData,index)">编辑</a>&nbsp;
        </span>`,
        props:{
            rowData:{
                type:Object
            },
            field:{
                type:String
            },
            index:{
                type:Number
            }
        },
        methods:{
            update(){

                // 参数根据业务场景随意构造
                let params = {type:'edit',index:this.index,rowData:this.rowData};
                this.$emit('on-custom-comp',params);
            },

            deleteRow(){

                // 参数根据业务场景随意构造
                let params = {type:'delete',index:this.index, rowData:this.rowData};
                this.$emit('on-custom-comp',params);

            }
        }
    });
</script>

<style scoped>
    .mtop-20 {
        margin-top: 15px
    }
</style>