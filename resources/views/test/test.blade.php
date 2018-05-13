@extends('layouts.app')
<style>
    img {
        width: 100px;
        height: 120px;
    }
</style>
@section('content')

    <div class="container" id="app_test">
        <list_question_template ></list_question_template>
    </div>
    <template id="list_question">
        <div>
            {{ Form::open(['url' => '/api/tasks', 'method' => 'get','id' => 'form_create_task', '@submit.prevent' => 'createTask', 'class'=> 'form-inline']) }}

            <div class="form-group">
                {{ Form::label('name', '任务名称') }}
                {{--{{ Form::text('', '', ['class' => 'form-control', '@model' => 'notes']) }}--}}
                <input type="text" v-model="notes" class="form-group">
                @{{notes}}
            </div>
            <div class="form-group">
                {{ Form::submit('添加任务',['class' => 'btn btn-success btn-block'] ) }}
            </div>
            {{ Form::close() }}
            <ul class="list-group">
                <li class="list-group-item" v-for="task in orderTasks"> @{{ task.id }} @{{ task.name }}
                    <strong @click="deleteItem(task)" style="color:red">x</strong>
                </li>
            </ul>
        </div>

    </template>

    <script>
        var resource = Vue.resource('/api/tasks/{id}');

        Vue.component('list_question_template', {
            template : '#list_question',
            // props : ['task_lists'],
            data : function(){
                return {
                    task_lists : [],
                    notes : ''
                }
            },
            created : function () {
                var vm = this;
                var url = '/api/tasks';
                $.getJSON(url).done(function(response){
                    vm.task_lists = response;
                });
            },
            computed :{
                orderTasks: function () {
                    return _.orderBy(this.task_lists, 'id', 'desc');
                }
            },
            methods : {
                deleteItem : function (question) {
                    var vm = this;
                    // 删除问题的请求
                    resource.delete({id: question.id }).then(function (response) {
                        // 从页面上也要删除文件
                        if (response.body.status === 'success') {
                            var index = vm.task_lists.indexOf(question);
                            vm.task_lists.splice(index, 1);
                        }
                    });
                },
                // 创建任务
                createTask: function(){
                    var url = $('#form_create_task').attr('action');
                    this.$http.post(url , {name:this.notes}).then(function (response) {
                        if (response.body.status === 'success') {
                            this.task_lists.unshift(response.body.task);
                            this.notes ='';
                        }
                    });
                }
            }
        });

        new Vue({
            el: '#app_test'
        });
    </script>
@endsection