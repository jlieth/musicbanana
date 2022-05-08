<script lang="ts">
    import Layout from "@/Layout.svelte"
    import SubHeader from "@/Components/SubHeader/UserPageSubHeader.svelte"
</script>

<Layout>
    <div slot="subheader">
        <SubHeader />
    </div>

    <main slot="main">
        Hi
    </main>
</Layout>

<style>
    main {
        @apply w-[1320px] flex flex-row md:flex-col items-stretch gap-3 p-3 md:p-0;
    }
</style>
