<template>
    <p>This is some fine text in a beautiful paragraph. And here comes a link with the title attribute:
        <button  title_tip="Hi, I am a link">This is a link</button>.
        Is it possible to style the default tooltip shown on mouse over when using the title attribute?
        Let's find out! <a href="#" title="I am another link">This is another link</a> to demonstrate how to style the
        tooltip with CSS.</p>
</template>

<script>
    export default {
        name: "Title"
    }
</script>

<style scoped>
    button{
        /*color: #d6005e;*/
        color: green;
    }
    button:hover{
        color: #ff2283;
        position: relative;
    }
    button[title_tip]:hover:after{
        content: attr(title_tip);
        padding: 8px 12px;
        color: #85003a;
        position: absolute;
        left: 0;
        top: 100%;
        white-space: nowrap;
        z-index: 200;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        -moz-box-shadow: 0px 0px 2px #c0c1c2;
        -webkit-box-shadow: 0px 0px 2px #c0c1c2;
        box-shadow: 0px 0px 2px #c0c1c2;
        background-image: -moz-linear-gradient(top, #ffffff, #eeeeee);
        background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #ffffff),color-stop(1, #eeeeee));
        background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
        background-image: -moz-linear-gradient(top, #ffffff, #eeeeee);
        background-image: -ms-linear-gradient(top, #ffffff, #eeeeee);
        background-image: -o-linear-gradient(top, #ffffff, #eeeeee);
        /*background-color: gray;*/
    }

</style>