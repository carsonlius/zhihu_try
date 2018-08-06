<template>
    <div>
        <div class="modal fade" id="modal-prompt" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center" v-if="title">
                        <h4 class="modal-title">
                            {{ title }}
                        </h4>
                    </div>

                    <div class="modal-body text-center">
                        <p class="p_white_space">
                            {{ body }}
                        </p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">确定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PromptModal",
        props : ["title", 'body', 'redirect_url'],
        mounted : function () {
            // 触发模态框
            $('#modal-prompt').modal('toggle');
        },
        methods : {
            redirectUrl : function () {
                window.open(this.redirect_url, '_blank');
            }
        }

    }
</script>

<style scoped>
    .p_white_space {
        white-space: pre-line;
    }
</style>