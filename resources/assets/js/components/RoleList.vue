<template>
    <div>
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

        <div class="mt20 mb20 bold"></div>
        <v-pagination @page-change="pageChange" @page-size-change="pageSizeChange" :total="total" :page-size="pageSize" :layout="['total', 'prev', 'pager', 'next', 'sizer', 'jumper']"></v-pagination>
    </div>
</template>

<script>
    export default {
        name: "RoleList",
        data(){
            return {
                tableDate : [],
                pageIndex:1,
                total : 0,
                pageSize:20,
                tableConfig: {
                    multipleSort: false,
                    tableData: [],
                    columns: [
                    {field: 'id', title: 'ID', width: 80, titleAlign: 'center', columnAlign: 'center',isResize:true},
                    {field: 'name', title: '角色名称', width: 150, titleAlign: 'center', columnAlign: 'center',isResize:true},
                    {field: 'slug', title: '角色Slug', width: 150, titleAlign: 'center', columnAlign: 'center',isResize:true},
                    {field: 'level', title: '角色等级', width: 80, titleAlign: 'center', columnAlign: 'center',isResize:true},
                    {field: 'description', title: '角色描述', width: 400, titleAlign: 'center', columnAlign: 'center',isResize:true},
                    {field: 'custome-adv', title: '操作',width: 200, titleAlign: 'center',columnAlign:'center',componentName:'table-operation',isResize:true}
                ]
                }
            }
        },
        mounted : function(){
            // 获取数据源
            this.initList();
        },
        methods:{
            // 获取数据源
            initList: function(){
                let vm = this;
                this.$http.get('/api/role', {responseType:'json'}).then(function(response){
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.tableDate  = response.body.list_roles;
                        vm.total = vm.tableDate.length;
                        vm.getTableData();
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
                    this.$http.post('/api/role/' + params.rowData.id, {}, {responseType: 'json'}).then(function(response){
                        console.log(response);
                        if (response.body.status === 0) {
                            // 从列表中删除
                            Vue.delete( vm.tableDate, params.index);
                            this.getTableData();
                        }
                    });

                }else if (params.type === 'edit'){ // do edit operation
                    // 编辑
                    let url_edit = '/Role/' + params.rowData.id + '/edit';
                    window.open(url_edit, '_blank');
                }
            }
        },

    }

    // 自定义列组件
    Vue.component('table-operation', {
        template: `<span>
        <a href="" class="btn btn-info btn-xs" @click.stop.prevent="update(rowData,index)">编辑</a>&nbsp;
        <a href="" class="btn btn-danger btn-xs" @click.stop.prevent="deleteRow(rowData,index)">删除</a>
        </span>`,
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
    .title-cell-class-name-test1 {
        background-color: #2db7f5;
        color:#fff;
    }
    .title-cell-class-name-test2 {
        background-color: #f60;
        color:#fff;
    }
</style>