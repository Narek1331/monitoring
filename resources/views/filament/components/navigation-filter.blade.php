<div
    class="w-full px-2 pt-2 md:pt-0"
    @if (filament()->isSidebarCollapsibleOnDesktop())
        x-bind:class="$store.sidebar.isOpen ? 'block' : 'hidden'"
    @endif
>
    <x-filament::input.wrapper class="relative w-full">
        {{-- <span
            class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-600 dark:text-gray-400 text-xs flex items-center gap-1"
        >
            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
        </span> --}}

        <x-filament::input
            type="text"
            placeholder="{{ __('Поиск...') }}"
            x-data="sidebarSearch()"
            x-ref="search"
            x-on:input.debounce.300ms="filterItems($event.target.value)"
            x-on:keydown.escape="clearSearch"
            x-on:keydown.meta.j.prevent.document="$refs.search.focus()"
            class="!pl-9 !pr-3 w-full text-sm"
        />
    </x-filament::input.wrapper>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebarSearch', () => ({
                init() {
                    this.$refs.search.value = ''
                },
                filterItems(searchTerm) {
                    const groups = document.querySelectorAll('.fi-sidebar-nav-groups .fi-sidebar-group')
                    searchTerm = searchTerm.toLowerCase()
                    groups.forEach(group => {
                        const groupButton = group.querySelector('.fi-sidebar-group-button')
                        const groupText = groupButton?.textContent.toLowerCase() || ''
                        const items = group.querySelectorAll('.fi-sidebar-item')
                        let hasVisibleItems = false
                        const groupMatches = groupText.includes(searchTerm)
                        items.forEach(item => {
                            const itemText = item.textContent.toLowerCase()
                            const isVisible = itemText.includes(searchTerm) || groupMatches
                            item.style.display = isVisible ? '' : 'none'
                            if (isVisible) hasVisibleItems = true
                        })
                        group.style.display = (hasVisibleItems || groupMatches) ? '' : 'none'
                    })
                },
                clearSearch() {
                    this.$refs.search.value = ''
                    this.filterItems('')
                }
            }))
        })
    </script>
</div>
