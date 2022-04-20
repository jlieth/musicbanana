<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"
    import Header from "@/Layout/Header/Header.svelte"

    $: console.log($page.props)

    let count: number = 0;

    function incrementCount() {
        count++;
    }
</script>

<div id="wrapper">
    <Header />

    <main class="w-full flex items-stretch justify-center text-header-800">
        <slot name="content">
            <h1>Symfony + Svelte = &lt;3</h1>

            <button class="bg-amber-600" on:click={incrementCount}>
                Clicked {count}
                {count === 1 ? "time" : "times"}
            </button>
        </slot>
    </main>

</div>

<style>
    #wrapper {
        @apply max-w-xxl mx-auto my-2 bg-gray-100 rounded-md;
        @apply border border-gray-500 border-opacity-50;
        @apply md:my-0 md:rounded-none md:border-0;
    }

    h1 {
        text-align: center;
        color: #d26d26;
    }
</style>
