<script lang="ts">
    let searchHidden = true
    $: searchClass = searchHidden ? "hidden" : ""
    $: console.log(searchClass);

    function toggleSearch() {
        searchHidden = searchHidden !== true
    }
</script>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark header">
    <div class="container-fluid">
        <!-- musicbanana text -->
        <h4 class="navbar-text logo">music<span class="contrast">banana</span></h4>

        <span class="spacer" style="flex-grow: 1"></span>

        <span role="button" id="toggle-search" on:click={toggleSearch}>
            <img src="/img/search.png" alt="">
        </span>

        <!-- Button that toggles navbar content on small screens -->
        <button
            type="button"
            class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#topnav"
            aria-controls="topnav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topnav" style="flex-grow: 0;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="search" class="container-fluid bg-dark pb-2 {searchClass}">
    <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
</div>

<style>
    nav.header h4.logo {
        margin-bottom: 0.25rem;
        color: #fff;
        opacity: 0.8;
    }

    nav.header h4.logo span.contrast {
        color: var(--highlight-color);
    }

    nav.header #toggle-search {
        margin: 0 0.5rem;
    }

    #search.hidden {
        display: none;
    }
</style>
