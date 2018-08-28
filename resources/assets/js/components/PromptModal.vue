<template>
    <div class="modal fade" id="modal-prompt" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center" v-if="title">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title font-bold">
                        {{ title }}
                    </h4>
                </div>

                <div class="modal-body text-center">
                    <p class="p_white_space font-bold">
                        {{ body }}
                    </p>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" :class="btn_class_left"  v-if="btn_name_left" @click.prevent="redirectUrl('left')" data-dismiss="modal"> {{ btn_name_left }}</button>
                    <button type="button" :class="btn_class_right" v-if="btn_name_right" @click.prevent="redirectUrl('right')" data-dismiss="modal">{{ btn_name_right }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PromptModal",
        data : function(){
            return {
                modal_show : false,
                title : '',
                body : '',
                btn_name_left : '取消', // 左侧按钮的显示的文字,
                btn_name_right : '确定', // 左侧按钮的显示的文字,
                btn_class_left : 'btn btn-secondary', // 左侧按钮的类
                btn_class_right : 'btn btn-secondary btn-primary', // 右侧按钮的类
                btn_url_left : '', // 左侧按钮跳转的url
                btn_url_right : '', // 右侧按钮跳转的url
            }
        },
        methods : {
            // 初始化参数
            open: function(params){
                this.title = !! params.title ? params.title : this.title;
                this.body = !! params.body ? params.body : this.body;
                this.btn_name_left = !!params.btn_name_left ? params.btn_name_left : this.btn_name_left;
                this.btn_name_right = !!params.btn_name_right ? params.btn_name_right : this.btn_name_right;
                this.btn_class_left = !!params.btn_class_left ? params.btn_class_left : this.btn_class_left;
                this.btn_class_right = !!params.btn_class_right ? params.btn_class_right : this.btn_class_right;
                this.btn_url_left = !!params.btn_url_left ? params.btn_url_left : this.btn_url_left;
                this.btn_url_right = !!params.btn_url_right ? params.btn_url_right : this.btn_url_right;

                $('#modal-prompt').modal('toggle');
            },

            // 按钮点击事件
            redirectUrl : function(source){
                switch (source) {
                    case 'left':
                        if (!!this.btn_url_left) {
                            window.location.href = this.btn_url_left;
                        }
                        break;
                    case 'right':
                        if (!!this.btn_url_right) {
                            window.location.href = this.btn_url_right;
                        }
                        break;
                }

            }
        }
    }
</script>
<style>
    .font-bold {
        font-weight: bold;
    }
</style>
