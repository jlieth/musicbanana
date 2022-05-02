<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"
    import { BorderAlert } from "flowbite-svelte"

    $: flash = $page.props.flash
</script>

<div class="flash">
    {#each flash.error as error}
    <BorderAlert alertId="border-alert-2" color="red" closeBtn>
        {error}
    </BorderAlert>
    {/each}

    {#each flash.success as success}
    <BorderAlert alertId="border-alert-2" color="green" closeBtn>
        {success}
    </BorderAlert>
    {/each}
</div>
