<!-- Search bar from here: https://tailwindcomponents.com/component/navbar-1-->

<script lang="ts">
    import Fa from "svelte-fa"
    import { faSearch } from "@fortawesome/free-solid-svg-icons"

    export let hidden: boolean = true
</script>

<div class:hidden class="w-screen md:w-1/3 h-10 bg-gray-200 cursor-pointer border border-gray-300 text-sm rounded-full flex">
    <input
        type="search"
        name="search"
        placeholder="Search"
        class="flex-grow px-4 rounded-l-full rounded-r-full text-sm focus:outline-none"
    >
    <button class="m-3 mr-5 text-lg text-gray-700 w-4 h-4">
        <Fa icon={faSearch} />
    </button>

</div>
