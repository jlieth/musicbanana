<script lang="ts">
    import Header from "@/Layout/Header/Header.svelte"

    let count: number = 0;

    function incrementCount() {
        count++;
    }
</script>

<div id="wrapper" class="max-w-xxl mx-auto bg-gray-100">
    <Header />

    <h1>Symfony + Svelte = &lt;3</h1>

    <button class="bg-amber-600" on:click={incrementCount}>
        Clicked {count}
        {count === 1 ? "time" : "times"}
    </button>

</div>

<style>
    h1 {
        text-align: center;
        color: #d26d26;
    }
</style>
