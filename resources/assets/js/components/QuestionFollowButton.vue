<template>
    <button class="btn btn-sm btn-default">
    </button>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            thi.$http.post('/api/question/follower', {}).then(response => {
                console.log(response.data);
            });


        }
    }
</script>
