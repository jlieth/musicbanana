<script lang="ts">
    import Layout from "@/Layout.svelte"
</script>

<Layout>
    <div slot="sidebar" class="hidden"></div>
    <div slot="content" class="content">
        Hi
    </div>
</Layout>

<style>
    .content {
        @apply flex flex-row md:flex-col items-stretch gap-3 p-3 md:p-0;
    }
</style>
