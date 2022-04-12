<script lang="ts">
    import Main from "@/Layout/Main.svelte"

	let count: number = 0;

	function incrementCount() {
		count++
	}
</script>

<Main />

<h1>Symfony + Svelte = &lt;3</h1>

<button on:click={incrementCount}>
	Clicked {count} {count === 1 ? 'time' : 'times'}
</button>

<style>
    h1 {
        text-align: center;
        color: #D26D26;
    }
</style>
