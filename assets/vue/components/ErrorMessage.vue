<template>
    <div
        class="alert alert-danger"
        role="alert"
    >
        {{ error.response.data.error }}
    </div>
</template>
<style>
    .alert-danger {
        top: 10px;
        width: 423px;
        text-align: center;
    }
</style>
<script>
    export default {
        name: "ErrorMessage",
        props: {
            error: {
                type: Error,
                required: true
            }
        },
    }
</script>