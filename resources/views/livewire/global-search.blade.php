<div>
<div  class="fi-global-search flex items-center">
    <div class="sm:relative">
        <div x-id="['input']" class="fi-global-search-field">
            <label x-bind:for="$id('input')" class="sr-only" for="input-1">
                Глобальный поиск
            </label>

            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 focus-within:ring-primary-600 dark:focus-within:ring-primary-500">
                <div class="fi-input-wrp-prefix items-center gap-x-3 ps-3 flex pe-2">
                    <svg style=";" wire:loading.remove.delay.default="1" wire:target="search" class="fi-input-wrp-icon h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"></path>
                    </svg>

                    <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="animate-spin fi-input-wrp-icon h-5 w-5 text-gray-400 dark:text-gray-500" wire:loading.delay.default="" wire:target="search">
                        <path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
                    </svg>
                </div>

                <div class="fi-input-wrp-input min-w-0 flex-1">
                    <input class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-0 pe-3" autocomplete="off" maxlength="1000" placeholder="Поиск" type="search" wire:key="global-search.field.input" x-bind:id="$id('input')" x-on:keydown.down.prevent.stop="$dispatch('focus-first-global-search-result')" x-data="{}" wire:model.live.debounce.500ms="search" id="input-1">
                </div>
            </div>
        </div>
    </div>
</div>

</div>
