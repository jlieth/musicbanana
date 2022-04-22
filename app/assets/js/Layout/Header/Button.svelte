<script lang="ts">
    let class_ = ""
    export {class_ as class}
</script>

<div class="btn {class_}">
	<slot></slot>
</div>

<style>
    .btn {
        @apply flex justify-center items-center;
        @apply cursor-pointer;
        @apply text-gray-200 bg-header-800;
        @apply ring ring-white/20 ring-offset-[-2px];
    }

    .btn:hover {
        @apply text-gray-100 bg-header-700 ring-highlight-500;
    }

    .btn > :global(*) {
        @apply w-full px-3 py-2;
        @apply text-gray-200;
    }

    .btn > :global(*:hover) {
        @apply text-gray-100;
    }
</style>
