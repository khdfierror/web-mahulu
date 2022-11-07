<template>
    <form
        class="flex w-full px-2 bg-gray-100 rounded-2xl dark:bg-brand-secondary/20 ring-2 ring-transparent focus-within:ring-brand-secondary dark:focus-within:ring-brand-primary"
        action="#"
        method="GET"
    >
        <label for="search-field" class="sr-only">Search</label>
        <div
            class="relative w-full text-brand-secondary/60 focus-within:text-brand-secondary dark:text-slate-600 dark:focus-within:text-brand-primary"
        >
            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                <icon-tabler-search class="w-5 h-5" aria-hidden="true" />
            </div>
            <input
                id="search-field"
                class="block w-full h-full px-8 py-2 bg-transparent border-transparent text-brand-dark dark:text-white placeholder-brand-secondary dark:placeholder-slate-600 dark:focus:placeholder-slate-700 focus:outline-none focus:placeholder-brand-secondary/60 dark:focus:placeholder-text-brand-secondary focus:ring-0 focus:border-transparent sm:text-sm"
                placeholder="Search"
                type="text"
            />
            <div
                class="absolute inset-y-0 right-0 flex items-center pr-2 text-xs pointer-events-none"
            >
                <span>⌘K</span>
            </div>
        </div>
    </form>
</template>
