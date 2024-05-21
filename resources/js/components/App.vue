<template>
    <div v-show="this.msg != null" class="alert alert-primary" role="alert">
    Добавлена новая статья <strong>{{this.msg}}</strong>
</div>
</template>
<script>
    export default {
    data() { return { msg: null } },
        created() {
            window.Echo.channel('my-channel').listen('ArticleEvent', (article) => {
                console.log(article);
                this.msg=article.title;
                // alert('Добавлена новая статья!');
            })
        }
    }
</script>
