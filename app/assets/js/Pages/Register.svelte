<script lang="ts">
    import { useForm } from "@inertiajs/inertia-svelte"
    import router from "@/router"
    import Layout from "@/Layout.svelte"

    export let token: String

    let url = router.generate("register_attempt", true)
    let form = useForm({
        name: null,
        email: null,
        plainPassword: null,
        _token: token,
    })

    function submit() {
        let options = {forceFormData: true}
        $form.post(url, options)
    }
</script>

<Layout>
    <main slot="main">
        <h2>Create an account</h2>

        <form on:submit|preventDefault={submit}>
            <div>
                <label for="name" class="required">Name</label>
                <input type="text" id="name" name="name" bind:value={$form.name} minlength="2" maxlength="180" required />
            </div>
            <div>
                <label for="email" class="required">Email</label>
                <input type="email" id="email" name="email" bind:value={$form.email} maxlength="255" required />
            </div>
            <div>
                <label for="password" class="required">Password</label>
                <input type="password" id="password" name="password" bind:value={$form.plainPassword} autocomplete="new-password" required />
            </div>

            <button type="submit">Sign up</button>
        </form>
    </main>
</Layout>

<style lang="postcss">
    main {
        @apply max-w-xs w-full mx-auto;
    }

    h2 {
        @apply mb-10 text-center text-4xl font-bold;
    }

    form {
        @apply flex flex-col gap-4;
    }

    input {
        @apply w-full mt-1 py-2 px-3 rounded-md;
        @apply border border-gray-300 focus:border-highlight-500 focus:outline-none;
        @apply focus:ring focus:ring-highlight-300 focus:ring-opacity-25;
        @apply shadow-sm;
    }

    button {
        @apply w-full px-4 py-2 rounded-md font-semibold text-white;
        @apply bg-highlight-800 hover:bg-highlight-700;
        @apply focus:outline-none focus:border-highlight-500;
        @apply focus:ring focus:ring-highlight-300 focus:ring-opacity-25;
        @apply shadow-md transition;
    }
</style>
